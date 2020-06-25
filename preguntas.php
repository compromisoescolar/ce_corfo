<?php
require'conf/conf_requiere.php';
session_start();
if (isset($_SESSION['user'])) {
    ?>
    <!DOCTYPE html>
    <html lang="es" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title></title>
            <?php include"assets/css/css.php"; ?>
        </head>
        <body>
            <!-- Menu-->
            <nav class="navbar navbar-light">
                <a class="navbar-brand" href="#"><img src="assets/img/logo.png"/></a>
                <span class="navbar-text"><a href="salir.php"><img src="assets/img/salir.png"></a></span>
            </nav>
            <!--Fin Menu-->
            <div class="container">
                <div class="row mb-3">
                    <h4 class="text-white">Preguntas</h4>        
                </div>

                 <div class="row">
           
           <div class="col-md-6">
           <h4 class="text-white">Preguntas Chile</h4>
           <?php echo preguntas_chile(); ?>
           </div>            
           <div class="col-md-6">
           <h4 class="text-white">Preguntas Perú</h4>
           <?php echo preguntas_peru();?>
           </div>            
           <div class="col-md-6">
           <h4 class="text-white">Preguntas Uruguay</h4>
           <?php echo preguntas_uruguay(); ?>
           </div> 
           <div class="col-md-6">
           <h4 class="text-white">Preguntas España</h4>
           <?php echo preguntas_espana(); ?>
          
           </div>           
         
           </div>               

            </div>
           
            <?php include"assets/js/js.php"; ?>
        </body>
    </html>
    <?php
} else {
    header("location:login_auto_gestion.php");
}
