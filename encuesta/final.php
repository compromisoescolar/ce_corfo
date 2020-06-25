<?php
session_start();
require_once "diplomas/genera_diploma.php";
if (isset($_SESSION['estudiante'])) {
  
    genera_diploma($_SESSION['estudiante']);
    
    txt_termina_encuesta($_SESSION['estudiante'],$_SESSION['pais']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Compromiso Escolar - Encuesta Estudiante</title>
    <?php require_once"css.php"; ?>
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script type="text/javascript">

            url_base = window.location;
           

            $( document ).ready(function() {
                /////////// cerrar modal ///////////////////////
               

                $("#btn_cerrar_session").click(function() {
                    window.location.replace(
                        url_base.protocol + "//" + 
                        url_base.host + "/" + 
                        "index2.php"
                    );
                });

              
                
            });
        </script>
</head>
<body>
    <div id="cabecera-salir" style="padding-top: 1px; background: #fc455c;">
        <table width="100%" height="100%" style="padding-top: 200px;">
            <tr valign="top">
                <td align="left" valign="top" style="padding-top: 0px;">
                    <div style="display: flex; align-items: baseline;">
                        <img style="height: 78px; width: 750px;"  src="../assets/img/c1_encuesta.png">
                        <div style="margin-top: 20px; margin-left: 195px; font-size: 20px; position: absolute; color: white;">
                            Encuesta finalizada
                        </div>
                    </div>
                </td> 

                <td>
                    <div  style="width: 140px; height: 50px; ">
                        <button id="btn_cerrar_session" style="text-decoration: none; background: transparent; width: 100%; height: 100%;  background-repeat: no-repeat; border-radius: 35px; border: none; cursor:pointer; overflow: hidden; outline:none; background-position: center;">
                            <img style="width: 100%; height: 100%; " src="../assets/img/salir-2.png">
                        </button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    

    <div class="contenido row justify-content-center align-items-center">
        <div class="card_c" style="background: #40c2d4;">
            <div id="resultados" class="resultado_final">
                <h1 style="font: condensed 330% sans-serif; color: white;">¡Gracias por tu ayuda!</h1>
                <hr style="background: white; height: 1px;">
                <h2 style="font: condensed 150% sans-serif; color: white;">
                    Si lo deseas puedes descargar ahora tu diploma de participación
                </h2>
                <br>
                <div class="col-md-12">
                    <?php $ruta_imagen = "diplomas/diploma_".$_SESSION['estudiante'].".jpg"; ?>
                    <a href="diplomas/diploma.php?tipo=im&diploma=diploma_<?php echo $_SESSION['estudiante'];?>.jpg">
                        <img class="hvr hvr-grow" src="<?php echo $ruta_imagen;?>" width="350"></a>
                </div>
                <div class="col-md-12 pt-3">
                    <span style="font-style: normal; font-weight: 700;  margin: 2em 0 0.5em;font-size: 1.2em; color: white;">
                        O si prefieres puedes enviarlo a un correo electrónico:
                    </span>
                </div>
                <div class="col-md-12 pt-3">
                    <input type="email" style="border-radius:5px; box-shadow:0px 1px 0px 1px #DEDEDE; border:1px solid #DEDEDE; width:520px;" id="txt_email" placeholder="correo@dominio.cl">
                </div>      
                <div class="col-md-12 pt-3">
                    <input id="btn_correo" type="button" value="Enviar" style="cursor:pointer; width:100px; border-radius:5px; width: 130px;" data-ruta="L2RpcGxvbWFzL0RpcGxvbWFfMjM4NzQ5MjcuanBn">
                </div>                      
                <div style="display: inline-block; padding-left: 50px; text-align: left; vertical-align: top">
                    <div id="msgErrorEmail" style="text-align:center;"></div>
                </div>     
                <p class="url"></p>
            </div> 
        </div>
    </div>
    <?php require_once "js.php"; ?>
    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        $('#btn_correo').click(function() {
            var cuenta = document.getElementById('txt_email').value;   
            var tipo = 'em';
            var token = "<?php echo $_SESSION['estudiante']; ?>";   
            
            if(cuenta === '') {
                alertify.alert('Notificación',"Para realizar el envío de el diploma a tu correo debes ingresar una dirección de correo.");
            } else {
                $.ajax({
                    url: 'diplomas/descarga_imagen.php',
                    dataType:"text",
                    data: {      
                        cuenta:cuenta,                  
                        token:token,
                        tipo:tipo     
                    },
                    type: 'GET',
                    
                    beforeSend: function() {   
                        document.getElementById('msgErrorEmail').innerHTML = '<div class="alert alert-info" role="alert"><i class="fa fa-spinner fa-3x fa-spin"></i> Enviando Correo, por favor espere</div>';
                        $("#btn_correo").attr('disabled', true);        
                                              
                    }, success:function(response) {
                            console.log(response);                   
                            var condicion = JSON.parse(response);
                          
                            if(condicion.estado === "1") {
                                $("#btn_correo").attr('disabled', false);
                                document.getElementById('msgErrorEmail').innerHTML = '<div class="alert alert-success mt-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Correo Enviado <i class="fa fa-check fa-2x"></i></div>';  
                            } else if(condicion.estado === "0"){
                                $("#btn_correo").attr('disabled', false);
                                document.getElementById('msgErrorEmail').innerHTML = '<div class="alert alert-danger mt-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Correo Enviado <i class="fa fa-times-circle-o fa-2x"></i></div>';  
                            } else if(condicion.estado === "error") {
                            $("#btn_correo").attr('disabled', false);
                                document.getElementById('msgErrorEmail').innerHTML = '<div class="alert alert-danger mt-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Correo no enviado, por favor verifique su dirección de correo.<i class="fa fa-exclamation-circle fa-2x"></i></div>';  
                            }          
                        }
                    }
                );
            }
        });

        $(function(){
            $('#opcion1').tooltip({
                placement: "left",
                title: "En esta opción puedes descargar el diploma a la computadora."
            });
            $("#opcion2").tooltip({
                placement: "left",
                title: "En esta opción puedes enviar el diploma a una cuenta de correo."
            });
        });
    </script>
    <footer  class="page-footer font-small mdb-color lighten-3 pt-4" style="margin-bottom: 0px; padding-bottom: 0px; bottom: 0; height: 200px;">
        <div class="container" style="margin-bottom: 20px;">
           <table id="tab_foot" width="100%" border="0">
                <tr>
                    <td align="left" width="33%">
                        <div style="float: center; display: flex;">
                            <img style="margin-right: 5px;" width="44" src="../assets/img/mineduc.png">
                            <img style="margin-right: 5px;" width="120" src="../assets/img/fondef.png">
                            <img style="margin-right: 5px;" width="100" src="../assets/img/corfo.png">
                            <img style="margin-right: 5px;" width="30" src="../assets/img/ufro.png">
                            <img style="margin-right: 5px;" width="90" src="../assets/img/autonoma.png">
                            <img style="margin-right: 5px;" width="150" src="../assets/img/fund_telefonica.png">
                        </div>
                    </td>
                    <td align="right" width="33%">
                        <p style="font-size: small; text-align: left; text-align: justify;"> 
                            Estas encuestas forman parte del Proyecto FONDEF ID14I10078-ID14I20078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.
                        </p>
                    </td>
                </tr>
           </table>
        </div>
    </footer>
</body>
</html>
<?php
    } else {
        header("location:../salir.php");
    }