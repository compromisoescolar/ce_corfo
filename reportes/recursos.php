<?php
    require '../conf/conexion_db.php';
    require '../conf/funciones.php';
    require '../conf/funciones_db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Compromiso Escolar</title>
        <?php include "../assets/css/css.php"; ?>
        <link href="https://fonts.googleapis.com/css?family=Arimo|Covered+By+Your+Grace&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Arimo', sans-serif;
                font-family: 'Covered By Your Grace', cursive;
            }
        </style>
</head>
<body>

    <section class="imagen-inicio">      
        <div class="cabezera-imagen"></div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                      <h3 class="text-center">¡Descubre!</h3>
                      <h4 class="text-center"> Recursos Educativos de Compromiso Escolar</h4>
                    </div>
                    
                </div>         
        </div>
    </section>    

    <section class="recurso-pagina-principal">
        <div class="col-md-12">
       <a class="btn btn-primary" href="index2.php"><i class="fa fa-home fa-2x" aria-hidden="true"></i> VOLVER AL INICIO </a>
        </div>
        
    </section>

    <section class="recursos-contenido">
        <div class="container">
       
        <!--<iframe src="https://docs.google.com/gview?url=http://www.softpatagonia.com/autogestion/ejemplo.docx&embedded=true" style="width: 100%;"></iframe>-->
            <div class="col-12">
                    <h2 class="text-center">Recursos agregados recientemente</h2>
                </div>
            <div class="row mb-2">
                 <div class="col-md-2 text-center">                   
                </div>
                              
                <div id="aula-hover" class="col-md-3 text-center bordes-recursos">
                    <a onclick="aula()" class="text-center">Tallerres para Aula</a>
                </div>
                <div  id="familia-hover" class="col-md-3 text-center bordes-recursos">
                    <a onclick="familia()" class="text-center">Talleres para Familia</a>
                </div>
                <div id="otros-hover" class="col-md-3 text-center bordes-recursos">
                    <a onclick="otros()" class="text-center">Otros Materiales</a>
                </div>                    
               
            </div>

            <div class="row" id="talleres_aula">
             <?php echo select_material_talleres_aula();?>
           </div>
            <div class="row" id="talleres_familia">
             <?php echo select_material_talleres_familia();?>
            </div>
            <div class="row" id="talleres_otros">
             <?php echo select_material_talleres_otros();?>
            </div>

        </div>
        
     </section>

     <section class="footer-final">
         <div class="container">
         <div class="row">
        <div class="col-md-7">
        <div class="text-justify mb-4"> Estas encuestas forman parte del Proyecto FONDEF ID14I10078-
ID14I20078 Medición del compromiso del niño, niña y adolescente con
sus estudios para la promoción de trayectorias educativas exitosas.</div>
          <div class="row">
            <div class="col-md-3 col-sm-6">
            <a href="http://www.conicyt.cl/fondef/" target="_blank" class="hvr-float-shadow"><img src="assets/img/conicyt.png" alt="" width="100px" height="90.99px"></a>    

            </div>
            <div class="col-md-3 col-sm-6">
            <a href="https://www.mineduc.cl" target="_blank" class="hvr-float-shadow"><img src="assets/img/Mineduc.svg" alt="" width="160px" height="90.99px"></a>

            </div>
            <div class="col-md-3 col-sm-6">
            <a href="https://www.ufro.cl/" target="_blank" class="hvr-float-shadow"><img src="encuesta/logo_ufro.png" alt="" width="160px" height="90.99px"> </a>

            </div>
            <div class="col-md-3 col-sm-6">
            <a href="https://www.uautonoma.cl" target="_blank" class="hvr-float-shadow"><img src="assets/img/autonoma.jpg" alt="" width="100px" height="90.99px"></a> 
            </div>
          </div>
        </div>
        <div class="col-md-5">   

             <a href="https://www.uautonoma.cl" target="_blank" class="hvr-float-shadow"><img src="assets/img/manual.png" alt="" width="200px" height="190px"></a>  

            
             <a href="https://www.uautonoma.cl" target="_blank" class="hvr-float-shadow"><img src="assets/img/registro_usuario.png" alt="" width="200px" height="190px"></a> 
                         
                       
        </div>
      </div>
             
         </div>

        </section>
     <?php include "../assets/js/js.php"; ?>
     <script>

            $("#talleres_familia").hide(250);
            $("#talleres_aula").show(250);
            $("#talleres_otros").hide(250);
            $("#aula-hover").addClass("hover-aula") 

function familia(){
    if ($('#talleres_familia').is(':hidden')) {
            $("#talleres_familia").show(250);           
            $("#talleres_aula").hide(250);
            $("#talleres_otros").hide(250);

            $("#aula-hover").removeClass("hover-aula") 
            $("#familia-hover").addClass("hover-familia") 
            $("#otros-hover").removeClass("hover-otros") 
} else {
   
}
}

function aula(){
    if ($('#talleres_aula').is(':hidden')) {
            $("#talleres_familia").hide(250);           
            $("#talleres_aula").show(250);           
            $("#talleres_otros").hide(250);

            $("#aula-hover").addClass("hover-aula") 
            $("#familia-hover").removeClass("hover-familia") 
            $("#otros-hover").removeClass("hover-otros") 
} else {
   
}
}

function otros(){
    if ($('#talleres_otros').is(':hidden')) {
            $("#talleres_familia").hide(500);
            $("#talleres_aula").hide(500);          
            $("#talleres_otros").show(500);

            $("#aula-hover").removeClass("hover-aula") 
            $("#familia-hover").removeClass("hover-familia") 
            $("#otros-hover").addClass("hover-otros") 
} else {
   
}
}




     </script>
</body>
</html>