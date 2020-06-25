<?php 

require "../../conf/conexion_db.php";
require "../../conf/funciones_db.php";
$tipo = $_GET["tipo"];
$id_establecimiento = $_GET["id"];
if($tipo == "establecimiento"){
    try{
        
    
        $con = connectDB_demos();
        $query =  $con->query("SELECT b.id_soste AS codigo, b.nom_soste AS nombre, b.apelli_soste AS apellido, b.run_soste AS run, b.fecha_registro_soste AS fecha_registro, c.ce_establecimiento_nombre AS establecimiento,
        d.id_usu AS id_sostenedor_usu,d.nombre_usu AS usu_sostenedor, d.contrasena_usu AS pass_sostenedor
        FROM ce_establecimiento_sostenedor a 
        INNER JOIN ce_sostenedor b ON a.sostenedor_id = b.id_soste
        INNER JOIN ce_establecimiento c ON a.establecimiento_id = c.id_ce_establecimiento
        JOIN ce_usuarios d ON b.run_soste = d.nombre_usu
        WHERE a.establecimiento_id = '$id_establecimiento'");
        $con = NULL;
           
        foreach($query AS $fila){
          $arreglo["data"][]= $fila;
        }  
        echo json_encode($arreglo);
      
    
    
    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

if($tipo == "cursos"){

try{
    $con = connectDB_demos();

    $query =  $con->query("SELECT a.id_esta_curs_doc AS id_pivot,
     b.id_ce_curso AS id_curso,
     b.ce_curso_nombre AS nombre_curso,
     d.ce_nombre AS nivel,
     CONCAT(c.ce_docente_nombres,' ',c.ce_docente_apellidos) AS nom_docente,
     d.ce_id_niveles AS id_nivel,
     c.id_ce_docente as id_docente     
    FROM ce_estable_curso_docente a      
    INNER JOIN ce_curso b ON a.ce_fk_curso = b.id_ce_curso
    INNER JOIN ce_docente c ON a.ce_fk_docente = c.id_ce_docente
    INNER JOIN ce_niveles d ON a.ce_fk_nivel = d.ce_id_niveles   
    WHERE a.ce_fk_establecimiento = '$id_establecimiento' ");       
   $cantidad = $query->rowCount();
   $con = NULL;

 
   foreach( $query AS $fila){
    $arreglo["data"][]= $fila;
}  
         echo json_encode($arreglo);
     
        


}catch(Exception $ex){
    exit("Excepción Captutrada: ".$ex->getMessage());
}
}

if($tipo == "docentes"){
try{
    $con = connectDB_demos();

    $query =  $con->query("SELECT b.id_ce_docente AS id_doc,
    b.ce_docente_nombres AS nom_doc,
    b.ce_docente_apellidos AS apelli_doc,
    b.ce_docente_run AS run_docente,
    b.ce_docente_email AS email_doc,
   IFNULL(c.ce_curso_nombre, 'indefinido') AS nom_curso, 
   IFNULL(d.ce_nombre, 'indefinido') AS nivel_curso,     
   e.id_ce_establecimiento AS id_estable,
   f.id_usu AS id_usuario_docente,
     f.nombre_usu AS nom_usu_docente,
     f.contrasena_usu AS pass_usu_docente  
   
   FROM ce_estable_curso_docente a
   
   LEFT JOIN ce_docente b ON a.ce_fk_docente = b.id_ce_docente
   LEFT JOIN ce_curso c ON a.ce_fk_curso = c.id_ce_curso
   LEFT JOIN ce_niveles d ON a.ce_fk_nivel = d.ce_id_niveles
   LEFT JOIN ce_establecimiento e ON a.ce_fk_establecimiento = e.id_ce_establecimiento
   JOIN ce_usuarios f ON  b.ce_docente_run = f.nombre_usu
   WHERE e.id_ce_establecimiento = '$id_establecimiento' ");       

   $con = NULL;

       
   foreach( $query AS $fila){
    $arreglo["data"][]= $fila;
}  
         echo json_encode($arreglo);


}catch(Exception $ex){
    exit("Excepción Captutrada: ".$ex->getMessage());
}
}

?>