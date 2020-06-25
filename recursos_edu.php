<?php
    require 'conf/conexion_db.php';
    require 'conf/funciones_db.php';
    require_once 'reportes/dist/conf/funciones_reportes.php';   
    
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reportes Compromiso Escolar </title>
    <?php require "css.php"; ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/jquery.loading.js"></script>
    <script src="assets/js/jquery.loading.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <style type="text/css">
        html {
            padding: 0;
            margin: 0;
        }

        body {
            padding: 0;
            margin: 0;
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

        #id_cont {
            white-space: nowrap;
            overflow-x: auto;
        }



        

        .table td:first-child, .table th:first-child {
            width: 50%;
        }

        .main-sidebar {
            background: #fc455c;
        }       

        .header {
            background: #ab2946;
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
            background: #ab2946;
        }

        .content-header {
            background: #fc455c; 
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
            background-color: #ab294685;
        }

        .skin-blue .sidebar-menu > li > .treeview-menu {
            margin: 0 1px;
            background: #ab2946;
        }

        .skin-blue .sidebar-menu > li > a {
            border-left-color: white;
            padding-left: 5px;
        }

        .skin-blue .sidebar-menu .treeview-menu > li > a {
            color: #ffffff85;
        }

        .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a {
            color: #ffffff;
            background: #fc455c;
        }

        .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.treeview > a {
            color: #ffffff;
            background: #fc455c;
        }

        .tit-menu {
            float: left; 
            text-align: left; 
            color: white;
        }

        .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side { 
            background-color: #fc455c;
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

        /* pestañas superiores */
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
            background-color: #fc455c; /* zapallo */
        }

        .nav-pills > li.pest_li:focus > a { 
            background-color: #fc455c7d; /* zapallo */
        }

        .id_recur_edu {
            margin-top: 210px;
            margin-left: 20px;
            padding-top: 1px;
            padding-left: 10px;
            width: 195px;
            height: 300px;
            background-image: url("../assets/img/modulo-resultados.png");
            background-repeat: no-repeat;
            background-size:100% auto;
            text-shadow: 0.5px 0.5px 0.5px black;
            color: white; 
            border-radius: 10px;
        }

        .id_recur_edu:hover, .btn_side:hover, #img_btn_salir:hover, .celda_file:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        .id_recur_edu:active, #img_btn_salir:active, .btn_side:active, .celda_file:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }

        #id_car:hover {
            -webkit-box-shadow: 0px 10px 5px 0px rgba(0,0,0,0.85);
            -moz-box-shadow: 0px 10px 5px 0px rgba(0,0,0,0.85);
            box-shadow: 0px 10px 5px 0px rgba(0,0,0,0.85);
            cursor:pointer; 
            cursor: hand;
            opacity: 0.5;
        }

        #id_car:active {
            -webkit-box-shadow: 0px 10px 5px 0px rgba(0,0,0,0.65);
            -moz-box-shadow: 0px 10px 5px 0px rgba(0,0,0,0.65);
            box-shadow: 0px 10px 5px 0px rgba(0,0,0,0.65);
            cursor:pointer; 
            cursor: hand;
            opacity: 0.4;
        }

        #cabecera_tit {
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #id_view_pdf {
            margin-top: 0;
            padding: 8px;
            width: 98%;
            min-width: 300px;
            height: auto;
            vertical-align: baseline;
        }

        #id_panel_2 {
            vertical-align: middle;
        }

        .skin-blue .sidebar-menu > li.active > a {
            border-left-color: #f4af1f; 
        }

        #btn_descargar {
            box-shadow: rgba(0, 0, 0, 0.22) 1px 1px 1px 1px;
            border-radius: 3px;
            background-color: #2d6693;
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 14px;
            font-weight: 900;
            border: 1.5px solid #2d6693;
            color: white;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 7px;
            padding-bottom: 7px;
            width: 150px;
            text-align: left;
        }

        #tit {
            background-color: #4cc2d4;
            height: 2px;
        }

        .h_tit {
          
            color: #4cc2d4;
            font-size: 16px;
        }

        .linea_file {
            height: 3px;
            width: 100%;
            min-width: 150px;
            background-color: #4cc2d4;
        }

        .cont-files {
            overflow-y: scroll;
            min-height: 500px;
        }

        #tit_h {
            color: #4cc2d4;;
        }

        .tit_h {
            color: #4cc2d4;
            background-color: #4cc2d4;
        }

        .celda_file {
            padding: 10px;
        }

      

        .skin-blue .sidebar-menu .treeview-menu > li > a {
            color: #ffffff85;
        }

        .skin-blue .sidebar-menu .treeview-menu > li > a.activo {
            color: #ffffff;
        }

        .skin-blue .sidebar-menu .treeview-menu > li > a:hover , a.activo:hover {
            color: #ffffff40;
        }
    </style>
    <script type="text/javascript">
         var extension = "";
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background: #BDC3C7; ">
    <div id="linea-superior" style="background: #f4a11f; padding-left: 100px; max-height: 80px; white-space: nowrap; overflow: hidden;"> 
        <table width="100%" height="100%" >
            <tr width="100%"> 
                <td align="left" width="50%">
                    <div style="display: flex; align-items: baseline; background: #f4a11f; ">
                        <img style="margin-left: 5%; height: 78px; background-repeat: no-repeat;"  src="../assets/img/c3_recursos_m.png">
                        <div style="white-space: nowrap; overflow: hidden; margin-top: 30px; margin-left: 30%; font-size: 20px; position: absolute; color: white;">
                            
                        </div>
                    </div>
                </td> 
                <td align="left" >
                    <h3 style="color: white;">Módulo de Recursos Educativos</h3>
                </td>
                <td align="right" width="50%">
                    <table width="100%">
                        <tr width="100%">
                            
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
        <div class="card_c" style="padding-right: 150px; padding-left: 150px; padding-top: 10px;padding-bottom: 10px;">
            <div class="wrapper card_c" id="subir_head" style="background: #fc455c; border: 1.5px solid #A5A1A0; border-radius: 10px;">
                
                <div id="estudiantes" >
                    <aside class="main-sidebar" style="background: #fc455c; ">

                    <!-- sidebar: style can be found in sidebar.less -->
                    <section class="sidebar" style="width: 220px; float: right;">
                        <!-- Sidebar user panel -->
                        <div class="user-panel" style="background: #ab2946;">
                            <div class="pull-left image">
                                <br>
                            </div>
                            <div class="tit-menu">
                                <p>
                                    Menú de Navegación
                                </p>
                            </div>
                        </div>
                        <ul  class="sidebar-menu" data-widget="tree" style="background: #ab2946;">
                            <li  class="active treeview" style="background: #ab2946;">
                                <a class="menu-flag menu1" id="menu_flag_1" href="#">
                                    <img style="width: 18px;" src="assets/img/compromiso-b.png">
                                    <span> ¿Qué es el CE?</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                    <input class="a-menu" value="1" hidden>
                                </a>
                                <ul class="treeview-menu" id="t-menu-1">
                                    <li style='padding: 5px; margin-left: 0;' class="">
                                        <a id="select_curso" href="#">
                                            Desafío para la educación
                                            <input class="i-menu" value="1" hidden>
                                        </a>
                                    </li>
                                    <li style='padding: 5px; margin-left: 0;' class="">
                                        <a id="select_curso" href="#">
                                            Enlaces periodísticos
                                            <input class="i-menu" value="2" hidden>
                                        </a>
                                    </li>
                                    <li style='padding: 5px; margin-left: 0;'  class="">
                                        <!-- estudiante.php -->
                                        <a id="" href="#">
                                            Videos y Presentaciones
                                            <input class="i-menu" value="3" hidden>
                                        </a>
                                    </li>
                                    <li style='padding: 5px; margin-left: 0;'  class="">
                                        <!-- estudiante.php -->
                                        <a id="" href="#">
                                            Artículos Científicos
                                            <input class="i-menu" value="4" hidden>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li  class="active treeview" style="background: #ab2946;">
                                <a class="menu-flag" id="menu_flag_2" href="#">
                                    <img style="width: 18px;" src="assets/img/diagnostico-b.png">
                                    <span>Diagnóstico y evaluación</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                    <input class="a-menu" value="2" hidden>
                                </a>
                                <ul class="treeview-menu" id="t-menu-2">
                                    <li style='padding: 5px; margin-left: 0;' >
                                        <a id="select_curso" href="#">
                                            Evaluación y predicción
                                            <input class="i-menu" value="1" hidden>
                                        </a>
                                    </li>
                                    <li style='padding: 5px; margin-left: 0;'  class="">
                                        <!-- estudiante.php -->
                                        <a id="" href="#">
                                            Diagnóstico e interpretación
                                            <input class="i-menu" value="2" hidden>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li  class="active treeview" style="background: #ab2946;">
                                <a class="menu-flag" id="menu_flag_3" href="#">
                                    <img style="width: 18px;" src="assets/img/promocion-b.png">
                                    <span>Promoción e intervención</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                    <input class="a-menu" value="3" hidden>
                                </a>
                                <ul class="treeview-menu" id="t-menu-3">
                                    <li style='padding: 5px; margin-left: 0;' >
                                        <a id="select_curso" href="#">
                                            Compromiso Escolar
                                            <input class="i-menu" value="1" hidden>
                                        </a>
                                    </li>
                                    <li style='padding: 5px; margin-left: 0;'  class="">
                                        <!-- estudiante.php -->
                                        <a id="" href="#">
                                            Factores de contexto
                                            <input class="i-menu" value="2" hidden>
                                        </a>
                                    </li>
                                    <li style='padding: 5px; margin-left: 0;'  class="">
                                        <!-- estudiante.php -->
                                        <a id="" href="#">
                                            Manual de Intervención
                                            <input class="i-menu" value="3" hidden>
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
                <div class="content-wrapper" style="margin-right: 20px;">
                    <section id="cabecera_tit" style="background: #fc455c; display: flex; justify-content: center; text-align: center; padding-bottom: 8px;">

                        <a style="width: 60px; height: 30px; border: 0; color: white; background: #fc455c; font-size: 30px;" class="openbtn btn_side">☰</a> 
                        <div style="width: 100%; justify-content: center; text-align: center; white-space: nowrap; overflow: hidden;">
                            <h3 style="color: white; justify-content: center; text-align: center; ">
                                ¿Qué es el Compromiso Escolar?
                            </h3>
                        </div>
                        
                    </section>
                    <div id="id_cont" style=" border: 1px black; height: 100%; margin: 20px; display: flex; align-items: center;">
                        <table id="id_paneles" width="100%" cellspacing="10" cellpadding="10" style="border-radius: 5px; background: white; height: 100%; min-height: 600px; padding: 3px; margin-bottom: 20px;" >     
                        <tr align="center" valign="center" style="height: 100%;">
                            <td id="titu_td" width="60%" align="left" valign="center" style="padding-right: 20px; padding: 20px;">
                                <div id="id_panel" style="overflow-x: scroll; overflow-y: scroll; width: 100%; height: 100%;">
                                    
                                </div>
                            </td>
                            <td id="id_panel_2" width="40%" align="center" valign="top">
                                <table width="100%">
                                    <tr valign="top"> 
                                        <td id="id_view_pdf_td" width="100%" style="">
                                            <!-- <iframe id="id_view_pdf" src="" style="margin-top: 10px; min-height: 450px;" ></iframe> -->
                                            <img id="id_view_pdf" src="" >
                                        </td>
                                    </tr>
                                    <tr align="center" >
                                        <table width="100%" style="">
                                            <tr align="center" valign="top" >
                                                <td valign="center" align="center" style="padding-top: 20px; padding-bottom: 20px; text-align: center;">
                                                    <a id="btn_descargar" style="cursor: pointer">
                                                        Descargar  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                                <td align="center">
                                                    <img id='id_tipo' src='assets/img/ppt.png' style='width: 50px; padding-bottom: 10px;'>
                                                </td>
                                            </tr>
                                        </table>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
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
                                <img style="margin-right: 5px;" width="44" src="assets/img/mineduc.png">
                                <img style="margin-right: 5px;" width="120" src="assets/img/fondef.png">
                                <img style="margin-right: 5px;" width="110" src="assets/img/corfo.png">
                                <img style="margin-right: 5px; padding-top: 5px;" width="60" src="assets/img/ufro.png">
                                <img style="margin-right: 5px; padding-bottom: 4px;" width="100" src="assets/img/autonoma.png">
                                <img style="margin-right: 5px; padding-bottom: 4px;" width="160" src="assets/img/fund_telefonica.png">
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
    
    <script>
        var archivos;
        var fil_descargar = "";
        var url_base = window.location;
        var flag_menu = 1;
        var flag_menu_1 = 1;
        var flag_menu_2 = 1;
        var flag_menu_3 = 1;
        var flag_sbar = 1;
        var id_fol;

        $('#btn_curso').attr("disabled", true);
              
        setTimeout(() => {
            $('#btn_reporte_curso').show();
            $('#cargando_reporte_curso').hide();
            $('#btn_curso').attr("disabled", false);
        }, 5000);

        $("#talleres_familia").hide(250);
        $("#talleres_aula").show(250);
        $("#talleres_otros").hide(250);
        $("#aula-hover").addClass("hover-aula"); 

        function familia() {
            if ($('#talleres_familia').is(':hidden')) {
                $("#talleres_familia").show(250);           
                $("#talleres_aula").hide(250);
                $("#talleres_otros").hide(250);

                $("#aula-hover").removeClass("hover-aula") 
                $("#familia-hover").addClass("hover-familia") 
                $("#otros-hover").removeClass("hover-otros") 
            } 
        }

        function aula() {
            if ($('#talleres_aula').is(':hidden')) {
                $("#talleres_familia").hide(250);           
                $("#talleres_aula").show(250);           
                $("#talleres_otros").hide(250);

                $("#aula-hover").addClass("hover-aula") 
                $("#familia-hover").removeClass("hover-familia") 
                $("#otros-hover").removeClass("hover-otros") 
            } 
        }

        function otros() {
            if ($('#talleres_otros').is(':hidden')) {
                $("#talleres_familia").hide(500);
                $("#talleres_aula").hide(500);          
                $("#talleres_otros").show(500);

                $("#aula-hover").removeClass("hover-aula") 
                $("#familia-hover").removeClass("hover-familia") 
                $("#otros-hover").addClass("hover-otros") 
            } 
        }

        function Mostrar_pdf(url) {
            $(".file").click(function () {
               $('#id_view_pdf').prop('src', url)
            });
        }

        function SetPreVisual(id) {
            $("#id_view_pdf").attr("src", archivos[0][id]);
        }

        function ciEquals(a, b) {
            return typeof a === 'string' && typeof b === 'string'
                ? a.localeCompare(b, undefined, { sensitivity: 'accent' }) === 0
                : a === b;
        }

        function CambiaTipo(exten) {
            var ext = exten.split("/")[0];
            extension = ext;
            var tipo = "";
            if(ciEquals(ext.toLowerCase(), ("pdf").toLowerCase()) == true) {
                tipo = "pdf.png";
            } else if(ciEquals(ext, ("mp4").toLowerCase()) == true) {
                tipo = "mp4.png";
            } else if(ciEquals(ext, ("ppt").toLowerCase()) == true || ciEquals(ext, ("pptx").toLowerCase()) == true) {
                tipo = "ppt.png";
            } else {
                tipo = "doc.png";
            }

            $("#id_tipo").attr(
                "src",
                "assets/img/" + tipo
            );

            if(extension != "pdf" && extension != "mp4") {
                /*$("#btn_descargar").removeAttr("download");
                $("#btn_descargar").attr(
                    "href",
                     archivos[1][id_fol]
                );*/
            } else {
                $("#btn_descargar").removeAttr("download");
            }
            console.log(extension);
        }

        function GetIdFold(id_f) {
            id_fol = id_f;
            console.log('id_fol: ' + id_fol)
            fil_descargar = url_base.protocol + "//" + 
            url_base.host + "/" + 
            archivos[1][id_f];
            console.log('archivos: ' + archivos);
            console.log("aqui: " + fil_descargar);
            var extension = (fil_descargar + "").split("/");
            console.log('extension: ' + extension);
            extension = extension[extension.length - 1].split(".")[1];
            CambiaTipo(extension);
            console.log("fin: " + extension);
        }

        $("#id_view_pdf").on("load", function() {
            /*
            setInterval(
                function() {
                    $('#id_view_pdf').loading({
                        message: 'Cargando...'
                    });
                }, 
                2000
            );*/
        });
          
        $(document).ready(function () {
            $("#titulo_div").empty();
            var sel_menu = 2;
            var sel_sub_menu = 1;

            $("#t-menu-1").slideToggle("fast");
            $("#t-menu-2").slideUp("fast");
            $("#t-menu-3").slideUp("fast");
            
            $("#btn_descargar").click(function () {
                window.open(fil_descargar, '_blank');
            });

            var tabla = "<table width='100%''><tbody id='id_archivos' align='center' valign='center'></tbody></table>"
         
            $("#id_panel").append(tabla);
            
            $(".skin-blue .sidebar-menu .treeview-menu > li > a").click(function () {
                $(".skin-blue .sidebar-menu .treeview-menu > li > a").css("color", "#ffffff85");
                $(this).css("color", "#ffffff");
                sel_sub_menu = $(this).find(".i-menu").val();
                
                $.ajax({
                    type: "POST",
                    url: 'conf/listar_arch.php',
                    data: {
                        folder: sel_menu, 
                        sub_folder: sel_sub_menu
                    }, success: function(data) {
                        $("#id_archivos").empty();
                        console.log('sel_menu: ', sel_menu,' - sel_sub_menu: ', sel_sub_menu);
                        archivos = JSON.parse(data);
                        var tab = "";
                        console.log('archivos: ',archivos);

                        tab += "<tr align='center' valign='center' class='id_filas' >";
                        $("#id_view_pdf").attr("src", archivos[0][0]);
                        for (var i = 0; i < archivos[1].length; i++) {
                            tab += "<td align='left' valign='center' style='width: 150px; min-width: 150px'><div onclick='SetPreVisual("+ i +")' id='id_car' style='width: 150px; min-width: 150px; cursor:pointer; cursor: hand'><img onclick='GetIdFold("+ i +")' alt='' class='file' style='width: 150px; min-width: 150px;' src='assets/img/carpeta.png'><p style='padding-top: 4px;'>" + (archivos[1][i]).split("/")[4] + "</p><hr class='linea_file'></div></td>";

                            if ((i + 1) % 3 == 0 && archivos[1].length >= 3) {
                                if ((i + 1) != archivos[1].length) {
                                    tab += "</tr>";
                                } else {
                                    tab += "</tr><tr align='center' valign='center' class='id_filas'>";
                                }
                            }
                        }
                        GetIdFold(0);
                        if (archivos[1].length < 3) {
                            tab += "<td align='left' valign='center' style='width: 150px; min-width: 150px'><div style='width: 150px; min-width: 150px;'></div></td></tr>";
                        }
                        $("#id_archivos").append(tab);
                    }
                });
            });

            $("#menu_flag_1").click(function(){
                sel_menu = $(this).find(".a-menu").val();
                $("#t-menu-1").slideToggle("fast");
                $("#t-menu-2").slideUp("fast");
                $("#t-menu-3").slideUp("fast");
            });

            $("#menu_flag_2").click(function(){
                sel_menu = $(this).find(".a-menu").val();
                $("#t-menu-1").slideUp("fast");
                $("#t-menu-2").slideToggle("fast");
                $("#t-menu-3").slideUp("fast");
            });

            $("#menu_flag_3").click(function(){
                sel_menu = $(this).find(".a-menu").val();
                $("#t-menu-1").slideUp("fast");
                $("#t-menu-2").slideUp("fast");
                $("#t-menu-3").slideToggle("fast");
            });

            $(".btn_side").click(function () {
                flag_sbar *= -1;
                if (flag_sbar != 1) {
                    $("#subir_head").css("padding-left", "20px");
                    $(".main-sidebar").css({"-webkit-transform":"translate(-230px, 0)"});
                    $(".main-sidebar").css({"-ms-transform":"translate(-230px, 0)"});
                    $(".main-sidebar").css({"-o-transform":"translate(-230px, 0)"});
                    $(".main-sidebar").css({"transform":"translate(-230px, 0)"});
                    $(".content-wrapper, .main-footer").css("margin-left", "0");

                } else {
                    $("#subir_head").css("padding-left", "0px");
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
                    "inicia_reportes.php"
                );
            });

            $("#img_btn_salir").click(function () {
                window.location.href = 
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "salir.php?csrf=<?php echo $_SESSION["token"]; ?>";
            });

            $.ajax({
                type: "POST",
                url: 'conf/listar_arch.php',
                data: {
                    folder: 1, 
                    sub_folder: 1
                }, success: function(data) {
                    $("#id_archivos").append(tab);
                    $("#id_archivos").empty();
                    archivos = JSON.parse(data);
                    var tab = "";

                    tab += "<tr align='center' valign='center' >";
                    $("#id_view_pdf").attr("src", archivos[0][0]);
                    for (var i = 0; i < archivos[1].length; i++) {
                        tab += "<td align='left' valign='center' style='width: 150px; min-width: 150px'><div onclick='SetPreVisual("+ i +")' id='id_car' style='width: 150px; min-width: 150px; cursor:pointer; cursor: hand'><img onclick='GetIdFold("+ i +")' alt='' class='file' style='width: 150px; min-width: 150px;' src='assets/img/carpeta.png'><p style='padding-top: 4px;'>" + (archivos[1][i]).split("/")[4] + "</p><hr class='linea_file'></div></td>";

                        if ((i + 1) % 3 == 0 && archivos[1].length >= 3) {
                            if ((i + 1) != archivos[1].length) {
                                tab += "</tr>";
                            } else {
                                tab += "</tr><tr align='center' valign='center'>";
                            }
                        }
                    }
                    GetIdFold(0);
                    if (archivos[1].length < 3) {
                        tab += "<td align='left' valign='center' style='width: 150px; min-width: 150px'><div style='width: 150px; min-width: 150px;'></div></td></tr>";
                    }
                    $("#id_archivos").append(tab);
                }
            });
        });
    </script>

    <style type="text/css">
        
    </style>
</body>
</html>