<?php
require 'conf/conf_requiere.php';
session_start();
$_SESSION["token"] = md5(uniqid(mt_rand(), true));
if (isset($_SESSION['user'])) {
   $resultado =  curso_datos($_GET['establecimiento'], $_GET['docente'], $_GET['curso']);
    ?>
    <!DOCTYPE>
    <html>
        <head>
            <?php require 'assets/css/css.php'; ?>
         <style>
             
             </style>
        </head>
        <body style="background: #418bcc;">
            <!-- Menu-->
            <nav class="navbar navbar-light" style="background-color: white">
                <a class="navbar-brand" href="#"><img src="assets/img/logo_compromiso_escolar.png"/></a>
                <span class="navbar-text"><a href="salir.php?csrf=<?php echo $_SESSION["token"];?>"><img src="assets/img/salir.png"></a></span>
            </nav>
            <!--Fin Menu-->
          
               
                    <div class="col-md-12" style="padding-top: 2rem">
                        <div class="row mb-5">
                            <div class="col-md-4">
                            <a class="text-white" href="index.php" data-toggle="tooltip" data-placement="bottom" title="Volver"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-md-4">
                              <div class="text-center text-white"><h3><?php echo $resultado["nomcurso"];?></h3></div>
                             
                            </div>
                            <div class="col-md-4 text-right">
                            <button id="new_student" class="btn btn-success new_student"  type="button" data-toggle="modal" data-target="#modal_nuevo">Nuevo Estudiante</button>
                            </div>
                        </div>
                        <div class="table-responsive">    

                           
                           
                           
                           <table id="tabla_curso_estalecimiento" class="table table-bordered" width="100%">
            <thead class="text-white bg-color">
             <tr>  
                <th>Codigo</th>             
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Run</th>
                <th>Fecha nacimiento</th>
                <th>Ciudad</th>
                <th>Fecha Carga</th>
                <th>Curso</th>
                <th>Contraseña</th>
                <th>id establecimiento</th>
                <th>id docente</th>
                <th>id curso</th>                
                <th></th>
                <th></th>

            </tr>
          </thead>        
        </table>
              
                        </div>                                           

                    </div>
             
            <?php require 'php/modal_actualizar.php'; ?>
            <?php require 'php/modal_crear.php'; ?>
            <?php include "assets/js/js.php"; ?>
           

        <script>
       // paginacion_editar_estudiantes();

        $(document).ready(function(){
  var establecimiento = <?php echo $_GET['establecimiento'];?>;
  var docente =  <?php echo $_GET['docente'];?>;
  var curso = <?php echo $_GET['curso'];?>;

  listar_curso_establecimiento(establecimiento,docente,curso);
  actualizar_estudiante_editado();
  nuevo_estudiante(establecimiento,docente,curso);
})
var listar_curso_establecimiento = function(establecimiento,docente,curso){
        var tabla_estudiantes_curso =  $("#tabla_curso_estalecimiento").DataTable({
         
        "language":{
            "url": "assets/librerias/datatable/spanish.json"
         },
         "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $('td', nRow).css({'background-color': '#17A2B8','color': 'white'});
                },
        
        "ajax":{
            "method":"GET",
            "url":"php/editar_curso.php",
            "data":{"establecimiento":establecimiento,"docente":docente,"curso":curso}        
        },
        "columns":[
            {"data":"identificador"},
            {"data":"nombres"},
            {"data":"apellidos"},
            {"data":"run"},
            {"data":"fecha_nac"},
            {"data":"ciudad"},          
            {"data":"ingreso"},           
            {"data":"curso"},
            {"data":"token"},
            {"data":"id_establecimiento"},
            {"data":"id_docente"},
            {"data":"id_curso"},
            {"defaultContent":"<button type='button' class='editar_curso btn btn-danger'>Editar <i class='fa fa-pencil-square-o'></i></button>"},
            {"defaultContent":"<button type='button' class='eliminar_estudiante btn btn-danger'>Eliminar <i class='fa fa-trash-o' aria-hidden='true'></i></button>"}
        ],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 8 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 9 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 10 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 11 ],
                "visible": false,
                "searchable": false
            }

            
            ]
        
    })
   
    obtener_datos_editar_curso("#tabla_curso_estalecimiento tbody",tabla_estudiantes_curso);
    eliminar_estudiante("#tabla_curso_estalecimiento tbody",tabla_estudiantes_curso);
}

