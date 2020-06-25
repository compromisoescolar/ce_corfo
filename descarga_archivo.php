<?php
header("Content-disposition: attachment; filename=Compromiso_Escolar_Demo.xlsx");
header("Content-type: application/excel");
readfile("documentos/Compromiso_Escolar_Demo.xlsx");

