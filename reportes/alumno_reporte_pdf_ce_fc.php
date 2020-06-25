<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

require_once "../assets/librerias/vendor/autoload.php";
require_once "dist/conf/require_conf.php";

use GuzzleHttp\Client;

session_start();

if (!isset($_SESSION['user']) || !isset($_GET['id_alumno'])) {
    header("location: login.php");
    exit();
}

//definimos constantes para la cantidad de preguntas según el tipo, en esta encuesta CE + FC = 47 y COG + FAM + AF + CON + PAR + PRO = 47
define('NUM_CE', 29);
define('NUM_FC', 18);
define('NUM_COG', 12);
define('NUM_FAM', 3);
define('NUM_AF', 10);
define('NUM_CON', 7);
define('NUM_PAR', 7);
define('NUM_PRO', 8);

date_default_timezone_set('America/Santiago');

$conn = connectDB_demos();

$cursos_total_ce = [];

$cursos_total_fc = [];

$cursos_total_af = [];

$cursos_total_con = [];

$cursos_total_cog = [];

$cursos_total_fa= [];

$cursos_total_pa = [];

$cursos_total_pr = [];

$usuario_stmt = $conn->prepare('SELECT * FROM ce_usuarios where nombre_usu = :username');
$usuario_stmt->execute([
    'username' => $_SESSION['user']
]);

$usuario = $usuario_stmt->fetch();

$role = $conn->query("SELECT ce_roles.* from ce_roles join ce_rol_user cru on ce_roles.id_rol = cru.id_roles_fk where cru.id_usuario_fk = {$usuario['id_usu']} AND id_rol = 2")->fetch();

if (!$role) {
    header("location: index.php");
    exit();
}

$establecimiento_id = $usuario['fk_establecimiento'];

$establecimiento_stmt = $conn->prepare("SELECT * FROM ce_establecimiento WHERE id_ce_establecimiento = :id");
$establecimiento_stmt->execute([
    'id' => $establecimiento_id
]);

$establecimiento = $establecimiento_stmt->fetch();

/*alumno*/

