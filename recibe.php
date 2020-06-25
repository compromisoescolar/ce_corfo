<?php
	// If you need to parse XLS files, include php-excel-reader
  require('assets/librerias/simplexlsx.class.php');
  
if (isset($_FILES['file'])) {
$xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
echo '<h1>Resultado tabla hoja 1</h1>';
echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';

list($cols,) = $xlsx->dimension();

foreach( $xlsx->rows() as $k => $r) {
    if ($k == 0) continue; // skip first row
    echo '<tr>';
    for( $i = 0; $i < $cols; $i++)
    {
        echo '<td>'.( (isset($r[$i])) ? $r[$i] : '&nbsp;' ).'</td>';
    }
    echo '</tr>';
}
echo '</table>';

echo '<h1>Resultado tabla de hoja 2</h1>';
echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';

list($cols,) = $xlsx->dimension();

foreach( $xlsx->rows(2) as $k => $r) {
    if ($k == 0) continue; // skip first row
    echo '<tr>';
    for( $i = 0; $i < $cols; $i++)
    {
        echo '<td>'.( (isset($r[$i])) ? $r[$i] : '&nbsp;' ).'</td>';
    }
    echo '</tr>';
}
echo '</table>';

echo '<h1>Resultado tabla de hoja 3</h1>';
echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';

list($cols,) = $xlsx->dimension();

foreach( $xlsx->rows(3) as $k => $r) {
    if ($k == 0) continue; // skip first row
    echo '<tr>';
    for( $i = 0; $i < $cols; $i++)
    {
        echo '<td>'.( (isset($r[$i])) ? $r[$i] : '&nbsp;' ).'</td>';
    }
    echo '</tr>';
}
echo '</table>';



}
