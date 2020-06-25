<?php 
session_start();
if(isset($_SESSION["user"])){
    $id_establecimiento = $_SESSION["identificador_estable"];
require_once '../conf/conexion_db.php';
require_once '../conf/funciones_db.php';

vista_sostenedores($id_establecimiento); 
}
else {
    header("location:../index2.php");
}
?>
<script>
actualiza_sostenedor()
diseno_tabla_sostenedor()
</script>