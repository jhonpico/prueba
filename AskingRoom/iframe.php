    <div class="barrasup">
        <div class="contenedor">
            <div class="row ">
                <form name="search" action="/<?php echo $_SURLS['tutor']?>/" method="get">
                    <div class="tutor_campobusqueda">
                        <div>
                            <div class="input-group i-group-s">                         
                                <input type="search" class="form-control input-search input-lg" aria-label="Text input with checkbox" placeholder="Buscar Tutores" name="term" value="<?php echo $term?>">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="tutor_selec">
                        <div class="buscar_materias_cont">
                            <div class="bucar_materias_div">Materias</div>
                            <ul class="bucar_materias_ul">
                            <?php
                                foreach ($materias as $key => $plan) {?>
                                <li class="asinatura_ul_li"><a class="linkdecoration numero" href="/<?php echo $_SURLS['tutores']?>/<?php echo $plan["slug"] ?>"><?php echo $plan["name"] ?></a></li>
                            <?php }?>
                            </ul>
                        </div> 
                        <div class="buscar_materias_cont">
                            <div class="bucar_grados_div">Grados</div>
                            <ul class="bucar_grados_ul">
                            <?php
                                foreach ($grados as $key => $plan) {?>
                                <li class="asinatura_ul_li"><a class="linkdecoration numero" href="/<?php echo $_SURLS['tutores']?>/<?php echo $plan["slug"] ?>"><?php echo $plan["name"] ?></a></li>
                            <?php }?>
                            </ul>
                        </div>                      
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
if(!$_IFRAME){?>
    <div class="container top-padding">
        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-12">
                <h1 class="page-header"><?php echo $_ID==16?'Cursos':'VIDEO TUTORIALES'?></h1>
                <?php
                if(count($iframes)){
                    foreach ($iframes as $key => $iframe) {
                        $img='url('.$iframe['picture']['prefix'].$iframe['picture']['t03'].')';
                    ?>
                        <div class="col-md-6 _clase fodoblansombra">
                            <!--Primer Post -->
                            <h2 class="h2-page-header">
                                <a href="/<?php echo $_SURLS[$_ID==16?'clases':'herramientas'] ?>/<?php echo $iframe['slug'] ?>"><?php echo $iframe['title']?></a>
                            </h2>
                            <div class="_preimg">
                                <div class="_fix_35"></div>
                                <div class="_img" style="background-image:<?php echo $img ?>"></div>
                            </div>
                            <p class="p noticia"><?php echo $iframe['seo']['meta-description'] ?></p>
                            <p class="p link">
                                <a class="btn btn-red" href="/<?php echo $_SURLS[$_ID==16?'clases':'herramientas'] ?>/<?php echo $iframe['slug'] ?>"><?php echo $_ID==16?'Ver el Curso':'Ir a la Herramienta'?> <span class="glyphicon glyphicon-chevron-right"></span></a>
                            </p>
                            <hr>
                        </div>
                        <?php
                    }
                }
                else{
                ?>
               
                <?php
                }?>             
            </div>
        </div>
        <!-- /.row -->
    </div>
    <div class="row text-center" >
        <ul class="pagination">
            <?php
            $Pages=pages($PTotal,$PActual);
            if(isset($Pages['first'])){
            ?><li><a href="#" aria-label="Previous" data-page="<?php echo $Pages['first']?>"><span aria-hidden="true">&laquo;</span></a></li>
            <?php
            }
            if(isset($Pages['prev'])){?>
                <li><a href="#" aria-label="Previous" data-page="<?php echo $Pages['prev']?>"><span aria-hidden="true">&lsaquo;</span></a></li>
            <?php
            }
            foreach ($Pages['pages'] as $kPage => $Page) {
            ?>
                <li><a href="#" <?php echo $Page==$PActual?'class="active"':''?> data-page="<?php echo $Page?>"><?php echo $Page?></a></li>
            <?php
            }
            if(isset($Pages['next'])){
            ?><li><a href="#" aria-label="Next" data-page="<?php echo $Pages['next']?>"><span aria-hidden="true">&rsaquo;</span></a></li>
            <?php
            }
            if(isset($Pages['last'])){?>
                <li><a href="#" aria-label="Next" data-page="<?php echo $Pages['last']?>"><span aria-hidden="true">&raquo;</span></a></li>
            <?php
            }?>
        </ul>
    </div>
    <!-- /.container -->
<?php
}
else{
?>
    <div class="container top-padding">
        <div class="row">
            <div class="col-lg-12">
                <!-- TTitulo-->
                <h1><?php echo $iframe['title']?></h1>
                 <!-- Imagen -->
                <iframe width="853" height="480" src="<?php echo $iframe['content']?>" frameborder="0" allowfullscreen></iframe>
                <!-- Contenido -->
                <p class="lead"><?php echo $iframe['seo']['meta-description']?></p>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
<?php
}