$id_alumno = $_GET['id_alumno'];
$alumno_stmt = $conn->prepare("SELECT * FROM ce_participantes INNER JOIN ce_curso ON ce_participantes.ce_curso_id_ce_curso = ce_curso.id_ce_curso
WHERE id_ce_participantes = :id");
$alumno_stmt->execute([
    'id' => $id_alumno
]);

$object_alumno = $alumno_stmt->fetch();
/* Fin alumno */

$totalParticipantesStmt = $conn->prepare('SELECT count(id_ce_participantes) as participantes from ce_participantes where ce_establecimiento_id_ce_establecimiento = :id');
$totalParticipantesStmt->execute([
    'id' => $establecimiento_id
]);

$totalParticipantes = $totalParticipantesStmt->fetch()['participantes'];

// alumno
$query =  $conn->query("SELECT ce_participanes_token as token
        FROM ce_participantes         
        WHERE id_ce_participantes = '$id_alumno'");
        $tokens = $query->fetchAll();
        $token = $tokens[0]["token"];

$stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
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
". NUM_AF ."            AS Afectivo,
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

$stmt->execute(['alumno' => $token]);

$categorias = array();

while ($curso_result = $stmt->fetch()) {
    $curso_ce = array(       
        'y' => round((float) $curso_result['CE'])        
        );
    array_push($cursos_total_ce, $curso_ce);
    $curso_fc = array(        
        'y' => round((float) $curso_result['FC'])       
    );    
    array_push($cursos_total_fc, $curso_fc);
    $curso_af = array(       
        'y' => round((float) $curso_result['Afectivo'])        
        );
    array_push($cursos_total_af, $curso_af);
    $curso_con = array(        
        'y' => round((float) $curso_result['Conductual'])       
    );    
    array_push($cursos_total_con, $curso_con);
    $curso_cog = array(        
        'y' => round((float) $curso_result['Cognitivo'])       
    );  
    array_push($cursos_total_cog, $curso_cog);

    $curso_fa = array(       
        'y' => round((float) $curso_result['Familia'])        
        );
    array_push($cursos_total_fa, $curso_fa);
    $curso_pa = array(        
        'y' => round((float) $curso_result['Pares'])       
    );    
    array_push($cursos_total_pa, $curso_pa);
    $curso_pr = array(        
        'y' => round((float) $curso_result['Profesores'])       
    );  
    array_push($cursos_total_pr, $curso_pr);

    array_push($categorias, $curso_result['ce_anio_curso']);
}

while ($curso_result = $stmt->fetch()) {
    $curso_fa = array(       
        'y' => round((float) $curso_result['Familia'])        
        );
    array_push($cursos_total_fa, $curso_fa);
    $curso_pa = array(        
        'y' => round((float) $curso_result['Pares'])       
    );    
    array_push($cursos_total_pa, $curso_pa);
    $curso_pr = array(        
        'y' => round((float) $curso_result['Profesores'])       
    );  
    array_push($cursos_total_pr, $curso_pr);
}

$tabla_ce_fc_minmax = '<table id="tabla_ce_fc" style="margin-top: 30px; margin-bottom: 0px; width:700px; border-collapse: collapse;">
<thead>
    <tr>      
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Puntaje</th>
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Compromiso escolar</th>
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Factores contextuales</th>        
    </tr>
</thead>
<tbody>
<tr>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
Mínimo</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
</tr>
<tr>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
Máximo</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
</tr>
</tbody>
</table>';

$tabla_dim1_minmax = '<table id="tabla_dim1_minmax" style="margin-top: 30px; margin-bottom: 0px; width:700px; border-collapse: collapse;">
<thead>
    <tr>      
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Puntaje</th>
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Afectivo</th> 
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Cognitivo</th>
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Conductual</th>        
    </tr>
</thead>
<tbody>
<tr>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
Mínimo</span>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
</tr>
<tr>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
Máximo</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
</tr>
</tbody>
</table>';

$tabla_dim2_minmax = '<table id="tabla_dim2_minmax" style="margin-top: 30px; margin-bottom: 0px; width:700px; border-collapse: collapse;">
<thead>
    <tr>      
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Puntaje</th>
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Familia</th> 
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Profesores</th>
        <th align="right" style="font-size:18px;border-bottom: solid 1px red; padding: 3px;">Pares</th>        
    </tr>
</thead>
<tbody>
<tr>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
Mínimo</span>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
1</span></td>
</tr>
<tr>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
Máximo</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
<td align="right" style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">
5</span></td>
</tr>
</tbody>
</table>';

$tabla_inter_ce_fc = '';

$i = 0;

$totalCe = 0;
$promCe = 0;

$totalFc = 0;
$promFc = 0;

while($i < count($cursos_total_ce)) {      
    $totalCe+= $cursos_total_ce[$i]['y'];
    $totalFc+= $cursos_total_fc[$i]['y'];
    $i++;
}

$promCe = round($totalCe / count($cursos_total_ce));

$promFc = round($totalFc / count($cursos_total_ce));

$i = 0;
while($i < count($cursos_total_ce)) {
    $alert1 = '';
    $alert2 = '';
    if($cursos_total_ce[$i]['y'] < $promCe) {
        $alert1 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert1 = '&nbsp;&nbsp;';
    }
    if($cursos_total_fc[$i]['y'] < $promFc) {
        $alert2 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert2 = '&nbsp;&nbsp;';
    }
    $tabla_inter_ce_fc = $tabla_inter_ce_fc.
    '<tr>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">'.
    $categorias[$i].'</span></td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_ce[$i]['y'].''.$alert1.'</td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_fc[$i]['y'].''.$alert2.'</td>'.
    '</tr>';
    $i++;
}

$tabla_ce_fc = '<table id="tabla_ce_fc" style="margin-top: 30px; margin-bottom: 0px; width:700px; border-collapse: collapse;">
<thead>
    <tr>
        <th scope="col"></th>
        <th align="right" style="font-size:18px">Compromiso escolar</th>
        <th align="right" style="font-size:18px">Factores contextuales</th>        
    </tr>
</thead>
<tbody>
    '.$tabla_inter_ce_fc.'
</tbody>
</table>';

$tabla_inter_dim1 = '';

$i = 0;

$totalAf = 0;
$promAf = 0;

$totalCog = 0;
$promCog = 0;

$totalCon = 0;
$promCon = 0;

while($i < count($cursos_total_ce)) {      
    $totalAf+= $cursos_total_af[$i]['y'];
    $totalCog+= $cursos_total_cog[$i]['y'];
    $totalCon+= $cursos_total_con[$i]['y'];
    $i++;
}

$promAf = round($totalAf / count($cursos_total_af));

$promCon = round($totalCon / count($cursos_total_con));

$promCog = round($totalCog / count($cursos_total_cog));

$i = 0;
while($i < count($cursos_total_af)) {
    $alert1 = '';
    $alert2 = '';
    $alert3 = '';
    if($cursos_total_af[$i]['y'] < $promAf) {
        $alert1 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert1 = '&nbsp;&nbsp;';
    }
    if($cursos_total_con[$i]['y'] < $promCon) {
        $alert2 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert2 = '&nbsp;&nbsp;';
    }
    if($cursos_total_cog[$i]['y'] < $promCog) {
        $alert3 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert3 = '&nbsp;&nbsp;';
    }
    $tabla_inter_dim1 = $tabla_inter_dim1.
    '<tr>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">'.
    $categorias[$i].'</span></td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_af[$i]['y'].''.$alert1.'</td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_con[$i]['y'].''.$alert2.'</td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_cog[$i]['y'].''.$alert3.'</td>'.
    '</tr>';
    $i++;
}

$tabla_dim1 = '<table id="tabla_ce_fc" style="margin-top: 30px; margin-bottom: 0px; width:700px; border-collapse: collapse;">
<thead>
    <tr>
        <th scope="col"></th>
        <th align="right" style="font-size:18px">Afectivo</th>
        <th align="right" style="font-size:18px">Conductual</th>     
        <th align="right" style="font-size:18px">Cognitivo</th>        
    </tr>
</thead>
<tbody>
    '.$tabla_inter_dim1.'
</tbody>
</table>';

$tabla_inter_dim2 = '';


$i = 0;

$totalFa = 0;
$promFa = 0;

$totalPr = 0;
$promPr = 0;

$totalPa = 0;
$promPa = 0;

while($i < count($cursos_total_fa)) {      
    $totalFa+= $cursos_total_fa[$i]['y'];
    $totalPr+= $cursos_total_pr[$i]['y'];
    $totalPa+= $cursos_total_pa[$i]['y'];
    $i++;
}

$promFa = round($totalFa / count($cursos_total_fa));

$promPr = round($totalPr / count($cursos_total_pr));

$promPa = round($totalPa / count($cursos_total_pa));


$i = 0;
while($i < count($cursos_total_fc)) {
    $alert1 = '';
    $alert2 = '';
    $alert3 = '';
    if($cursos_total_fa[$i]['y'] < $promFa) {
        $alert1 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert1 = '&nbsp;&nbsp;';
    }
    if($cursos_total_pa[$i]['y'] < $promPa) {
        $alert2 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert2 = '&nbsp;&nbsp;';
    }
    if($cursos_total_pr[$i]['y'] < $promPr) {
        $alert3 = '<span style="color:darkOrange; font-weight: bold">*</span>';
    } else {
        $alert3 = '&nbsp;&nbsp;';
    }
    $tabla_inter_dim2 = $tabla_inter_dim2.
    '<tr>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;"><span style="font-weight:bold; color:black;">'.
    $categorias[$i].'</span></td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_fa[$i]['y'].''.$alert1.'</td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_pa[$i]['y'].''.$alert2.'</td>'.
    '<td style="border-bottom: solid 1px red; padding: 3px;" align="right">'.$cursos_total_pr[$i]['y'].''.$alert3.'</td>'.
    '</tr>';
    $i++;
}

$tabla_dim2 = '<table id="tabla_ce_fc" style="margin-top: 30px; margin-bottom: 0px; width:700px; border-collapse: collapse;">
<thead>
    <tr>
        <th scope="col"></th>
        <th align="right" style="font-size:18px">Familiar</th>
        <th align="right" style="font-size:18px">Pares</th>     
        <th align="right" style="font-size:18px">Profesores</th>        
    </tr>
</thead>
<tbody>
    '.$tabla_inter_dim2.'
</tbody>
</table>';

$opcionesCeFcColegio = [
    "colors" => [
        "#40c3d4",
        "#fc455c",
        "#f39c12"
    ],
    "chart"=> [
        "type" => "column"
    ],
    "title"=> [
        "text" => ""
    ],
    "yAxis"=> [
        "allowDecimals"=> false,
        "title"=> [
            "text"=> "Puntaje"
        ],
        "max"=> 5
    ],
    "xAxis" => ["categories" => $categorias],
    "series" => [[
        "data" => $cursos_total_ce,
        "name" => "Compromiso escolar"
    ], 
    [
        "data" => $cursos_total_fc,
        "name" => "Factores contextuales"
    ]
    ],
    "credits" => [
        "enabled" => false
    ]
];

$opcionesImgCeFcColegio = [
    [
        'name' => 'options',
        'contents' => json_encode($opcionesCeFcColegio)
    ],

    [
        'name' => 'filename',
        'contents' => 'reporte_ce_fc_est.png'
    ],

    [
        'name' => 'type',
        'contents' => 'type/png'
    ]    
];

$opcionesDim1Colegio = [
    "colors" => [
        "#40c3d4",
        "#fc455c",
        "#f39c12"
    ],
    "chart"=> [
        "type" => "column"
    ],
    "title"=> [
        "text" => ""
    ],
    "yAxis"=> [
        "allowDecimals"=> false,
        "title"=> [
            "text"=> "Puntaje"
        ],
        "max"=> 5
    ],
    "xAxis" => ["categories" => $categorias],
    "series" => [[
        "data" => $cursos_total_af,
        "name" => "Afectivo"
    ], 
    [
        "data" => $cursos_total_con,
        "name" => "Conductual"
    ], 
    [
        "data" => $cursos_total_cog,
        "name" => "Cognitivo"
    ]
    ],
    "credits" => [
        "enabled" => false
    ]
];

$opcionesDim2Colegio = [
    "colors" => [
        "#40c3d4",
        "#fc455c",
        "#f39c12"
    ],
    "chart"=> [
        "type" => "column"
    ],
    "title"=> [
        "text" => ""
    ],
    "yAxis"=> [
        "allowDecimals"=> false,
        "title"=> [
            "text"=> "Puntaje"
        ],
        "max"=> 5
    ],
    "xAxis" => ["categories" => $categorias],
    "series" => [[
        "data" => $cursos_total_fa,
        "name" => "Familia"
    ], 
    [
        "data" => $cursos_total_pa,
        "name" => "Pares"
    ], 
    [
        "data" => $cursos_total_pr,
        "name" => "Profesores"
    ]
    ],
    "credits" => [
        "enabled" => false
    ]
];

$opcionesImgDim1Colegio = [
    [
        'name' => 'options',
        'contents' => json_encode($opcionesDim1Colegio)
    ],

    [
        'name' => 'filename',
        'contents' => 'reporte_dim1_est.png'
    ],

    [
        'name' => 'type',
        'contents' => 'type/png'
    ]    
];

$opcionesImgDim2Colegio = [
    [
        'name' => 'options',
        'contents' => json_encode($opcionesDim2Colegio)
    ],

    [
        'name' => 'filename',
        'contents' => 'reporte_dim2_est.png'
    ],

    [
        'name' => 'type',
        'contents' => 'type/png'
    ]    
];


try {
    $client = new Client();

    if ($totalParticipantes != 0) {
        $res = $client->post('http://export.highcharts.com/', [
            'multipart' => $opcionesImgCeFcColegio
        ]);

        $res2 = $client->post('http://export.highcharts.com/', [
            'multipart' => $opcionesImgDim1Colegio
        ]);
        
        $res3 = $client->post('http://export.highcharts.com/', [
            'multipart' => $opcionesImgDim2Colegio
        ]);


        $imagen_ce_fc_colegio = "data:image/png;base64,".base64_encode($res->getBody());

        
        $imagen_dim1_colegio = "data:image/png;base64,".base64_encode($res2->getBody());

        
        $imagen_dim2_colegio = "data:image/png;base64,".base64_encode($res3->getBody());
    }

} catch (\GuzzleHttp\Exception\ClientException $exception) {
    echo $exception->getResponse()->getBody();
    return;
}

if ($totalParticipantes != 0 ) {
    $graficos_edu = '<center>
    <div style="width=100%;"><img src="'.$imagen_ce_fc_colegio.'"></div>
    '; 
    $graficos_dim1 = '<center>
    <div style="width=100%;"><img src="'.$imagen_dim1_colegio.'"></div>
    '; 
    $graficos_dim2 = '<center>
    <div style="width=100%;"><img src="'.$imagen_dim2_colegio.'"></div>
    ';
}



 
$mpdfConfig = array(
  'mode' => 'utf-8', 
  'format' => 'A4',
  'orientation' => 'P',
  'margin_header'=>0,  
  'margin_bottom'=>20,
  'margin_left'=>0,
  'margin_right'=>0, 
  'margin_top'=> 30 
);
$mpdf = new \Mpdf\Mpdf($mpdfConfig);

$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold;"><img src="../assets/img/encabezado_informe_estudiante.jpg"/></div>', 'O', true);

$mpdf->SetHTMLFooter('<div style="text-align: center;border-top: 1px solid #fc455c; color: #fc455c; padding-top:3px;">{PAGENO}</div>');

$mpdf->WriteHTML('<div class="container">
<div class="descrip_com_esco">

<div class="subtitulos pt-1">Reporte de seguimiento
<p style="margin-bottom: 20px; font-weight:normal; color:black">En esta sección se puede apreciar la dinámica del compromiso escolar y sus factores contextuales a través del tiempo.</p>
</div>

<h2 style="margin-bottom: 0">Estudiante: '.$object_alumno["ce_participantes_nombres"].' '.$object_alumno["ce_participantes_apellidos"].'</h2>
<h2 style="margin-top: 0">Curso: '.$object_alumno["ce_curso_nombre"].'</h2>
'.$tabla_ce_fc.'<p>(<span style="color:darkOrange">*</span>) Corresponde al puntaje inferior promedio a los últimos reportes.</p>
<br>'.$tabla_ce_fc_minmax.'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
 <p>La gráfica muestra el seguimiento del compromiso escolar y sus factores contextuales, respecto del establecimiento educacional.</p>
 <br> 
'.$graficos_edu.'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'.$tabla_dim1
.'<p>(<span style="color:darkOrange">*</span>) Corresponde al puntaje inferior promedio a los últimos reportes.</p><br>'.$tabla_dim1_minmax.'<p>La gráfica muestra el seguimiento del compromiso escolar y sus dimensiones afectiva, conductual y cognitiva, respecto del establecimiento educacional.</p>
<br><br>'.$graficos_dim1.'
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'.$tabla_dim2.'<p>(<span style="color:darkOrange">*</span>) Corresponde al puntaje inferior promedio a los últimos reportes.</p><br>'.$tabla_dim2_minmax.'
<p>La gráfica muestra el seguimiento de los factores contextuales  y sus dimensiones familia, pares y profesores, respecto del establecimiento educacional.</p>
<br><br>'.$graficos_dim2.'
</center>
    </div>
    </div>');


$mpdf->Output('reporte_estudiante.pdf', 'I');