<?php
session_start();
$__route__=$_REQUEST["__route__"];
$route=explode('/', $__route__);
$_AJAX=isset($_REQUEST["_AJAX"])?$_REQUEST["_AJAX"]:0;

include 		"phplib/cons.php";
require_once 	"phplib/funciones.php";
require_once 	"phplib/start.php";
require_once 	"phplib/class.upload.php";



if(!isset($_SESSION['_SESS']) || $_SESSION['_SESS']=='') $_SESSION['_SESS']=clave(time());
$_key=clave(time().$_SESSION['_SESS']);
$_SESSION['_key']=$_key;

//  INICIA CONFIGURACION //
Start($_PARAMETROS,$_LANDING);
$_OP_URL=$_PARAMETROS["S3_URL1"];
$_LW_URL=$_PARAMETROS["LWSERVICE"];

$_LOGO=sprintf('http://%s/img/_logo.png',$_SERVER["HTTP_HOST"]);
$_LOGO_COMPANY=sprintf('http:%s%s',$_OP_URL,$_PARAMETROS["LOGO"]);
$_LOGO_STORE=sprintf('http:%s%s',$_OP_URL,$_PARAMETROS["LOGO_STORE"]);
$_SUFIX_TITLE=$_PARAMETROS["S_NOMBCORTO"].', '.$_PARAMETROS["S_SLOGAN"];


$_mt_url=$_LW_URL;
$_mt_image=$_LOGO;
$_mt_description=$_PARAMETROS["MT_DESC"];
$_mt_title=$_PARAMETROS["MT_TITLE"];
$_mt_tw_stwitter=$_PARAMETROS["MT_TWSITE"];
$_mt_tw_ctwitter=$_PARAMETROS["MT_TWCREAT"];
$_mt_cc=$_PARAMETROS["MT_CCODE"];
$_APP=$_PARAMETROS["MT_IPHONENAME"]!=''||$_PARAMETROS["MT_IPADNAME"]!=''||$_PARAMETROS["MT_GPLAYNAME"]!='';
$_mt_gplus='';

$android_url='askingroom://';
$ios_url='askingroom://';

///////////////////
// SCHEMA MOVIL //
///////////////////
$_SHEMA_IOS=array(
    '@context'=>'http://schema.org'
  , '@type'=>'SoftwareApplication'
  , 'image'=>$_LOGO_STORE
  , 'name'=>$_PARAMETROS["MT_IPHONENAME"]
  , 'author'=>array(  '@type'=>'Organization'
                  ,   'url' =>  'http://motumdata.com'
                  ,   'name'=>'Motum Data DEV')
  , 'description'=>$_mt_description
  , 'downloadURL'=>$_PARAMETROS["MT_IPHONEPAGE"]
  , 'applicationCategory'=>'BusinessApplication'
  , 'offers'=>array('price'=>0,'priceCurrency'=>'COP')
  , 'operatingSystem'=>'iOS');
$_SHEMA_Android=array(
    '@context'=>'http://schema.org'
  , '@type'=>'SoftwareApplication'
  , 'image'=>$_LOGO_STORE
  , 'name'=>$_PARAMETROS["MT_GPLAYNAME"]
  , 'author'=>array(  '@type'=>'Organization'
                  ,   'url' =>  'http://motumdata.com'
                  ,   'name'=>'Motum Data DEV')
  , 'description'=>$_mt_description
  , 'downloadURL'=>$_PARAMETROS["MT_GPLAYPAGE"]
  , 'applicationCategory'=>'BusinessApplication'
  , 'offers'=>array('price'=>0,'priceCurrency'=>'COP')
  , 'operatingSystem'=>'Android');


$_og_fb_id=$_PARAMETROS["FB_APPID"];
$http_title=sprintf("%s - %s",$_PARAMETROS["S_NOMBCORTO"],$_PARAMETROS["S_SLOGAN"]);
$favicon=sprintf("%s%s",$_OP_URL,$_PARAMETROS["FAVICON"]);


//*************************//
//*************************//
//*************************//
if($_COOKIE["_token_f_a"]!='' && $_COOKIE["_token_f_b"]!=''){
	$opt=$_OPT;	
	$opt['tp']=10101;
	$index_req=ApiConsole($opt,'GET',$_URL_API,'',$_user);	
}

//*************************//
if($_user["_token_a"]=='' || $_user["_token_b"]==''){
	setcookie("_token_f_a",false,time() - 3600,'/',$dominio_activo,false,true);
	setcookie("_token_f_b",false,time() - 3600,'/',$dominio_activo,false,true);
	$validate=false;
}
else{
	$_LANG_DEF=$_user['lang'];
	//SI ESTA LOGEADO QUITA LANG POR QUE USARÁ EL DE EL NAVEGADOR POR OBLIGACIÓN
	setcookie("lang",false,time() - 3600,'/',$dominio_activo,false,true); 
	$validate=true;
}
/********************************/
/********************************/
$_NOTICIA=false;

