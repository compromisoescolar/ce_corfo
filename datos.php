<?php

require_once "assets/librerias/vendor/autoload.php";
require_once "conf/conf_requiere.php";
$id_estable = 6; 
$id_docente = 234;
$id_curso = 116;
$resultado =  curso_datos($id_estable, $id_docente, $id_curso);

echo $resultado["nomcurso"];

?>
