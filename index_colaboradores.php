<?php
	require_once 'conf/conf_requiere.php';
	session_start();
	if (isset($_SESSION['user'])) {
		$id_establecimiento = $_SESSION["identificador_estable"];
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
<meta charset="utf-8">
<title></title>
	<?php include"assets/css/css.php"; ?>
	<style type="text/css">
		.conta {
			margin-right: 100px;
			margin-left: 100px; 
			padding-bottom: 20px;
		}
	</style>
</head>
<body style="background: #418bcc;">
	<!-- Menu-->
	<nav class="navbar navbar-light" style="background-color: white">
		<a class="navbar-brand" href="/modulos.php"><img src="assets/img/logo_compromiso_escolar.png" /></a>
		<span class="navbar-text"><a href="salir.php"><img
					src="assets/img/salir.png"></a></span>
	</nav>
	<!--Fin Menu-->
	<div class="conta" style="padding-top: 2rem">

		<div class="row mb-3">
			<h4 class="text-white">Gestión de Establecimientos</h4>
		</div>

		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item"><a class="nav-link active bg-success" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Docentes</a></li>
					<li class="nav-item"><a class="nav-link bg-success" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Cursos</a></li>
					<li class="nav-item"><a class="nav-link bg-success" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Sostenedores</a></li>
					<li class="nav-item"><a class="nav-link bg-success" href="/modulos.php"><i
								class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a></li>
				</ul>
				<div class="tab-content" id="myTabContent">

					<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

						<div class="row" id="doc_profesores">
							<button type="button" class="btn btn-light mt-2" data-toggle="modal" data-target="#modal_profesor">
								Nuevo Docente <i class="fa fa-users" aria-hidden="true"></i>
							</button>

						</div>
						<div id="tabla_prof" class="pt-4"></div>
						<?php include "php/modal_nuevo_profesor.php"; ?>
							<?php include "php/modal_update_docente.php"; ?>

					</div>

					<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

						<button type="button" class="btn btn-light mt-2" data-toggle="modal" data-target="#modal_curso">
							Nuevo Curso <i class="fa fa-plus-circle" aria-hidden="true"></i>
						</button>
						<div id="tabla_cur" class="col-md-12 pt-4"></div>
						<div id="modal_nuevo_curso"></div>
						<div id="modal_actualiza_curso"></div>

					</div>

					<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
						<div class="row">
							<button type="button" class="btn btn-light mt-2" data-toggle="modal" data-target="#modal_sostenedor">
								Nuevo Sostenedor <i class="fa fa-user-plus" aria-hidden="true"></i>
							</button>
						</div>

						<div id="tabla_soste" class="pt-4"></div>

						<?php include "php/modal_nuevo_sostenedor.php"; ?>
						<?php include "php/modal_update_sostenedor.php"; ?>
					</div>

				</div>
			</div>
		</div>

	</div>
	<?php include "assets/js/js.php"; ?>
	<script>
		$("#tabla_soste").load("php/tabla_sostenedor.php");
		$("#tabla_prof").load("php/tabla_docente.php");
		$("#tabla_cur").load("php/tabla_curso.php");			 		 
		$("#modal_nuevo_curso").load("php/modal_nuevo_curso.php");
		$("#modal_actualiza_curso").load("php/modal_update_curso.php");

		ingreso_curso();
		ingreso_sostenedor();
		ingreso_docente();             
		ante_poner_letra();    
		ante_poner_letra_update_docente()
		ante_poner_letra_new_sos();       
		ante_poner_letra_update_sostenedor();   

	</script>
</body>
</html>
<?php
	} else {
		header("location:index2.php");
	}
?>