$SHeader=true;
$SFooter=true;
$ErrType=0;
$UserType=$_user['_type'];

/*
$ErrType=1 -> Usuario No Autorizado
$ErrType=2-> Página No Encontrada
*/

$S_URL=$_TURL[$route[1]];
$_ID=isset($S_URL['id'])?$S_URL['id']:0;
$_PROFILE=false;

$forms=$S_URL['form'];
$_mt_title=isset($S_URL['title'])?$S_URL['title']:$_mt_title;

$ErrStatus=($S_URL['val']==1&&!$validate)
			||($S_URL['val']==0&&$validate);

/* deshabilitar botones tutor */
$UserType==3?$claseuser="items_butons_button2":$claseuser="items_butons_button";
$UserType==3?$titleuser='title="Solo habilitado para estudiantes"':$titleuser='title="click sobre el botón "';
/* fin deshabilitar botones tutor*/

if(!$ErrStatus){	
	$http_title=$_mt_title.' - '.$_SUFIX_TITLE;
	if($_ID==1){		
		$opt=$_OPT_NL;	
		$opt["tp"]=20000;		
		$results=ApiConsole($opt,'GET',$_URL_API); 
		$datatesti=$results["testimonios"];
		$dataplan=$results["planes"];
		$datamaterias=$results["materias"];
		$datagrados=$results["grados"];

		/*
		1 Superior
		2 Tercio
		3 Naraja
		4 Normal
		*/
		$planes=array();
		foreach ($dataplan as $pI => $plan) {
			$planes[$plan['tipo']][]=$plan;
		}
	}
	elseif ($_ID==26){
		$opt=$_OPT_NL;	
		$opt["tp"]=20000;		
		$results=ApiConsole($opt,'GET',$_URL_API); 
		$datamaterias=$results["materias"];
		$datagrados=$results["grados"];
	}
	elseif($_ID==2){
		$opt=$_OPT;	
		$opt["tp"]=5010;

		$page=isset($_GET["page"])?$_GET["page"]:0;
		$term=isset($_GET["term"])?urldecode($_GET["term"]):'';		

		if(isset($_GET["page"]))		$opt["page"]=$page;
		if(isset($_GET["term"]))		$opt["term"]=$term;
		if($route[2]!='') 			$opt["mat"]=$route[2];
		
		$reg=ApiConsole($opt,'GET',$_URL_API);
		$Tutores=$reg['Tutor'];			
		$PTotal=$reg['pages']['max'];
		$PActual=$reg['pages']['act'];

		$opt=$_OPT_NL;	
		$opt["tp"]=10006;
		$opt["tables"][]='materias';
		$results=ApiConsole($opt,'GET',$_URL_API); 
		$materias=$results["tables"]["materias"];

		
	}
	elseif($_ID==29){
		$opt=$_OPT;	
		$opt["tp"]=5010;
		$IsProfile=$route[2]!='';
		if($IsProfile){
			$opt["user"]=$route[2];		
			$reg=ApiConsole($opt,'GET',$_URL_API);
			$Tutores=$reg['Tutor'];	
			$Tutor=current($Tutores);
			$img=$Tutor['display']['id']==0?'/img/avatar_2x.png':$Tutor['display']['prefix'].$Tutor['display']['big'];
			$name=$Tutor['name'].' '.$Tutor['lastname'];
		}		
	}
		

	//Solicitar Clase
	elseif($_ID==15){

		$materia=isset($_GET["materia"])?$_GET["materia"]:0;
		$grado=isset($_GET["grado"])?$_GET["grado"]:0;

			
		$opt=$_OPT;	
		$opt["tp"]=5010;		
		$opt["user"]=$route[2];		
		$reg=ApiConsole($opt,'GET',$_URL_API);
		$Tutores=$reg['Tutor'];	
		$Tutor=current($Tutores);
		$img=$Tutor['display']['id']==0?'/img/avatar_2x.png':$Tutor['display']['prefix'].$Tutor['display']['big'];
		$name=$Tutor['name'].' '.$Tutor['lastname'];
	}
	//programar una clase
	elseif ($_ID==31) {
		
		$materia=isset($_GET["materia"])?$_GET["materia"]:0;
		$grado=isset($_GET["grado"])?$_GET["grado"]:0;
			
		$opt=$_OPT;	
		$opt["tp"]=5010;		
		$opt["user"]=$route[2];		
		$reg=ApiConsole($opt,'GET',$_URL_API);
		$Tutores=$reg['Tutor'];	
		$Tutor=current($Tutores);
		$img=$Tutor['display']['id']==0?'/img/avatar_2x.png':$Tutor['display']['prefix'].$Tutor['display']['big'];
		$name=$Tutor['name'].' '.$Tutor['lastname'];
	}
	//Pagos
	elseif($_ID==30){
		$opt=$_OPT;	
		$opt["tp"]=5020;		
		$opt["reference_sale"]=$_REQUEST['referenceCode'];	
		$opt["state_pol"]=$_REQUEST['transactionState'];	
		$opt["sign"]=$_REQUEST['signature'];			
		$reg=ApiConsole($opt,'GET',$_URL_API);
	}
	//Ecuenta
	elseif($_ID==11){
		$opt=$_OPT;	
		$opt["tp"]=5080;		
		$opt["reference_sale"]=$_REQUEST['referenceCode'];	
		$opt["state_pol"]=$_REQUEST['transactionState'];	
		$opt["sign"]=$_REQUEST['signature'];			
		$reg=ApiConsole($opt,'GET',$_URL_API);
		$ecuentas=$reg['ecuenta'];	
		print_r($_user["_ttend"]);	
	}
	// Noticias, Herramientas y Clases
	elseif($_ID==18){
		$opt=$_OPT;	
		$opt["tp"]=5090;
		if($route[2]!=''){
			$_NOTICIA=true;
			$opt["noticia"]=$route[2];		
		}
				
		$reg=ApiConsole($opt,'GET',$_URL_API); 
		$noticias=$reg['noticias'];
		$PTotal=$reg['pages']['max'];
		$PActual=$reg['pages']['act'];

		$Cant=count($noticias);
		if($Cant!=0&&$_NOTICIA){
			$noticia=current($noticias);
			$_mt_title=$noticia['seo']['meta-title'];
			$_mt_description=$noticia['seo']['meta-description'];
			$_mt_image='http:'.$noticia['picture']['prefix'].$noticia['picture']['big'];
		}
		elseif($Cant==0&&$_NOTICIA){
			$ErrStatus=true;
			$ErrType=2;
		}
	}	
	// Noticias, Herramientas y Clases
	elseif($_ID==16||$_ID==17){
		$opt=$_OPT_NL;	
		$opt["tp"]=10006;
		$opt["tables"]["0"]='materias';	
		$opt["tables"]["1"]='grados';				
		$results=ApiConsole($opt,'GET',$_URL_API); 
		$materias=$results["tables"]["materias"];
		$grados=$results["tables"]["grados"];
		$term=isset($_GET["term"])?urldecode($_GET["term"]):'';		

		$opt=$_OPT;	
		$opt["tp"]=5100;
		$opt["tipo"]=2;
		if($_GET['materia']!='')	$opt["mat"]=$_GET['materia'];
		if($_GET['grado']!='')		$opt["grad"]=$_GET['grado'];
		if($_GET['term']!='')		$opt["term"]=$term;
		if($route[2]!=''){
			$_IFRAME=true;
			$opt["iframe"]=$route[2];		
		}			
		$reg=ApiConsole($opt,'GET',$_URL_API); 
		$iframes=$reg['iframe'];
		$PTotal=$reg['pages']['max'];
		$PActual=$reg['pages']['act'];

		$Cant=count($iframes);
		if($Cant!=0&&$_IFRAME){
			$iframe=current($iframes);
			$_mt_title=$iframe['seo']['meta-title'];
			$_mt_description=$iframe['seo']['meta-description'];
			$_mt_image='http:'.$iframe['picture']['prefix'].$iframe['picture']['big'];
		}
		elseif($Cant==0&&$_IFRAME){
			$ErrStatus=true;
			$ErrType=2;
		}

	}
	// Planes y Precios
	elseif($_ID==22){	

		$opt=$_OPT_NL;	
		$opt["tp"]=5050;
		if($route[2]!=''){
			$_PROMO=true;
			$opt["promo"]=$route[2];		
		}				
		$reg=ApiConsole($opt,'GET',$_URL_API);
		$dataplan=$reg['planes'];		
		$Cant=count($dataplan);
		
		// Pagina de Mas Información
		if($Cant!=0&&$_PROMO){
			$plan=current($dataplan);
			$_mt_title=$plan['seo']['meta-title'];
			$_mt_description=$plan['seo']['meta-description'];
			$_mt_image='http:'.$plan['picture']['prefix'].$plan['picture']['big'];
		}
		elseif($Cant==0&&$_PROMO){
			$ErrStatus=true;
			$ErrType=2;
		}
	}
	// Testimonios
	elseif($_ID==28){		
		$opt=$_OPT_NL;	
		$opt["tp"]=5110;
		if($route[2]!=''){
			$_TESTIMONIO=true;
			$opt["test"]=$route[2];		
		}				
		$reg=ApiConsole($opt,'GET',$_URL_API); 
		$datatesti=$reg['testimonios'];		
		$Cant=count($datatesti);
		
		// Pagina de Mas Información
		if($Cant!=0&&$_TESTIMONIO){
			//$testimonio=current($datatesti);
			/*$_mt_title=$promo['seo']['meta-title'];
			$_mt_description=$promo['seo']['meta-description'];*/
			$_mt_image='http:'.$testimonio['photo']['prefix'].$testimonio['photo']['big'];
		}
		elseif($Cant==0&&$_TESTIMONIO){
			$ErrStatus=true;
			$ErrType=2;
		}
	}
	
}
else{
	$_ID=0;
	$ErrType=1;
}


