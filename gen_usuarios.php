<?php 
require 'conf/conexion_db.php';
require 'conf/funciones.php';
require 'conf/funciones_db.php';  


$con = connectDB_demos();

//INSERTAMOS USUARIOS PROFESORES 
$query_count = $con->query("SELECT * FROM ce_docente");

foreach($query_count AS $fila){
$user = $fila["ce_docente_run"];
if($user == "132352704" || $user == "89429099" || $user == "156823597" || $user == "117811905"  ){
    continue;
}
$rest = substr($fila["ce_docente_run"], 0, -1);


insertar_usuario($user, $rest);

}

/*
$query_count = $con->query("SELECT * FROM ce_usuarios");
foreach($query_count AS $fila){

$user = $fila["id_usu"];
if($user == "1" || $user == "2" || $user == "3" || $user == "4" || $user == "7" ){
    continue;
}
$query_count = $con->query("INSERT INTO ce_rol_user(id_usuario_fk,id_roles_fk) VALUES ('$user','1') ");
}
*/
?>