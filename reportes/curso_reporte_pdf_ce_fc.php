<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

require_once "../assets/librerias/vendor/autoload.php";
require_once "dist/conf/require_conf.php";

use GuzzleHttp\Client;

session_start();

if (!isset($_SESSION['user']) || !isset($_GET['id_curso'])) {
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

/*Curso*/

$id_curso = $_GET['id_curso'];
$curso_stmt = $conn->prepare("SELECT * FROM ce_curso WHERE id_ce_curso = :id");
$curso_stmt->execute([
    'id' => $id_curso
]);

$object_curso = $curso_stmt->fetch();
/* Fin curso */

$totalParticipantesStmt = $conn->prepare('SELECT count(id_ce_participantes) as participantes from ce_participantes where ce_establecimiento_id_ce_establecimiento = :id');
$totalParticipantesStmt->execute([
    'id' => $establecimiento_id
]);

$totalParticipantes = $totalParticipantesStmt->fetch()['participantes'];

// ESTABLECIMIENTO

$stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
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
ce_participantes.ce_fk_nivel as nivel,
ce_anio_curso       
FROM ce_encuesta_resultado
JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
WHERE ce_estado_encuesta = 1
AND ce_establecimiento_id_ce_establecimiento = :id    
GROUP BY ce_curso.ce_curso_nombre ORDER BY ce_anio_curso ASC");

$stmt->execute(['id' => $establecimiento_id]);

$cursos_dimensiones_1_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
COUNT(id_ce_participantes)                                as participantes,
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
". NUM_AF ."             AS Afectivo,
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
ce_participantes.ce_fk_nivel as nivel,
ce_anio_curso       
FROM ce_encuesta_resultado
JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
WHERE ce_estado_encuesta = 1
AND ce_establecimiento_id_ce_establecimiento = :id 
GROUP BY ce_curso.ce_curso_nombre  ORDER BY ce_anio_curso ASC");

$cursos_dimensiones_1_stmt->execute(array('id' => $establecimiento_id));

$cursos_dimensiones_2_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
COUNT(id_ce_participantes)                                as participantes,
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
AND ce_establecimiento_id_ce_establecimiento = :id 
GROUP BY ce_curso.ce_curso_nombre ORDER BY ce_anio_curso ASC");

$cursos_dimensiones_2_stmt->execute(array('id' => $establecimiento_id));

$categorias = array();

while ($cursos_result = $stmt->fetch()) {
    $curso_ce = array(       
        'y' => round((float) $cursos_result['CE'])        
        );
    array_push($cursos_total_ce, $curso_ce);
    $curso_fc = array(        
        'y' => round((float) $cursos_result['FC'])       
    );    
    array_push($cursos_total_fc, $curso_fc);
    array_push($categorias, $cursos_result['ce_anio_curso']);
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


while ($cursos_result = $cursos_dimensiones_1_stmt->fetch()) {
    $curso_af = array(       
        'y' => round($cursos_result['Afectivo'])       
        );
    array_push($cursos_total_af, $curso_af);
    $curso_con = array(        
        'y' => round($cursos_result['Conductual'])       
    );    
    array_push($cursos_total_con, $curso_con);
    $curso_cog = array(        
        'y' => round($cursos_result['Cognitivo'])      
    );  
    array_push($cursos_total_cog, $curso_cog);
}

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



while ($cursos_result = $cursos_dimensiones_2_stmt->fetch()) {
    $curso_fa = array(       
        'y' => round($cursos_result['Familia'])  
        );
    array_push($cursos_total_fa, $curso_fa);
    $curso_pa = array(        
        'y' => round($cursos_result['Pares'])   
    );    
    array_push($cursos_total_pa, $curso_pa);
    $curso_pr = array(        
        'y' => round($cursos_result['Profesores'])      
    );  
    array_push($cursos_total_pr, $curso_pr);
}

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
$curso_prom_stmt->execute([':curso' => $id_curso, ':currentYear' => $currentYear]);
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
        $curso_prom_prev_stmt->execute([':curso' => $id_curso, ':previousYear' => $previousYear]);
        $promPrevYear = $curso_prom_prev_stmt->fetchAll();

        $preguntas_stmt = $conn->prepare('SELECT ce_pregunta_nombre, ce_preguntas_codigo, ce_orden         
        FROM ce_preguntas WHERE ce_nivel = 1
        ORDER BY ce_orden asc
        ');
        $preguntas_stmt->execute();
        $preguntas = $preguntas_stmt->fetchAll();

        $tabla_simbologia = '<h3 class="text-center">Comparación temporal por ítem</h3><br>
        <table class="table table-striped" style="border-collapse: collapse; margin-left:10px;">
        <thead>
            <tr>
                <th scope="row" style="text-align:center;width:60px;">Ítem</th>
                <th scope="row" style="text-align:center">Descripción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center; border-bottom: solid 1px red; padding: 2px;">&#8593;</td>
                <td style="text-align:left; border-bottom: solid 1px red; padding: 2px;">Mejoró la tendencia para esa pregunta respecto al año anterior</td>                                                          
            </tr>
            <tr>
                <td style="text-align:center; border-bottom: solid 1px red; padding: 2px;">0</td>
                <td style="text-align:left; border-bottom: solid 1px red; padding: 2px;">Se mantiene la tendencia para esa pregunta respecto al año anterior</td>
            </tr>
            <tr>
                <td style="text-align:center; border-bottom: solid 1px red; padding: 2px;">&#8595;</td>
                <td style="text-align:left; border-bottom: solid 1px red; padding: 2px;">Bajó la tendencia para esa pregunta respecto al año anterior</td>
            </tr>
        </tbody>
    </table>';

        $tabla_inicio_preguntas = '<table class="table table-striped" style="border-collapse: collapse;"><thead> 
                                <col>
                                <colgroup span="2"></colgroup>
                                <colgroup span="2"></colgroup>
                                <tr>
                                <th colspan="1" scope="colgroup" style="border-top:none !important;"></th>
                                <th colspan="2" scope="colgroup" style="border-top:none !important; text-align:center; min-width:180px"><span style="margin-right:10px">Promedio del curso</span> <i class="fa fa-question-circle" style="color:#2d6693; font-size: 26px" aria-hidden="true" onclick="referencia_tabla_simbologia()"></i></th>
                                <th scope="col" style="border-top:none !important;text-align:right;"></th>
                                </tr>
                                <tr>
                                <th></th>
                                <th scope="col" style="text-align:center">'. $promPrevYear[0]["ce_anio_contestada"]. '</th>
                                <th scope="col" style="text-align:center">'. $promCurrentYear[0]["ce_anio_contestada"]. '</th>
                                <th scope="col">Tendencia</th>
                                </tr>
                                </thead>
                                <tbody>';
$aux = '<tr>
<td style="border-bottom: solid 1px red; padding: 2px;">' . $preguntas[0][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p1"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p1"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p1"],$promCurrentYear[0]["ce_p1"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[1][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p2"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p2"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p2"],$promCurrentYear[0]["ce_p2"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[2][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p3"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p3"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p3"],$promCurrentYear[0]["ce_p3"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[3][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p4"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p4"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p4"],$promCurrentYear[0]["ce_p4"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[4][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p5"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p5"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p5"],$promCurrentYear[0]["ce_p5"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[5][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p6"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p6"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p6"],$promCurrentYear[0]["ce_p6"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[6][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p7"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p7"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p7"],$promCurrentYear[0]["ce_p7"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[7][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p8"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p8"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p8"],$promCurrentYear[0]["ce_p8"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[8][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p9"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p9"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p9"],$promCurrentYear[0]["ce_p9"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[9][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p10"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p10"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p10"],$promCurrentYear[0]["ce_p10"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[10][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p11"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p11"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p11"],$promCurrentYear[0]["ce_p11"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[11][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p12"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p12"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p12"],$promCurrentYear[0]["ce_p12"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[12][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p13"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p13"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p13"],$promCurrentYear[0]["ce_p13"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[13][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p14"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p14"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p14"],$promCurrentYear[0]["ce_p14"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[14][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p15"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p15"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p15"],$promCurrentYear[0]["ce_p15"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[15][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p16"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p16"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p16"],$promCurrentYear[0]["ce_p16"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[16][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p17"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p17"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p17"],$promCurrentYear[0]["ce_p17"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[17][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p18"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p18"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p18"],$promCurrentYear[0]["ce_p18"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[18][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p19"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p19"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p19"],$promCurrentYear[0]["ce_p19"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[19][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p20"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p20"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p20"],$promCurrentYear[0]["ce_p20"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[20][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p21"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p21"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p21"],$promCurrentYear[0]["ce_p21"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[21][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p22"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p22"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p22"],$promCurrentYear[0]["ce_p22"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[22][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p23"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p23"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p23"],$promCurrentYear[0]["ce_p23"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[23][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p24"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p24"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p24"],$promCurrentYear[0]["ce_p24"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[24][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p25"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p25"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p25"],$promCurrentYear[0]["ce_p25"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[25][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p26"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p26"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p26"],$promCurrentYear[0]["ce_p26"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[26][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p27"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p27"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p27"],$promCurrentYear[0]["ce_p27"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[27][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p28"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p28"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p28"],$promCurrentYear[0]["ce_p28"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[28][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p29"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p29"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p29"],$promCurrentYear[0]["ce_p29"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[29][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p30"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p30"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p30"],$promCurrentYear[0]["ce_p30"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[30][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p31"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p31"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p31"],$promCurrentYear[0]["ce_p31"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[31][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p32"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p32"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p32"],$promCurrentYear[0]["ce_p32"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[32][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p33"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p33"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p33"],$promCurrentYear[0]["ce_p33"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[33][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p34"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p34"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p34"],$promCurrentYear[0]["ce_p34"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[34][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p35"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p35"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p35"],$promCurrentYear[0]["ce_p35"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[35][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p36"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p36"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p36"],$promCurrentYear[0]["ce_p36"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[36][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p37"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p37"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p37"],$promCurrentYear[0]["ce_p37"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[37][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p38"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p38"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p38"],$promCurrentYear[0]["ce_p38"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[38][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p39"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p39"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p39"],$promCurrentYear[0]["ce_p39"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[39][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p40"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p40"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p40"],$promCurrentYear[0]["ce_p40"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[40][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p41"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p41"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p41"],$promCurrentYear[0]["ce_p41"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[41][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p42"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p42"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p42"],$promCurrentYear[0]["ce_p42"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[42][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p43"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p43"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p43"],$promCurrentYear[0]["ce_p43"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[43][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p44"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p44"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p44"],$promCurrentYear[0]["ce_p44"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[44][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p45"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p45"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p45"],$promCurrentYear[0]["ce_p45"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[45][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p46"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p46"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p46"],$promCurrentYear[0]["ce_p46"] ).'</td></tr>
<tr>
<td style="border-bottom: solid 1px red; padding: 2px;" >' . $preguntas[46][0] .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promPrevYear[0]["ce_p47"])). '</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="center">'. round(($promCurrentYear[0]["ce_p47"])) .'</td>
<td style="border-bottom: solid 1px red; padding: 2px;" align="right">'.calcTendencia($promPrevYear[0]["ce_p47"],$promCurrentYear[0]["ce_p47"] ).'</td></tr>
';
             
$tabla_preguntas_fin = '</tbody></table>';
$tabla_preguntas_union = $tabla_inicio_preguntas.$aux.$tabla_preguntas_fin;
                                
function calcTendencia($prev, $curr)
{
    $result = '';
    if((round($curr) - round($prev)) == 0)
    {
        $result = '0';
    } else if ($curr - $prev < 0) {
        $result = '&#8595; '  .((round($curr) - round($prev))); 
    } else {
        $result = '<span style="color:#3c763d; font-size:16px;"> &#8593; +'  .((round($curr) - round($prev))).'</span>'; 
    }

    return $result;
}
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

$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold;"><img src="../assets/img/encabezado_informe_curso.jpg"/></div>', 'O', true);

$mpdf->SetHTMLFooter('<div style="text-align: center;border-top: 1px solid #fc455c; color: #fc455c; padding-top:3px;">{PAGENO}</div>');

$mpdf->WriteHTML('<div class="container">
<div class="descrip_com_esco">

<div class="subtitulos pt-1">Reporte de seguimiento
<p style="margin-bottom: 20px; font-weight:normal; color:black">En esta sección se puede apreciar la dinámica del compromiso escolar y sus factores contextuales a través del tiempo.</p>
</div>

<h2>Colegio: '.$object_curso["ce_curso_nombre"].'</h2>
'.$tabla_ce_fc.'<p>(<span style="color:darkOrange">*</span>) Corresponde al puntaje inferior promedio a los últimos reportes.</p>
<br>'.$tabla_ce_fc_minmax.'<br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
 <p>La gráfica muestra el seguimiento del compromiso escolar y sus factores contextuales, respecto del establecimiento educacional.</p>
 <br> 
'.$graficos_edu.'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'.$tabla_dim1
.'<p>(<span style="color:darkOrange">*</span>) Corresponde al puntaje inferior promedio a los últimos reportes.</p><br>'.$tabla_dim1_minmax.'<p>La gráfica muestra el seguimiento del compromiso escolar y sus dimensiones afectiva, conductual y cognitiva, respecto del establecimiento educacional.</p>
<br><br>'.$graficos_dim1.'
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'.$tabla_dim2.'<p>(<span style="color:darkOrange">*</span>) Corresponde al puntaje inferior promedio a los últimos reportes.</p><br>'.$tabla_dim2_minmax.'
<p>La gráfica muestra el seguimiento de los factores contextuales  y sus dimensiones familia, pares y profesores, respecto del establecimiento educacional.</p>
<br><br>'.$graficos_dim2.'<br><br>'.$tabla_simbologia.'<br><br>'.$tabla_preguntas_union.'
</center>
    </div>
    </div>');


$mpdf->Output('reporte_curso.pdf', 'I');