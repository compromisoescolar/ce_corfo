<?php
session_start();
if (isset($_SESSION['estudiante'])) {
    $mi_pais = $_SESSION['pais'];
   
    require_once "../conf/conexion_db.php";
    require_once "../conf/funciones_db.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Compromiso Escolar - Encuesta Estudiante</title>
        <?php require_once"css.php"; ?>
    </head>
    <body>
        <div id="cabecera-salir"><span class="logo"></span><div id="salir"><a id="link-salir" href="../salir.php"><span id="btn-salir" title="Salir">&nbsp;</span>Salir</a></div></div>
        <div id="container" class="lang-es groupbygroup showprogress showqnumcode-X js">    
            <div id="region-main" class="s-5835389 mt-5">
                <div id="content" class="outerframe">
                    <div id="welcome">
                        <div class="row">
                            <div class="col-sm-4">            
                                <div class="dibujo_welcome">
                                </div>
                            </div>
                            <div class="col-sm-8">                         
                               
                               <?php $result = etimologia_textos($mi_pais); echo $result["text_1_ini"];?>
                               
                            </div>
                        </div>

                    </div>
                    <p class="navigator"><button type="submit" id="movenextbtn" value="movenext" name="movenext" accesskey="n" class="submit button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-secondary" role="button" aria-disabled="false">Comenzar</button></p>
                </div>
            </div>
        </div>
        <div id="footer">

            <div id="nro-fondef">
                <h1 style="text-align: center;padding-top: 10px">Estas encuestas forman parte del proyecto Proyecto FONDEF ID14I10078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas</h1>
            </div>
            <!--span class="logo"></span-->
            <div id="logocontainer">
                <div>
                    <div class="logo"><a href="http://www.conicyt.cl/fondef/" target="_blank"><img width="272" height="91" style="display:inline; vertical-align:middle;" alt="FONDEF" src="logo_fondef.png"></a></div>

                    <div class="logo"><a href="http://www.ufro.cl/" target="_blank"><img width="160" height="91" style="display:inline; vertical-align:middle;" alt="Universidad de la Frontera" src="logo_ufro.png"></a></div>

                    <div class="logo"><a href="https://www.uautonoma.cl/" target="_blank"><img width="205" height="91" style="display:inline; vertical-align:middle;" alt="Universidad Autonoma de Chile" src="logo_autonoma.png"></a></div>
                </div>

            </div>
            <div style="text-align: center;" class="footer-descripcion">Universidad de La Frontera Avenida Francisco Salazar 01145, Temuco - Chile</div>
        </div>
        <?php require_once "js.php"; ?>
        <script>
            history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
	
        document.getElementById("movenextbtn").onclick = function() {
            location.href ="respn_encues2";
                };
        
        </script>
    </body>
</html>
<?php
} else {
    header("location:../index2.php");
}