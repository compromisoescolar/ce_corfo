<?php

require_once "assets/librerias/vendor/autoload.php";
require_once "conf/conf_requiere.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

$timezone  = -3;
$hora_fecha =  gmdate("Ymj-His", time() + 3600*($timezone+date("I")));

$curso_id = $_GET["curso_comp"];
$docente_id = $_GET["docente_comp"];
$establecimiento_id = $_GET["establecimiento_comp"];
$var_aleatoria = $_GET["comple"];

if( $var_aleatoria == "curso"){

    $conexion = connectDB_demos();
    $consulta = $conexion->prepare("SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos,
    c.ce_curso_nombre AS curso,
    a.ce_participanes_token AS token,
    a.ce_estado_encuesta AS estado
    FROM ce_participantes a
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    WHERE 1=1 AND
    a.ce_establecimiento_id_ce_establecimiento = :establecimiento_id AND
    a.ce_docente_id_ce_docente = :docente_id AND
    a.ce_curso_id_ce_curso = :curso_id");

 $consulta->execute([
     "establecimiento_id"=>$establecimiento_id,
     "docente_id"=>$docente_id,
     "curso_id"=>$curso_id
 ]);
    $conexion = NULL;
   

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getProperties()
    ->setCreator("Jonathan Barrera")
    ->setLastModifiedBy("Jonathan Barrera")
    ->setTitle("2019 XLSX")
    ->setSubject("2019 XLSX")
    ->setDescription("Curso")
    ->setKeywords("2019 php")
    ->setCategory("Junior");
$spreadsheet->getActiveSheet()->setTitle("Curso");
$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(100);
$sheet->getTabColor()->setRGB('8CB517');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri Light');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $fontStyle = [
        'font' => [
            'size' => 12
        ]
        
      ];
      $spreadsheet->getActiveSheet()->mergeCells('A1:E1')->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      $sheet->setCellValue('A1', 'Curso')->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A1')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle("A2:E2")->applyFromArray($fontStyle);
      $sheet->getStyle('A2:E2')->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A2:E2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      
    $sheet->setCellValue('A2', 'Nombres')->getStyle('A2')->getFont()->setBold(true);
    $sheet->setCellValue('B2', 'Apellidos')->getStyle('B2')->getFont()->setBold(true);
    $sheet->setCellValue('C2', 'Curso')->getStyle('C2')->getFont()->setBold(true);
    $sheet->setCellValue('D2', 'Token')->getStyle('D2')->getFont()->setBold(true);
    $sheet->setCellValue('E2', 'Estado')->getStyle('E2')->getFont()->setBold(true);
    
    $spreadsheet->getActiveSheet()->setAutoFilter('A2:E2');
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $i = 3;
    while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) { 
           
        $curso = $row['curso'];

        if($row["estado"] == 1){
            $estado = "Encuesta respondida";
        } elseif($row["estado"] == 0){
            $estado = "Encuesta no respondida";
        } 
       
       $sheet->setCellValueByColumnAndRow(1, $i, $row['nombres']);
       $sheet->setCellValueByColumnAndRow(2, $i, $row['apellidos']);
       $sheet->setCellValueByColumnAndRow(3, $i, $row['curso']);
       $sheet->setCellValueByColumnAndRow(4, $i, $row['token']);
       $sheet->setCellValueByColumnAndRow(5, $i,  $estado);
       
        $i++;
    
    }
    
   $writer = new Xls($spreadsheet);
   
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$curso.$hora_fecha .'.xls"'); 
header('Cache-Control: max-age=0');

