<?php
require_once "conf/conf_requiere.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("location: login_auto_gestion.php");
    exit();
}

$id = $_POST["id_doc"];
$nombre = $_POST['nombre_doc'];
 
echo $id;

$con = connectDB_demos();

unlink("documentos/material/$nombre");

$deleteQuery = $con->prepare('DELETE FROM ce_doc_documentos where doc_id = :doc_id');
$deleteQuery->execute([
    'doc_id' => $id
]);

$con = Null;
