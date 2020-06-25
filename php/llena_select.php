<?php
require_once '../conf/conexion_db.php';
require_once '../conf/funciones_db.php';

$id_establecimiento = $_POST["id_establecimiento"];
$tipo = $_POST["tipo"];

if($tipo == "llena_cursos"){
    echo select_docente($id_establecimiento);

}

?>