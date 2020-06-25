<?php

require_once "dist/conf/require_conf.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}

$conn = connectDB_demos();

$cursos_basica = [];
$cursos_media = [];

$usuario_stmt = $conn->prepare('SELECT * FROM ce_usuarios where nombre_usu = :username');
$usuario_stmt->execute([
    'username' => $_SESSION['user']
]);

$usuario = $usuario_stmt->fetch();

$role = $conn->query("SELECT ce_roles.* from ce_roles join ce_rol_user cru on ce_roles.id_rol = cru.id_roles_fk where cru.id_usuario_fk = {$usuario['id_usu']} AND id_rol = 2")->fetch();

if (!$role) {
    header("location: index.php");
    exit();
}

$establecimiento_id = $usuario['fk_establecimiento'];

$establecimiento_stmt = $conn->prepare("SELECT * FROM ce_establecimiento WHERE id_ce_establecimiento = :id");
$establecimiento_stmt->execute([
    'id' => $establecimiento_id
]);

$establecimiento = $establecimiento_stmt->fetch();

$cursos_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
       COUNT(id_ce_participantes)                                as participantes,
       SUM(ce_p1 + ce_p2 + ce_p3 + ce_p4 + ce_p5 + ce_p6 + ce_p7 + ce_p8 + ce_p9 + ce_p10 +
           ce_p11 + ce_p12 + ce_p13 + ce_p14 + ce_p15 + ce_p16 + ce_p17 + ce_p18 + ce_p19 + ce_p20 +
           ce_p21 + ce_p22 + ce_p23 + ce_p24 + ce_p25 + ce_p26 + ce_p27 + ce_p28 + ce_p29) /
       COUNT(ce_participantes.id_ce_participantes)               AS CE,
       SUM(ce_p30 +
           ce_p31 +
           ce_p32 +
         #Inicio de preguntas Apoyo Profesores
           ce_p33 +
           ce_p34 +
           ce_p35 +
           ce_p36 +
           ce_p37 +
           ce_p38 +
           ce_p39 +
           ce_p40 +
         #Inicio de preguntas Apoyo Pares
           ce_p41 +
           ce_p42 +
           ce_p43 +
           ce_p44 +
           ce_p45 +
           ce_p46 +
           ce_p47) / COUNT(ce_participantes.id_ce_participantes) AS FC,
       ce_participantes.ce_fk_nivel as nivel
FROM ce_encuesta_resultado
       JOIN ce_participantes ON ce_participantes_token_fk = ce_participanes_token
       JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
WHERE ce_estado_encuesta = 1
  AND ce_establecimiento_id_ce_establecimiento = :id
group by ce_curso_nombre");

$cursos_stmt->execute([
    'id' => $establecimiento_id
]);

while ($cursos_result = $cursos_stmt->fetch()) {
    $curso = [
        'name' => $cursos_result['nombre'],
        'x' => (float) $cursos_result['FC'],
        'y' => (float) $cursos_result['CE'],
        'participantes' => (int) $cursos_result['participantes']
    ];

    if ($cursos_result['nivel'] == 1) {
        $curso['color'] = 'rgb(206, 225, 255)';

        array_push($cursos_basica, $curso);
    } else {
        if ($cursos_result['nivel'] == 2) {
            $curso['color'] = 'rgb(95, 55, 188)';

            array_push($cursos_media, $curso);
        }
    }
}

$totalParticipantesBasica = array_reduce($cursos_basica, function ($accum, $item) {
    return $accum + $item['participantes'];
}, 0);

$totalParticipantesMedia = array_reduce($cursos_media, function ($accum, $item) {
    return $accum + $item['participantes'];
}, 0);

if($totalParticipantesBasica == 0){
    $hidden_basica = "hidden";
}else{
    $hidden_basica = "";
}
if($totalParticipantesMedia == 0){
    $hidden_media = "hidden";
}else{
    $hidden_media = "";
}
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
                                         <?php echo $establecimiento['ce_establecimiento_nombre'] ?> <strong>|</strong> <i class="fa fa-sign-out" aria-hidden="true"></i>Salir
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
                                <p>Establecimiento</p> 
                                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                             
                            </div>
                        </div>
                       
                        <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">Menú De Navegación</li>
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-th"></i> <span>Establecimiento</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>

                    <ul class="treeview-menu">
                        <li>
                            <a href="#">Reportes</a>
                        <li><a href="../modulos.php"><i class="fa fa-arrow-left" aria-hidden="true"></i></i>Volver</a>
                        </li>
                        </li>
                    </ul>
                </li>

                                                     
                            
                        </ul>
                        </li>
                        </ul>
                    </section>
                    <!-- /.sidebar -->
                </aside>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">                   
                    <section class="content-header">
                        <h1 class="text-center">
                            Establecimiento <?php echo $establecimiento['ce_establecimiento_nombre']; ?></span>
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

       <?php tarjeta_de_presentacion_establecimiento($establecimiento_id); ?>
   
                        </div>
                        <!-- /.row -->
                        <!-- Main row -->
                        <div class="row">
                   
                            <!-- Left col -->
                            <section class="col-lg-12 connectedSortable">
                            <a id="btn_save" href="reporte_curso.php" target="_blank" class="btn btn-primary pull-right" style="margin-right:2%;margin-bottom:-20%;">Descargar Reporte <i class="fa fa-download" aria-hidden="true"></i></a>
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
    <?php echo dimension_afectivo_establecimiento($establecimiento_id);?>
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
    <?php dimension_conductual_establecimiento($establecimiento_id); ?>
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
    <?php dimension_cognitivo_establecimiento($establecimiento_id); ?>
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
    <?php dimension_apoyo_familiar_establecimiento($establecimiento_id); ?>
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
    <?php dimension_pares_establecimiento($establecimiento_id); ?>
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
    <?php  dimension_profesores_establecimiento($establecimiento_id); ?>
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
 /*
            nom_establecimiento($_SESSION["id_establecimiento"]);

            $ce = suma_total_dimension_compromiso_escolar_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);


            $demos_fc = suma_total_dimension_factores_contextuales_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

            $compromiso_escolar = suma_afectivo_coductual_cognitivo_final($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

            $factores_contextuales = fc_suma_familiar_pares_profesores($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

*/
            ?>

            <!-- ./wrapper -->
            <?php require "dist/js/js.php"; ?>

            <?php include "partes/graficos_curso.php"; ?>
            <script>
                sesion();
                </script>
        </body>
    </html>
    <?php
