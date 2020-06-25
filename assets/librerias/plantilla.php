<?php
	require 'fpdf.php';
  define('FPDF_FONTPATH','font/');

	class PDF extends FPDF
	{
		function Header()
		{
			//$this->Image('assets/img/logo.jpg', 5, 5, 30 );
                        $this->Image('assets/img/image.jpg','0','0','300','300','JPG');	
			$this->SetFont('Arial','B',20);                        
			$this->Cell(30);
			$this->Cell(120,10, utf8_decode('Compromiso Escolar'),0,0,'C');
                        //$this->SetTextColor(250,250,250);
			$this->Ln(20);
		}

		function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I', 8);
			$this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
		}
	}
?>
