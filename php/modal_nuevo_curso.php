<?php 
session_start();
require_once '../conf/conexion_db.php';
require_once '../conf/funciones_db.php';
?>
<div class="modal fade" id="modal_curso">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h4 class="modal-title">Nuevo Curso</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<form id="formulario_curso">
					<div class="row">
						<div class="col-md-6">
							<label>Nombre Curso:</label>
							 <input type="text" name="nom_curso" id="nom_curso" class="form-control" required />
							 <span class="text-danger" id="nom_requerido"> * Campo requerido</span>
						</div>
						<div class="col-md-6">
							<label>Nivel Curso:</label>
            <?php niveles_compromiso_escolar();?>
          </div>
						<div class="col-md-6">
							<label>Docente Curso:</label>
            <?php lista_docente_establecimiento($_SESSION["identificador_estable"]);?>
		  </div>
		  <div class="col-md-6">
             <label>Año Curso:</label>
             <select name="anios_curso" id="anios_curso" class="niveles_ce form-control ">
				 <?php cargar_lista_anios() ?>
            </select>
          </div>   
					</div>
			
			</div>

			<div class="modal-footer">
				<input type="submit" id="guarda_curso" class="btn btn-primary" value="Guardar" />
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
			</form>
		</div>
	</div>
</div>
<script>

		$("#nom_requerido").hide();
        $(document).ready(function(){
	   $("#guarda_curso").click(function(){
		   var curso_nombre = $("#nom_curso").val();
		   if(curso_nombre === ''){
			$("#nom_curso").addClass("is-invalid");
			$("#nom_curso").focus();
			$("#nom_requerido").show();
		   }else{
			var formData = new FormData(document.getElementById("formulario_curso"));
           formData.append("dato", "nuevo_curso");
           $.ajax({
		      url: "php/gest_administrador.php",
              type: "post",
              dataType: "html",
              data: formData,
              cache: false,
              contentType: false,
       processData: false,
       
       success: function(response){
		console.log(response);        
         $("#tabla_soste").load("php/tabla_sostenedor.php");
         $("#tabla_prof").load("php/tabla_docente.php");
          $("#tabla_cur").load("php/tabla_curso.php");       
            
         var respuesta = (JSON.parse(response));
         if(respuesta.estado === "1"){
          alertify.success("<div class='text-white text-center'>Datos guardados exitosamente</>");  
         
         }
         else if(respuesta.estado === "0"){        
          alertify.error("<div class='text-white text-center'>Datos no guardados</>");    
         
         }else if(respuesta.estado === "2"){          
			alertify.error("<div class='text-white text-center'>El docente ya registra un curso asociado</>");             
         
         }
        

       }
          })

		   }
	
          
})
})
</script>