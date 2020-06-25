<?php
require_once '../conf/conexion_db.php';
require_once '../conf/funciones_db.php';

$id_seccion = filter_input(INPUT_POST, 'seccion_doc', FILTER_SANITIZE_NUMBER_INT);
$talleres_doc = filter_input(INPUT_POST, 'talleres_doc', FILTER_SANITIZE_NUMBER_INT);

$nombre_archivo =$_FILES['documento_carga']['name'];
$tipo_archivo = $_FILES["archivo"]["type"];
$tamano_archivo = $_FILES["archivo"]["size"];

$uploaddir = '../documentos/material/';
$material = 'documentos/material/';
$uploadfile = $uploaddir.$_FILES['documento_carga']['name'];
move_uploaded_file($_FILES['documento_carga']['tmp_name'], $uploadfile);
// DE AQUI QUITO LA BARRA DEL INICIO
$userfile_extn = get_extension($_FILES['documento_carga']['name']);
switch ($userfile_extn) {
    case "pdf":
        $nombre = $_FILES['documento_carga']['name'];
        $nombre_fin = str_replace(".pdf", "", $nombre);
        $imagen_pdf = "assets/img/pdf.svg";
        insertar_material($nombre_fin, $material, $imagen_pdf, $userfile_extn, $id_seccion,$talleres_doc);
        break;
    case "docx":
        $nombre = $_FILES['documento_carga']['name'];
        $nombre_fin = str_replace(".docx", "", $nombre);
        $imagen_excel = "assets/img/doc.svg";
        insertar_material($nombre_fin, $material, $imagen_excel, $userfile_extn, $id_seccion,$talleres_doc);
        break;
    case "pptx":
        $nombre = $_FILES['documento_carga']['name'];
        $nombre_fin = str_replace(".pptx", "", $nombre);
        $imagen_ppt = "assets/img/pptx.svg";
        insertar_material($nombre_fin, $material, $imagen_ppt, $userfile_extn, $id_seccion,$talleres_doc);
        break;
    case "mp4":
        $nombre = $_FILES['documento_carga']['name'];
        $nombre_fin = str_replace(".mp4", "", $nombre);
        $imagen_video = "assets/img/video-player.svg";
        insertar_material($nombre_fin, $material, $imagen_video, $userfile_extn, $id_seccion,$talleres_doc);
        break;
    case "mp3":
        $nombre = $_FILES['documento_carga']['name'];
        $nombre_fin = str_replace(".mp3", "", $nombre);
        $imagen_mp3 = "assets/img/mp3.svg";
        insertar_material($nombre_fin, $material, $imagen_mp3, $userfile_extn, $id_seccion,$talleres_doc);
        break;
}


function get_extension($str)
{
    return end(explode(".", $str));
}
