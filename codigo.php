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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include "assets/css/css.php"; ?>
       
   
    </head>
    <body>

    <section class="cod_header imagen_container">
    
            <div class="container">
            <div id="logo"></div>
                <div class="row">
                <div class="col-md-7">
                    <h1 class="h1_portada">¡Bienvenidos/as!</h1>
                    <p class="text-white text-justify">
                       En este sitio tendrás acceso a encuestas que buscan medir el compromiso escolar de niños, niñas y adolescentes. A partir de esta información se pueden generar acciones para que todos y todas asistan a la escuela, permanezcan en la educación básica y logren terminar la educación de manera exitosa.
                   </p>
                </div>

                    <div class="col-md-5">
                    <div class="entercode">
                        
                        <div id="id_ingre_cod">
                            <form enctype="multipart/form-data" id="fm_codigo" method="post">
                                <h3  class="border-bottom text-info pb-1 mb-2">Selecciona Tu Pais: <i class="fa fa-globe" aria-hidden="true"></i></h2>
                                    <select name="sel_country" id="sel_country" class="form-control mb-3" >
                                        <?php echo select_pais(); ?>                
                                    </select>
                                    <h3 class="border-bottom text-info pb-1 mb-3">Ingresa tu Código: <i class="fa fa-unlock-alt" aria-hidden="true"></i></h2>

                                        <div class="codigo_txt">
                                            <input type="text" name="txt_contrasena" id="txt_contrasena" class="form-control has-warning" placeholder="Tú Código">
                                        </div>    
                                        <button id="btn_token" type="submit" class="icon-submit btn-limon-validar"><span id="ingresar">INGRESAR </span><div id="spinner"></div>
                                           </button>                                         
                                        </form>
                                        </div>
                       
                                        </div> 

                    </div>

                </div>
            </div>
        </section>

        <section class="cod_botones">
            <div class="container">
                <div class="row">    

                         <div class="col-md-6">
                            <div class="pt-2"><span class="text-muted">Los participantes pueden acceder a reportes y material de apoyo de compromiso escolar en los siguintes link:</span></div>                                                                         
                         </div>

                         <div class="col-md-2 col-6 text-center">
                           <a href='reportes/login' target="_parent" class="btn btn-primary mt-1  mb-1 hvr-grow  text-white">Reportes <i class="fa fa-pie-chart" aria-hidden="true"></i></a>                         
                         </div>

                         <div class="col-md-2 col-6 text-center">
                           <a href='recursos' target="_parent" class="btn btn-primary mt-1 mb-1 hvr-grow text-white">Material de Apoyo <i class="fa fa-lightbulb-o" aria-hidden="true"></i></a>
                         </div>

                          <div class="col-md-2 col-6 text-center">
                           <a href='reportes/ce_admin' target="_parent" class="btn btn-primary mt-1 mb-1 hvr-grow text-white">Administración <i class="fa fa-users" aria-hidden="true"></i></a>
                         </div>

                    </div>               
            </div>
        </section>

        
        <section class="cod_contenido">
             <div class="container">
                 <div class="row mt-5">

                     <div class="col-md-12">
                     <p class="texto"><strong>Estas encuestas forman parte del Proyecto FONDEF ID14I10078-ID14I20078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.</strong></p>
                    
                     </div>
                    
                     <div class="col-md-3 mt-2 text-center">
                     <a href="http://www.conicyt.cl/fondef/" target="_blank" class="hvr-float-shadow"><img src="encuesta/logo_fondef.png" alt="" width="272px" height="90.99px"></a>                                                             
                     </div>
                     <div class="col-md-3 mt-2 text-center">
                        <a href="https://www.mineduc.cl" target="_blank" class="hvr-float-shadow"><img src="assets/img/Mineduc.svg" alt="" width="160px" height="90.99px"></a>
                     </div>

                     <div class="col-md-3 mt-2 text-center">
                         <a href="https://www.ufro.cl/" target="_blank" class="hvr-float-shadow"><img src="encuesta/logo_ufro.png" alt="" width="160px" height="90.99px"> </a>
                     </div>

                     <div class="col-md-3 mt-2 text-center">
                      <a href="https://www.uautonoma.cl" target="_blank" class="hvr-float-shadow"><img src="encuesta/logo_autonoma.png" alt="" width="205px" height="90.99px"></a>                                                               
                                                                        
                     </div>
               
                 </div>
             </div>

        </section>        
        <section class="cod_footer">
            <div class="container">
                <div class="row">
                 <div class="col-md-5">
                 <p class="text-muted">Universidad de La Frontera Avenida Francisco Salazar 01145, Temuco - Chile</p>
                 </div>
                 <div class="col-md-7">
                 <p class="text-muted">Si necesitas solicitar tus claves de acceso gratuito a nuestra plataforna de compromiso escolar, favor contacta a nuestro equipo al correo electrónico <i class="fa fa-envelope text-danger" data-toggle="tooltip" data-placement="top" title="Contacto"></i> info@compromisoescolar.com </p>                 
                 </div>
                </div>
            </div>
        </section>


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
                                        </body>
                                        </html>