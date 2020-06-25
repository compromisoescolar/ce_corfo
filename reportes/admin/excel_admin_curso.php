<?php 

require_once '../../assets/librerias/simplexlsx.class.php';


function select_token_pdf_admin($id_establecimiento, $id_docente, $curso) {
    $conexion = connectDB_demos();

    $query = $conexion->query("SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos, a.ce_participanes_token AS token,
    b.ce_establecimiento_nombre AS nom_estable, c.ce_curso_nombre AS nom_curso
    FROM ce_participantes a
    INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    WHERE a.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND a.ce_docente_id_ce_docente = '$id_docente' AND a.ce_curso_id_ce_curso = '$curso'");

    //obtenemos los parametros para formar el pdf

    $query_datos = $conexion->query("SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos, a.ce_participanes_token AS token,
    b.ce_establecimiento_nombre AS nom_estable, c.ce_curso_nombre AS nom_curso
    FROM ce_participantes a
    INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    WHERE a.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND a.ce_docente_id_ce_docente = '$id_docente' AND a.ce_curso_id_ce_curso = '$curso'");
    $conexion = NULL;
    ob_start();

    $consulta = $query_datos->fetch(PDO::FETCH_ASSOC);

    require_once '../../assets/librerias/vendor/autoload.php';
    
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
$mpdf->WriteHTML('<div><img src="../../assets/img/logo.png" width="100px"></div>');
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
$mpdf->Output('../../documentos/pdf/' . $consulta["nom_estable"] . '_' .$consulta["nom_curso"] . ".pdf",\Mpdf\Output\Destination::FILE);

    ob_end_flush();
$ruta = "<a class='' href='../../documentos/pdf/".$consulta["nom_estable"]."_".$consulta["nom_curso"].".pdf' target='_new'>Decargar PDF Curso: ".$consulta["nom_curso"]."_".$consulta["nom_estable"]." <i class='fa fa-download'></i></a>";
    return $ruta;
}

?>