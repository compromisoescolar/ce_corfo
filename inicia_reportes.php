<?php 
 error_reporting(E_ERROR | E_PARSE);
 $params = session_get_cookie_params();
 setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
     true,  // this is the secure flag you need to set. Default is false.
     true  // this is the httpOnly flag you need to set
 );

 require 'conf/conexion_db.php';
 require 'conf/funciones.php';
 require 'conf/funciones_db.php';

 $_SESSION["token"] = md5(uniqid(mt_rand(), true));
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

                $("#btn_ingresar").click(function() {
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

                $("#btn_cerrar_modal").click(function() {
                    $('#id_ingre_cod').attr( "aria-hidden", "true" );
                });
                
            });
        </script>
        <style type="text/css">
            #fondo_der {
                background-image: url("../assets/img/Fdo_p_resultados.png");
                background-repeat: no-repeat;
                background-size: 100% 100%;
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
    <body style="background: #BDC3C7; ">
        <div id="linea-superior" style="background: #40c2d4;"> 
            <table width="100%" height="100%" >
                <tr>
                    <td align="left">
                        <div style="display: flex; align-items: baseline; background: #40c2d4;">
                            <img style="height: 78px; width: 750px;"  src="assets/img/C2_Resultados.png">
                            <div style="margin-top: 20px; margin-left: 195px; font-size: 20px; position: absolute;">
                                Módulo de Resultados
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

        <div id="contenido" class="contenido row justify-content-center align-items-center">
            <div class="card_c" style="padding-bottom: 100px;" id="fondo_der">
                <table align="center" width="100%" cellspacing="10">
                    <tr align="left" valign="top"  width="100%">
                        <td align="left" valign="center" width="50%" style="padding-top: 40px;">
                            <h1 id="c_tit">
                                Reportes de resultados
                                <hr id="tit">
                            </h1>
                        </td>
                    </tr>
                    <tr align="left" valign="top"  width="100%" style="">
                        <td align="left" valign="center" width="50%" style="padding-top: 15px;">
                            <p style="text-align: justify; font: condensed 100% Source Sans Pro; color: #212529; font-size: larger;">
                                En este apartado los distintos tipos de usuarios podrán consultar resultados sobre compromiso escolar y factores contextuales por estudiante, curso o establecimiento escolar. Profesores, establecimientos educacionales y sostenedores podrán acceder a información diferenciada disponible en la plataforma. 
                            </p>
                            <p style="text-align: justify; font: condensed 100% Source Sans Pro; color: #212529; font-size: larger;">
                                Para ingresar a los resultados según su área de trabajo, debe hacer click en “INGRESAR” e introducir sus credenciales. Si no las tiene, solicítela al/a la administrador/a de esta plataforma en el establecimiento escolar en el cual trabaja.
                            </p>
                        </td>
                        <td align="center" valign="bottom" width="50%">
                            <div style="width: 100%; height: 100%; display: flex; float: left; padding-bottom: 30px; padding-left: 240px;">
                                <button id="btn_ingresar" class="btn_naranjo" data-toggle="modal" data-target="#id_ingre_cod" align="left" style="width: 200px;">
                                    INGRESAR
                                </button>
                            </div>
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
            <div class="card" id="form-encuesta" style="height: 400px;">
                <div class="card-body">
                    <div class="modal-header"  style="text-align: center; line-height: 7px; border: 0; margin: 0; padding: 0;">
                        <h5 style="" class="modal-title">Formulario de acceso</h5>
                        <button id="btn_cerrar_modal" type="button" class="close" data-dismiss="id_ingre_cod" aria-hidden="true">&times;</button>
                    </div>
                    <div style="text-align: center; line-height: 7px;">
                        <hr style="background: #fc455c;">
                    </div>
                    <form id="inicia_reporte" method="POST">
                        <br>
                        <div style="text-align: center; margin-bottom: 4px;">
                            <i class="fa fa-user" style="color: #fc455c;" aria-hidden="true"></i> &nbsp; Profesionales de la educacion
                        </div>
                        <br>
                        <div class="form-group has-feedback">
                            <?php echo tipo_usuario(); ?>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="usuario" required />
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" id="contrasena" name="contrasena" class="form-control"  autocomplete="password" placeholder="contraseña" required />
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <input type="hidden" name="token" value="" id="token">
                        <button style="border-radius: 2px; background-color: #fc455c; font-family: ‘Source Sans Pro’, sans-serif; font-size: 12px; font-weight: 900; min-width:120px; height:30px; width: 100%; margin-top: 15px; border-radius: 5px; color: white; box-shadow: rgba(0, 0, 0, 0.22) 1px 1px 1px 1px; border: 1.5px solid #fc455c;" name="login-button" id="ingresar_rep" type="submit" class="icon-submit btn-limon-validar">
                            <span id="inicia_rep">
                                Ingresar
                            </span>
                            <div id="spinner"></div>
                        </button>  
                    </form>
                </div>
            </div>
        </div>

        <?php include "assets/js/js.php"; ?>
        <script src="reportes/dist/js/funciones.js"></script>
        <script>
            login_final();  
        </script>
        
        <style type="text/css">
            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                font-weight: 900;
            }

            body {
                color: #212529;
            }
        </style>
    </body>
</html>