var obtener_datos_editar_curso = function(tbody, tabla){
        $(tbody).on('click', "button.editar_curso", function(){
            dato = tabla.row( $(this).parents("tr")).data();
            
            var idestudiante = $("#idestudiante_update").val(dato.identificador);  
            var nomb_estudiante = $("#nombre_es").val(dato.nombres); 
            var apell_estudiante = $("#apelli_es").val(dato.apellidos);
            var run_estudiante = $("#run_es_proble").val(dato.run);
            var fech_nacimiento = $("#fecha_naci_es").val(dato.fecha_nac);
            var ciu_estudiante = $("#ciu_est").val(dato.ciudad);
           // var fechacarga_estudiante = $("$").val(dato.);
            var curso_estudiante = $("#curso_es").val(dato.curso); 
            var token_estudiante = $("#token_es").val(dato.token);
            var idestablecimiento_estudiante = $("#idestablecimiento_update").val(dato.id_establecimiento);
            var iddocente = $("#iddocente_update").val(dato.id_docente); 
            var idcurso = $("#idcurso_update").val(dato.id_curso); 
           
           $("#modalactualizarestudiante").modal();
            console.log(dato);
            
        })
    }

    var actualizar_estudiante_editado = function(){
   $("#actualizar_curso_estudi").on("submit", function(e){
     e.preventDefault();   
     var frm_auctualiza_estudiante = new FormData(document.getElementById("actualizar_curso_estudi"));     
     frm_auctualiza_estudiante.append("update","update");
     
     $.ajax({
      url: "conf/delete.php",
      method: "POST",   
      dataType: "html",              
      data: frm_auctualiza_estudiante,
      cache: false,
                contentType: false,
                processData: false,
                    
     }).done(function (info){      
       console.log(info)
       var json_parse = JSON.parse(info);
       if(json_parse.estado === '1'){
       
         alertify.success("<div class='text-white text-center'>Estudiante actualizado</div>");
         $('#tabla_curso_estalecimiento').DataTable().ajax.reload();
       }
       else if(json_parse.estado === '0'){
       
        alertify.error("<div class='text-white text-center'>Estudiante no actualizado</div>");       
        $('#tabla_curso_estalecimiento').DataTable().ajax.reload();
        }

      
      
     })
    
   })
 }

 var nuevo_estudiante = function(establecimiento,docente,curso){
   $("#crear_estudiante").on("submit", function(e){
     e.preventDefault();   
     var frm_nuevo_estudiante = new FormData(document.getElementById("crear_estudiante"));     
     frm_nuevo_estudiante.append("nuevo","new");
     frm_nuevo_estudiante.append("idestablecimiento",establecimiento);
     frm_nuevo_estudiante.append("iddocente",docente);
     frm_nuevo_estudiante.append("idcurso",curso);
     $.ajax({
      url: "conf/delete.php",
      method: "POST",   
      dataType: "html",              
      data: frm_nuevo_estudiante,
      cache: false,
                contentType: false,
                processData: false,
                    
     }).done(function (response){     
        
        alertify.alert(response);
                            var fila = (JSON.parse(response));
                            if (fila.Estado === 'Exitoso') {
                                alertify.alert('Notificación', 'El Estudiante con nombre ' + fila.nombre + ' ' + fila.apellidos + ', Run: ' + fila.run_estudi + ' se ha guardado correctamente');
                                $('#tabla_curso_estalecimiento').DataTable().ajax.reload();
                            }
                            if (fila.Estado === 'Existe') {
                                alertify.alert('Notificación', 'El Estudiante con nombre ' + fila.nombre + ' ' + fila.apellidos + ', Run: ' + fila.run_estudi + ' existe');
                                $('#tabla_curso_estalecimiento').DataTable().ajax.reload();
                            }

      
      
     })
    
   })
 }

 var eliminar_estudiante = function(tbody, tabla){
    $(tbody).on('click', "button.eliminar_estudiante", function(){
            dato = tabla.row( $(this).parents("tr")).data();
            var idestudiante = dato.identificador;                     
            alertify.confirm('Notificación', '¿Esta seguro que desea eliminar el estudiante seleccionado?', function(){
                console.log(idestudiante);
                var eliminar_estudiante = new FormData();    
                eliminar_estudiante.append("eliminar","borra"); 
                eliminar_estudiante.append("id",idestudiante); 
                $.ajax({
                    url: "conf/delete.php",
                    method: "POST",   
                    dataType: "html",              
                    data: eliminar_estudiante,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function (response){
                    console.log(response);
                    var fila = (JSON.parse(response));
                    if(fila.estado === "1"){
                        alertify.success("<div class='text-white text-center'>Estudiante eliminado </div>");
                        $('#tabla_curso_estalecimiento').DataTable().ajax.reload();
                    }else if(fila.estado === "0"){
                        alertify.error("<div class='text-white text-center'>Estudiante no eliminado</div>");
                        $('#tabla_curso_estalecimiento').DataTable().ajax.reload();
                    }
                })
             }
                , function(){ 

                });


           
            
        })

 }


        </script>
        </body>

    </html>
    <?php
} else {
    header("location:reportes/login");
}
