<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("location: ../login_administrador.php");
    exit();
}


require '../../conf/conexion_db.php';
require '../../conf/funciones_db.php';


$establecimiento =  $_SESSION["identificador_estable"];
$nom_estable = nombre_establecimiento($_SESSION['user']);


$_SESSION["token"] = md5(uniqid(mt_rand(), true));

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
                <span class="titulo">Panel de administración de establecimiento</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                   
                    <li class="dropdown user user-menu">
                        <a href="../../salir.php?csrf=<?php echo $_SESSION["token"]; ?>">
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
                        <li class="active" id="activo_docente">
                            <a id="contendido_docentes">Docentes</a>
                        <li id="activo_curso">
                         <a id="contendido_cursos" >Cursos</a>
                        </li>
                        <li id="activo_sostenedores">
                         <a id="contendido_sostenedores" >Sostenedores</a>
                        </li>
                         <li>
                          <a href="../modulos.php"><i class="fa fa-arrow-left" aria-hidden="true"></i>Volver</a>
                         </li>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

  
  <div class="content-wrapper">

  <div id="secci_docentes">
  <section class="content-header">
                        <h1 class="text-center">
                            Establecimiento <?php echo $nom_estable;?></span>
                        </h1>
                        <ul class="nav nav-pills">                         
                            <li  class="active"><a class="curso_docentes" data-toggle="pill" ><span>Docentes</span></a></li>
                            
                          
                        </ul>
  </section>

    <section class="content">
   
       
      <div class="row">   
      
        <div class="col-xs-12">      

        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Lista de Docentes</h3>
              <span class="pull-right"><button class="nuevo_doc btn btn-primary" data-toggle="modal" data-target="#modal_nuevo_estudiante">Nuevo docente <i class="fa fa-plus" aria-hidden="true"></i></button></span>
            </div>
                 <!-- /.box-header -->
                 <div class="box-body">
                 <div class="table-responsive"><table id="tabla_docente" class="table table-bordered" width="100%">
       <thead >
       <tr><th>Codigo</th>
       <th>Nombre Docente</th>
       <th>Apellido Docente</th>
       <th>Run Docente</th>
       <th>Email Docente</th>
       <th>Curso</th>
       <th>Nivel</th>
       <th>id establecimiento</th>
       <th> id usuario</th>  
       <th>Usuario</th>     
       <th>contraseña</th>   
       
       <th></th>
       </tr></thead> </table>             
            
     
            </div>
            <!-- /.box-body -->       
          </div>
     
          <!-- /.box -->           
  


        </div>
      
      </div>
    
    </section>
    </div>


<!--  Sección Cursos -->
    <div  id="secci_cursos" hidden>
    <section class="content-header" >
                        <h1 class="text-center">
                            Establecimiento <?php //echo $establecimiento['ce_establecimiento_nombre']; ?></span>
                        </h1>
                        <ul class="nav nav-pills">                         
                            <li  class="active"><a class="curso_asociados" data-toggle="pill" ><span>Cursos</span></a></li>
                            
                          
                        </ul>
  </section>

    <section class="content">
   
       
      <div class="row">   
      
        <div class="col-xs-12">   
        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Lista de cursos </h3>
              <span class="pull-right"><button class="btn btn-primary" data-toggle="modal" data-target="#modal_nuevo_curso">Nuevo curso <i class="fa fa-plus" aria-hidden="true"></i></button></span>
            </div>
            
                 <!-- /.box-header -->
                 <div class="box-body">
        <div class="table-responsive">
            <table id="tabla_curso" class="table table-bordered" width="100%">
            <thead>       
           <tr>
               <th>Codigo</th>
               <th>curso</th>    
               <th>id_nivel</th> 
               <th>id_docente</th>   
               <th>Nombre Curso</th>
               <th>Nivel Curso</th>         
               <th>Nombre Docente</th>                
               <th></th>                
              
           </tr>
        </thead>
        </table>
        </div>
        </div>
        </div>
        </div>
      
      </div>
    
    </section>
    </div>


