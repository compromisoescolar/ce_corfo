<?php
session_start();

header('Content-Type: text/html; charset=UTF-8');

require_once '../../conf/conexion_db.php';
require_once 'excel_admin_curso.php';
require_once '../../conf/funciones_db.php';

$id_establecimiento = $_SESSION["identificador_estable"];
$id_docente = $_POST["id_profesor"];
$id_curso_nivel = explode(",", $_POST["id_curso"]);
$id_pais =  $_SESSION["pais_establecimiento"];
$id_curso = $id_curso_nivel[0];
$id_nivel = $id_curso_nivel[1];
$anio = date("Y");

$xlsx = new SimpleXLSX($_FILES['file']['tmp_name']);

$con = connectDB_demos();

$cantidad = 0;

$query = $con->prepare("SELECT id_ce_participantes FROM ce_participantes WHERE ce_participantes_run = :ce_participantes_run");

foreach ($xlsx->rows() as $fila) {
    $run_estudiante = $fila[3];

    $query->execute([
        "ce_participantes_run" => "$fila[3]"
    ]);

    $resultado = $query->rowCount();

    if ($resultado >= 1) {
        $cantidad ++;
    }
    if ($resultado <= 0) {
        $cantidad = 0;
    }
}

$con = NULL;

if ($cantidad != 0) {
    $datos = array(
        'estado' => '3'
    );
    header('Content-Type: application/json');
    echo json_encode($datos, JSON_FORCE_OBJECT);
} else if ($cantidad == 0) {

    $resultado = valida_carga_excel($id_establecimiento, $id_docente, $id_curso);
    
    if ($resultado == FALSE) {

        $values = array();
        $contador = 0;

        foreach ($xlsx->rows() as $fila) {

            if ($contador >= 2) {
                $nombres = $fila[0];
                $apellidos = $fila[1];
                $fech_nacimiento = $fila[2];
                $run_estudiante = $fila[3];
                $ciudad = $fila[4];
                //$run_estudi = substr($run_estudiante, 0, - 1);
                $token_estu = "A".$run_estudiante;
                $nuevo = nuevo_estudiante_uni($nombres,$apellidos,$run_estudiante,$fech_nacimiento,$token_estu,$ciudad,$id_establecimiento,$id_docente,$id_curso,$id_nivel,$id_pais, $anio);
            }
            $contador ++;
        }
        if ($nuevo == TRUE) {
            $archivo = select_token_pdf_admin($id_establecimiento, $id_docente, $id_curso);

            $datos = array(
                'estado' => '1',
                'archivo' => $archivo
            );
            header('Content-Type: application/json');
            echo json_encode($datos, JSON_FORCE_OBJECT);
        } else if ($nuevo == FALSE) {
            $datos = array(
                'estado' => '0'
            );
            header('Content-Type: application/json');
            echo json_encode($datos, JSON_FORCE_OBJECT);
        }
    } else if ($resultado == TRUE) {
        $datos = array(
            'estado' => '2'
        );
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }
}
?>