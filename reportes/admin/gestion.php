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
                        <li class="active" id="activo_docente">
                            <a id="contendido_docentes">Lista de Cursos</a>
                        <li id="activo_curso">
                         <a id="contendido_cursos" >Carga Nuevo Curso</a>
                        </li>                        
                         <li>
                          <a href="../modulos.php"><i class="fa fa-arrow-left" aria-hidden="true"></i>Reportes establecimiento</a>
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
                            <li  class="active"><a class="curso_docentes" data-toggle="pill" ><span>Lista de Cursos</span></a></li>
                            
                          
                        </ul>
  </section>

    <section class="content">
   
       
      <div class="row">   
      
        <div class="col-xs-12">      

        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Lista de Cursos</h3>
              <span class="pull-right"><button class="nuevo_doc btn btn-primary" data-toggle="modal" data-target="#modal_nuevo_estudiante">Nuevo docente <i class="fa fa-plus" aria-hidden="true"></i></button></span>
            </div>
                 <!-- /.box-header -->
                 <div class="box-body">
                 <div id="curso_establecimiento_selec"></div>      
                
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
                            <li  class="active"><a class="curso_asociados" data-toggle="pill" ><span>Carga de nuevo curso</span></a></li>
                            
                          
                        </ul>
  </section>

    <section class="content">
   
       
      <div class="row">   
      
        <div class="col-xs-12">   
        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Carga de nuevo curso</h3>            
            </div>
            
                 <!-- /.box-header -->
                 <div class="box-body">

                  <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile"> 
            <form class="formulario" enctype="multipart/form-data" id="formulario_excel" method="post">      
              <div class="col-md-3">
                <label>Docente</label>
                <select name="id_profesor" id="id_profesor" class="form-control">
                      <?php  echo select_establecimiento($establecimiento);?>
                      </select>
              </div>
              <div class="col-md-3">
                <label>Curso</label>
              <select name="id_curso" id="id_curso" class="form-control">    
                      <option value="" disabled selected>Seleccione un docente</option>                 
              </select>
              </div>
              <div class="col-md-3">
                <label>Seleccionar archivo xlsx</label>
              <input type="file" name="file" id="file" class="form-control" required>
              </div> 
              <div class="col-md-3">
                 <input type="submit" id="boton_subir_excel" value="Cargar" name="Cargar" class="btn btn-danger pb">
              </div>    
            </form>
            <div class="col-xs-12 mt-4">
            <div class="text-center" id="dowload">
                     
            </div>
            </div>
             <div class="mt-4">
                <div class="col-md-4">
                <h4>Paso 1</h4>
                                <a class="archivos_descargar" href="../../descarga_archivo.php">-Descargar Archivo Excel de Ejemplo</a><br>
                                <a class="archivos_descargar" href="../../descarga_archivo_llenar.php">-Descargar Archivo Excel recepción de datos</a>
                </div>
                <div class="col-md-4">
                <h4>Paso 2</h4>
                                <p>-Completar el archivo Excel recepción de datos.</p>
                                <p>-</p>
                  
                </div>
                <div class="col-md-4">
                <h4>Paso 3</h4>
                                <p>- Subimos el archivo completado con el boton 'Seleccionar archivo'</p>
                                <p>-Presionamos el Boton 'Cargar'</p>                  
                </div>

              </div>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        
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


<?php require "js.php"; ?>


<script>

$(document).ready(function(){
  var establecimiento = <?php echo $establecimiento;?>

  $("#curso_establecimiento_selec").load("../../lista_curso_establecimientos_admin.php");
  llena_profesores_admin();
  validar_extension_admin();
  carga_excel_admin();
    
})

    $("#contendido_docentes").click(function(){
        document.getElementById('secci_cursos').hidden = true; 
        document.getElementById('secci_docentes').hidden = false;   
         $("#activo_docente").addClass("active");
         $("#activo_curso").removeClass("active");   
        
        
        
    })

    $("#contendido_cursos").click(function(){    

        document.getElementById('secci_docentes').hidden = true;
        document.getElementById('secci_cursos').hidden = false;
        $("#activo_curso").addClass("active");   
        $("#activo_docente").removeClass("active");
       

    })

   
   
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


</script>
</body>
</html>
