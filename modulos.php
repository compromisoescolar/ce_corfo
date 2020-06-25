<?php
	error_reporting(E_ERROR | E_PARSE);
	ob_start();
	require_once 'conf/conf_requiere.php';
	session_start();

	if (isset($_SESSION['user'])) {
	$usuario = $_SESSION['user'];
	$tipo_usuario = $_SESSION["tipo_usuario"];

	$_SESSION["token"] = md5(uniqid(mt_rand(), true));

    $params = session_get_cookie_params();
    setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
        true,  // this is the secure flag you need to set. Default is false.
        true  // this is the httpOnly flag you need to set
    );
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <link rel=”shortcut icon” type=”image/png” href=”assets/img/favicon.png”/>
    <title></title>
    <?php
        include "assets/css/css.php";
    ?>
<style type="text/css">
    	.btn_cuadrado:hover { 
		  cursor: pointer;
		  opacity: 0.7;
		}

		.btn_cuadrado:active { 
		  cursor: pointer;
		  opacity: 0.4;
		}
    </style>
    </head>
<body style="background: #418bcc;">
	<!-- Menu-->
	<nav class="navbar navbar-light" style="background-color: white">
		<a class="navbar-brand" href="/modulos.php"><img src="assets/img/logo_compromiso_escolar.png" /></a>
		<span class="navbar-text"><a href="salir.php?csrf=<?php echo $_SESSION["token"]; ?>"><img
				src="assets/img/salir.png"></a></span>
	</nav>
	<!--Fin Menu-->
	<div class="container">	
		<div class="row">
			<div class="col-md-12 mt-5">
			<div class="d-flex justify-content-center">
			<span class="compromiso">Panel de <span class="">Administración </span><br><span class="compromiso-texto"></span></span> 
				
		</div>
			</div>
	
		</div>	
		<?php if ($_SESSION['privilegios'] == 0){
		  if( $tipo_usuario == 1){
						curso_docentes($usuario);	

			echo "<script>document.location.herf = 'reportes/estudiante.php'</script>";
		
		}
		?>                  		                
					
		<?php  if( $tipo_usuario == 2){?>
			<?php  pais_establecimiento($usuario);
			header("Location:reportes/colegio_index.php");
			?>
			<div class="mt-4">
			<div class="row">
				<div class="col-md-6 hvr hvr-grow mb-2" id="reporte_colegio" hidden>
					<div class="cuadrado-modulos">
					<i class="fa fa-pie-chart fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>Módulo Reportes</p> 
					</div>
				</div>

				<div class="col-md-6 hvr hvr-grow mb-2" >
				<p class="text-justify text-white">En este sitio tendrás acceso a las opciones de administración de la plataforma. 

Puedes crear los token de acceso a los alumnos(as) a la encuesta, administrar los usuarios que acceden a reportes y crear los cursos participantes. 

Todos los datos ingresados a este módulo se van relacionando de manera de tener un único repositorio de claves de acceso y personas que están participando de la  información que entrega la plataforma de compromiso escolar.</p>

				</div>
				
				<div class="col-md-6 mt-3">
					<div class="cuadrado-modulos ml-5 btn_cuadrado" id="admin_establecimiento">
						<i class="fa fa-pie-chart fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>
							Administración de usuarios y cursos.
						</p>					
					</div>
					<br>
					<div class="cuadrado-modulos ml-5 btn_cuadrado"  id="autogestion_estable">
						<i class="fa fa-tasks fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>
							Crear Token.
						</p>
					</div>
				</div>
			</div>
		</div>
                   

			   <?php } ?> 
			   
			   <?php  
			   if( $tipo_usuario == 3){
				   header("Location:reportes/sostenedor_index.php");
				 }  
				 ?>
                
				<?php  if( $tipo_usuario == 4){?>
					<div class="centrado">
			<div class="row" style="padding-top: 12rem">
			<div class="col-md-6 hvr hvr-grow mb-2" id="lista_establecimientos">
					<div class="cuadrado-modulos" style="cursor: pointer">
					<i class="fa fa-pie-chart fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>Establecimientos</p>
					</div>
				</div>
				<div class="col-md-6  hvr hvr-grow mb-2" id="editar_preguntas">
				<div class="cuadrado-modulos">
				<i class="fa fa-tasks fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>Edición de Preguntas</p> 
					 
					</div>

				</div>



               
				</div>
			
				</div>

			   <?php } 
			
			}
			else if($_SESSION['privilegios'] == 1){?>   
			<?php  if( $tipo_usuario == 2){?>
				<div class="mt-4">
			<div class="row">
				<div class="col-md-6 hvr hvr-grow mb-2" id="reporte_colegio" hidden>
					<div class="cuadrado-modulos">
					<i class="fa fa-pie-chart fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>Módulo Reportes</p> 
					</div>
				</div>

				<div class="col-md-6 hvr hvr-grow mb-2" >
				<p class="text-justify text-white">En este sitio tendrás acceso a las opciones de administración de la plataforma. 

Puedes crear los token de acceso a los alumnos(as) a la encuesta, administrar los usuarios que acceden a reportes y crear los cursos participantes. 

Todos los datos ingresados a este módulo se van relacionando de manera de tener un único repositorio de claves de acceso y personas que están participando de la  información que entrega la plataforma de compromiso escolar.</p>

				</div>
				
				<div class="col-md-6 mt-3">
					<div class="cuadrado-modulos ml-5 btn_cuadrado" id="admin_establecimiento">
						<i class="fa fa-pie-chart fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p> 
							Administración de usuarios y cursos.
						</p>					
					</div>
					<br>
					<div class="cuadrado-modulos ml-5 btn_cuadrado"  id="autogestion_estable">
						<i class="fa fa-tasks fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>
							Crear Token.
						</p>
					</div>
					

				</div>
			</div>
		</div>
			   

			<?php }?>
		
				<?php  if( $tipo_usuario == 4){?>
					<div class="centrado">
			<div class="row" style="padding-top: 12rem">
			<div class="col-md-6 hvr hvr-grow mb-2" id="lista_establecimientos">
					<div class="cuadrado-modulos" style="cursor: pointer">
					<i class="fa fa-pie-chart fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>Establecimientos</p> 
					</div>
				</div>
				<div class="col-md-6  hvr hvr-grow mb-2" id="editar_preguntas" hidden="true">
				<div class="cuadrado-modulos">
				<i class="fa fa-tasks fa-3x center-icono text-success" aria-hidden="true"></i>						
						<p>Edición de Preguntas</p> 
					 
					</div>

				</div>



               
				</div>
			
				</div>

			   <?php }?> 
			

		
			   <?php }?>
			


	</div>
		<?php include "assets/js/js.php"; ?>    
		<script>
			
		  redireccionar(<?php  echo $tipo_usuario?>);
		</script>
    </body>
</html>
<?php
	} else {
	    header("location:reportes/login");
	}
	ob_end_flush();
?>