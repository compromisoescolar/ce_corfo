<?php

$nombre_documento = $_GET["nombre"];
$extension_documento = $_GET["extension"];

header ('Content-Disposition: attachment; filename="'.basename($nombre_documento).'"'); 
header ('Content-Type: application/octet-stream'); 

readfile($nombre_documento); 
   
?>