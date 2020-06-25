<?php 

session_start();

require_once "funciones_php.php";
require_once "../conf/conexion_db.php";
require_once "../conf/funciones_db.php";


//DECLARACIÓN DE VARIABLES DE LOS RESULTADOS  

$txt_token = $_SESSION['estudiante'];

$txt_1 =  $_POST["numero1"];
$txt_2 =  $_POST["numero2"];
$txt_3 = $_POST["numero3"];// R
//invertimos respuesta
$txt_3_r = invierte_numero($txt_3);
$txt_4 =$_POST["numero4"];// R
//invertimos respuesta
$txt_4_r = invierte_numero($txt_4);
$txt_5 = $_POST["numero5"];
$txt_6 =$_POST["numero6"];

$txt_7 = $_POST["numero7"];
$txt_8 = $_POST["numero8"];
$txt_9 = $_POST["numero9"];// R
//invertimos respuesta
$txt_9_r = invierte_numero($txt_9);
$txt_10 = $_POST["numero10"];
$txt_11 =$_POST["numero11"]; // R
//invertimos respuesta
$txt_11_r = invierte_numero($txt_11);
$txt_12 = $_POST["numero12"];

$txt_13 = $_POST["numero13"];
$txt_14 = $_POST["numero14"];
$txt_15 = $_POST["numero15"];
$txt_16 = $_POST["numero16"];
$txt_17 = $_POST["numero17"];
$txt_18 = $_POST["numero18"];
$txt_19 = $_POST["numero19"];
$txt_20 = $_POST["numero20"];
$txt_21 = $_POST["numero21"];
$txt_22 = $_POST["numero22"];
$txt_23 = $_POST["numero23"];// R
//invertimos respuesta
$txt_23_r = invierte_numero($txt_23);
$txt_24 = $_POST["numero24"];
$txt_25 = $_POST["numero25"];
$txt_26 = $_POST["numero26"];
$txt_27 = $_POST["numero27"];
$txt_28 = $_POST["numero28"]; // R
//invertimos respuesta
$txt_28_r = invierte_numero($txt_28);
$txt_29 = $_POST["numero29"];
$txt_30 = $_POST["numero30"];
$txt_31 = $_POST["numero31"];
$txt_32 = $_POST["numero32"];
$txt_33 = $_POST["numero33"];
$txt_34 = $_POST["numero34"];
$txt_35 = $_POST["numero35"];
$txt_36 = $_POST["numero36"];
$txt_37 = $_POST["numero37"];
$txt_38 = $_POST["numero38"];
$txt_39 = $_POST["numero39"];
$txt_40 = $_POST["numero40"];
$txt_41 = $_POST["numero41"];
$txt_42 = $_POST["numero42"];
$txt_43 = $_POST["numero43"];
$txt_44 = $_POST["numero44"];
$txt_45 = $_POST["numero45"];
$txt_46 = $_POST["numero46"];
$txt_47 = $_POST["numero47"];
$hora_inicio = $_POST["hora_inicio"];
$hora_final = $_POST["hora_final"];



$respuesta_estudiantes = guarda_respuesta($txt_token,$hora_inicio,$hora_final,$txt_1,$txt_2,$txt_3_r,$txt_4_r,$txt_5,$txt_6,$txt_7,$txt_8,$txt_9_r,$txt_10
,$txt_11_r,$txt_12,$txt_13,$txt_14,$txt_15,$txt_16,$txt_17,$txt_18,$txt_19,$txt_20
,$txt_21,$txt_22,$txt_23_r,$txt_24,$txt_25,$txt_26,$txt_27,$txt_28_r,$txt_29,$txt_30
,$txt_31,$txt_32,$txt_33,$txt_34,$txt_35,$txt_36,$txt_37,$txt_38,$txt_39,$txt_40
,$txt_41,$txt_42,$txt_43,$txt_44,$txt_45,$txt_46,$txt_47);

if($respuesta_estudiantes == TRUE){
$datos = array('estado' => '1');
header('Content-Type: application/json');
echo json_encode($datos, JSON_FORCE_OBJECT);

}

else if($respuesta_estudiantes == FALSE){
$datos = array('estado' => '0');
header('Content-Type: application/json');
echo json_encode($datos, JSON_FORCE_OBJECT);

}
else
{
$datos = array('estado' => 'error');
header('Content-Type: application/json');
echo json_encode($datos, JSON_FORCE_OBJECT);

}
?>