<?php

session_start();

require_once '../conf/conexion_db.php';
require_once '../assets/librerias/simplexlsx.class.php';
require_once '../conf/funciones_db.php';


$file = $_FILES['file']['name'];

$file_ext = strrchr($file, '.');
$estado = "";
if ($file_ext == '.xlsx') {

    $contador = 0;


    $xlsx = new SimpleXLSX($_FILES['file']['tmp_name']);

    foreach ($xlsx->rows() as $fila) {
        if ($contador == 0) {
            if ($fila[0] != "Datos Estudiantes") {
                $estado = 1;
                $datos = array('estado' => "1");
                header('Content-Type: application/json');
                echo json_encode($datos, JSON_FORCE_OBJECT);
                exit;
            }
        } else if ($contador == 1) {
            if ($fila[0] != "Nombres Estudiantes" || $fila[1] != "Apellidos Estudiantes" || $fila[3] != "Run" || $fila[4] != "Ciudad") {
                $estado = 1;
                $datos = array('estado' => "1");
                header('Content-Type: application/json');
                echo json_encode($datos, JSON_FORCE_OBJECT);
                exit;
            }
        } else if ($contador > 1) {
            if ($fila[0] == '' || $fila[1] == '' || $fila[2] == '' || $fila[3] == '' || $fila[4] == '') {
                $estado = 3;
                $datos = array('estado' => "3");
                header('Content-Type: application/json');
                echo json_encode($datos, JSON_FORCE_OBJECT);
                exit;
            }
        }

        $contador++;
    }

    $estado = 5;

    if($estado == 5){
        $datos = array('estado' => "5");
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);
    }

}else{
    $datos = array('estado' => "4");
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);
}
?>