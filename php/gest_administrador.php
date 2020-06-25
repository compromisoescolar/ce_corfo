<?php
session_start();
if(isset($_SESSION["user"])){

require_once '../conf/conexion_db.php';
require_once '../conf/funciones_db.php';

$valor = filter_var($_POST["dato"], FILTER_SANITIZE_STRING);


if($valor == "establecimiento"){

    $nom_establecimiento =  filter_var($_POST["nombre_establecimiento"], FILTER_SANITIZE_STRING);

    $rbd_establecimiento = filter_var($_POST["rbd_establecimiento"], FILTER_SANITIZE_NUMBER_INT);

    $id_pais = filter_var($_POST["sel_country_id"], FILTER_SANITIZE_NUMBER_INT);

    $datos = guarda_establecimientos($nom_establecimiento,$rbd_establecimiento,$id_pais);

    if($datos != "existe"){
        $datos = array('estado' => '1'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
    
    else if($datos == "existe"){
        $datos = array('estado' => '0'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }
}



else if($valor == "sostenedor"){

    $nom_sostenedor = filter_var($_POST["nom_soste"], FILTER_SANITIZE_STRING);

    $apelli_sostenedor = filter_var($_POST["apelli_soste"], FILTER_SANITIZE_STRING);

    $run_sostenedor = filter_var($_POST["run_soste"], FILTER_SANITIZE_NUMBER_INT);

    $id_establecimiento =    $_SESSION["identificador_estable"];

    $user_sostenedor = filter_var($_POST["user_sostenedor"], FILTER_SANITIZE_STRING);

    $pass_sostenedor = filter_var($_POST["pass_sostenedor"], FILTER_SANITIZE_STRING);

    $datos = ingreso_nuevo_sostenedor($nom_sostenedor,$apelli_sostenedor,$run_sostenedor,$id_establecimiento, $user_sostenedor, $pass_sostenedor);

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

else if( $valor == "sostenedor_update"){

    $id_sostenedor = filter_var($_POST["id_soste_update"], FILTER_SANITIZE_NUMBER_INT);

    $nom_sostenedor = filter_var($_POST["nom_soste_update"], FILTER_SANITIZE_STRING);

    $apelli_sostenedor = filter_var($_POST["apelli_soste_update"], FILTER_SANITIZE_STRING);

    $run_sostenedor = filter_var($_POST["run_soste_update"], FILTER_SANITIZE_NUMBER_INT);

    $id_user_sostenedor = filter_var($_POST["id_user_soste_update"], FILTER_SANITIZE_NUMBER_INT);

    $user_sostenedor = filter_var($_POST["user_soste_update"], FILTER_SANITIZE_STRING);

    $pass_sostenedor = filter_var($_POST["pass_soste_update"], FILTER_SANITIZE_STRING);

    $resul_update = actualizar_sostenedor($id_sostenedor,$nom_sostenedor,$apelli_sostenedor,$run_sostenedor,$id_user_sostenedor,$user_sostenedor,$pass_sostenedor);

    if($resul_update == TRUE){
        $datos = array('estado' => '1'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);

    }else if($resul_update == FALSE){
        $datos = array('estado' => '0'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);  
    }

}
else if($valor == "nuevo_docente"){

    $nom_docente = filter_var($_POST["nom_prof"], FILTER_SANITIZE_STRING);

    $apelli_docente = filter_var($_POST["apelli_prof"], FILTER_SANITIZE_STRING);

    $run_docente = filter_var($_POST["run_prof"], FILTER_SANITIZE_STRING);

    $mail_docente = filter_var($_POST["mail_prof"], FILTER_SANITIZE_EMAIL);

    $id_establecimiento =    $_SESSION["identificador_estable"];

    $user_docente = filter_var($_POST["user_docente"], FILTER_SANITIZE_STRING);

    $pass_docente = filter_var($_POST["pass_docente"], FILTER_SANITIZE_STRING);



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
else if($valor == "docente_update"){
   $id_docente =  filter_var($_POST["id_docente_update"], FILTER_SANITIZE_NUMBER_INT);

    $nom_docente = filter_var($_POST["nom_docente_update"], FILTER_SANITIZE_STRING);

    $apelli_docente = filter_var($_POST["apelli_docente_update"], FILTER_SANITIZE_STRING);

    $run_docente = filter_var($_POST["run_docente_update"], FILTER_SANITIZE_STRING);

    $mail_docente = filter_var($_POST["mail_docente_update"], FILTER_SANITIZE_EMAIL);

    $id_establecimiento =    $_SESSION["identificador_estable"];

    $id_docente_user =  filter_var($_POST["id_docente_user_update"], FILTER_SANITIZE_NUMBER_INT);

    $nom_docente_user = filter_var($_POST["user_docente_update"], FILTER_SANITIZE_STRING);

    $pass_docente_user = filter_var($_POST["pass_docente_update"], FILTER_SANITIZE_STRING);

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
else if( $valor == "nuevo_curso"){
   
 
     $nom_curso = filter_var($_POST["nom_curso"], FILTER_SANITIZE_STRING);
 
     $nivel_curso = filter_var($_POST["niveles_ce"], FILTER_SANITIZE_NUMBER_INT); 
   
     $id_docente = filter_var($_POST["id_curso_docente"], FILTER_SANITIZE_NUMBER_INT);
 
     $id_establecimiento =    $_SESSION["identificador_estable"];

     $anio_curso = filter_var($_POST["anios_curso"], FILTER_SANITIZE_NUMBER_INT);


     $curso =  registro_nuevo_curso($nom_curso, $id_establecimiento, $id_docente, $nivel_curso, $anio_curso);    
 
 
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

 else if( $valor == "curso_update"){
    //$id_docente =  filter_var($_POST["id_docente_update"], FILTER_SANITIZE_NUMBER_INT);
    $id_curso = filter_var($_POST["id_curso_update"], FILTER_SANITIZE_NUMBER_INT); 

     $nom_curso = filter_var($_POST["nombre_curso_update"], FILTER_SANITIZE_STRING);
 
     $nivel_curso = filter_var($_POST["niveles_ce_update"], FILTER_SANITIZE_NUMBER_INT);
 
     //$especiali_curso = filter_var($_POST["especialidad_curso_update"], FILTER_SANITIZE_STRING);
 
     $id_docente = filter_var($_POST["id_curso_docente_update"], FILTER_SANITIZE_NUMBER_INT);
 
     $id_establecimiento =    $_SESSION["identificador_estable"];

     $ce_anio_curso =  filter_var($_POST["anios_curso"], FILTER_SANITIZE_NUMBER_INT);

     $curso = update_curso($id_curso, $nom_curso, $id_establecimiento, $id_docente, $nivel_curso, $ce_anio_curso);

   // $curso =  registro_nuevo_curso( $nom_curso, $especiali_curso, $id_establecimiento, $id_docente, $nivel_curso);
 
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
 



}else {
    header("location:../index2.php");
}