<!--  Sección Sostenedores -->
   <div id="secci_sostenedores" hidden>
    <section class="content-header">
                        <h1 class="text-center">
                            Establecimiento <?php //echo $establecimiento['ce_establecimiento_nombre']; ?></span>
                        </h1>
                        <ul class="nav nav-pills">                         
                            <li  class="active"><a class="curso_sostendores" data-toggle="pill" ><span>Sostenedores</span></a></li>
                            
                          
                        </ul>
  </section>

    <section class="content">
   
       
      <div class="row">   
      
        <div class="col-md-12">     
                   
        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Lista de Sostenedores</h3>
              <span class="pull-right"><button class="btn btn-primary" data-toggle="modal" data-target="#modal_nuevo_sostenedor">Nuevo sostenedor <i class="fa fa-plus" aria-hidden="true"></i></button></span>
            </div>
                 <!-- /.box-header -->
                 <div class="box-body">
                 
            <table id="tabla_sostenedor" class="table table-bordered" width="100%">
            <thead>
             <tr>
                <th>Codigo</th>
                <th>Nombre Sostenedor</th>
                <th>Apellido Sostenedor</th>
                <th>Run Sostenedor</th>
                <th>Fecha Registro</th>
                <th>Establecimiento</th>
                <th>id sostenedor</th>
                <th>usuario sostenedor</th>
                <th>contraseña sostenedor</th>
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

<!-- Nuevo Docente -->
<div class="modal fade" id="modal_nuevo_estudiante" tabindex="-1" role="dialog" aria-labelledby="modal_nuevo_estudiante" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Docente</h4>
      </div>
      <div class="modal-body">    
          <div class="row"> 
          <form id="guardar_docente">       
        <div class="col-md-3">        
            <label>Nombres Docente:</label>
            <input name="nombres_docente_nuevo" type="text" id="nombres_docente_nuevo" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Apellidos Docente:</label>
            <input name="apellidos_docente_nuevo" type="text" id="apellidos_docente_nuevo" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Run Docente:</label>
            <input name="run_docente_nuevo" type="number" id="run_docente_nuevo" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Email Docente:</label>
            <input name="email_docente_nuevo" type="text" id="email_docente_nuevo" class="form-control"/>
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

<!-- Actualizar Docente -->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Editar Docente</h4>
      </div>
      <div class="modal-body">    
          <div class="row"> 
          <form id="actualizar_docente">         
        <div class="col-md-3">        
            <label>Nombres Docente:</label>
            <input name="nombres_docente_actualizar" type="text" id="nombres_docente_actualizar" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Apellidos Docente:</label>
            <input name="apellidos_docente_actualizar" type="text" id="apellidos_docente_actualizar" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Run Docente:</label>
            <input name="run_docente_actualizar" type="text" id="run_docente_actualizar" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Email Docente:</label>
            <input name="email_docente_actualizar" type="text" id="email_docente_actualizar" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Usuario Docente:</label>
            <input name="usuario_docente_actualizar"  type="text" id="usuario_docente_actualizar" class="form-control" data-toggle="tooltip" data-placement="bottom" id="right" title="" data-original-title="El usuario es generado a patir del RUN." name="usuario_docente_nuevo"  readonly/>
        </div>
        <div class="col-md-3">
        <label>Contraseña Docente:</label>
            <input name="pass_docente_actualizar" type="text" id="pass_docente_actualizar" class="form-control" readonly/>
        </div>

        <div hidden>
        <input name="id_docente" type="text" id="id_docente" class="form-control"/> 
        <input name="id_docente_user" type="text" id="id_docente_user" class="form-control"/> 
        </div>

          </div>        
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Actualizar Docente fin  -->

<!-- Nuevo curso -->
<div class="modal fade" id="modal_nuevo_curso" tabindex="-1" role="dialog" aria-labelledby="modal_nuevo_curso" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Curso</h4>
      </div>
      <div class="modal-body">    
          <div class="row">   
          <form id="nuevo_curso">       
        <div class="col-md-3">        
            <label>Nombre Curso:</label>
            <input name="nombre_curso_nuevo" type="text" id="nombre_curso_nuevo" class="form-control"/>
        </div>
        <div class="col-md-3">
        <label>Docente:</label>        
        <?php lista_docente_establecimiento($establecimiento);?>
        </div>
        <div class="col-md-3">
        <label>Nivel</label>
        <?php niveles_compromiso_escolar();?>        
       
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

<!-- Nuevo curso fin  -->

