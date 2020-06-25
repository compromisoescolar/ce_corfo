<?php 
	error_reporting(E_ERROR | E_PARSE);
	session_start(); 
	$usu_autogestion = $_SESSION['user'];
	$estudiante = $_SESSION['estudiante'];
	if($estudiante != '' && isset($_GET["csrf"]) && $_GET["csrf"] == $_SESSION["token"]){
		header('Location: index2.php'); 
		session_destroy(); 
	} else if($usu_autogestion != '' && isset($_GET["csrf"]) && $_GET["csrf"] == $_SESSION["token"]) {
		header('Location: index2.php');
		array_map('unlink', glob("reportes/dist/img/individual/*.png"));
		array_map('unlink', glob("reportes/dist/img/curso/*.png"));
		session_destroy();
	} else if (isset($_GET["csrf"])) {
		header('Location: index2.php');
		array_map('unlink', glob("reportes/dist/img/individual/*.png"));
		array_map('unlink', glob("reportes/dist/img/curso/*.png"));
		session_destroy();
	}
	

