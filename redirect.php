<?php 

session_start();

$_SESSION["id_curso"] =  $_GET["id_curso"];
$_SESSION["id_nivel"] = $_GET["id_nivel"];

header("Location: reportes/estudiante.php");
?>