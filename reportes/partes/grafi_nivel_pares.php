<?php
session_start();

$url = $_POST["url"];
$id_establecimiento = $_SESSION["id_establecimiento"];
$id_curso = $_SESSION["id_profesor"];

ob_start();
$img = imagecreatefrompng($url);

imagepng($img,"../dist/img/curso/".$id_establecimiento.'_'.$id_curso."_pares.png");
imagedestroy($img);
ob_end_clean();

?>