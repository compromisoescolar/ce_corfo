<?php
require_once "dist/conf/require_conf.php";
session_start();

if(isset($_SESSION['user'])){
    ce_carpetas();
$usuario = $_SESSION['user'];
$tipo_usuario = $_SESSION["tipo_usuario"];

$tipo = segun_tipo_usuario($usuario, $tipo_usuario); 


if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    unset($_SESSION['status']);
}

$_SESSION["token"] = md5(uniqid(mt_rand(), true));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reportes Compromiso Escolar </title>
    <!-- Tell the browser to be responsive to screen width -->
    <?php require "dist/css/css.php"; ?>
    <style type="text/css">
  .table td:first-child, .table th:first-child {
    min-width: 1000px;
    width: 1000px;
    
  }
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" id="subir_head">

    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <img src="dist/img/logo.png" alt="" srcset="">
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" aria-expanded="false" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="titulo">PANEL DE REPORTES COMPROMISO ESCOLAR </span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="../salir.php?csrf=<?php echo $_SESSION["token"];?>">                         
                           <?php
                           echo $_SESSION["profesor_nombres"];?> <strong>|</strong> <i class="fa fa-sign-out" aria-hidden="true"></i>Salir
                        </a>
                       
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
                   <br>
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
                        <li><a href="index_doc.php"><i class="fa fa-users" aria-hidden="true"></i>Curso</a></li>
                        <li class="active"><a href="estudiante.php"><i class="fa fa-user" aria-hidden="true"></i>Estudiantes</a></li>
                         
                    </ul>
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
                <h1 class="text-center">
                    Estudiantes de <span id="estudi_curso"></span>
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
                                <label>Seleccione un Estudiante <i class="fa fa-user"></i>:</label>
                                <select name="sle_estudiantes" id="sle_estudiantes" class="form-control">
                                    <?php
                                    select_estudiantes_por_curso($tipo["id_ce_docente"]);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                          
                        </div>
                    </div>
                </div>

                <div class="row">
                
                    <!-- Left col -->
                    <section class="col-lg-12 connectedSortable">
                    
                        <div class="tab-content">
                            <div class="row">
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
  </div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">       
        <a><i class="fa fa-arrow-circle-up fa-3x hvr-grow" aria-hidden="true" onclick="subir_a_cabezera();"></i></a>
    </div>
   
</footer>

<div class="control-sidebar-bg"></div>

          
<?php require "dist/js/js.php"; ?>

<script>
sesion();
var combo = document.getElementById("sle_estudiantes");
var selected = combo.options[combo.selectedIndex].value;

carga_individual_estudiante_factores_contextuales(selected);

carga_factores_contextuales_index(combo);
document.getElementById('estudi_curso').innerText = "<?php curso_de_establecimiento($_SESSION["id_profesor"]);?>";
</script>
</body>
</html>
<?php 
}else{
header("location:login.php");
}
