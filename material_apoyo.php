<?php
require_once "conf/conf_requiere.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}

$rbd = $_SESSION['user'];

if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    unset($_SESSION['status']);
}

?>

<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
    <?php include "assets/css/css.php" ?>
</head>
<body>
<!-- Menu-->
<nav class="navbar navbar-light">
    <a class="navbar-brand" href="#"><img src="assets/img/logo.png"/></a>
    <span class="navbar-text"><a href="salir.php"><img src="assets/img/salir.png"></a></span>
</nav>

<?php if (isset($status)): ?>
    <div class="alert alert-<?php echo $status['type'] ?>">
        <strong><?php echo $status['message'] ?></strong>
    </div>
<?php endif ?>

<div class="container">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <!--  <li class="nav-item">
            <a class="" id="home-tab" data-toggle="tab" href="#listapdf" role="tab" aria-controls="home"
               aria-selected="true">Archivos Pdf</a>
        </li>
        <li class="nav-item">
            <a class="" id="profile-tab" data-toggle="tab" href="#listadocx" role="tab" aria-controls="profile"
               aria-selected="false">Archivos docx</a>
        </li>
        <li class="nav-item">
            <a class="" id="profile-tab" data-toggle="tab" href="#listapptx" role="tab" aria-controls="profile"
               aria-selected="false">Archivos pptx</a>
        </li>
        <li class="nav-item">
            <a class="" id="profile-tab" data-toggle="tab" href="#listamp4" role="tab" aria-controls="profile"
               aria-selected="false">Archivos Mp4</a>
        </li>
        <li class="nav-item">
            <a class="" id="profile-tab" data-toggle="tab" href="#listamp3" role="tab" aria-controls="profile"
               aria-selected="false">Archivos mp3</a>
       <!--> </li>
        <li class="nav-item <?php //echo $tipo_usuario;?>">
            <a class="" id="profile-tab" data-toggle="tab" href="#nuevodocumento" role="tab" aria-controls="profile"
               aria-selected="false">Nuevo Documento</a>
        </li>

    </ul>
    <div class="row my-4 ml-1">
        <a class="btn btn-warning btn-sm text-white" href="modulos">Volver </a>
    </div>

    <div class="tab-content" id="myTabContent">
        <!--<div class="tab-pane fade show active" id="listapdf" role="tabpanel" aria-labelledby="home-tab">
            <h4>Archivos PDF</h4>
            <div class="row">
                <?php //echo select_material_pdf() ?>
            </div>
        </div>

        <div class="tab-pane fade show" id="listadocx" role="tabpanel" aria-labelledby="home-tab">
            <h4>Archivos DOCX</h4>
            <div class="row">
                <?php //echo select_material_docx(); ?>
            </div>
        </div>

        <div class="tab-pane fade show" id="listapptx" role="tabpanel" aria-labelledby="home-tab">
            <h4>Archivos PPTX</h4>
            <div class="row">
                <?php //echo select_material_pptx(); ?>
            </div>
        </div>

        <div class="tab-pane fade show" id="listamp4" role="tabpanel" aria-labelledby="home-tab">
            <h4>Archivos MP4</h4>
            <div class="row">
                <?php //echo select_material_video(); ?>
            </div>
        </div>

        <div class="tab-pane fade show" id="listamp3" role="tabpanel" aria-labelledby="home-tab">
            <h4>Archivos MP3</h4>
            <div class="row">
                <?php //echo select_material_mp3(); ?>
            </div>
 </div>-->

        <div class="tab-pane fade show active" id="nuevodocumento" role="tabpanel" aria-labelledby="home-tab">
            <form enctype="multipart/form-data" class="form-horizontal" id="carga_documento" method="post"
                  action="conf/sube_material.php">
                <div class="row mt-4 content-center">
                    <div class="form-row">
                    <div class="col-md-4">
                            <label class="text-white mb-1"> Talleres</label>
                            <select class="form-control" id="talleres_doc" name="talleres_doc">
                                <?php echo select_talleres(); ?>
                            </select>                            
                        </div>
                        <div class=" col-md-4">
                            <label class="text-white mb-1"> Sección</label>
                            <select class="form-control" id="seccion_doc" name="seccion_doc">
                                <?php echo select_seccion(); ?>
                            </select>
                            <input class="invisible" name="rbd_establecimiento"
                                   value="<?php echo $_SESSION['user']; ?>"/>

                        </div>
                        <div class="col-md-4">
                            <label class="text-white mb-1">Nuevo Documento</label>
                            <input id="file_doc" type="file" class="form-control" name="documento_carga" required>
                        </div>
                        <div class="4">
                            <button type="submit" class="btn btn-danger"> Subir Documento <i class="fa fa-upload" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>

            </form>
            <div id="spinner_doc" class="row justify-content-center">

            </div>
        </div>
    </div>
    <?php include "assets/js/js.php"; ?>
<script>
validar_extension_apoyo();
</script>
</body>

</html>
