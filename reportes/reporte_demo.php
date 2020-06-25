<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

require_once "../assets/librerias/vendor/autoload.php";
require_once "dist/conf/require_conf.php";

$mpdf->Output();
