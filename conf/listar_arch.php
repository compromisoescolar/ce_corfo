<?php  
	require 'funciones_db.php';

	$folder 	= intval($_POST["folder"]) - 1;
	$sub_folder = intval($_POST["sub_folder"]) - 1;
	$dir_base = "../documentos/recursos_educativos";

	$carpetas = ListarDir(
		$dir_base, 
		0
	);

	$sub_carpetas = ListarDir(
		$dir_base."/".$carpetas[$folder], 
		0
	);
	$archis = ListarDir(
		$dir_base."/".$carpetas[$folder]."/".$sub_carpetas[$sub_folder], 
		1
	);

	//echo $sub_carpetas[$sub_folder];
	
	echo json_encode($archis);
?>