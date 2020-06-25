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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Reportes Compromiso Escolar </title>
    <?php require "dist/css/css.php"; ?>
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/librerias/highcharts/highcharts.js"></script>
<script src="../assets/librerias/highcharts/highcharts-more.js"></script>
<script src="../assets/librerias/highcharts/modules/exporting.js"></script>
<script src="../assets/librerias/highcharts/modules/offline-exporting.js"></script>

    <style type="text/css">
        html {
            padding: 0;
            margin: 0;
        }

        body {
            padding: 0;
            margin: 0;
        }

        @media (max-width: 1299px) {
            .main-sidebar {
                -webkit-transform: translate(-230px, 0);
                -ms-transform: translate(-230px, 0);
                -o-transform: translate(-230px, 0);
                transform: translate(-230px, 0);
            }
        }

        @media (min-width: 1300px) {
            .sidebar-collapse .main-sidebar {
                -webkit-transform: translate(-230px, 0);
                -ms-transform: translate(-230px, 0);
                -o-transform: translate(-230px, 0);
                transform: translate(-230px, 0);
            }
        }

        @media (max-width: 1299px) {
            .sidebar-open .main-sidebar {
                -webkit-transform: translate(0, 0);
                -ms-transform: translate(0, 0);
                -o-transform: translate(0, 0);
                transform: translate(0, 0);
            }
        }

        @media (max-width: 1299px) {
            .main-sidebar {
                -webkit-transform: translate(-230px, 0);
                -ms-transform: translate(-230px, 0);
                -o-transform: translate(-230px, 0);
                transform: translate(-230px, 0);
                margin-left: 0;
            }
        }

        @media (max-width: 1300px) {
            .content-wrapper, .main-footer {
                margin-left: 0;
            }
        }

        #id_btn_grafica {
            width: 50%;
            padding-right: 5px;
        }

        @media only screen and (max-width: 1100px), (min-device-width: 1099px) and (max-device-width: 1024px)  {

            table.botones_sup,  tr#btn_sup , td.td-res {
                display: block;
            }

            #id_btn_grafica, #id_btn_descargar {
                position: relative;
                width: 100%;
                text-align: left;
                padding-right: 0;
            }

        }

        .table td:first-child, .table th:first-child {
            width: 50%;
        }

        .main-sidebar {
            background: #f4af1f;
        }       

        .header {
            background: #f27611;
        }

        .titulo_sup {
            padding-top: 0;
            margin: 0;
            color: white;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .sidebar {
            width: 220px; 
            float: right;
            margin-top: 0;
        }

        .user-panel {
            background: #f27611;
        }

        .content-header {
            background: #f4af1f; 
        }

        .panel {
            width:  100%;
            margin: 0;
            padding: 0;
        }

        .panel-body {
            width:  100%;
            padding: 15px;
            margin-top: 0;
        }

        .cursor_dimensiones{
            text-decoration: none;
        }

        div.label-alerta-media.hvr.hvr-grow {
            text-decoration: none;
        }

        .skin-blue .sidebar-menu .treeview-menu > li.active > a, .skin-blue .sidebar-menu .treeview-menu > li > a:hover {
            color: #ffffff;
            background-color: #f2761185;
        }

        .skin-blue .sidebar-menu > li > .treeview-menu {
            margin: 0 1px;
            background: #f27611;
        }

        .skin-blue .sidebar-menu > li > a {
            border-left-color: white;
        }

        .skin-blue .sidebar-menu .treeview-menu > li > a {
            color: #ffffff85;
        }

        .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a {
            color: #ffffff;
            background: #f4af1f;
        }

        .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.treeview > a {
            color: #ffffff;
            background: #f4af1f;
        }

        .tit-menu {
            float: left; 
            text-align: left; 
            color: white;
        }

        .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side { 
            background-color: #f4af1f;
        }

        .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
            font-size: 18px;
            font-weight: 400;
            color: black;
            background-color: #e6e6e6;
            border-bottom-color: #e6e6e68c;
        }

        .nav-pills > li > a { 
            font-size: 18px;
            border-radius: 0;
            border-top: 3px solid transparent;
            color: white;
            background-color: #cfd0d1;
        }

        .nav-pills > li.pest_li.active > a { 
            font-size: 18px;
            border-radius: 0;
            border-top: 3px solid transparent;
            color: color: #000000;
            background-color: #cfd0d1; /* gris claro */
        }

        .nav-pills > li.pest_li > a { 
            font-size: 18px;
            border-radius: 0;
            border-top: 0.5px solid white;
            border-left: 0.5px solid white;
            border-right: 0.5px solid white;
            color: white;
            background-color: #f4af1f; /* zapallo */
        }

        .nav-pills > li.pest_li:focus > a { 
            background-color: #f4af1f7d; /* zapallo */
        }

        .id_recur_edu {
            margin-top: 210px;
            margin-left: 20px;
            padding-top: 1px;
            padding-left: 10px;
            width: 195px;
            height: 300px;
            background-image: url("../assets/img/recursos-educativos.png");
            background-repeat: no-repeat;
            background-size:100% auto;
            text-shadow: 0.5px 0.5px 0.5px black;
            color: white; 
            border-radius: 10px;
        }

        .id_recur_edu:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        .id_recur_edu:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }

        #img_btn_salir:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        #img_btn_salir:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }

        .btn_side:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        .btn_side:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }

        .btn_cuadrado:hover { 
            cursor: pointer;
            opacity: 0.7;
        }

        .btn_cuadrado:active { 
            cursor: pointer;
            opacity: 0.4;
        }

        
    </style>
    <style>
        .loader {
            position: absolute;
            left: calc(50% + 15px);
            background: transparent;
          border: 16px solid #99CC99;
          border-radius: 50%;
          border-top: 16px solid #3498DB;
          width: 60px;
          height: 60px;
          -webkit-animation: spin 1s linear infinite; /* Safari */
          animation: spin 1s linear infinite;
        }
        /* Safari */
        @-webkit-keyframes spin {
          0% { -webkit-transform: rotate(0deg); }
          100% { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background: #BDC3C7; ">
    <div id="linea-superior" style="background: #40c2d4; padding-left: 100px; "> 
        <table width="100%" height="100%" id="linea-superior_tab">
            <tr width="100%"> 
                <td align="left" width="50%">
                    <div style="display: flex; align-items: baseline; background: #40c2d4;">
                        <img style="height: 78px; width: 750px;"  src="../assets/img/C2_Resultados.png">
                        <div style="margin-top: 30px; margin-left: 195px; font-size: 20px; position: absolute; color: white;">
                            Módulo de Resultados
                        </div>
                    </div>
                </td> 
                <td align="right" width="50%">
                    <table width="100%">
                        <tr width="100%">
                            <td id="id_td_id_nombre" align="right" width="50%">
                                <h3 id="id_nombre" style="color: white;">
                                    <?php echo $_SESSION["profesor_nombres"];?>    
                                </h3>
                            </td>
                            <td align="right" width="50%">
                                <button id="btn_cerrar_session" style="text-decoration: none; background: transparent; width: 100%; height: 100%;  background-repeat: no-repeat; border-radius: 35px; border: none; cursor:pointer; overflow: hidden; outline:none; background-position: center;">
                                    <img id="img_btn_salir" style="width: 128px; height: 48px; " src="../assets/img/salir-2.png">
                                </button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="contenido row justify-content-center align-items-center">
        <div class="card_c" style="padding-right: 200px; padding-left: 200px; padding-top: 10px;padding-bottom: 10px;">
            <div class="wrapper card_c" id="subir_head" style="border: 1.5px solid #A5A1A0; border-radius: 10px;">
                <div id="curso_p" style="display: none;">
                    <aside class="main-sidebar" style="background: #f4af1f;">
                        
                        <div style="position: absolute; top: 0px; left: 16px; color: white;">   
                            <h4><?php echo $_SESSION["tipo_nombre"]; ?></h4>

                        </div>
                        
    <section class="sidebar">
        <div class="user-panel">
            
            <div class="tit-menu">
                <p>
                   Menú de Navegación
                </p>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
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
    <div align="left" class="id_recur_edu">    
        
    </div>
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header" style="background: #f4af1f; display: flex; justify-content: center; text-align: center;">
        <a style="width: 60px; height: 60px; border: 0; color: white; background: #f4af1f; padding: 0; font-size: 30px;" class="openbtn btn_side">☰</a>
        <ul class="nav nav-pills" style="margin-top: 37px; display: flex; justify-content: flex-start; text-align: left;">
            <li class="pest_li active" style="">
                <a class="curso_dimensiones" data-toggle="pill" href="#dimensiones">
                    <span>Dimensiones</span>
                </a>
            </li>
            <li class="pest_li">
                <a id="nivel" data-toggle="pill" href="#niveles">
                    Niveles
                </a>
            </li>
        </ul>
        <div style="width: 100%; color: white;">
            <h3 class="text-center" style="position: relative; right: 10%; ">
                Curso 
                <span id="curso"></span>
            </h3>
        </div>

    </section>
    <section class="content">
        <div class="row">
            <?php 
                tarjeta_de_presentacion(
                    $_SESSION["id_establecimiento"], 
                    $_SESSION["id_profesor"]
                ); 
            ?>
        </div>
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <button id="btn_curso" style="margin-top:2.3%;" class="btn btn-primary pull-right">
                    <span id="btn_reporte_curso" hidden>
                        Descargar Reporte 
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </span> 
                    <span id="cargando_reporte_curso">
                        Cargando Reporte
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                    </span>
                </button>
                <div class="tab-content">
                    <div id="dimensiones" class="tab-pane fade in active mt-4">
                        <ul class="nav nav-pills">
                            <li class="active gray-pestana">
                                <a data-toggle="pill" href="#dimensiones_compro_escolar">
                                    Compromiso Escolar
                                </a>
                            </li>
                            <li>
                                <a class="gray-pestana" data-toggle="pill" href="#dimensiones_factores_contextuales">   
                                    Factores Contextuales
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="dimensiones_compro_escolar" class="tab-pane fade in active mt-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4><b>Compromiso Escolar</b></h4></div>
                                    <div class="panel-body">
                                        <ul class="nav nav-pills">
                                            <li class="active">
                                                <a data-toggle="pill" href="#dimension_afect">
                                                    Afectivo
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="pill" href="#dimension_conduc">
                                                    Conductual
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="pill" href="#dimension_cogniti">
                                                    Cognitivo
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="dimension_afect" class="tab-pane fade in active mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-responsive-sm table-hover" style="text-align: justify; text-justify: inter-word; font-size: 13px; border: 1px #d9edf7;">
                                                            <thead style="background: #d9edf7;">
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    echo dimension_afectivo_curso_copia(
                                                                        $_SESSION["id_establecimiento"], 
                                                                        $_SESSION["id_profesor"]
                                                                    ); 
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                            <div id="dimension_conduc" class="tab-pane fade">
                                                <br>
                                                <?php include"partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-responsive-sm table-hover" style="text-align: justify; text-justify: inter-word; font-size: 13px; border: 1px #d9edf7;">
                                                            <thead style="background: #d9edf7;">
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php dimension_conductual_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                            <div id="dimension_cogniti" class="tab-pane fade">
                                                <br>
                                                <?php include"partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-responsive-sm table-hover" style="text-align: justify; text-justify: inter-word; font-size: 13px; border: 1px #d9edf7;">
                                                            <thead style="background: #d9edf7;">
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
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
                                    <div class="panel-heading text-center">
                                        <h4><b>Factores Contextuales</b></h4></div>
                                    <div class="panel-body">
                                        <ul class="nav nav-pills">
                                            <li class="active"><a data-toggle="pill" href="#dimension_familia">Apoyo Familia</a></li>
                                            <li><a data-toggle="pill" href="#dimension_pares">Apoyo Pares</a></li>
                                            <li><a data-toggle="pill" href="#dimension_profesores">Apoyo Profesores</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="dimension_familia" class="tab-pane fade in active mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-responsive-sm table-hover" style="text-align: justify; text-justify: inter-word; font-size: 13px; border: 1px #d9edf7;">
                                                            <thead style="background: #d9edf7;">
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php dimension_apoyo_familiar_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>

                                            <div id="dimension_pares" class="tab-pane fade mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-responsive-sm table-hover" style="text-align: justify; text-justify: inter-word; font-size: 13px; border: 1px #d9edf7;">
                                                            <thead style="background: #d9edf7;">
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php dimension_pares_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>

                                            <div id="dimension_profesores" class="tab-pane fade mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-responsive-sm table-hover" style="text-align: justify; text-justify: inter-word; font-size: 13px; border: 1px #d9edf7;">
                                                            <thead style="background: #d9edf7;">
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
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
                            <li class="active">
                                <a data-toggle="pill" href="#niveles_compromiso_escolar">
                                    Compromiso Escolar
                                </a>
                            </li>
                            <li>
                                <a data-toggle="pill" href="#niveles_factores_contextuales">
                                    Factores Contextuales
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="niveles_compromiso_escolar" class="tab-pane fade in active mt-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4>Compromiso Escolar</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12 mt-4">
                                            <?php include 'partes/alerta_niveles.php'; ?>
                                            <div class="mt-4">
                                                <table width="100%" cellspacing="30">
                                                    <tr align="center" valign="center">
                                                        <td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 2px black; border-bottom-right-radius: 30px;" width="33%" align="center" valign="center">
                                                            <div id="grafico_nivel_curso_afectivo" style="max-width: 80%; height: 300px;  margin: 0 auto">
                                                                
                                                            </div>
                                                        </td>
                                                        <td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 2px black; border-bottom-right-radius: 30px;" width="33%" align="center" valign="center">
                                                            <div id="grafico_nivel_curso_conductual" style="max-width: 80%; height: 300px;  margin: 0 auto"></div>
                                                        </td>
                                                        <td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 2px black; border-bottom-right-radius: 30px;" width="33%" align="center" valign="center">
                                                            <div id="grafico_nivel_curso_cognitivo" style="max-width: 80%; height: 300px;  margin: 0 auto">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="niveles_factores_contextuales" class="tab-pane fade" style="margin-top: 15px;">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4>Factores Contextuales</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12 mt-4">
                                            <?php include 'partes/alerta_niveles_fc.php'; ?>
                                            <div class="mt-4">
                                                <table width="100%">
                                                    <tr align="center" valign="center">
                                                        <td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 2px black; border-bottom-right-radius: 30px;" width="33%" align="center" valign="center">
                                                            <div id="grafico_nivel_apoyo_familia" style="max-width: 80%; height: 300px;  margin: 0 auto">
                                                                
                                                            </div>
                                                        </td>
                                                        <td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 2px black; border-bottom-right-radius: 30px;" width="33%" align="center" valign="center">
                                                            <div id="grafico_nivel_apoyo_profesores" style="max-width: 80%; height: 300px;  margin: 0 auto"></div>
                                                        </td>
                                                        <td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 2px black; border-bottom-right-radius: 30px;" width="33%" align="center" valign="center">
                                                            <div id="grafico_nivel_apoyo_pares" style="max-width: 80%; height: 300px;  margin: 0 auto">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
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
                                    <div class="panel-heading text-center">
                                        <h4>Estudiantes</h4></div>
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
</div>
                </div>
                <div id="estudiantes">
                    <aside class="main-sidebar" style="background: #f4af1f;">
                        <div style="position: absolute; top: 0px; left: 16px; color: white;">
                            <h4>
                                <?php echo $_SESSION["tipo_nombre"]; ?>
                            </h4>
                        </div>
                    <!-- sidebar: style can be found in sidebar.less -->
                    <section class="sidebar" style="width: 220px; float: right;">
                        <!-- Sidebar user panel -->
                        <div class="user-panel" style="background: #f27611;">
                            <div class="pull-left image">
                                <br>
                            </div>
                            <div class="tit-menu">
                                <p>
                                    Menú de Navegación
                                </p>
                            </div>
                        </div>
                        <!-- sidebar menu: : style can be found in sidebar.less -->
                        <ul  class="sidebar-menu" data-widget="tree" style="background: #f27611;">
                            <li  class="active treeview" style="background: #f27611;">
                                <a class="menu-flag" id="menu_flag" href="#">
                                    <i class="fa fa-user-circle-o"></i>
                                    <span> Profesor</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li style='padding: 5px;'>
                                        <a id="select_curso" href="#">
                                            <i class="fa fa-users" aria-hidden="true"></i> Curso
                                        </a>
                                    </li>
                                    <li style='padding: 5px;' class="active">
                                        <!-- estudiante.php -->
                                        <a id="" href="#">
                                            <i class="fa fa-user" aria-hidden="true"></i>Estudiantes
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </section>
                    <!-- /.sidebar -->
                    <div align="left" class="id_recur_edu">    
                        
                    </div>
                </aside>
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <section class="content-header" style="background: #f4af1f; display: flex; justify-content: center; text-align: center;">

                        <a style="width: 60px; height: 60px; border: 0; color: white; background: #f4af1f; padding: 0; padding-right: 10px; font-size: 30px;" class="openbtn btn_side">☰</a> 
                        <div style="width: 100%; display: flex; justify-content: center; text-align: center; padding-bottom: 15px;">
                            <h3 class="text-center" style="position: absolute; color: white;">
                                Estudiantes de <span id="estudi_curso">
                                    <?php curso_de_establecimiento($_SESSION["id_profesor"]);?></span>
                            </h3>
                        </div>
                        <ul class="nav nav-pills" style="margin-top: 15px; background: transparent;">
                            <li class="pest_li active" style="background: transparent;">
                                <a class="curso_dimensiones" data-toggle="pill" style="background: transparent; border: 0;" href="#dimensiones">
                                    <span style="background: transparent; color: transparent;">---</span>
                                </a>
                            </li>
                        </ul>
                    </section>
                    <div id="contenido_por_estudiante">
                        <!-- Content Header (Page header) -->

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
                    <div class="loader" id="loading_flag"></div>
                            <div class="col-md-12" id="selector_est">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="selector_estudiantes" class="col-xs-6">
                                            <label>Seleccione un Estudiante <i class="fa fa-user"></i>:</label>
                                            <select onchange="E_seleccionado(this)" name="sle_estudiantes" id="sle_estudiantes" class="form-control">
                                                <?php
                                                  select_estudiantes_por_curso($tipo["id_ce_docente"]);
                                                    ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div id="recibe_resultados_estudiantes_2">

                            </div>
                        </section>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="page-footer pt-4" style="margin-bottom: 0px; background: white; padding-top: 40px; padding-bottom: 0px; bottom: 0; height: 200px;">
            <div class="container" style="margin-bottom: 20px;">
               <table cellpadding="10">
                    <tr>
                        <td align="left" valign="center">
                            <div style="display: flex; align-items: baseline;">
                                <img style="margin-right: 5px;" width="44" src="../assets/img/mineduc.png">
                                <img style="margin-right: 5px;" width="120" src="../assets/img/fondef.png">
                                <img style="margin-right: 5px;" width="110" src="../assets/img/corfo.png">
                                <img style="margin-right: 5px; padding-top: 5px;" width="60" src="../assets/img/ufro.png">
                                <img style="margin-right: 5px; padding-bottom: 4px;" width="100" src="../assets/img/autonoma.png">
                                <img style="margin-right: 5px; padding-bottom: 4px;" width="160" src="../assets/img/fund_telefonica.png">
                            </div>
                        </td>
                        <td width="33%" align="center" valign="center" >
                            <p style="font-size: small; text-align: justify; font: condensed 100% sans-serif; color: #212529;"> 
                                Estas encuestas forman parte del Proyecto FONDEF ID14I10078-ID14I20078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.
                            </p>
                        </td>
                    </tr>
               </table>
            </div>
        </footer>
    <div id="id_ingre_cod" class="modal fade">
        <div class="card" id="form-encuesta" style="">
            <div class="card-body">
                <div class="modal-header"  style="text-align: center; line-height: 7px; border: 0; margin: 0; padding: 0;">
                    <h5 style="" class="modal-title">Formulario de acceso</h5>
                    <button id="btn_cerrar_modal" type="button" class="close" >&times;</button>
                </div>
                <div style="text-align: center; line-height: 7px;">
                    <hr style="background: #fc455c;">
                </div>
                <form id="form_admin">
                    <br>
                    <div style="text-align: center; margin-bottom: 4px;">
                        <i class="fa fa-user" style="color: #fc455c;" aria-hidden="true"></i> &nbsp; Administracion
                    </div>
                    <br>
                    <div class="form-group has-feedback">
                        <?php echo usuario_administrador(); ?>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="usuario" required />
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="contrasena" name="contrasena" class="form-control"  autocomplete="password" placeholder="contraseña" required />
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <button style="border-radius: 2px; background-color: #fc455c; font-family: ‘Source Sans Pro’, sans-serif; font-size: 12px; font-weight: 900; min-width:120px; height:30px; width: 100%; margin-top: 15px; border-radius: 5px; color: white; box-shadow: rgba(0, 0, 0, 0.22) 1px 1px 1px 1px; border: 1.5px solid #fc455c;" name="login-button" id="ingresar_admin" type="submit" class="icon-submit btn-limon-validar">
                        <span id="inicia_rep">
                            Ingresar
                        </span>
                        <div id="spinner"></div>
                    </button>  
                </form>
            </div>
        </div>
    </div>

    <style type="text/css">
        #id_panel_niveles, #id_sub_tit_niveles {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #id_td_id_nombre {
            max-width: 250px;
             overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        
    </style>

    <?php require "dist/js/js.php"; ?>
    <?php
        nom_establecimiento($_SESSION["id_establecimiento"]);
        $ce = suma_total_dimension_compromiso_escolar_copia(
        $_SESSION["id_establecimiento"], 
        $_SESSION["id_profesor"]
        );

        $demos_fc = suma_total_dimension_factores_contextuales_copia(
        $_SESSION["id_establecimiento"], 
        $_SESSION["id_profesor"]
        );

        $compromiso_escolar = suma_afectivo_coductual_cognitivo_final(
        $_SESSION["id_establecimiento"], 
        $_SESSION["id_profesor"]
        );

        $factores_contextuales = fc_suma_familiar_pares_profesores(
        $_SESSION["id_establecimiento"], 
        $_SESSION["id_profesor"]
        );
    ?>
    <?php include "partes/graficos_curso.php"; ?>
    <script>
        sesion();
        window.flag_load = true;
        var url_base = window.location;
        var flag_menu = 1;
        var flag_sbar = 1;
        var fewSeconds = 5;
        var des_flag = false;

        function login_admin() {
            url_base_2 = url_base.protocol + "//" + url_base.host;
            dir = url_base_2 + "/php/valida_login.php";
            $('#ingresar_admin').on("click", function() {
                const user = document.getElementById("usuario").value;
                const pass = document.getElementById("contrasena").value;
                if (user == "") {
                    alertify.notify("Debes ingresar el usuario");
                    document.getElementById("usuario").focus();
                    return false;
                } else if (pass == "") {
                    alertify.notify("Debes ingresar la contraseña");
                    document.getElementById("contrasena").focus();
                    return false;
                } else {
                    cadena = "usuario=" + $('#usuario').val() +
                        "&contrasena=" + $('#contrasena').val() +
                        "&tipo_usuario=" + $('#tipo_usuario').val() +
                        "&privilegios=" + "1";
                    $.ajax({
                        type: "POST",
                        url: dir,
                        data: cadena,
                        cache: false,
                        statusCode: {
                            404: function() {
                                alertify.alert("Alerta", "Pagina no Encontrada");
                                document.getElementById("ingresar_admin").disabled = false;
                                document.getElementById("spinner").innerHTML = '';
                                document.getElementById("inicia_rep").innerHTML = 'Ingresar';

                            },
                            502: function() {
                                alertify.alert("alerta", "Ha ocurrido un error al conectarse con el servidor");
                                document.getElementById("ingresar_admin").disabled = false;
                                document.getElementById("spinner").innerHTML = '';
                                document.getElementById("inicia_rep").innerHTML = 'Ingresar';
                            }
                        },
                        beforeSend: function() {
                            document.getElementById("ingresar_admin").disabled = true;
                            document.getElementById("inicia_rep").innerHTML = '';
                            document.getElementById("spinner").innerHTML = '</i> <i class="fa fa-spinner fa-2x fa-spin  fa-fw">';
                        },
                        success: function(r) {
                            if (r == 1) {
                                window.location.replace(
                                    url_base.protocol + "//" + 
                                    url_base.host + "/" + 
                                    "modulos.php"
                                );
                            } else {
                                document.getElementById("ingresar_admin").disabled = false;
                                document.getElementById("spinner").innerHTML = '';
                                document.getElementById("inicia_rep").innerHTML = 'Ingresar';
                                alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
                                alertify.alert('Usuario Incorrecto');
                            }
                        }
                    });
                }
            });
        }

        function DescargarPDF(token) {
            window.open(
                url_base.protocol + "//" + 
                url_base.host + "/" + 
                "reportes/reporte_individual.php?token=" + token, 
                '_blank'
            );
        }

        $("#btn_reporte_descargar").ready(function () {
            $("#btn_reporte_descargar").attr("disabled", "true");
            $("div-btn-msg-1").attr("display", "none");
            $("div-btn-msg-2").attr("display", "block");
            $("#btn_aux").show();
            $("#btn_reporte_descargar").hide();
            setTimeout(function() {
                $("div-btn-msg-1").attr("display", "block");
                $("div-btn-msg-2").attr("display", "none");
                $("#btn_reporte_descargar").removeAttr( "disabled" );
                $("#btn_aux").hide();
                $("#btn_reporte_descargar").show();
            }, 
            fewSeconds * 1000
            );
        });

        $("#btn_curso").ready(function () {
            $("#btn_curso").attr("disabled", "true");
            $("#cargando_reporte_curso").show();
            $("#btn_reporte_curso").hide();
            setTimeout(function() {
                $("#btn_curso").removeAttr( "disabled" );
                $("#cargando_reporte_curso").hide();
                $("#btn_reporte_curso").show();
            }, 
            5000
            );

            $("#loading_flag").hide();
        });

        
        function E_seleccionado(argument) {
            $("#loading_flag").show();
        }
        $(document).ready(function () {
            if (window.hasOwnProperty("estudiante_seleccionado")) {
                $('#sle_estudiantes2 option').eq(
                    window.estudiante_seleccionado
                ).prop(
                    'selected', 
                    true
                );

                $('#sle_estudiantes option').eq(
                    window.estudiante_seleccionado
                ).prop(
                    'selected', 
                    true
                );
            }
            $("#selector_estudiantes").css("display", "none");
            var combo = document.getElementById("sle_estudiantes");
            var selected = combo.options[combo.selectedIndex].value;

            carga_individual_estudiante_factores_contextuales(selected, <?php echo $tipo["id_ce_docente"] ?>);
            carga_factores_contextuales_index(combo);

            $("#select_curso").click(function () {
                $("#estudiantes").hide();
                $("#curso_p").show();
            });

            $("#select_estudiante").click(function () {
                $("#estudiantes").show();
                $("#curso_p").hide();
            });

            $(".menu-flag").click(function () {
                flag_menu *= -1;
                if (flag_menu != 1) {
                    $(".skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.treeview > a").css("background", "#f27611");
                } else {
                    $(".skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.treeview > a").css("background", "#f4af1f");
                }
            });

            $(".btn_side").click(function () {
                flag_sbar *= -1;
                if (flag_sbar != 1) {
                    console.log("holaaa");
                    $(".main-sidebar").css({"-webkit-transform":"translate(-230px, 0)"});
                    $(".main-sidebar").css({"-ms-transform":"translate(-230px, 0)"});
                    $(".main-sidebar").css({"-o-transform":"translate(-230px, 0)"});
                    $(".main-sidebar").css({"transform":"translate(-230px, 0)"});
                    $(".content-wrapper, .main-footer").css("margin-left", "0");

                } else {
                    $(".main-sidebar").css({"-webkit-transform":"translate(0, 0)"});
                    $(".main-sidebar").css({"-ms-transform":"translate(0, 0)"});
                    $(".main-sidebar").css({"-o-transform":"translate(0, 0)"});
                    $(".main-sidebar").css({"transform":"translate(0, 0)"});
                    $(".content-wrapper, .main-footer").css("margin-left", "230px");
                }

                
                
            });

            $(".id_recur_edu").click(function () {
                window.location.replace(
                    url_base.protocol + "//" + 
                    url_base.host + "/" + 
                    "recursos_edu.php"
                );
            });



            

            $("#img_btn_salir").click(function () {
                window.location.replace(
                    url_base.protocol + "//" + 
                    url_base.host + "/" + 
                    "salir.php"
                );
            });

            $("#btn_curso").click(function () {
                window.open(
                    url_base.protocol + "//" + 
                    url_base.host + "/" + 
                    "reportes/reporte_curso.php", 
                    '_blank'
                );
            });

            $("#bt_manual").click(function () {
                window.open(
                    url_base.protocol + "//" + 
                    url_base.host + "/" + 
                    "documentos/Manual_de_Usuario_Plataforma_Compromiso_Escolar_11-09-2019.pdf", 
                    '_blank'
                );
            });

            $("#bt_admin").click(function() {
                $("id_ingre_cod").modal('toggle');
                $("body").find("#id_ingre_cod").css('display', 'block');
                $("body").find(".modal-backdrop").css('display', 'block');
                $("body").css('padding', '0');
            });

            $("#btn_cerrar_modal").click(function() {
                //$("body").find("#id_ingre_cod").attr( "aria-hidden", "true");
                $("body").find("#id_ingre_cod").css('display', 'none');
                $("body").find(".modal-backdrop").css('display', 'none');
                
               // $("body").find(".card").css('display', 'none');
                $("body").css('padding', '0px');
                
                $("body").css('overflow-y', 'scroll');
                $("body").css('padding', '0');
            });
        });

        $(document).on('change','#sle_estudiantes2',function() {
            
            var combo = document.getElementById("sle_estudiantes2");
            var selected = combo.options[combo.selectedIndex].value;

            carga_individual_estudiante_factores_contextuales(selected, <?php echo $tipo["id_ce_docente"] ?>);
            carga_factores_contextuales_index(combo);
            setTimeout(function() {
                $("div-btn-msg-1").attr("display", "block");
                $("div-btn-msg-2").attr("display", "none");
                $("#btn_reporte_descargar").removeAttr( "disabled" );
                $("#btn_aux").hide();
                $("#btn_reporte_descargar").show();
            }, 
            fewSeconds * 1000
            );
        });

        $('#sle_estudiantes2').ready(function() {
            
            var combo = document.getElementById("sle_estudiantes2");
            var selected = combo.options[combo.selectedIndex].value;

            carga_individual_estudiante_factores_contextuales(selected, <?php echo $tipo["id_ce_docente"] ?>);
            carga_factores_contextuales_index(combo);
            setTimeout(function() {
                $("div-btn-msg-1").attr("display", "block");
                $("div-btn-msg-2").attr("display", "none");
                $("#btn_reporte_descargar").removeAttr( "disabled" );
                $("#btn_aux").hide();
                $("#btn_reporte_descargar").show();
            }, 
            fewSeconds * 1000
            );
        });


        
    </script>
</body>
<style type="text/css">
    .modal-title {
        margin-bottom: 0;
        display: flex;
        text-align: center;
        position: absolute;
        left: 28%;
    }

    .card {
        width: 400px; 
        height: 360px;
        min-height: 350px;
        position: absolute;
        left: 50%;
        top: 30%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        border-radius: 15px;
        background: white;
    }
    body {
        padding: 0;
    }

    .card-body {
        padding-top: 15px;
        border-radius: 15px;
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
        background-color: white;
    }
  .small-box > .inner {
    padding: 10px;
    overflow: hidden;
    outline: auto;
    max-height: 100px;
  }

  .niv {
    padding: 0;
    white-space: nowrap;
}
</style>
</html>
<?php 
} else {
    header("location: inicia_reportes.php");
}