// NO INDEX CONDITION //
$_NOINDEX=$ErrType!=0||$S_URL['val']==1||$forms;

// SI ES UN FORM AJAX
if($forms&&$_AJAX==1){
	echo '<div>';
	include "page/forms.php";
	echo '</div>';
}
elseif($_AJAX==1){
?>
	<script id="NewMeta">	
		(function(){
			var InNew=[
					{type:"property",name:"fb:app_id",value:"<?php echo $_og_fb_id?>"}
				,	{type:"property",name:"article:author",value:"<?php echo $_og_fb_autor?>"}
				,	{type:"property",name:"article:section",value:"<?php echo $section?>"}
				,	{type:"property",name:"article:published_time",value:"<?php echo $published_time?>"}
				,	{type:"property",name:"article:modified_time",value:"<?php echo $modified_time?>"}
				,	{type:"property",name:"og:updated_time",value:"<?php echo $updated_time?>"}
				,	{type:"property",name:"og:title",value:"<?php echo $_mt_title ?>"}
				,	{type:"property",name:"og:type",value:"<?php echo $_og_type?>"}
				,	{type:"property",name:"og:url",value:"<?php echo $_mt_url ?>"}
				,	{type:"property",name:"og:image",value:"<?php echo $_mt_image?>"}
				,	{type:"property",name:"og:description",value:"<?php echo $_mt_description?>"}
				,	{type:"name",name:"twitter:card",value:"summary_large_image"}
				,	{type:"name",name:"twitter:site",value:"@<?php echo $_og_tw_stwitter?>"}
				,	{type:"name",name:"twitter:creator",value:"@<?php echo $_og_tw_ctwitter?>"}
				,	{type:"name",name:"twitter:title",value:"<?php echo $_mt_title ?>"}
				,	{type:"name",name:"twitter:description",value:"<?php echo $_mt_description?>"}
				,	{type:"name",name:"twitter:image:src",value:"<?php echo $_mt_image?>"}
				,	{type:"name",name:"twitter:domain",value:"<?php echo $_PARAMETROS["MT_DOMAIN"]?>"}
				,	{type:"name",name:"twitter:app:name:iphone",value:"<?php echo $_PARAMETROS["MT_IPHONENAME"]?>"}
				,	{type:"name",name:"twitter:app:name:ipad",value:"<?php echo $_PARAMETROS["MT_IPADNAME"]?>"}
				,	{type:"name",name:"twitter:app:name:googleplay",value:"<?php echo $_PARAMETROS["MT_GPLAYNAME"]?>"}
				,	{type:"name",name:"twitter:app:id:iphone",value:"<?php echo $_PARAMETROS["MT_IPHONEID"]?>"}
				,	{type:"name",name:"twitter:app:id:ipad",value:"<?php echo $_PARAMETROS["MT_IPADID"]?>"}
				,	{type:"name",name:"twitter:app:id:googleplay",value:"<?php echo $_PARAMETROS["MT_GPLAYID"]?>"}
				,	{type:"name",name:"description",value:"<?php echo $_mt_description?>"}
				,	{type:"title",name:"title",value:"<?php echo $http_title?>"}
				,	{type:"url",name:"url",value:"<?php echo $_mt_url?>"}
			];
			PutMeta(InNew);
		})();	
	</script>
	<div id="cWRAP" class="swrap">
		<?php				
		include("page/index.php");			
		?>
		</div>	
	</div>
	<?php
}
elseif($_AJAX==0){
	$scripPage=str_replace('&','&amp;',$_SERVER['REQUEST_URI']);
	if($error!=0) header('404 404 Not Found', true, 404);
?><!doctype html>
<html lang="<?php echo $_LANG_DEF?>">	
	<?php include("inc/head.php");?>		
	<body class="bg01">
		<!--TGM-->
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5ZF5GZW"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->

		<?php 
			include "inc/body_before.php";
		?>
		<div class="wrap">			
			<div id="cWRAP" class="swrap" data-href="<?php echo $scripPage?>">	
			<?php				
			include("page/index.php");			
			?>
			</div>		
		</div>
		<?php 
			include "inc/body_after.php";
		?>
	</body>
</html>
<?php
}
?>