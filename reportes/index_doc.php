<?php

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

require "dist/conf/require_conf.php";
session_start();

if (isset($_SESSION['user'])) {
 
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Reportes Compromiso Escolar</title>
            <!-- Tell the browser to be responsive to screen width -->
            <?php require "dist/css/css.php"; ?>

        </head>
      
        <body class="hold-transition skin-blue sidebar-mini">

            <div class="wrapper" id="subir_head">

                <header class="main-header">
                  
                    <a href="" class="logo">
                   
                        <img src="dist/img/logo.png" alt="" srcset="">
                    </a>
                  
                    <nav class="navbar navbar-static-top">
                      
                        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="titulo">PANEL DE REPORTES COMPROMISO ESCOLAR</span>
                        </a>
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                              
                                <li class="dropdown user user-menu">                                   
                                         <a href="../salir.php">                         
                                          <?php echo $_SESSION["profesor_nombres"];?> <strong>|</strong> <i class="fa fa-sign-out" aria-hidden="true"></i>Salir
                                         </a>                                 
                                </li>                                

                            </ul>
                        </div>
                    </nav>
                </header>
               
                <aside class="main-sidebar">
                 
                    <section class="sidebar">
                       
                        <div class="user-panel">
                            <div class="pull-left image">
                               <br>
                            </div>
                            <div class="pull-left info">
                                <p><?php echo $_SESSION["tipo_nombre"]; ?></p> 
                                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                             
                            </div>
                        </div>
                       
                        <ul class="sidebar-menu" data-widget="tree">
                            <li class="header">Menú De Navegación</li>

                            <?php
                            $id_rol = $_SESSION["tipo_usuario"];

                            $menu = menu_modulos($id_rol);
                            foreach ($menu as $fila) {
                                echo $fila["menu"];
                            }
                            ?>                           
                            
                        </ul>
                      
                    </section>
                    <!-- /.sidebar -->
                </aside>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">                   
                    <section class="content-header">
                        <h1 class="text-center">
                            Curso <span id="curso"></span>
                        </h1>
                        <ul class="nav nav-pills">                         
                            <li  class="active"><a class="curso_dimensiones" data-toggle="pill" href="#dimensiones"><span>Dimensiones</span></a></li>
                            <li><a data-toggle="pill" href="#niveles">Niveles</a></li>
                          
                        </ul>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">

    <?php tarjeta_de_presentacion($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
   
                        </div>
                        <!-- /.row -->
                        <!-- Main row -->
                        <div class="row">
                   
                            <!-- Left col -->
                            <section class="col-lg-12 connectedSortable">
                              
                          <button id="btn_curso" style="margin-top:2.3%;" class="btn btn-primary pull-right"><span  id="btn_reporte_curso" hidden><a class="text-white" href="reporte_curso.php" target="_blank">Descargar Reporte <i class="fa fa-download" aria-hidden="true"></i></a></span> <span id="cargando_reporte_curso">Cargando Reporte<i class="fa fa-spinner fa-spin fa-fw"></i></span></button>
                                <div class="tab-content">
                                  
                                    <div id="dimensiones" class="tab-pane fade in active mt-4">
                                        <ul class="nav nav-pills">
                                            <li class="active"><a data-toggle="pill" href="#dimensiones_compro_escolar">Compromiso Escolar</a></li>
                                            <li><a data-toggle="pill" href="#dimensiones_factores_contextuales">Factores Contextuales</a></li>
                                           
                                        </ul>
                                        <div class="tab-content">
                                            <div id="dimensiones_compro_escolar" class="tab-pane fade in active mt-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading text-center"><h4><b>Compromiso Escolar</b></h4></div>
                                                    <div class="panel-body">
                                                       <ul class="nav nav-pills">
                                                        <li class="active"><a data-toggle="pill" href="#dimension_afect">Afectivo</a></li>
                                                        <li><a data-toggle="pill" href="#dimension_conduc">Conductual</a></li>
                                                        <li><a data-toggle="pill" href="#dimension_cogniti">Cognitivo</a></li>
                                                    </ul>

                                                        <div class="tab-content">
                                                            <div id="dimension_afect" class="tab-pane fade in active mt-4">
    <?php include "partes/alertas_info.php"; ?>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered" style="font-size:14px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Items</th>                                                                              
                                                                                <th>NU</th>
                                                                                <th>AL</th>
                                                                                <th>AM</th>
                                                                                <th>MV</th>
                                                                                <th>SC</th>                                                                                                                                     
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size:16px;">
    <?php echo dimension_afectivo_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div id="dimension_conduc" class="tab-pane fade">
    <?php include"partes/alertas_info.php"; ?>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Items</th>                                                                               
                                                                                <th>NU</th>
                                                                                <th>AL</th>
                                                                                <th>AM</th>
                                                                                <th>MV</th>
                                                                                <th>SC</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size:16px;">
    <?php dimension_conductual_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div id="dimension_cogniti" class="tab-pane fade">
    <?php include"partes/alertas_info.php"; ?>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Items</th>                                                                             
                                                                                <th>NU</th>
                                                                                <th>AL</th>
                                                                                <th>AM</th>
                                                                                <th>MV</th>
                                                                                <th>SC</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size:16px;">
    <?php dimension_cognitivo_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <div id="dimensiones_factores_contextuales" class="tab-pane fade mt-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading text-center"><h4><b>Factores Contextuales</b></h4></div>
                                                    <div class="panel-body">
                                                        <ul class="nav nav-pills">
                                                            <li class="active"><a data-toggle="pill" href="#dimension_familia">Apoyo Familiar</a></li>
                                                            <li><a data-toggle="pill" href="#dimension_pares">Apoyo Pares</a></li>
                                                            <li><a data-toggle="pill" href="#dimension_profesores">Apoyo Profesores</a></li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <div id="dimension_familia" class="tab-pane fade in active mt-4">
    <?php include "partes/alertas_info.php"; ?>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Items</th>                                                                               
                                                                                <th>NU</th>
                                                                                <th>AL</th>
                                                                                <th>AM</th>
                                                                                <th>MV</th>
                                                                                <th>SC</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size:16px;">
    <?php dimension_apoyo_familiar_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div id="dimension_pares" class="tab-pane fade mt-4">
    <?php include "partes/alertas_info.php"; ?>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Items</th>                                                                                
                                                                                <th>NU</th>
                                                                                <th>AL</th>
                                                                                <th>AM</th>
                                                                                <th>MV</th>
                                                                                <th>SC</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size:16px;">
    <?php dimension_pares_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div id="dimension_profesores" class="tab-pane fade mt-4">
    <?php include "partes/alertas_info.php"; ?>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Items</th>                                                                               
                                                                                <th>NU</th>
                                                                                <th>AL</th>
                                                                                <th>AM</th>
                                                                                <th>MV</th>
                                                                                <th>SC</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size:16px;">
    <?php dimension_profesores_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div id="niveles" class="tab-pane fade">
                                        <ul class="nav nav-pills">
                                            <li class="active"><a data-toggle="pill" href="#niveles_compromiso_escolar">Compromiso Escolar</a></li>
                                            <li><a data-toggle="pill" href="#niveles_factores_contextuales">Factores Contextuales</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="niveles_compromiso_escolar" class="tab-pane fade in active mt-4">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading text-center"><h4>Compromiso Escolar</h4></div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12 mt-4">
    <?php include 'partes/alerta_niveles.php'; ?>
                                                            <div class="mt-4">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-4">
                                                                        <div class="bordes-div">

                                                                            <div id="grafico_nivel_curso_afectivo" style="min-width: 300px; height: 400px; max-width: 600px; margin: 0 auto"></div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="bordes-div">

                                                                            <div id="grafico_nivel_curso_conductual" style="min-width: 300px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="bordes-div">

                                                                            <div id="grafico_nivel_curso_cognitivo" style="min-width: 300px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="niveles_factores_contextuales" class="tab-pane fade">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading text-center"><h4>Factores Contextuales</h4></div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12 mt-4">
    <?php include 'partes/alerta_niveles_fc.php'; ?>
                                                            <div class="mt-4">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-4">
                                                                      <div class="bordes-div"> 

                                                                        <div id="grafico_nivel_apoyo_familia"></div>

                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                      <div class="bordes-div">
                                                                        <div id="grafico_nivel_apoyo_profesores"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                     <div class="bordes-div">
                                                                        <div id="grafico_nivel_apoyo_pares"></div>
                                                                    </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div id="dispersion" class="tab-pane fade">
                                        <ul class="nav nav-pills">
                                            <li class="active"><a data-toggle="pill" href="#dispersion_estudiantes_compromiso_escolar">Compromiso Escolar</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="dispersion_estudiantes_compromiso_escolar" class="tab-pane fade in active">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading text-center"><h4>Estudiantes</h4></div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12 mt-4">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                    </section>

                    <!-- /.content -->

                </div>
                <!-- /.content-wrapper -->
              </div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">       
        <a><i class="fa fa-arrow-circle-up fa-3x hvr-grow" aria-hidden="true" onclick="subir_a_cabezera();"></i></a>
    </div>
   
</footer>

<div class="control-sidebar-bg"></div>

            
            <?php
 
            nom_establecimiento($_SESSION["id_establecimiento"]);

            $ce = suma_total_dimension_compromiso_escolar_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);


            $demos_fc = suma_total_dimension_factores_contextuales_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

            $compromiso_escolar = suma_afectivo_coductual_cognitivo_final($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

            $factores_contextuales = fc_suma_familiar_pares_profesores($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);


            ?>

            <!-- ./wrapper -->
            <?php require "dist/js/js.php"; ?>

            <?php include "partes/graficos_curso.php"; ?>
            <script>

                
$('#btn_curso').attr("disabled", true);
              
                setTimeout(() => {
                    $('#btn_reporte_curso').show();
                    $('#cargando_reporte_curso').hide();
                    $('#btn_curso').attr("disabled", false);
                }, 5000);
                sesion();
                </script>
        </body>
    </html>
    <?php
} else {
    header("location:login.php");
}
