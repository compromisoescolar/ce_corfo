<?php 

require_once"../assets/librerias/vendor/autoload.php";

$timezone  = -3;
$hora  =  gmdate("Y_m_j", time() + 3600*($timezone+date("I")));
$hora_fecha =  gmdate("Ymj-His", time() + 3600*($timezone+date("I")));

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getProperties()
    ->setCreator("Jonathan Barrera")
    ->setLastModifiedBy("Jonathan Barrera")
    ->setTitle("Office 2016 XLSX")
    ->setSubject("Office 2016 XLSX")
    ->setDescription("Documento que muestra los negocios pre cerrados de CRM_ODOO")
    ->setKeywords("Office 2018 php")
    ->setCategory("Junior");
$spreadsheet->getActiveSheet()->setTitle("Precerrados $hora");
$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(100);
$sheet->getTabColor()->setRGB('8CB517');
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri Light');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

$fontStyle = [
  'font' => [
      'size' => 14
  ]
  
];
$spreadsheet->getActiveSheet()->getStyle("A1:E1")->applyFromArray($fontStyle);
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');
$spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
$spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('8CB517');

$array = array('A','B','C','D');
$spreadsheet->getActiveSheet()->setAutoFilter('A1:E1');
for($i=1;$i<=1;$i++){
    foreach($array as $recorre){
        $sheet->setCellValue(''.$recorre.''.$i.'', 'Estado')->getStyle(''.$recorre.''.$i.'')->getFont()->setBold(true);
        $recorre++;
    }

$sheet->setCellValue('B'.$i.'', 'Equipo')->getStyle('B1')->getFont()->setBold(true);
$sheet->setCellValue('C'.$i.'', 'Correo')->getStyle('C1')->getFont()->setBold(true);
$sheet->setCellValue('D'.$i.'', 'Cliente/Oportunidad')->getStyle('D1')->getFont()->setBold(true);
$sheet->setCellValue('E'.$i.'', 'Monto')->getStyle('E1')->getFont()->setBold(true);
}
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(11.50);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18.50);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(21.13);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(77.38);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(19.00);

$spreadsheet->getActiveSheet()->getComment('A1')->getText()->createTextRun('Nombre Completo');

//pc jonathan
$dbconn3 = pg_connect("host=localhost port=5432 dbname=espex_demos user= postgres  password=jona1994");
//pc empresa
//$dbconn3 = pg_connect("host=10.0.0.41 port=5432 dbname=Espex_Clientes user=openpg password=openpgpwd");
$result = pg_query($dbconn3, "SELECT crm_lead.id,crm_lead.create_date,crm_lead.date_closed, crm_stage.NAME AS estado,
crm_team.NAME AS equipo,
res_users.login AS correo,
res_partner.NAME AS cliente,
crm_lead.NAME AS oportunidad,
crm_lead.planned_revenue AS monto
FROM crm_lead
INNER JOIN crm_stage ON crm_lead.stage_id = crm_stage.id
INNER JOIN crm_team ON crm_lead.team_id = crm_team.id
INNER JOIN res_users ON crm_lead.user_id = res_users.id
INNER JOIN res_partner ON crm_lead.partner_id = res_partner.id
WHERE crm_lead.stage_id = 3 AND DATE_PART('year',crm_lead.create_date) = 2018 ORDER BY crm_team.NAME ASC ");
$dbconn3 = NULL;
$i = 2;
while ($row = pg_fetch_assoc($result)) {
    if($i > 2){
        $spreadsheet->getActiveSheet()->getRowDimension($i)->setOutlineLevel(1);
    }
$sheet->setCellValue('A'.$i.'', $row["estado"]);
$spreadsheet->getActiveSheet()->getStyle('A'.$i.'')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('A'.$i.'')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('A'.$i.'')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('A'.$i.'')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('A'.$i.'');
$sheet->setCellValue('B'.$i.'', $row["equipo"]);
$spreadsheet->getActiveSheet()->getStyle('B'.$i.'')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('B'.$i.'')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('B'.$i.'')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('B'.$i.'')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('B'.$i.'');
$sheet->setCellValue('C'.$i.'', $row["correo"]);
$spreadsheet->getActiveSheet()->getStyle('C'.$i.'')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('C'.$i.'')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('C'.$i.'')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('C'.$i.'')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('C'.$i.'');

$cliente = $row["cliente"].', '.$row["oportunidad"];
$sheet->setCellValue('D'.$i.'', $cliente);
$spreadsheet->getActiveSheet()->getStyle('D'.$i.'')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('D'.$i.'')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('D'.$i.'')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('D'.$i.'')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('D'.$i.'');
$sheet->setCellValue('E'.$i.'', $row["monto"]);
$spreadsheet->getActiveSheet()->getStyle('E'.$i.'')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('E'.$i.'')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('E'.$i.'')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('E'.$i.'')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$spreadsheet->getActiveSheet()->getStyle('E'.$i.'');
$spreadsheet->getActiveSheet()->getCell('E'.$i.'')->getCalculatedValue();
$spreadsheet->getActiveSheet()->getStyle('E'.$i.'')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);

    $i++;
  }
  $total = $i+1;
  $sheet->getStyle('E'. $total.'')->getFont()->setBold(true);
  $fontStyle = [
    'font' => [
        'size' => 16
    ]
];
  $spreadsheet->getActiveSheet()->getStyle('E'. $total.'')->applyFromArray($fontStyle);
  $spreadsheet->getActiveSheet()->getStyle('E'. $total.'')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
  $spreadsheet->getActiveSheet()->getStyle('E'. $total.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('8CB517');
  $sheet->setCellValue('E'. $total.'', '=SUM(E2:E'.$i.')');
  $spreadsheet->getActiveSheet()->getStyle('E'. $total.'')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);

// Create a new worksheet called "My Data"
$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'My Data');

// Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
$spreadsheet->addSheet($myWorkSheet, 1);
$spreadsheet->setActiveSheetIndex(1);
$spreadsheet->setActiveSheetIndex(1)->mergeCells('A1:F1')
                    ->setCellValue('A1', 'REPORTE DE INGRESOS POR CONDUCTOR')
                    ->setCellValue('A2', 'ID SERVICIO')
                    ->setCellValue('B2', 'FECHA')
                    ->setCellValue('C2', 'CONDUCTOR')
                    ->setCellValue('D2', 'DESTINO')
                    ->setCellValue('E2', 'TIPO')
                    ->setCellValue('F2', 'PRECIO');

$spreadsheet->setActiveSheetIndex(0);
$writer = new Xlsx($spreadsheet);
$writer->save('reportes/precerrados_'.$hora_fecha.'.xlsx');

////////////////DESCARGAR////////

header("Content-disposition: attachment; filename=precerrados_".$hora_fecha .".xlsx");
header("Content-type: application/excel");
readfile("reportes/precerrados_".$hora_fecha .".xlsx");
