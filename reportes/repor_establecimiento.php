<?php
require_once "dist/conf/require_conf.php";
session_start();
/*
$id_establecimiento = 5;
$id_profesor = 11;
$id_curso = 10;
*/
$usuario = $_SESSION['user'];
$tipo_usuario = $_SESSION["tipo_usuario"];

$tipo = segun_tipo_usuario($usuario, $tipo_usuario);


if(isset($_SESSION['user'])){
    if($_SESSION['user'] == "123" ){
        header("location:index_admin.php");
    }


if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    unset($_SESSION['status']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reportes Compromiso Escolar</title>
    <!-- Tell the browser to be responsive to screen width -->
    <?php require "dist/css/css.php"; ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" id="subir_head">

    <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <img src="dist/img/logo.png" alt="" srcset="">
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" aria-expanded="false" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="titulo">PANEL DE REPORTES COMPROMISO ESCOLAR</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php
                           echo iniciales($_SESSION["profesor_nombres"]);  
                             ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                   <?php echo  $_SESSION["profesor_nombres"];?>
                                    <small><?php echo fecha_hora(); ?></small>
                                </p>
                            </li>
                           
                            <!-- Menu Footer-->
                            <li class="user-footer">                                
                                <div class="pull-right">
                                    <a href="../salir" class="btn btn-default btn-flat">Salir</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php echo  $_SESSION["tipo_nombre"];?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Menú De Navegación</li>
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-user-circle-o"></i> <span> Profesor</span>
                        <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index_doc.php"><i class="fa fa-users"
                                                                      aria-hidden="true"></i>Curso</a></li>
                        <li  class="active"><a href="estudiante.php"><i class="fa fa-user" aria-hidden="true"></i>Estudiantes</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-university"></i>
                        <span>Establecimiento</span>
                        <span class="pull-right-container">
                                    <span class="label label-primary pull-right">4</span>
                                </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                        <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                        <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                        <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed
                                Sidebar</a></li>
                    </ul>
                </li>
                <li>
                    <a href="pages/widgets.html">
                        <i class="fa fa-th"></i> <span>Sostenedor</span>
                        <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                    </a>
                </li>
                <li>
                    <a href="pages/widgets.html">
                        <i class="fa fa-user-secret"></i> <span>Zona Administrador</span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div id="contenido_por_estudiante">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                  Aqui va establecimiento<label id="establecimiento"></label>
                </h1>

            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <?php if (isset($status)): ?>
                        <div class="alert alert-<?php echo $status['type'] ?>">
                            <strong><?php echo $status['message'] ?></strong>
                        </div>
                    <?php endif ?>
                </div>

                <div class="row">
                    <?php include "partes/tarjetas_presentaciones_curso.php"; ?>
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-xs-6">
                                <label>Cursos Asociados <i class="fa fa-users"></i>:</label>
                                <form action="reporte_demo.php" id="intermediate" name="inputMachine[]" multiple="multiple" method="post">
                                <select class="sle_establecimiento" name="sle_establecimiento[]" id="sle_establecimiento" multiple="multiple" style="width: 100%">
                                    <?php
                                     select_curso_por_establecimiento($_SESSION["id_establecimiento"]);
                                    ?>
                                </select>
                                <input type="submit" class="btn btn-primary" value="buscar"/> 
</form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--<button class="btn btn-primary pull-right">Descargar Reporte <i class="fa fa-download" aria-hidden="true"></i></button>-->
                        </div>
                    </div>
                </div>

                <div class="row">
                
                    <!-- Left col -->
                    <section class="col-lg-12 connectedSortable">
                    <div class="panel panel-info mt-1">
      <div class="panel-heading">Panel with panel-info class</div>
      <div class="panel-body">
      <div class="col-md-12">
                                    <div id="recibe_resultados_estudiantes_fc"></div>
                              
                        </div>
      </div>
    </div>

                                
                    </section>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
       
        <a><i class="fa fa-arrow-circle-up fa-3x hvr-grow" aria-hidden="true" onclick="subir_a_cabezera();"></i></a>
    </div>
    <strong>Copyright &copy; 2018 <a>Softpatagonia</a>.</strong> All rights
    reserved.
    <div id="container_dispersionn"></div>
</footer>

<div class="control-sidebar-bg"></div>

<?php

?>
<!-- ./wrapper -->
<?php require "dist/js/js.php"; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>

$(document).ready(function() {
    $('.sle_establecimiento').select2();
});
zoom();
var combo = document.getElementById("establecimiento");
var selected = combo.options[combo.selectedIndex].value;

//carga_individual_estudiante_factores_contextuales(selected);

//carga_factores_contextuales_index(combo);
//document.getElementById('estudi_curso').innerText = "<//?php curso_de_establecimiento($_SESSION["id_profesor"]);?>";
</script>
</body>
</html>
<?php 
}else{
header("location:login.php");
}
