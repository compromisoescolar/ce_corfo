<?php
require 'conf/conf_requiere.php';
session_start();
$_SESSION["token"] = md5(uniqid(mt_rand(), true));
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
	<nav class="navbar navbar-light">
		<a class="navbar-brand" href="#"><img src="assets/img/logo.png" /></a>
		<span class="navbar-text"><a href="salir.php?csrf=<?php echo $_SESSION["token"];?>"><img
				src="assets/img/salir.png"></a></span>
	</nav>
	<!--Fin Menu-->
	<div class="container">
		<div class="row">
			<div>
				<h4 class="text-white">Gestion de Encuesta</h4>
				<a class="btn btn-warning text-white" href="modulos.php"> Volver</a>
			</div>

		</div>
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="col-3">
					<label name="" class="text-white" for="">Selecciona Pais:</label>
                        <?php  selecciona_paises(); ?>
                        </div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<div class="float-right">
					<h3 class="text-white">
						<i class="fa fa-align-right"></i> Lista de Preguntas
					</h3>
				</div>
			</div>
			<div class="col-md-5">
				<div class="float-right">
					<div class="mb-2">
						<label for="" class="text-white">Buscar <i class="fa fa-search "></i></label>
						<input id="search_pregunta" type="text" class="form-control mr-2"
							placeholder="buscar...">

					</div>
				</div>

			</div>


		</div>



	</div>
	<div id="containerdos" class="container">

		<div id="recibe_preguntas_pais"></div>


	</div>

            <?php include"assets/js/js.php"; ?>
            <script>              
               
                $(document).ready(function (){
                    $("#select_pais").change(function() {
                        var id_pais = $("#select_pais").val();
                        $.ajax({
                            type:"POST",
                            url: "conf/selecciona_pais.php",
                            data: "id_pais=" + id_pais,
                            cache: false,
                            statusCode: {
                            404: function () {
                            alertify.alert("Pagina no Encontrada");                         
                    },
                    502: function () {
                        alertify.alert("Ha ocurrido un error al conectarse con el servidor");

                    }
                },
                success: function(response){
                    $("#recibe_preguntas_pais").html(response);        
                        $(document).ready(function () {
                    $("#search_pregunta").keyup(function () {
                        _this = this;
                        // Show only matching TR, hide rest of them
                        $.each($("#tabla_pais tbody tr"), function () {
                            if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                                $(this).hide();
                            else
                                $(this).show();
                        });
                        if ($("#search_pregunta").val() === "") {                                               
                          $('#recibe_preguntas_pais').load("conf/selecciona_pais.php", {id_pais: id_pais});
                          
                         
                        }
                    });
                });
                  
                }
               
                        
                    })
                })
            })

            function editar_pregunta(){
                $(document).ready(function() {
                    $(".editar_pregunta").click(function(){
                        var id_pregunta = $(this).parents("tr").find("td").eq(0).text();
                        var nombre_pregunta = $(this).parents("tr").find("td").eq(1).text();
                        alertify.success(id_pregunta+" "+nombre_pregunta);
                    })
                })
            }
                </script>

</body>
</html>
<?php
} else {
    header("location:login_auto_gestion.php");
}
