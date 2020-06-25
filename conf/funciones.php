<?php

try {
    require 'assets/librerias/simplexlsx.class.php';   
} catch (Exception $e) {
    require '../assets/librerias/simplexlsx.class.php';
}
//require 'assets/librerias/plantilla.php';
session_start();
    $params = session_get_cookie_params();
    setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
        true,  // this is the secure flag you need to set. Default is false.
        true  // this is the httpOnly flag you need to set
    );
    
function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern) - 1;
    for ($i = 0; $i < $longitud; $i++)
        $key .= $pattern{mt_rand(0, $max) };

    return $key;
}

function rand_chars($c, $l, $u = FALSE) {
    if (!$u)
        for ($s = '', $i = 0, $z = strlen($c) - 1; $i < $l; $x = rand(0, $z), $s .= $c{$x}, $i++)
            ;
    else
        for ($i = 0, $z = strlen($c) - 1, $s = $c{rand(0, $z)}, $i = 1; $i != $l; $x = rand(0, $z), $s .= $c{$x}, $s = ($s{$i} == $s{$i - 1} ? substr($s, 0, -1) : $s), $i = strlen($s))
            ;
    return $s;
}

function limpia_espacios($cadena) {
    $cadena = str_replace(' ', '', $cadena);
    return $cadena;
}

function cleanData(&$str) {
    if ($str == 't')
        $str = 'TRUE';
    if ($str == 'f')
        $str = 'FALSE';
    if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
        $str = "'$str";
    }
    if (strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
    $str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
}

//funcion select para exportar a pdf la lista de los estudiantes por curso...
/*
function select_token_pdf($establecimiento, $rbd, $curso) {
    $conexion = connectDB();
    $query = $conexion->query("SELECT firstname,lastname, token FROM lime_tokens_583538 WHERE attribute_3 = '$establecimiento' AND attribute_4 = '$rbd' AND attribute_5 ='$curso'");
    ob_start();
    $pdf = new PDF();
    $pdf->SetTextColor(250, 250, 250);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetDrawColor(65, 139, 204);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(70, 10, 'Establecimiento: ' . utf8_decode($establecimiento), 0, 1, 'L', 0);
    $pdf->Cell(70, 10, 'Curso: ' . utf8_decode($curso), 0, 1, 'L', 0);
    $pdf->Cell(100, 10, 'Nota: ', 0, 1, 'L', 0);
    $pdf->Ln(10);
    $pdf->Cell(70, 6, 'Nombres', 1, 0, 'C', 0);
    $pdf->Cell(40, 6, 'Apellidos', 1, 0, 'C', 0);
    $pdf->Cell(70, 6, 'Token', 1, 1, 'C', 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(255, 255, 255);
    while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(70, 6, utf8_decode($data['firstname']), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode($data['lastname']), 1, 0, 'C');
        $pdf->Cell(70, 6, utf8_decode($data['token']), 1, 1, 'C');
    }
    $pdf->Output($establecimiento . '_' . $curso . ".pdf", 'D');
    $pdf->Output('documentos/pdf/' . $establecimiento . '_' . $curso . ".pdf", 'F');
    ob_end_flush();
}
*/
function select_token_pdf_copia($id_establecimiento, $id_docente, $curso) {
    $conexion = connectDB_demos();

    $sql = "SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos, a.ce_participanes_token AS token,
    b.ce_establecimiento_nombre AS nom_estable, c.ce_curso_nombre AS nom_curso
    FROM ce_participantes a
    INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    WHERE a.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND a.ce_docente_id_ce_docente = '$id_docente' AND a.ce_curso_id_ce_curso = '$curso'";
    $query = $conexion->query($sql);
    
    $query2 = $conexion->query($sql);
    
    $conexion = NULL;
    ob_start();

    $consulta = $query2->fetch(PDO::FETCH_ASSOC);
   

    try {
        require_once 'assets/librerias/vendor/autoload.php';
    } catch (Exception $e) {
        require_once '../assets/librerias/vendor/autoload.php';
    }
$mpdf = new \Mpdf\Mpdf([
    'mode'=>'utf-8'
]);


$mpdf->WriteHTML('<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background-color: #418BCC;
    color: white;
}
.pt-1{
    padding-top:1%;
}
.tamano_letra{
    font-size: 16 px;
}
</style>');

$mpdf->WriteHTML('<body>');
$mpdf->WriteHTML('<div><img src="assets/img/logo.png" width="100px"></div>');
$mpdf->WriteHTML('<div class="pt-1"><strong>Establecimiento: '. $consulta["nom_estable"].' <br><br>Curso: '.$consulta["nom_curso"].'</strong></div>');
$mpdf->WriteHTML('<div style="padding-top:3%"><table style="border-collapse: collapse; width: 800px; font-size: 18px;"><thead><tr><th>Nombres</th><th>Apellidos</th><th>Token</th></tr></thead>');
$mpdf->WriteHTML('<tbody>');
    while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<td style="">'.$data['nombres'].'</td>');
        $mpdf->WriteHTML('<td style="">'.$data['apellidos'].'</td>');
        $mpdf->WriteHTML('<td style="">'.$data['token'].'</td>');
        $mpdf->WriteHTML('</tr>');
    }  
