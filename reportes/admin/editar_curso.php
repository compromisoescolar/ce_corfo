<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("location: ../login_administrador.php");
    exit();
}


require '../../conf/conexion_db.php';
require '../../conf/funciones_db.php';

$establecimiento = $_GET['establecimiento'];
$docente = $_GET['docente'];
$curso = $_GET['curso'];  


$establecimiento =  $_SESSION["identificador_estable"];
$nom_estable = nombre_establecimiento($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reportes Compromiso Escolar</title>
    <!-- Tell the browser to be responsive to screen width -->
    <?php require "css.php"; ?>

</head>
<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper" id="subir_head">

<header class="main-header">
       
        <a class="logo">          
            <img src="../dist/img/logo.png" alt="" srcset="">
        </a>
       
        <nav class="navbar navbar-static-top">
           
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" aria-expanded="false" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="titulo">Panel de gestión de establecimiento</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                   
                    <li class="dropdown user user-menu">
                        <a href="../../salir.php">
                            <?php echo $nom_estable;?>|<i class="fa fa-sign-out" aria-hidden="true"></i>Salir
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
   
    <aside class="main-sidebar">
        
        <section class="sidebar">
          
            <div class="user-panel">
                <div class="pull-left image">
                    <br>
                </div>
                <div class="pull-left info">
                    <p></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
           
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Menú De Navegación</li>
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-th"></i> <span>Establecimiento </span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>

                    <ul class="treeview-menu">
                        <li class="active" id="activo_estudiantes">
                            <a id="contendido_docentes">Lista de estudiante</a>
                                               
                         <li id="activo_volver">
                          <a href="../modulos.php"><i class="fa fa-arrow-left" aria-hidden="true"></i>Volver</a>
                         </li>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

  
  <div class="content-wrapper">



<!--  Sección Sostenedores -->
   <div id="secci_sostenedores">
    <section class="content-header">
                        <h1 class="text-center">
                            Establecimiento <?php //echo $establecimiento['ce_establecimiento_nombre']; ?></span>
                        </h1>
                        <ul class="nav nav-pills">                         
                            <li  class="active"><a class="curso_sostendores" data-toggle="pill" ><span>Estudiantes</span></a></li>
                            
                          
                        </ul>
  </section>

    <section class="content">
   
       
      <div class="row">   
      
        <div class="col-md-12">     
                   
        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Lista de estudiantes</h3>
              <span class="pull-right"><button class="btn btn-primary" data-toggle="modal" data-target="#modal_nuevo_estudiante">Nuevo estudiante <i class="fa fa-plus" aria-hidden="true"></i></button></span>
            </div>
                 <!-- /.box-header -->
                 <div class="box-body">
                 
            <table id="tabla_estudiantes" class="table table-bordered" width="100%">
            <thead>
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
               
                    
            </tr>
          </thead>
        </table>
     
            </div>
            <!-- /.box-body -->       
          </div>
     
          <!-- /.box -->   

        </div>
      
      </div>
    
    </section>
    </div>


  </div>
  

  <footer class="main-footer">
    <div class="pull-right hidden-xs">       
        <a><i class="fa fa-arrow-circle-up fa-3x hvr-grow" aria-hidden="true" onclick="subir_a_cabezera();"></i></a>
    </div>
   <br><br>
</footer>


  <div class="control-sidebar-bg"></div>
</div>

<!-- Nuevo Estudiante -->
<div class="modal fade" id="modal_nuevo_estudiante" tabindex="-1" role="dialog" aria-labelledby="modal_nuevo_estudiante" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Estudiante</h4>
      </div>
      <div class="modal-body">    
          <div class="row"> 
          <form id="guardar_docente">       
        <div class="col-md-3">        
            <label>Nombres Estudiante:</label>
            <input name="nombres_docente_nuevo" type="text" id="nombres_docente_nuevo" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Apellidos Estudiante:</label>
            <input name="apellidos_docente_nuevo" type="text" id="apellidos_docente_nuevo" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Run Estudiante:</label>
            <input name="run_docente_nuevo" type="number" id="run_docente_nuevo" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Fecha Nacimiento:</label>
            <input name="email_docente_nuevo" type="text" id="email_docente_nuevo" class="fecha_estudiante form-control"/>
        </div>

        <div class="col-md-3">
        <label>Usuario Docente:</label>
            <input name="usuario_docente_nuevo" type="text" id="usuario_docente_nuevo"  class="form-control" data-toggle="tooltip" data-placement="bottom" id="right" title="" data-original-title="El usuario es generado a patir del RUN."  readonly/>
        </div>
        <div class="col-md-3">
        <label>Contraseña Docente:</label>
            <input name="pass_docente_nuevo" type="text" id="pass_docente_nuevo" class="form-control" readonly/>
        </div>       
          </div>        
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Nuevo Docente fin  -->


<?php require "js.php"; ?>


<script>

$(document).ready(function(){
  var establecimiento = <?php echo $establecimiento;?>;
  var docente =  <?php echo $docente;?>;
  var curso = <?php echo $curso;?>;

  listar_curso_establecimiento(establecimiento,docente,curso);
  $(document).ready(function () {
      $('.fecha_estudiante').datepicker({
          format: 'yyyy-mm-dd',
          language: 'es',
          todayBtn: true,
          todayHighlight: true,
          autoclose: true
      });
  });
})
var listar_curso_establecimiento = function(establecimiento,docente,curso){
        var tabla_estudiantes =  $("#tabla_estudiantes").DataTable({
         
        "language":{
            "url": "../../assets/librerias/datatable/spanish.json"
         },
        
        "ajax":{
            "method":"GET",
            "url":"datos_curso.php",
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
            {"defaultContent":"<button type='button' class='editar_estudiante btn btn-danger'>Editar <i class='fa fa-pencil-square-o'></i></button>"}
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
}

</script>
</body>
</html>
