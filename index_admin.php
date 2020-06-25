<?php
require_once 'conf/conf_requiere.php';
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
<body style="background: #418bcc;">
	<!-- Menu-->
	<nav class="navbar navbar-light" style="background-color: white">
		<a class="navbar-brand" href="/modulos.php"><img src="assets/img/logo_compromiso_escolar.png" /></a>
		<span class="navbar-text"><a href="salir.php"><img
				src="assets/img/salir.png"></a></span>
	</nav>
	<!--Fin Menu-->
	<div class="container" style="padding-top: 2rem">
		<div class="row mb-3">
			<div class="text-center">
				<h4 class="text-white">Gestión de Establecimientos</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<button type="button" class="btn btn-primary" data-toggle="modal"
					data-target="#myModal">
					Registrar Establecimiento <i class="fa fa-floppy-o"
						aria-hidden="true"></i>
				</button>
			</div>
			<div class="col-md-6">
				<a class=" text-white float-right" href="modulos.php"
					data-toggle="tooltip" data-placement="bottom" title="Volver"><i
					class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
			</div>

		</div>
		<div class="row" id="estable_lista"></div>
              
                <?php require 'php/nuevo_usuario.php'; ?>     

            </div>
	<div class="modal fade" id="myModal">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Registro de Establecimientos</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<form id="formulario_establecimiento">
						<div class="row">

							<div class="col-md-4">
								<label for=""> Nombre Establecimiento:</label> <input
									name="nombre_establecimiento" id="nombre_establecimiento"
									type="text" class="form-control" required> <span id="spnError2"
									style="color: Red; display: none"></span>
							</div>
							<div class="col-md-4">
							       	<label for=""> RBD Establecimiento:</label> <input
									id="rbd_establecimiento" name="rbd_establecimiento" type="text"	class="form-control" required>

							</div>
							<div class="col-md-4">
									   <label for=""> País Establecimiento:</label>
									   <select name="sel_country_id" id="sel_country_id" class="form-control mb-3" >
                                        <?php echo select_pais();?>                
                                    </select>					

							</div>

						</div>
				
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<div class="float-lg-right">
						<input type="submit" class="btn btn-primary" value="Guardar"
							onclick="">
					</div>
					<div class="float-right">
						<button type="button" class="btn btn-secondary pull-right"	data-dismiss="modal">Cerrar</button>
					</div>
					</form>
				</div>

			</div>
		</div>
	</div>
            <?php include "assets/js/js.php"; ?>
            <script>
           carga_establecimiento(); 
          $('#estable_lista').load("php/lista_establecimientos.php");
      
            </script>

</body>
</html>
<?php
} else {
    //header("location:reportes/login.php");
    header("location:index2.php");
}
