<?php 

require_once 'conf/conexion_db.php';
require_once 'conf/funciones.php';
require_once 'conf/funciones_db.php';  


$con = connectDB_demos();

#los que estan se actualiza el estado

$query_count = $con->query("SELECT a.id_ce_participantes AS id_participantes
  FROM ce_participantes  a
 WHERE a.ce_participanes_token  IN (SELECT b.ce_participantes_token_fk
 FROM ce_encuesta_resultado b)
 ");

foreach($query_count AS $fila){
    $id_participantes[] = array("id"=>$fila["id_participantes"]);
}

foreach($id_participantes AS $fila){

$id_participante = $fila["id"];

$query_count = $con->query("UPDATE ce_participantes SET ce_estado_encuesta = 1 WHERE id_ce_participantes = '$id_participante'");

}


?>