$writer->save('php://output'); // download file 

}else if( $var_aleatoria == "establecimiento"){

    $conexion = connectDB_demos();
    $consulta = $conexion->query("SELECT a.ce_participantes_nombres AS nombres, 
    a.ce_participantes_apellidos AS apellidos,
    c.ce_curso_nombre AS curso,
    a.ce_participanes_token AS token,
    b.ce_establecimiento_nombre AS establecimiento,
    a.ce_estado_encuesta AS estado
    FROM ce_participantes a
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
    WHERE a.ce_establecimiento_id_ce_establecimiento = '$establecimiento_id' ");
    $conexion = NULL;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getProperties()
    ->setCreator("Jonathan Barrera")
    ->setLastModifiedBy("Jonathan Barrera")
    ->setTitle("2018 XLSX")
    ->setSubject("2018 XLSX")
    ->setDescription("Establecimiento")
    ->setKeywords("2018 php")
    ->setCategory("Junior");
$spreadsheet->getActiveSheet()->setTitle("Establecimiento");
$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(100);
$sheet->getTabColor()->setRGB('8CB517');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri Light');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $fontStyle = [
        'font' => [
            'size' => 12
        ]
        
      ];
      $spreadsheet->getActiveSheet()->mergeCells('A1:F1')->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      $sheet->setCellValue('A1', 'Lista de Estudiantes')->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A1')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle("A2:F2")->applyFromArray($fontStyle);
      $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      
    $sheet->setCellValue('A2','Nombres')->getStyle('A2')->getFont()->setBold(true);
    $sheet->setCellValue('B2','Apellidos')->getStyle('B2')->getFont()->setBold(true);
    $sheet->setCellValue('C2','Curso')->getStyle('C2')->getFont()->setBold(true);
    $sheet->setCellValue('D2','Token')->getStyle('D2')->getFont()->setBold(true);
    $sheet->setCellValue('E2','Establecimiento')->getStyle('E2')->getFont()->setBold(true);
    $sheet->setCellValue('F2','Estado')->getStyle('F2')->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->setAutoFilter('A2:F2');
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

    $i = 3;
while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $establecimiento =  $row["establecimiento"];
    if($row["estado"] == 1){
        $estado = "Encuesta respondida";
    } elseif($row["estado"] == 0){
        $estado = "Encuesta no respondida";
    }

    $sheet->setCellValueByColumnAndRow(1, $i, $row['nombres']);
    $sheet->setCellValueByColumnAndRow(2, $i, $row['apellidos']);
    $sheet->setCellValueByColumnAndRow(3, $i, $row['curso']);
    $sheet->setCellValueByColumnAndRow(4, $i, $row['token']);
    $sheet->setCellValueByColumnAndRow(5, $i, $row['establecimiento']);
    $sheet->setCellValueByColumnAndRow(6, $i,  $estado);

$i++;   
   
}

$writer = new Xls($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$establecimiento.$hora_fecha .'.xls"'); 
header('Cache-Control: max-age=0');

$writer->save('php://output'); // download file 


}else if($var_aleatoria == "encuestados"){  

    $conexion = connectDB_demos();
    $consulta = $conexion->query("SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos,
    c.ce_curso_nombre AS curso,
    a.ce_participanes_token AS token,
    d.ce_establecimiento_nombre AS establecimiento
    FROM ce_participantes a
    INNER JOIN ce_encuesta_resultado b ON a.ce_participanes_token = b.ce_participantes_token_fk
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    INNER JOIN ce_establecimiento d ON a.ce_establecimiento_id_ce_establecimiento = d.id_ce_establecimiento
    WHERE a.ce_participanes_token = b.ce_participantes_token_fk AND
    a.ce_establecimiento_id_ce_establecimiento = '$establecimiento_id' AND
    a.ce_docente_id_ce_docente = '$docente_id' AND
    a.ce_curso_id_ce_curso = '$curso_id'");
    $conexion = NULL;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getProperties()
    ->setCreator("Jonathan Barrera")
    ->setLastModifiedBy("Jonathan Barrera")
    ->setTitle("2018 XLSX")
    ->setSubject("2018 XLSX")
    ->setDescription("Encuesta Realizadas")
    ->setKeywords("2018 php")
    ->setCategory("Junior");
$spreadsheet->getActiveSheet()->setTitle("realizadas");
$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(100);
$sheet->getTabColor()->setRGB('8CB517');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri Light');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $fontStyle = [
        'font' => [
            'size' => 12
        ]
        
      ];
      $spreadsheet->getActiveSheet()->mergeCells('A1:D1')->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      $sheet->setCellValue('A1', 'Encuestas contestadas')->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A1')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle("A2:D2")->applyFromArray($fontStyle);
      $sheet->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      
    $sheet->setCellValue('A2', 'Nombres')->getStyle('A2')->getFont()->setBold(true);
    $sheet->setCellValue('B2', 'Apellidos')->getStyle('B2')->getFont()->setBold(true);
    $sheet->setCellValue('C2', 'Establecimiento')->getStyle('C2')->getFont()->setBold(true);
    $sheet->setCellValue('D2', 'Curso')->getStyle('D2')->getFont()->setBold(true);
  
    $spreadsheet->getActiveSheet()->setAutoFilter('A2:D2');
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
   

    $i = 3;
while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $curso = $row["curso"];
       
    $sheet->setCellValueByColumnAndRow(1, $i, $row['nombres']);
    $sheet->setCellValueByColumnAndRow(2, $i, $row['apellidos']);
    $sheet->setCellValueByColumnAndRow(3, $i, $row['establecimiento']);
    $sheet->setCellValueByColumnAndRow(4, $i, $row['curso']);

$i++;   
    
}

