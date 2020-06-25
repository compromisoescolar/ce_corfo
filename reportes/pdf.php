<?php
$pdf = $_POST["pdf"];
$data = base64_decode($pdf);
header('Content-Type: application/pdf');
file_put_contents("mi_curso.pdf",$data);
header('Content-Type: application/pdf');

readfile("mi_curso.pdf");
?>

