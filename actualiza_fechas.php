<?php
require_once 'conf/conexion_db.php';
require_once 'conf/funciones.php';
require_once 'conf/funciones_db.php';


$xlsx = new SimpleXLSX("fechas/fechas_naci_colombia.xlsx");

$con = connectDB_demos();

$fecha_carga = '2019-04-30 09:23:33';

$cantidad = 0;

$query = $con->prepare("UPDATE ce_participantes SET ce_participantes_fecha_nacimiento=:fecha_naci, ce_participantes_fecha_registro=:fecha_carga WHERE ce_participantes_run=:run");
foreach ($xlsx->rows() as $fila) {
    if($cantidad >= 1){
        $run = $fila[0];//run
        $anio = $fila[2];//anio
       $mes =  $fila[3];//mes
       $dia = $fila[4];//dia

       $fecha_nacimiento = $anio."-".$mes."-".$dia;

      $query->execute([
          ":run"=>$run,
          ":fecha_naci"=> $fecha_nacimiento,
          ":fecha_carga"=>$fecha_carga
          ]);

    }
   
   $cantidad++;
}
?>