$writer = new Xls($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$curso.$hora_fecha .'.xls"'); 
header('Cache-Control: max-age=0');

$writer->save('php://output'); // download file 




}else if( $var_aleatoria == "no_encuestados"){

    $conexion = connectDB_demos();
    $consulta = $conexion->query("SELECT a.ce_participantes_nombres AS nombres, a.ce_participantes_apellidos AS apellidos,
    c.ce_curso_nombre AS curso,
    a.ce_participanes_token AS token,
    a.ce_estado_encuesta AS estado,
    d.ce_establecimiento_nombre AS establecimiento 
    FROM ce_participantes a
    INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
    INNER JOIN ce_establecimiento d ON a.ce_establecimiento_id_ce_establecimiento = d.id_ce_establecimiento
    WHERE 
    a.ce_establecimiento_id_ce_establecimiento = '$establecimiento_id' AND
    a.ce_docente_id_ce_docente = '$docente_id' AND
    a.ce_curso_id_ce_curso = '$curso_id' AND a.ce_estado_encuesta = 0");
    $conexion = NULL;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getProperties()
    ->setCreator("Jonathan Barrera")
    ->setLastModifiedBy("Jonathan Barrera")
    ->setTitle("2018 XLSX")
    ->setSubject("2018 XLSX")
    ->setDescription("Encuesta No Realizadas")
    ->setKeywords("2018 php")
    ->setCategory("Junior");
$spreadsheet->getActiveSheet()->setTitle("No Realizadas");
$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(100);
$sheet->getTabColor()->setRGB('8CB517');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri Light');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $fontStyle = [
        'font' => [
            'size' => 12
        ]
        
      ];
   

      $spreadsheet->getActiveSheet()->mergeCells('A1:D1')->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      $sheet->setCellValue('A1', 'Encuestas no contestadas')->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A1')
      ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $spreadsheet->getActiveSheet()->getStyle("A2:D2")->applyFromArray($fontStyle);
      $sheet->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
      $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
      $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBE2F1');
      
    $sheet->setCellValue('A2', 'Nombres')->getStyle('A2')->getFont()->setBold(true);
    $sheet->setCellValue('B2', 'Apellidos')->getStyle('B2')->getFont()->setBold(true);
    $sheet->setCellValue('C2', 'Establecimiento')->getStyle('C2')->getFont()->setBold(true);
    $sheet->setCellValue('D2', 'Curso')->getStyle('D2')->getFont()->setBold(true);
  
    $spreadsheet->getActiveSheet()->setAutoFilter('A2:D2');
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
   

    $i = 3;
while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
       $curso = $row["curso"];
       $sheet->setCellValueByColumnAndRow(1, $i, $row['nombres']);
       $sheet->setCellValueByColumnAndRow(2, $i, $row['apellidos']);
       $sheet->setCellValueByColumnAndRow(3, $i, $row['establecimiento']);
       $sheet->setCellValueByColumnAndRow(4, $i, $row['curso']);

$i++;   

}

$writer = new Xls($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$curso.$hora_fecha .'.xls"'); 
header('Cache-Control: max-age=0');

$writer->save('php://output'); // download file 
    
}

?>
    
