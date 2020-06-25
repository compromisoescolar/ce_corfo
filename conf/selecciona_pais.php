<?php 
require '../conf/conexion_db.php';
require '../conf/funciones_db.php';

$id_pais = $_POST["id_pais"];

//echo $id_pais;
selecciona_preguntas_por_pais($id_pais);