<!-- Actualizar curso -->
<div class="modal fade" id="modal_editar_curso" tabindex="-1" role="dialog" aria-labelledby="modal_editar_curso" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Editar Docente</h4>
      </div>
      <div class="modal-body">    
          <div class="row">    
          <form id="actualizar_curso">      
        <div class="col-md-3">
        <label>Docente Curso:</label>
        <?php lista_docente_establecimiento_update($establecimiento);?>       
        </div>

        <div class="col-md-3">
        <label>Nombres Curso:</label>
            <input name="nombres_curso_editar" type="text" id="nombres_curso_editar" class="form-control"/>
            
        </div>

        <div class="col-md-3">
        <label>Nivel Curso:</label>
        <?php niveles_compromiso_escolar_update($establecimiento);?>        

        </div> 
        
       <div hidden>
        <input name="id_curso_editar" type="text" id="id_curso_editar" class="form-control"/> 
        <input name="id_curso_docente_editar" type="text" id="id_curso_docente_editar" class="form-control"/> 
        </div>

          </div>        
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Actualizar curso fin  -->



<!-- Nuevo sostenedor -->
<div class="modal fade" id="modal_nuevo_sostenedor" tabindex="-1" role="dialog" aria-labelledby="modal_nuevo_sostenedor" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Nuevo Sostenedor</h4>
      </div>
      <div class="modal-body">    
        <div class="row">  
          <form id="nuevo_sostenedor">        
        <div class="col-md-3">
        <label>Nombre sostenedor:</label>
         <input name="nombre_nuevo_sostenedor" type="text" id="nombre_nuevo_sostenedor" class="form-control"/>
        </div>
        <div class="col-md-3">
         <label>Apellidos sostenedor:</label>
         <input name="apellidos_nuevo_sostenedor" type="text" id="apellidos_nuevo_sostenedor" class="form-control"/>
        </div>
        <div class="col-md-3">
         <label>Run sostenedor</label>        
         <input name="run_nuevo_sostenedor" type="text" id="run_nuevo_sostenedor" class="form-control"/>
        </div>
        <div class="col-md-3">
         <label>Usuario sostenedor</label>        
         <input name="usuario_nuevo_sostenedor" type="text" id="usuario_nuevo_sostenedor" class="form-control" readonly/>
        </div>
        <div class="col-md-3">
        <label>Contraseña sostenedor</label>        
        <input name="pass_nuevo_sostenedor" type="text" id="pass_nuevo_sostenedor" class="form-control" readonly/>
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

<!-- Nuevo sostenedor fin  -->

<!-- Actualizar sostenedor -->
<div class="modal fade" id="modal_editar_sostenedor" tabindex="-1" role="dialog" aria-labelledby="modal_editar_sostenedor" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Editar Sostenedor</h4>
      </div>
      <div class="modal-body">    
          <div class="row">      
          <form>    
          <div class="col-md-3">        
            <label>Nombre sostenedor:</label>
            <input  name="nombre_actualizar_sostenedor" type="text" id="nombre_actualizar_sostenedor" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Apellidos sostenedor:</label>
        <input name="apellidos_actualizar_sostenedor" type="text" id="apellidos_actualizar_sostenedor" class="form-control"/>
        </div>

        <div class="col-md-3">
        <label>Run sostenedor</label>        
        <input name="run_actualizar_sostenedor" type="text" id="run_actualizar_sostenedor" class="form-control"/>
        </div>   

        <div class="col-md-3">
        <label>Usuario sostenedor</label>        
        <input name="usuario_actualizar_sostenedor" type="text" id="usuario_actualizar_sostenedor" class="form-control" readonly/>
        </div>

        <div class="col-md-3">
        <label>Contraseña sostenedor</label>        
        <input name="pass_actualizar_sostenedor" type="text" id="pass_actualizar_sostenedor" class="form-control" readonly/>
        </div>   

        
       <div hidden>
        <input type="text" id="id_actualizar_sostenedor" class="form-control"/> 
        <input type="text" id="id_usuario_sostenedor" class="form-control"/> 
        </div>

          </div>        
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Actualizar sostenedor fin  -->

<?php require "js.php"; ?>


<script>

