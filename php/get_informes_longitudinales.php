<?php
session_start();
include_once '../conf/funciones_db.php';
include_once '../conf/conexion_db.php';

$metodo =  $_POST['method']; 

//definimos constantes para la cantidad de preguntas según el tipo, en esta encuesta CE + FC = 47 y COG + FAM + AF + CON + PAR + PRO = 47
define('NUM_CE', 29);
define('NUM_FC', 18);
define('NUM_COG', 12);
define('NUM_FAM', 3);
define('NUM_AF', 10);
define('NUM_CON', 7);
define('NUM_PAR', 7);
define('NUM_PRO', 8);

if(isset($_POST['method'])) {
    if($_POST['method'] == 'get_info_curso') {
        $curso = $_POST["curso"];

        $conn = connectDB_demos();
        $curso_stmt = $conn->prepare('SELECT ce_curso_nombre as nombre,
        COUNT(id_ce_participantes)                                as participantes,
        (SUM(ce_p1) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p2) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p3) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p4) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p5) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p6) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p7) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p8) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p9) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p10) / COUNT(ce_participantes.id_ce_participantes)  +
SUM(ce_p11) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p12) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p13) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p14) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p15) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p16) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p17) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p18) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p19) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p20) / COUNT(ce_participantes.id_ce_participantes)  +
SUM(ce_p21) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p22) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p23) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p24) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p25) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p26) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p27) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p28) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p29) / COUNT(ce_participantes.id_ce_participantes) ) /  '. NUM_CE .'         AS CE,
(SUM(ce_p30) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p31) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p32) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p33) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p34) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p35) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p36) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p37) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p38) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p39) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p40) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p41) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p42) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p43) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p44) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p45) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p46) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p47) / COUNT(ce_participantes.id_ce_participantes)) / '. NUM_FC. ' AS FC,
(
SUM(ce_p1)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p5)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p7)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p8)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p12) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p15) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p19) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p22) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p27) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p29) / COUNT(ce_participantes.id_ce_participantes)   ) /
'. NUM_AF .'            AS Afectivo,
(SUM(ce_p3) / COUNT(ce_participantes.id_ce_participantes)  +
 SUM(ce_p4)  / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p9)  / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p11) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p16) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p23) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p28) / COUNT(ce_participantes.id_ce_participantes)
) / '. NUM_CON .' AS Conductual,
(
SUM(ce_p2)  / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p6)  / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p10) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p13) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p14) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p17) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p18) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p20) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p21) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p24) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p25) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p26) / COUNT(ce_participantes.id_ce_participantes)) / '. NUM_COG .' AS Cognitivo,
(
SUM(ce_p30) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p31) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p32) / COUNT(ce_participantes.id_ce_participantes)
) / '. NUM_FAM .'               AS Familia,
(
SUM(ce_p41) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p42) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p43) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p44) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p45) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p46) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p47) / COUNT(ce_participantes.id_ce_participantes) 
) / '. NUM_PAR .' AS Pares,
(
SUM(ce_p33) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p34) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p35) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p36) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p37) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p38) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p39) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p40) / COUNT(ce_participantes.id_ce_participantes) 
) / '. NUM_PRO .' AS Profesores,
        ce_participantes.ce_fk_nivel as nivel,
        ce_anio_curso       
 FROM ce_encuesta_resultado
        JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
        JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
 WHERE ce_estado_encuesta = 1
   AND ce_establecimiento_id_ce_establecimiento = 41
 GROUP BY ce_curso.ce_curso_nombre ORDER BY ce_anio_curso ASC');
        $curso_stmt->execute(array(':curso' => $curso));

        /*promedio por año */
        $conn = connectDB_demos();
        $currentYear = date('Y');
        $curso_prom_stmt = $conn->prepare('SELECT 
        AVG(ce_p1) AS ce_p1, 
        AVG(ce_p2) AS ce_p2,
        AVG(ce_p3) AS ce_p3, 
        AVG(ce_p4) AS ce_p4,
        AVG(ce_p5) AS ce_p5, 
        AVG(ce_p6) AS ce_p6,
        AVG(ce_p7) AS ce_p7, 
        AVG(ce_p8) AS ce_p8,
        AVG(ce_p9) AS ce_p9, 
        AVG(ce_p10) AS ce_p10,
        AVG(ce_p11) AS ce_p11, 
        AVG(ce_p12) AS ce_p12,
        AVG(ce_p13) AS ce_p13, 
        AVG(ce_p14) AS ce_p14,
        AVG(ce_p15) AS ce_p15, 
        AVG(ce_p16) AS ce_p16,
        AVG(ce_p17) AS ce_p17, 
        AVG(ce_p18) AS ce_p18,
        AVG(ce_p19) AS ce_p19, 
        AVG(ce_p20) AS ce_p20,
        AVG(ce_p21) AS ce_p21, 
        AVG(ce_p22) AS ce_p22,
        AVG(ce_p23) AS ce_p23, 
        AVG(ce_p24) AS ce_p24,
        AVG(ce_p25) AS ce_p25, 
        AVG(ce_p26) AS ce_p26,
        AVG(ce_p27) AS ce_p27, 
        AVG(ce_p28) AS ce_p28,
        AVG(ce_p29) AS ce_p29, 
        AVG(ce_p30) AS ce_p30,
        AVG(ce_p31) AS ce_p31, 
        AVG(ce_p32) AS ce_p32,
        AVG(ce_p33) AS ce_p33,
        AVG(ce_p34) AS ce_p34, 
        AVG(ce_p35) AS ce_p35,
        AVG(ce_p36) AS ce_p36, 
        AVG(ce_p37) AS ce_p37,
        AVG(ce_p38) AS ce_p38, 
        AVG(ce_p39) AS ce_p39,
        AVG(ce_p40) AS ce_p40, 
        AVG(ce_p41) AS ce_p41,
        AVG(ce_p42) AS ce_p42, 
        AVG(ce_p43) AS ce_p43,
        AVG(ce_p44) AS ce_p44, 
        AVG(ce_p45) AS ce_p45,
        AVG(ce_p46) AS ce_p46, 
        AVG(ce_p47) AS ce_p47,
        COUNT(cp.id_ce_participantes), cer.ce_anio_contestada  FROM ce_encuesta_resultado cer
        INNER JOIN ce_participantes cp ON cer.ce_participantes_token_fk = cp.ce_participanes_token
        INNER JOIN ce_curso cc ON cp.ce_curso_id_ce_curso = cc.id_ce_curso
        WHERE cp.ce_curso_id_ce_curso = :curso AND cer.ce_anio_contestada = :currentYear');
        $curso_prom_stmt->execute([':curso' => $curso, ':currentYear' => $currentYear]);
        $promCurrentYear = $curso_prom_stmt->fetchAll();

        $previousYear = date('Y') - 1;
        $curso_prom_prev_stmt = $conn->prepare('SELECT 
        AVG(ce_p1) AS ce_p1, 
        AVG(ce_p2) AS ce_p2,
        AVG(ce_p3) AS ce_p3, 
        AVG(ce_p4) AS ce_p4,
        AVG(ce_p5) AS ce_p5, 
        AVG(ce_p6) AS ce_p6,
        AVG(ce_p7) AS ce_p7, 
        AVG(ce_p8) AS ce_p8,
        AVG(ce_p7) AS ce_p9, 
        AVG(ce_p10) AS ce_p10,
        AVG(ce_p11) AS ce_p11, 
        AVG(ce_p12) AS ce_p12,
        AVG(ce_p13) AS ce_p13, 
        AVG(ce_p14) AS ce_p14,
        AVG(ce_p15) AS ce_p15, 
        AVG(ce_p16) AS ce_p16,
        AVG(ce_p17) AS ce_p17, 
        AVG(ce_p18) AS ce_p18,
        AVG(ce_p19) AS ce_p19, 
        AVG(ce_p20) AS ce_p20,
        AVG(ce_p21) AS ce_p21, 
        AVG(ce_p22) AS ce_p22,
        AVG(ce_p23) AS ce_p23, 
        AVG(ce_p24) AS ce_p24,
        AVG(ce_p25) AS ce_p25, 
        AVG(ce_p26) AS ce_p26,
        AVG(ce_p27) AS ce_p27, 
        AVG(ce_p28) AS ce_p28,
        AVG(ce_p29) AS ce_p29, 
        AVG(ce_p30) AS ce_p30,
        AVG(ce_p31) AS ce_p31, 
        AVG(ce_p32) AS ce_p32,
        AVG(ce_p33) AS ce_p33,
        AVG(ce_p34) AS ce_p34, 
        AVG(ce_p35) AS ce_p35,
        AVG(ce_p36) AS ce_p36, 
        AVG(ce_p37) AS ce_p37,
        AVG(ce_p38) AS ce_p38, 
        AVG(ce_p39) AS ce_p39,
        AVG(ce_p40) AS ce_p40, 
        AVG(ce_p41) AS ce_p41,
        AVG(ce_p42) AS ce_p42, 
        AVG(ce_p43) AS ce_p43,
        AVG(ce_p44) AS ce_p44, 
        AVG(ce_p45) AS ce_p45,
        AVG(ce_p46) AS ce_p46, 
        AVG(ce_p47) AS ce_p47,
        COUNT(cp.id_ce_participantes), cer.ce_anio_contestada  FROM ce_encuesta_resultado cer
        INNER JOIN ce_participantes cp ON cer.ce_participantes_token_fk = cp.ce_participanes_token
        INNER JOIN ce_curso cc ON cp.ce_curso_id_ce_curso = cc.id_ce_curso
        WHERE cp.ce_curso_id_ce_curso = :curso AND cer.ce_anio_contestada = :previousYear');
        $curso_prom_prev_stmt->execute([':curso' => $curso, ':previousYear' => $previousYear]);
        $promPrevYear = $curso_prom_prev_stmt->fetchAll();
        /* fin promedio por año*/
        $curso = $curso_stmt->fetchAll();

        $preguntas_stmt = $conn->prepare('SELECT ce_pregunta_nombre, ce_preguntas_codigo, ce_orden         
        FROM ce_preguntas WHERE ce_nivel = 1
        ORDER BY ce_orden asc
        ');
        $preguntas_stmt->execute();
        $preguntas = $preguntas_stmt->fetchAll();

        $finalObject = array($curso,$promCurrentYear,$promPrevYear, $preguntas);

        echo json_encode($finalObject);
    } else if($_POST['method'] == 'get_info_alumno') {
        $alumno = $_POST["alumno"];

        $conn = connectDB_demos();
        $query =  $conn->query("SELECT ce_participanes_token as token
        FROM ce_participantes         
        WHERE id_ce_participantes = '$alumno'");
        $tokens = $query->fetchAll();
        $token = $tokens[0]["token"];
        $alumno_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
        CONCAT(ce_participantes_nombres, ' ', ce_participantes_apellidos) as nombre_participante,
        COUNT(id_ce_participantes)                                as participantes,
        (SUM(ce_p1) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p2) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p3) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p4) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p5) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p6) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p7) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p8) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p9) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p10) / COUNT(ce_participantes.id_ce_participantes)  +
