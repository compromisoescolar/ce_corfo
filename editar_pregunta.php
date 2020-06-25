<?php
require'conf/conf_requiere.php';
session_start();
$_SESSION["token"] = md5(uniqid(mt_rand(), true));
if (isset($_SESSION['user'])) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Pregunta</title>
    <?php include"assets/css/css.php"; ?>
</head>
<body>
  <!-- Menu-->
  <nav class="navbar navbar-light">
                <a class="navbar-brand" href="#"><img src="assets/img/logo.png"/></a>
                <span class="navbar-text"><a href="salir.php?csrf=<?php echo $_SESSION["token"]; ?>"><img src="assets/img/salir.png"></a></span>
            </nav>
            <!--Fin Menu-->
<div class="container">
<div class="row mt-2 mb-2">
<h4 class="text-white text-xs-center">Edición de Pregunta</h4>

</div>
<?php
$id_pregunta = $_GET["id_pregunta"];
$con = connectDB_demos();
$query = $con->query("SELECT * FROM ce_preguntas WHERE id_ce_preguntas = '$id_pregunta'");
$con = NULL;
$resultado = $query->fetch(PDO::FETCH_ASSOC);
?>
<form enctype="multipart/form-data" id="formu_editar_pregunta" method="post">
<div class="row border border-red rounded text-white pl-3 pr-3 pt-3 pb-3">
<div class="col-md-4">
<label for="">Pregunta <i class="fa fa-question"></i> :</label>
<textarea name="txt_nombre_pregunta" class="form-control" rows="3" required><?php echo $resultado["ce_pregunta_nombre"]; ?></textarea>
</div>
<div class="col-md-4">
<label for="">Dimensión <i class="fa fa-file"></i> :</label>
<?php selecciona_dimension(); ?>
</div>
<div class="col-md-4">
<label for="">Pais <i class="fa fa-globe"></i> :</label>
<?php selecciona_paises(); ?>
</div>
<div class="col-md-4">
<input name="txt_id_pregunta" id="txt_id_pregunta" type="text" class="invisible" value ="<?php echo $resultado["id_ce_preguntas"];?>">
</div>
<div class="col-md-4 mt-2 mb-2">
<button type="submit" class="btn btn-success"><i class="fa fa-edit"></i>Guardar Cambios</button>
</div>
</div>
</form>
</div>
    
<?php include"assets/js/js.php"; ?>
<script>
$(document).ready(function(){
    $("#select_dimension").val(<?php echo $resultado["ce_dimension_id"]; ?>);
    $("#select_pais").val(<?php echo $resultado["ce_pais_id_ce_pais"]; ?>);
})
</script>
</body>
</html>
<?php
} else {
    header("location:login_auto_gestion.php");
}