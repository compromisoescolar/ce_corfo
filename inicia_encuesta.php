<?php 
    require_once "conf/conexion_db.php";
    require_once "conf/funciones_db.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Compromiso Escolar</title>
        <?php include "assets/css/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="assets/css/estilo_inicio_encuesta.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="assets/js/jquery-1.10.2.js"></script>
        <script src="https://www.google.com/recaptcha/api.js?render=6Le4kagZAAAAAPrJvezXbADOrTQVxo69xZg1cyK6"></script>
         
        <script type="text/javascript">
            var flag = false;
            var flag2 = false;
            url_base = window.location;
            function Cerrar_modal() {
                $('#id_ingre_cod').modal('toggle');
                console.log("sdefs");
            }

            $( document ).ready(function() {
                /////////// cerrar modal ///////////////////////
                var modal = document.getElementById("id_ingre_cod");
                var span = document.getElementsByClassName("close")[0];

                span.onclick = function() {
                    var base_modal = document.querySelectorAll(".modal-backdrop,.show")[1];
                    modal.style.display = "none";
                    base_modal.style.display = "none";
                    flag2 = true;
                    $(document.body).css('padding', '0px');
                    $(document.body).css('overflow', 'scroll');
                    $(document.body).css('overflow-y', 'scroll');
                }
                /////////// cerrar modal ///////////////////////

                $("#btn_comenzar").click(function() {
                    if (flag2 == true) {
                        var base_modal = document.querySelectorAll(".modal-backdrop,.show")[1];
                        modal.style.display = "block";
                        base_modal.style.display = "block";
                    } 
                });

                $("#btn_cerrar_session").click(function() {
                    window.location.replace(
                        url_base.protocol + "//" + 
                        url_base.host + "/" + 
                        "index2.php"
                    );
                });

                $("#btn_repro").click(function() {
                    if (flag == false) {
                        $("#btn_repro").text("PAUSAR VIDEO");
                        $('#styled_video')[0].play();
                        $('#styled_video_container').addClass('style_it');
                        flag = true;

                    } else {
                        $("#btn_repro").text("REPRODUCIR VIDEO");
                        $('#styled_video_container').removeClass('style_it');
                        $('#styled_video')[0].pause();
                        flag = false;
                    }
                });


                $("#btn_cerrar_modal").click(function() {
                    $('#id_ingre_cod').attr( "aria-hidden", "true" );
                });
                
                
            });
        </script>
        <style type="text/css">
            #fondo_izq {
                background-image: url("../assets/img/Fdo_p_encuesta.png");
                background-repeat: no-repeat;
                background-size: 100% 100%;
            }

            element.style {
              text-align: justify;
              font: condensed 100% Source Sans Pro;
              color: #212529;
              font-size: larger;
            }

            .modal-title {
                margin-bottom: 0;
                display: flex;
                text-align: center;
                position: absolute;
                left: 25%;
            }
        </style>
    </head>
    <body style="background: #BDC3C7;">
        <div id="linea-superior"> 
            <table width="100%" height="100%" >
                <tr>
                    <td align="left">
                        <div style="display: flex; align-items: baseline;">
                            <img style="height: 78px; width: 750px;"  src="assets/img/c1_encuesta.png">
                            <div style="margin-top: 20px; margin-left: 195px; font-size: 20px; position: absolute;">
                                Prepárate para la encuesta
                            </div>
                        </div>
                    </td> 
  
                    <td>
                        <div  style="width: 140px; height: 50px; ">
                            <button id="btn_cerrar_session" style="text-decoration: none; background: transparent; width: 100%; height: 100%;  background-repeat: no-repeat; border-radius: 35px; border: none; cursor:pointer; overflow: hidden; outline:none; background-position: center;">
                                <img style="width: 100%; height: 100%; " src="assets/img/salir-2.png">
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="contenido row justify-content-center align-items-center">
            <div class="card_c" style="" id="fondo_izq">
                <table align="center" width="100%" cellspacing="10">
                    <tr align="left" valign="top"  width="100%">
                        <h2 id="c_tit">
                            <strong>Encuesta:</strong> Te invitamos a superar las nueve etapas
                            <hr id="tit">
                        </h2>
                    </tr>
                    <tr align="left" valign="top"  width="100%">
                        <td align="left" valign="center"  width="50%" style="padding-top: 40px;">
                            <p  style="text-align: justify; font: condensed 100% Source Sans Pro; color: #212529; font-size: larger;">
                                La encuesta que estás iniciando es de gran utilidad para medir el compromiso que tienes con tus estudios y el colegio, y así poder saber qué estudiantes o cursos requieren un apoyo adicional. <br> <br> Algunas de las preguntas que te planteamos serán más complicadas que otras. Se trata de contarnos qué sientes y piensas.
                            </p>
                        </td>
                        <td align="left" valign="center"  width="50%" style="padding-left: 30px; padding-top: 40px;">
                            <div id="styled_video_container">
                                <video src="assets/video/promocion.mp4" type="video/mp4" controls poster="assets/img/promocion_poster.png" id="styled_video" preload="metadata" loop>
                            </div>
                        </td>
                    </tr>
                    <tr align="left" valign="bottom"  width="100%">
                        <td align="center" width="50%" style="padding-left: 30px; padding-bottom: 30px; padding-top: 50px;">
                            <button id="btn_comenzar" align="center" data-toggle="modal" data-target="#id_ingre_cod">
                                COMENZAR
                            </button>
                        </td>
                        <td align="left" width="50%" style="padding-left: 30px; padding-bottom: 30px; padding-top: 50px;">
                            <button id="btn_repro" align="center">
                                REPRODUCIR VIDEO
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <footer class="page-footer pt-4" style="margin-bottom: 0px; padding-bottom: 0px; bottom: 0; height: 200px;">
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
                            <p style="font-size: small; text-align: justify; font: condensed 70% sans-serif; color: #212529;"> 
                                Estas encuestas forman parte del Proyecto FONDEF ID14I10078-ID14I20078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.
                            </p>
                        </td>
                    </tr>
               </table>
            </div>
        </footer>
        <div id="id_ingre_cod" class="modal fade" aria-hidden="true">
            <div class="card" id="form-encuesta">
                <div class="card-body">
                    <div class="modal-header"  style="text-align: center; line-height: 7px; border: 0; margin: 0; padding: 0;">
                        <h5 style="" class="modal-title">Formulario de acceso</h5>
                        <button id="btn_cerrar_modal" type="button" class="close" data-dismiss="id_ingre_cod" aria-hidden="true">&times;</button>
                    </div>
                    <div style="text-align: center; line-height: 7px;">
                        <hr style="background: #fc455c;">
                    </div>
                    <form enctype="multipart/form-data" id="fm_codigo" method="post">
                        <div style="text-align: center; margin-bottom: 4px;">
                            <i class="fa fa-user" style="color: #fc455c;" aria-hidden="true"></i> &nbsp; Estudiantes 
                        </div>
                        <br>

                        <div style="display: flex; align-items: baseline;">
                            Selecciona Tu Pais: &nbsp;
                            <div class="border-bottom text-info pb-1 mb-2">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                            </div>
                        </div>
                        
                        <select name="sel_country" id="sel_country" class="form-control mb-3" >
                            <?php echo select_pais(); ?>                
                        </select>

                        <div style="display: flex; align-items: baseline;">
                            Ingresa tu codigo: &nbsp;
                            <div class="border-bottom text-info pb-1 mb-2">
                                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="codigo_txt">
                            <input type="text" name="txt_contrasena" id="txt_contrasena" class="form-control has-warning" placeholder="Tú Código">
                        </div>                            
                        <input type="hidden" name="token" value="" id="token">  
                        <button style="border-radius: 2px; background-color: #fc455c; font-family: ‘Source Sans Pro’, sans-serif; font-size: 12px; font-weight: 900; min-width:120px; height:30px; width: 100%; margin-top: 15px; border-radius: 5px; color: white; box-shadow: rgba(0, 0, 0, 0.22) 1px 1px 1px 1px; border: 1.5px solid #fc455c;" id="btn_token" type="submit" class="icon-submit btn-limon-validar">
                            <span id="ingresar"> INGRESAR </span>
                            <div id="spinner"></div>
                        </button>  
                        <br>                                       
                    </form>
                </div>
            </div>
        </div>
        <?php include "assets/js/js.php"; ?>
        <script>
            valida_token_estu();
            var x = document.getElementById("txt_contrasena").required = true;
            var combo = document.getElementById("sel_country");
            var selected = combo.options[combo.selectedIndex].text;

            if (selected === "Error") {
                document.getElementById("txt_contrasena").readOnly = true;
                document.getElementById("txt_contrasena").value = 'atributos invalidos';
                document.getElementById("btn_token").setAttribute("disabled", "");

            }
        </script>
        <style type="text/css">
            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                font-weight: 900;
            }

            body {
                color: #212529;
            }


            element.style {
              text-align: justify;
              font: condensed 100% Source Sans Pro;
              color: #212529;
              font-size: larger;
            }
        </style>
    </body>
</html>