$mpdf->WriteHTML('</tbody>');
$mpdf->WriteHTML('</table></div>');
$mpdf->WriteHTML('</body>');
$mpdf->Output('documentos/pdf/' . $consulta["nom_estable"] . '_' .$consulta["nom_curso"] . ".pdf",\Mpdf\Output\Destination::FILE);

    ob_end_flush();
$ruta = "<a class= 'text-white' href='documentos/pdf/".$consulta["nom_estable"]."_".$consulta["nom_curso"].".pdf' target='_new'>Decargar PDF Curso: ".$consulta["nom_curso"]."_".$consulta["nom_estable"]." <i class='fa fa-download'></i></a>";
    return $ruta;
}

function select_token_pdf_admin($id_establecimiento, $id_docente, $curso) {
    $conexion = connectDB_demos();

    $query = $conexion->query("SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos, a.ce_participanes_token AS token,
    b.ce_establecimiento_nombre AS nom_estable, c.ce_curso_nombre AS nom_curso
    FROM ce_participantes a
    INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    WHERE a.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND a.ce_docente_id_ce_docente = '$id_docente' AND a.ce_curso_id_ce_curso = '$curso'");
    $conexion = NULL;
    ob_start();

    $consulta = $query->fetch(PDO::FETCH_ASSOC);


    

    try {
        require_once 'assets/librerias/vendor/autoload.php';
    } catch (Exception $e) {
        require_once '../assets/librerias/vendor/autoload.php';
    }
    
$mpdf = new \Mpdf\Mpdf([
    'mode'=>'utf-8'
]);


$mpdf->WriteHTML('<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background-color: #418BCC;
    color: white;
}
.pt-1{
    padding-top:1%;
}
.tamano_letra{
    font-size: 16 px;
}
</style>');

$mpdf->WriteHTML('<body>');
$mpdf->WriteHTML('<div><img src="assets/img/logo.png" width="100px"></div>');
$mpdf->WriteHTML('<div class="pt-1"><strong>Establecimiento: '. $consulta["nom_estable"].' <br><br>Curso: '.$consulta["nom_curso"].'</strong></div>');
$mpdf->WriteHTML('<div style="padding-top:3%"><table style="border-collapse: collapse; width: 800px; font-size: 18px;"><thead><tr><th>Nombres</th><th>Apellidos</th><th>Token</th></tr></thead>');
$mpdf->WriteHTML('<tbody>');
    while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<td style="">'.$data['nombres'].'</td>');
        $mpdf->WriteHTML('<td style="">'.$data['apellidos'].'</td>');
        $mpdf->WriteHTML('<td style="">'.$data['token'].'</td>');
        $mpdf->WriteHTML('</tr>');
    }  
$mpdf->WriteHTML('</tbody>');
$mpdf->WriteHTML('</table></div>');
$mpdf->WriteHTML('</body>');
$mpdf->Output('documentos/pdf/' . $consulta["nom_estable"] . '_' .$consulta["nom_curso"] . ".pdf",\Mpdf\Output\Destination::FILE);

    ob_end_flush();
$ruta = "<a class= 'text-white' href='../../documentos/pdf/".$consulta["nom_estable"]."_".$consulta["nom_curso"].".pdf' target='_new'>Decargar PDF Curso: ".$consulta["nom_curso"]."_".$consulta["nom_estable"]." <i class='fa fa-download'></i></a>";
    return $ruta;
}



//valida los campos antes de realizar la funcion;
function validar_campos($row0, $row1, $row2, $row3, $row4, $row5, $row6, $row7, $row8, $row9, $row10, $row11, $row12, $row13) {
    $cuenta = 0;

    if ($row0 == null) {
        echo 'El nombre del estudiante no puede estar vacío';
        exit;
    } elseif ($row1 == NULL) {
        echo 'El apellido del estudiante no puede estar vacío';
        exit;
    } elseif ($row2 == NULL) {
        echo 'La fecha de nacimiento del Estudiante no puede estar vacía';
        exit;
    } elseif ($row3 == NULL) {
        echo 'El Run del estudiante no puede estar vacáa';
        exit;
    } elseif ($row4 == NULL) {
        if ($row4 == 0){
            $$row4 = 0;
        }else{
             echo 'El Dígito verificador del Run del estudiante no puede estar vacío';
        exit;
        }
       
    } elseif ($cuenta == 1 && $row5 == NULL) {
        echo 'El campo Establecimiento no puede estar vacío';
        exit;
    } elseif ($cuenta == 1 && $row6 == NULL) {
        echo 'El RBD del establecimiento no puede estar vacío';
        exit;
    } elseif ($cuenta == 1 && $row7 == NULL) {
        echo 'El campo Curso no puede estar vacío';
        exit;
    } elseif ($row8 == NULL) {
        echo 'El Campo Ciudad no puede quedar vacío';
        exit;
    } elseif ($cuenta == 1 && $row9 == NULL) {
        echo 'Los nombres del docente no puede quedar vacíos';
        exit;
    } elseif ($cuenta == 1 && $row10 == NULL) {
        echo 'Los apellidos de los apoderados no puede estar vacíos';
        exit;
    } elseif ($cuenta == 1 && $row11 == NULL) {
        echo 'El Run del docente no puede estar vacío';
        exit;
    } elseif ($cuenta == 1 && $row12 == NULL) {
        echo 'El Digito verificador del docente no puede estar vacío';
        exit;
    } elseif ($cuenta == 1 && $row13 == NULL) {
        echo 'El email del docente no puede quedar vacío';
        exit;
    }
    
}


