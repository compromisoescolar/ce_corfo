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
                <span><a name="" id="" class="btn btn-warning float-right text-white" href="index_admin.php" role="button">Volver</a></span>  
                <div class="row mb-3">
                    <?php
                    $identificador = urldecode($_GET["id_usuario"]);
                    $con = $con = connectDB();
                    $query = $con->query("SELECT * FROM lime_usuarios WHERE usu_id = '$identificador'");
                    $con = NULL;
                    $resultado = $query->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <h4 class="text-white">Editar Usuario <?php echo $resultado["usu_nombre"]; ?></h4>  

                </div>  

                <div class="row" id="update_usuarios">
                    <form enctype="multipart/form-data" id="formu_nuevo_usuario_update" method="post">
                        <div class="row mb-3 text-white">
                            <div class="col-md-3">
                                <label>RBD Usuario:</label>
                                <input name="rbd_usuario_update" id="rbd_usuario_update" type="text" class="form-control" value="<?php echo $resultado["usu_nombre"]; ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Contraseña: <i id="mostrar_contrasena" class="fa fa-eye"  data-toggle="tooltip" data-placement="top" title="Mostrar Contraseña"></i></label>
                                <input name="contrase_usuario_update" id="contrase_usuario_update" type="password" class="form-control" value="<?php echo $resultado["usu_pass"]; ?>"  data-toggle="tooltip" data-placement="top" title="La contraseña de Generara Automaticamente" readonly>
                            </div>
                            <div class="col-md-3">                           
                                <label>Estado:</label>                         
                                <select name="select_estado_update" id="select_estado_update" class="form-control">
                                    <option value="1">Habilitado</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>  
                            <div class="col-md-2">
                                <input name="usuario_update_id" id="usuario_update_id" type="text" class="form-control invisible" value="<?php echo $resultado["usu_id"]; ?>" readonly>
                                <input name="usuario_update" id="usuario_update" type="text" class="form-control invisible" value="Actualizar Usuario" readonly>
                            </div>  
                            <div class="col-md-3"> 
                                <br>                         
                                <button id="editar_usuario" type="submit" class="btn btn-success">Editar <i class="fa fa-edit"></i></button>       
                            </div>
                        </div>
                    </form>



                </div>

            </div>


            <?php include"assets/js/js.php"; ?>      
            <script>
                $(document).ready(function () {
                    $("#select_estado_update").val(<?php echo $resultado["usu_active"]; ?>);
                });
                $(document).ready(function () {
                    $("#rbd_usuario_update").keyup(function () {
                        var value = $(this).val();
                        $("#contrase_usuario_update").val(value);
                    });
                });
                $(document).ready(function () {
                    $("#mostrar_contrasena").mouseover(function () {
                        $('#contrase_usuario_update').attr('type', 'text');
                    });
                    $("#mostrar_contrasena").mouseout(function () {
                        $('#contrase_usuario_update').attr('type', 'password');
                    });
                });
            </script>

        </body>
    </html>
    <?php
} else {
    header("location:login_auto_gestion.php");
}
