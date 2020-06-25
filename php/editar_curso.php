<?php 

require_once '../conf/conexion_db.php';

$id_establecimiento = $_GET['establecimiento'];
$id_docente = $_GET['docente'];
$id_curso = $_GET['curso'];  

try{


    $conexion = connectDB_demos();
    $query = $conexion->query("SELECT
    a.id_ce_participantes AS identificador,
    a.ce_participantes_nombres AS nombres,
    a.ce_participantes_apellidos AS apellidos,
    a.ce_participantes_run AS run,
    a.ce_participantes_fecha_nacimiento AS fecha_nac,
    a.ce_participanes_token AS token,
    a.ce_participantes_fecha_registro AS ingreso,
    a.ce_ciudad AS ciudad,
    b.ce_curso_nombre AS curso,
    a.ce_establecimiento_id_ce_establecimiento AS id_establecimiento,
    a.ce_docente_id_ce_docente as id_docente,
    a.ce_curso_id_ce_curso as id_curso
    FROM ce_participantes a
    INNER JOIN ce_curso b ON a.ce_curso_id_ce_curso = b.id_ce_curso
    INNER JOIN ce_establecimiento c ON a.ce_establecimiento_id_ce_establecimiento = c.id_ce_establecimiento
    INNER JOIN ce_docente d ON a.ce_docente_id_ce_docente = d.id_ce_docente 
    WHERE 
    a.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
    a.ce_docente_id_ce_docente = '$id_docente' AND
    a.ce_curso_id_ce_curso = '$id_curso'  GROUP BY a.id_ce_participantes");
    
   
    foreach($query AS $fila){
        $arreglo["data"][]= $fila;
      }  
      echo json_encode($arreglo);
       
           
        
    } catch (Exception $ex) {
        echo 'Excepción Capturada', $ex->getMessage(), "\n";
    }
    
?>