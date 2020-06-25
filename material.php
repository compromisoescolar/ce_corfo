<?php
 require_once 'conf/conf_requiere.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Compromiso Escolar-Material Apoyo</title>
    <?php include "assets/css/css.php" ?>
</head>
<body>
     <nav class="navbar navbar-light">
         <a class="navbar-brand" href="#"><img src="assets/img/logo.png"/></a>
         <span class="navbar-text"><a href="index2.php" data-toggle="tooltip" data-placement="top" title="Salir"><img src="assets/img/salir.png"></a></span>
     </nav>
    <div class="container">
        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="active" id="aula-tab" data-toggle="tab" href="#aula" role="tab" aria-controls="aula" aria-selected="true">Talleres Para Aula</a>
  </li>
  <li class="nav-item">
    <a class="" id="familia-tab" data-toggle="tab" href="#familia" role="tab" aria-controls="familia" aria-selected="false">Talleres Para Familia</a>
  </li>
  <li class="nav-item">
    <a class="" id="otros-tab" data-toggle="tab" href="#otros" role="tab" aria-controls="otros" aria-selected="false">Otros Materiales</a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="aula" role="tabpanel" aria-labelledby="aula-tab">
      <div class="row">
      <?php echo select_documentos_aula(); ?>
      </div>

  </div>
  <div class="tab-pane fade" id="familia" role="tabpanel" aria-labelledby="familia-tab">
  <div class="row">
      <?php echo select_documentos_familia(); ?>
      </div>

  </div>
  <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
  <div class="row">
      <?php echo select_documentos_otros(); ?>
      </div>
  </div>
</div>
       
    </div>

<?php include "assets/js/js.php"; ?>
</body>
</html>
