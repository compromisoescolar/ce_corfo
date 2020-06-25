<?php
require '../conf/conexion_db.php';
require '../conf/funciones_db.php';
require_once 'dist/conf/funciones_reportes.php';

function fecha_hora()
{
    $timezone = -3;
    $hora_fecha = gmdate("Y/m/j H:i:s", time() + 3600 * ($timezone + date("I")));

    return $hora_fecha;
}


function hola(){

    return "<script type='text/javascript'>
    $('#demo').html('Hello <b>world</b>!');
    </script>ccc<div id='demo'></div>";
}

function console_log($data) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log('".$output ."');</script>";
}