SUM(ce_p11) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p12) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p13) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p14) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p15) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p16) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p17) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p18) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p19) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p20) / COUNT(ce_participantes.id_ce_participantes)  +
SUM(ce_p21) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p22) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p23) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p24) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p25) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p26) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p27) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p28) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p29) / COUNT(ce_participantes.id_ce_participantes) ) /  ". NUM_CE ."         AS CE,
(SUM(ce_p30) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p31) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p32) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p33) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p34) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p35) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p36) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p37) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p38) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p39) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p40) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p41) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p42) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p43) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p44) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p45) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p46) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p47) / COUNT(ce_participantes.id_ce_participantes)) / ". NUM_FC. " AS FC,
(
SUM(ce_p1)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p5)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p7)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p8)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p12) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p15) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p19) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p22) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p27) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p29) / COUNT(ce_participantes.id_ce_participantes)   ) /
". NUM_AF ."           AS Afectivo,
(SUM(ce_p3) / COUNT(ce_participantes.id_ce_participantes)  +
 SUM(ce_p4)  / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p9)  / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p11) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p16) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p23) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p28) / COUNT(ce_participantes.id_ce_participantes)
) / ". NUM_CON ." AS Conductual,
(
SUM(ce_p2)  / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p6)  / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p10) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p13) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p14) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p17) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p18) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p20) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p21) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p24) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p25) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p26) / COUNT(ce_participantes.id_ce_participantes)) / ". NUM_COG ." AS Cognitivo,
(
SUM(ce_p30) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p31) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p32) / COUNT(ce_participantes.id_ce_participantes)
) / ". NUM_FAM ."               AS Familia,
(
SUM(ce_p41) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p42) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p43) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p44) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p45) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p46) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p47) / COUNT(ce_participantes.id_ce_participantes) 
) / ". NUM_PAR ." AS Pares,
(
SUM(ce_p33) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p34) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p35) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p36) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p37) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p38) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p39) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p40) / COUNT(ce_participantes.id_ce_participantes) 
) / ". NUM_PRO ." AS Profesores,
        ce_participantes.ce_fk_nivel as nivel,
        ce_anio_curso       
 FROM ce_encuesta_resultado
        JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
        JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
 WHERE ce_estado_encuesta = 1
   AND ce_participanes_token = :alumno
 GROUP BY ce_curso.ce_curso_nombre ORDER BY ce_anio_curso ASC");
        $alumno_stmt->execute(array(':alumno' => $token));

        $alumno = $alumno_stmt->fetchAll();

        echo json_encode($alumno);
    }
}

