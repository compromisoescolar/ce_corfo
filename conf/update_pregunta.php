<?php
error_reporting(E_ALL ^ E_NOTICE);
require 'conexion_db.php';
require 'funciones_db.php';
$resultado = 0;
$id_pre = $_POST["txt_id_pregunta"];
$nomb_pre = $_POST["txt_nombre_pregunta"];
$dimen_pre = $_POST["select_dimension"];
$pais_pre = $_POST["select_pais"];

$resultado = update_pregunta($id_pre,$nomb_pre,$dimen_pre,$pais_pre);

if($resultado >= 1){
    $datos = array('Estado' => 'Exitoso'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);

}elseif($resultado <= 0){
    $datos = array('Estado' => 'No_Exitoso'); 
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);

}

