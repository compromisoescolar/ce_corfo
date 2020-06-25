<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
require 'conexion_db.php';
require 'funciones_db.php';

function limpia_nombre($cadena) {
    $cadena = str_replace(' ', '', $cadena);
    return $cadena;
}

function puntos($s)
{
$s= str_replace('"','', $s);
$s= str_replace(':','', $s); 
$s= str_replace('.','', $s); 
$s= str_replace(',','', $s); 
$s= str_replace(';','', $s); 
$s= str_replace('-','', $s); 
return $s;
}

//CREAMOS LOS CRUD POR ID
if ($_POST['nuevo'] == 'new') {
    $nombres = $_POST['new_name'];
    $apellidos = $_POST['new_apellidos'];
    $run = $_POST['new_run'];
    $run_estudi =substr($run, 0,-1);
    $run_token = "A".$run;
    $ciudad = $_POST['ciudad_estudiante'];
    $fecha_nacimi = $_POST['fecha_naci_es_new'];
    
    $id_establecimiento = $_POST['idestablecimiento'];
    $id_curso = $_POST['idcurso'];
    $id_docente = $_POST['iddocente'];
    $id_pais =  $_SESSION["pais_establecimiento"];

    $anio = date("Y");

    //nivel del estudiante 
    $con = connectDB_demos();
    $query = $con->prepare("SELECT ce_fk_nivel FROM ce_participantes WHERE ce_establecimiento_id_ce_establecimiento=:id_estable AND ce_docente_id_ce_docente =:id_docen AND ce_curso_id_ce_curso=:id_curso");
    $query->execute([
        "id_estable"=>$id_establecimiento,
        "id_docen"=>$id_docente,
        "id_curso"=>$id_curso
    ]);
    $con = NULL;
    $resultado = $query->fetch(PDO::FETCH_ASSOC);

    $id_nivel = $resultado["ce_fk_nivel"];

    $valida =  compro_existen_estudiante($run);

    if($valida == 1){
        $datos = array('Estado' => 'Existe', 'nombre' => $nombres, 'apellidos' => $apellidos,'run_estudi' => $run); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  

    }else if($valida == 0){
        $resultado = nuevo_estudiante_uni($nombres,$apellidos,$run,$fecha_nacimi,$run_token,$ciudad,$id_establecimiento,$id_docente,$id_curso,$id_nivel,$id_pais, $anio);
        $datos = array('Estado' => 'Exitoso', 'nombre' => $nombres, 'apellidos' => $apellidos,'run_estudi' => $run); 
      header('Content-Type: application/json');
      echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
   

   

    

}

if ($_POST['eliminar'] == 'borra') {
    $id_estudiante = $_POST['id'];    
   $resultado =  eliminar_estudiante($id_estudiante);

    if($resultado == TRUE){
        $datos = array('estado' => '1'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  

    }else if($valresultadoida == FALSE){
     
        $datos = array('estado' => '0'); 
      header('Content-Type: application/json');
      echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
    }

if ($_POST['update'] == 'update') {

$id_estudiante = $_POST["idestudiante_update"];
$nom_estu = $_POST["nombre_es"];
$apelli_estu = $_POST["apelli_es"];
$run_estu =  puntos($_POST["run_es_proble"]);
$fecha_naci_estu = $_POST["fecha_naci_es"];
$ciu_estu = $_POST["ciu_est"];
$token_estu = $_POST["token_es"];

$actualiza_estudiante = actualiza_estudiantes($id_estudiante,$nom_estu, $apelli_estu, $run_estu, $fecha_naci_estu, $ciu_estu,$token_estu);    
 
if($actualiza_estudiante == TRUE){
    $datos = array('estado' => '1'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);

}else if($actualiza_estudiante == FALSE){
    $datos = array('estado' => '0'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);  
}

}

if($_POST['eliminar'] == 'eliminar_usuario'){ 
    $id_usuario = $_POST["id_usuario"];
    $query_eliminar_usuario = eliminar_usuario($id_usuario);
    if($query_eliminar_usuario == TRUE){
        $datos = array('Estado' => 'Exitoso'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  

    }
    elseif($query_eliminar_usuario == FALSE){
        $datos = array('Estado' => 'No_Eliminado_usuario'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  

    }    
 
}

if($_POST['usuario_nuevo_estable'] == 'Nuevo Usuario'){
    $rbd_usuario = $_POST["rbd_usuario"];
    $contrase_usuario = $_POST["contrase_usuario"];
    $estado_usuario = $_POST["select_estado"];

    $usu_existe = busca_usuario($rbd_usuario);
    if($usu_existe >= 1){
        $datos = array('Estado' => 'existe'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }elseif($usu_existe <= 0){
        $pass = 'E'.$contrase_usuario;
       $ingresa_usuario = nuevo_usuario($rbd_usuario,$pass,$estado_usuario);
       if( $ingresa_usuario >=1){
        $datos = array('Estado' => 'Guardado_Usuario'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);

       }  

        if( $ingresa_usuario <=0){
    $datos = array('Estado' => 'error'); 
     header('Content-Type: application/json');
     echo json_encode($datos, JSON_FORCE_OBJECT);
      }
   }
    
}

if($_POST["usuario_update"] == "Actualizar Usuario"){
$id_user_update = $_POST["usuario_update_id"];
$usuario_nombre = $_POST["rbd_usuario_update"];
$usuario_contrasena = $_POST["contrase_usuario_update"];
$usuario_estado = $_POST["select_estado_update"]; 
$quita_e = str_replace("E","",$usuario_contrasena);
$usua_contra = "E".$quita_e;

$actualizacion_usuario = actualizar_usuario($id_user_update,$usuario_nombre,$usua_contra,$usuario_estado);
if($actualizacion_usuario >= 1){
    $datos = array('Estado' => 'Actualizado_Usuario'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);

}elseif($actualizacion_usuario <= 0){
    $datos = array('Estado' => 'Actualizado_Usuario_error'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);

}
}