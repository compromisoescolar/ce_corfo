<?php
session_start();

$establecimiento =  $_SESSION["identificador_estable"];

require_once '../../conf/conexion_db.php';
require_once '../../conf/funciones_db.php';

$tipo = $_POST["tipo"];

if($tipo == "docente_nuevo"){

$nom_docente = filter_var($_POST["nombres_docente_nuevo"], FILTER_SANITIZE_STRING);

$apelli_docente = filter_var($_POST["apellidos_docente_nuevo"], FILTER_SANITIZE_STRING);

$run_docente = filter_var($_POST["run_docente_nuevo"], FILTER_SANITIZE_STRING);

$mail_docente = filter_var($_POST["email_docente_nuevo"], FILTER_SANITIZE_EMAIL);

$id_establecimiento =  $establecimiento;

$user_docente = filter_var($_POST["usuario_docente_nuevo"], FILTER_SANITIZE_STRING);

$pass_docente = filter_var($_POST["pass_docente_nuevo"], FILTER_SANITIZE_STRING);


$docente =  nuevo_docente($nom_docente, $apelli_docente, $run_docente, $mail_docente, $id_establecimiento,$user_docente,$pass_docente);

if($docente == "no_existe"){
    $datos = array('estado' => '1'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);

}else if($docente == "existe"){
    $datos = array('estado' => '0'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);  
}


}

else if($tipo == "docente_actualizar"){

    $id_docente =  filter_var($_POST["id_docente"], FILTER_SANITIZE_NUMBER_INT);

    $nom_docente = filter_var($_POST["nombres_docente_actualizar"], FILTER_SANITIZE_STRING);

    $apelli_docente = filter_var($_POST["apellidos_docente_actualizar"], FILTER_SANITIZE_STRING);

    $run_docente = filter_var($_POST["run_docente_actualizar"], FILTER_SANITIZE_STRING);

    $mail_docente = filter_var($_POST["email_docente_actualizar"], FILTER_SANITIZE_EMAIL);

    $id_establecimiento = $establecimiento;

    $id_docente_user =  filter_var($_POST["id_docente_user"], FILTER_SANITIZE_NUMBER_INT);

    $nom_docente_user = filter_var($_POST["usuario_docente_actualizar"], FILTER_SANITIZE_STRING);

    $pass_docente_user = filter_var($_POST["pass_docente_actualizar"], FILTER_SANITIZE_STRING);

   $docente =  actualizar_docente($id_docente,$nom_docente, $apelli_docente, $run_docente, $mail_docente, $id_establecimiento,$id_docente_user,$nom_docente_user,$pass_docente_user);

    if($docente == TRUE){
        $datos = array('estado' => '1'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);

    }else if($docente == FALSE){
        $datos = array('estado' => '0'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
}

else if( $tipo == "nuevo_curso"){
   
 
    $nom_curso = filter_var($_POST["nombre_curso_nuevo"], FILTER_SANITIZE_STRING);

    $nivel_curso = filter_var($_POST["niveles_ce"], FILTER_SANITIZE_NUMBER_INT); 
  
    $id_docente = filter_var($_POST["id_curso_docente"], FILTER_SANITIZE_NUMBER_INT);

    $id_establecimiento = $establecimiento;

       

    $valida_docente = estable_curso_nivel($id_docente);

    if($valida_docente >=1){
       $datos = array('estado'=>'2'); 
       header('Content-Type: application/json');
       echo json_encode($datos, JSON_FORCE_OBJECT);

    }else if($valida_docente <= 0){

       $curso =  registro_nuevo_curso($nom_curso,$id_establecimiento,$id_docente,$nivel_curso);    
   

       if($curso == TRUE){
           $datos = array('estado'=>'1'); 
           header('Content-Type: application/json');
           echo json_encode($datos, JSON_FORCE_OBJECT);
   
       }
       else if($curso == FALSE){
           $datos = array('estado' => '0'); 
           header('Content-Type: application/json');
           echo json_encode($datos, JSON_FORCE_OBJECT);  
       }
    }
  
}

else if( $tipo == "curso_actualizar"){
    
    $id_curso = filter_var($_POST["id_curso_editar"], FILTER_SANITIZE_NUMBER_INT); 

     $nom_curso = filter_var($_POST["nombres_curso_editar"], FILTER_SANITIZE_STRING);
 
     $nivel_curso = filter_var($_POST["niveles_ce_update"], FILTER_SANITIZE_NUMBER_INT);

     $id_docente = filter_var($_POST["id_curso_docente_editar"], FILTER_SANITIZE_NUMBER_INT);
 
     $id_establecimiento = $establecimiento;

     $curso = update_curso($id_curso, $nom_curso, $id_establecimiento, $id_docente, $nivel_curso);

   
 
     if( $curso == "exito"){
         $datos = array('estado' => '1'); 
         header('Content-Type: application/json');
         echo json_encode($datos, JSON_FORCE_OBJECT);
 
     }else if($curso == "no_exito"){
         $datos = array('estado' => '0'); 
         header('Content-Type: application/json');
         echo json_encode($datos, JSON_FORCE_OBJECT);  
     }
 }

 else if($tipo == "nuevo_sostenedor"){

    $nom_sostenedor = filter_var($_POST["nombre_nuevo_sostenedor"], FILTER_SANITIZE_STRING);

    $apelli_sostenedor = filter_var($_POST["apellidos_nuevo_sostenedor"], FILTER_SANITIZE_STRING);

    $run_sostenedor = filter_var($_POST["run_nuevo_sostenedor"], FILTER_SANITIZE_NUMBER_INT);

    $id_establecimiento = $establecimiento;

    $user_sostenedor = filter_var($_POST["usuario_nuevo_sostenedor"], FILTER_SANITIZE_STRING);

    $pass_sostenedor = filter_var($_POST["pass_nuevo_sostenedor"], FILTER_SANITIZE_STRING);

    $datos = ingreso_nuevo_sostenedor($nom_sostenedor,$apelli_sostenedor,$run_sostenedor,$id_establecimiento,$user_sostenedor,$pass_sostenedor);

    if($datos == "no_existe"){
        $datos = array('estado' => '1'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
    
    else if($datos == "existe" ){

        $datos = array('estado' => '3'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }else{
        $datos = array('estado' => '0'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
}



?>