$(document).ready(function(){
  var establecimiento = <?php echo $establecimiento;?>;
    listar_sostenedor(establecimiento);
    listar_docentes(establecimiento);
    listar_curso(establecimiento); 
    anteponer_letra_docente();
    guardar_docente();
    actualizar_docente();
    guardar_curso();
    actualizar_curso();
    anteponer_letra_sostenedor();
    guardar_sostenedor();
})

    $("#contendido_docentes").click(function(){
        document.getElementById('secci_cursos').hidden = true;      
        document.getElementById('secci_sostenedores').hidden = true;
        document.getElementById('secci_docentes').hidden = false;   
         $("#activo_docente").addClass("active");
         $("#activo_curso").removeClass("active");   
         $("#activo_sostenedores").removeClass("active"); 
        
        
    })

    $("#contendido_cursos").click(function(){      
        document.getElementById('secci_sostenedores').hidden = true;
        document.getElementById('secci_docentes').hidden = true;
        document.getElementById('secci_cursos').hidden = false;
        $("#activo_curso").addClass("active");   
        $("#activo_docente").removeClass("active");
        $("#activo_sostenedores").removeClass("active");   

    })

    $("#contendido_sostenedores").click(function(){         
        document.getElementById('secci_docentes').hidden = true;
        document.getElementById('secci_cursos').hidden = true;
        document.getElementById('secci_sostenedores').hidden = false;
        $("#activo_docente").removeClass("active");
         $("#activo_curso").removeClass("active");   
         $("#activo_sostenedores").addClass("active"); 
       
    })
    var listar_docentes = function(establecimiento){
        
        var tabla_docente =   $("#tabla_docente").DataTable({
            
        "language":{
            "url": "../../assets/librerias/datatable/spanish.json"
         },
     
        "ajax":{
            "method":"GET",
            "url":"listar.php",
            "data":{"id":establecimiento,"tipo":"docentes"}        
        },
        "columns":[
            {"data":"id_doc"},
            {"data":"nom_doc"},
            {"data":"apelli_doc"},
            {"data":"run_docente"},
            {"data":"email_doc"},
            {"data":"nom_curso"},
            {"data":"nivel_curso"},
            {"data":"id_estable"},
            {"data":"id_usuario_docente"},
            {"data":"nom_usu_docente"},
            {"data":"pass_usu_docente"},
            {"defaultContent":"<button type='button' class='editar btn btn-danger'>Editar <i class='fa fa-pencil-square-o'></i></button>"}
            
           
        ],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 7 ],
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
            }
            ]
        
    });

    obtener_datos_editar("#tabla_docente tbody", tabla_docente);
    }

    var listar_curso = function(establecimiento){
    var tabla_curso = $("#tabla_curso").DataTable({
    
        "language":{
            "url": "../../assets/librerias/datatable/spanish.json"
         },
        
        "ajax":{
            "method":"GET",
            "url":"listar.php",
            "data":{"id":establecimiento,"tipo":"cursos"}        
        },
        "columns":[
          
            {"data":"id_pivot"},
            {"data":"id_curso"},
            {"data":"id_nivel"},
            {"data":"id_docente"},
            {"data":"nombre_curso"},
            {"data":"nivel"},
            {"data":"nom_docente"},
            {"defaultContent":"<button type='button' class='editar_cursos btn btn-danger'>Editar <i class='fa fa-pencil-square-o'></i></button>"}
           
          
        ],
        "columnDefs": [
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 2 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 3 ],
                "visible": false,
                "searchable": false
            }

            
            ]
        
    })

    obtener_datos_editar_curso("#tabla_curso tbody",tabla_curso);
}

    var listar_sostenedor = function(establecimiento){
        var tabla_sostenedor =  $("#tabla_sostenedor").DataTable({
         
        "language":{
            "url": "../../assets/librerias/datatable/spanish.json"
         },
        
        "ajax":{
            "method":"GET",
            "url":"listar.php",
            "data":{"id":establecimiento,"tipo":"establecimiento"}        
        },
        "columns":[
            {"data":"codigo"},
            {"data":"nombre"},
            {"data":"apellido"},
            {"data":"run"},
            {"data":"fecha_registro"},
            {"data":"establecimiento"},
            {"data":"id_sostenedor_usu"},
            {"data":"usu_sostenedor"},
            {"data":"pass_sostenedor"},
            {"defaultContent":"<button type='button' class='editar_sostenedores btn btn-danger'>Editar <i class='fa fa-pencil-square-o'></i></button>"}
        ],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 7 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 8 ],
                "visible": false,
                "searchable": false
            }

            
            ]
        
    })

    obtener_datos_editar_sostenedor("#tabla_sostenedor tbody",tabla_sostenedor)

    
    }




    var obtener_datos_editar = function(tbody, tabla){
        $(tbody).on('click', "button.editar", function(){
            var dato = tabla.row( $(this).parents("tr")).data();
            var idocente = $("#id_docente").val(dato.id_doc);
            var nomdocente = $("#nombres_docente_actualizar").val(dato.nom_doc);
            var apellidocente = $("#apellidos_docente_actualizar").val(dato.apelli_doc);
            var run_docente = $("#run_docente_actualizar").val(dato.run_docente);
            var email_docente = $("#email_docente_actualizar").val(dato.email_doc);
            var usuario_docente_actualizar = $("#usuario_docente_actualizar").val(dato.nom_usu_docente);
            var pass_docente_actualizar = $("#pass_docente_actualizar").val(dato.pass_usu_docente);
           // var id_docente_user = $("#id_docente_user").val(dato.id_usuario_docente);
            $("#largeModal").modal();
            console.log(dato);
            
        })
    }

    var obtener_datos_editar_curso = function(tbody, tabla){
        $(tbody).on('click', "button.editar_cursos", function(){
            var dato = tabla.row( $(this).parents("tr")).data();
   
           var id_docente_editar = $("#id_curso_docente_update").val(dato.id_docente);
            var id_nivel_editar = $("#niveles_ce_update").val(dato.id_nivel);
            var nom_curso_editar = $("#nombres_curso_editar").val(dato.nombre_curso);
            var id_curso = $("#id_curso_editar").val(dato.id_curso);
            var id_docente = $("#id_curso_docente_editar").val(dato.id_docente);
            $("#modal_editar_curso").modal();
            console.log(dato);
            
        })
    }

    var obtener_datos_editar_sostenedor = function(tbody, tabla){
        $(tbody).on('click', "button.editar_sostenedores", function(){
            var dato = tabla.row( $(this).parents("tr")).data();   
           var id_sostenedor_editar = $("#id_actualizar_sostenedor").val(dato.codigo);
            var nombre_sostenedor_editar = $("#nombre_actualizar_sostenedor").val(dato.nombre);
            var apellidos_sostenedor_editar = $("#apellidos_actualizar_sostenedor").val(dato.apellido);
            var run_sostenedor_editar = $("#run_actualizar_sostenedor").val(dato.run);
            var id_usuario_sostenedor = $("#id_usuario_sostenedor").val(dato.id_sostenedor_usu);
            var usuario_actualizar_sostenedor = $("#usuario_actualizar_sostenedor").val(dato.usu_sostenedor);
            var pass_actualizar_sostenedor = $("#pass_actualizar_sostenedor").val(dato.pass_sostenedor);
            
            $("#modal_editar_sostenedor").modal();
            console.log(dato);
            
        })
    }


    //reload de una tabla con datatable
   var reload_tablas = function(){
    $('#tabla_docente').DataTable().ajax.reload();
    $('#tabla_curso').DataTable().ajax.reload();
    $('#tabla_sostenedor').DataTable().ajax.reload();
   }
     
     //   $('#tabla_docente').DataTable().ajax.reload();
     
 var anteponer_letra_docente = function(){ 
        $( "#run_docente_nuevo" ).keyup(function() {    
                var value = $( this ).val();             
              $( "#pass_docente_nuevo" ).val("P"+value);                   
                 
              $( "#usuario_docente_nuevo").val(value);
                                
          }); 
          $( "#run_docente_actualizar" ).keyup(function() {    
                var value = $( this ).val();             
              $( "#pass_docente_actualizar" ).val("P"+value);                   
                 
              $( "#usuario_docente_actualizar").val(value);
                                
          }); 
 }

 var anteponer_letra_sostenedor = function(){ 
        $("#run_nuevo_sostenedor" ).keyup(function() {    
                var value = $(this).val();             
              $("#pass_nuevo_sostenedor").val("P"+value);                   
                 
              $("#usuario_nuevo_sostenedor").val(value);
                                
          }); 

          $("#run_actualizar_sostenedor" ).keyup(function(){    
                var value = $(this).val();             
              $( "#pass_actualizar_sostenedor" ).val("P"+value);                   
                 
              $( "#usuario_actualizar_sostenedor").val(value);
                                
          }); 
 }
 var guardar_docente = function(){
   $("#guardar_docente").on("submit", function(e){
     e.preventDefault();   
     var frm_docente = new FormData(document.getElementById("guardar_docente"));     
     frm_docente.append("tipo","docente_nuevo");
     $.ajax({
      url: "prueba.php",
      method: "POST",   
      dataType: "html",              
      data: frm_docente,
      cache: false,
                contentType: false,
                processData: false,
                    
     }).done(function (info){      
       var json_parse = JSON.parse(info);
       if(json_parse.estado === '1'){
       
         alertify.success("docente registrado");
         reload_tablas();
         limpiar_input();
       }
       else if(json_parse.estado === '0'){
       
        alertify.error("El o la docente ya esta registrado");       
        reload_tablas();
        }

      
      
     })
    
   })
 }

 var actualizar_docente = function(){
   $("#actualizar_docente").on("submit", function(e){
     e.preventDefault();   
     var frm_docente = new FormData(document.getElementById("actualizar_docente"));     
     frm_docente.append("tipo","docente_actualizar");
     $.ajax({
      url: "prueba.php",
      method: "POST",   
      dataType: "html",              
      data: frm_docente,
      cache: false,
                contentType: false,
                processData: false,
                    
     }).done(function (info){      
       console.log(info)
       var json_parse = JSON.parse(info);
       if(json_parse.estado === '1'){
       
         alertify.success("docente actualizado");
         reload_tablas();
       }
       else if(json_parse.estado === '0'){
       
        alertify.error("docente no actualizado");       
        reload_tablas();
        }

      
      
     })
    
   })
 }

 var guardar_curso = function(){
   $("#nuevo_curso").on("submit", function(e){
     e.preventDefault();   
     var frm_curso = new FormData(document.getElementById("nuevo_curso"));     
     frm_curso.append("tipo","nuevo_curso");
     $.ajax({
      url: "prueba.php",
      method: "POST",   
      dataType: "html",              
      data: frm_curso,
      cache: false,
                contentType: false,
                processData: false,
                    
     }).done(function (info){ 
       console.log(info);     
       var json_parse = JSON.parse(info);
       if(json_parse.estado === '1'){       
         alertify.success("Curso registrado");
         reload_tablas();
         limpiar_input();
       }
       else if(json_parse.estado === '0'){       
        alertify.error("registro de curso no realizado");       
        reload_tablas();
        }

        else if(json_parse.estado === '2'){       
       alertify.error("El o la docente ya registra un curso asociado");       
       reload_tablas();
       }

      
     })
    
   })
 }

 var actualizar_curso = function(){
   $("#actualizar_curso").on("submit", function(e){
     e.preventDefault();   
     var frm_curso = new FormData(document.getElementById("actualizar_curso"));     
     frm_curso.append("tipo","curso_actualizar");
     $.ajax({
      url: "prueba.php",
      method: "POST",   
      dataType: "html",              
      data: frm_curso,
      cache: false,
                contentType: false,
                processData: false,
                    
     }).done(function (info){ 
       console.log(info);     
       var json_parse = JSON.parse(info);
       if(json_parse.estado === '1'){       
         alertify.success("Curso actualizado");
         reload_tablas();
         limpiar_input();
       }
       else if(json_parse.estado === '0'){       
        alertify.error("Curso no actualizado");       
        reload_tablas();
        }

     })
    
   })
 }

 var guardar_sostenedor = function(){
   $("#nuevo_sostenedor").on("submit", function(e){
     e.preventDefault();   
     var frm_sostenedor = new FormData(document.getElementById("nuevo_sostenedor"));     
     frm_sostenedor.append("tipo","nuevo_sostenedor");
     $.ajax({
      url: "prueba.php",
      method: "POST",   
      dataType: "html",              
      data: frm_sostenedor,
      cache: false,
      contentType: false,
      processData: false,
                    
     }).done(function (info){ 
       console.log(info);     
       var json_parse = JSON.parse(info);
       if(json_parse.estado === '1'){       
         alertify.success("Sostenedor registrado");
         reload_tablas();
         limpiar_input();
       }
       else if(json_parse.estado === '0'){       
        alertify.error("registro de sostenedor no realizado");       
        reload_tablas();
        }

        else if(json_parse.estado === '3'){       
       alertify.error("El o la sostedor/ra ya se encuestra registrado");         
       reload_tablas();
       }

      
     })
    
   })
 }

 var limpiar_input = function(){
  document.getElementById("guardar_docente").reset();
  document.getElementById("nuevo_sostenedor").reset();
 }

</script>
</body>
</html>
