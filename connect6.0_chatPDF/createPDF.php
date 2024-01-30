<?php 

require('fpdf/fpdf.php'); 
require_once("db_connect.php");

class PDF extends FPDF { 

	// Page header 
	function Header() { 
		
		// Add logo to page 
		$this->Image('hexa.png',10,8,33); 
		
		// Set font family to Arial bold 
		$this->SetFont('Arial','B',20); 
		
		// Move to the right 
		$this->Cell(80); 
		
		// Header 
		$this->Cell(50,10,'',1,0,'C'); 
		
		// Line break 
		$this->Ln(20); 
	} 

	// Page footer 
	function Footer() { 
		
		// Position at 1.5 cm from bottom 
		$this->SetY(-15); 
		
		// Arial italic 8 
		$this->SetFont('Arial','I',8); 
		
		// Page number 
		$this->Cell(0,10,'Page ' . 
			$this->PageNo() . '/{nb}',0,0,'C'); 
	} 
} 

// Instantiation of FPDF class 
$pdf = new PDF(); 

// Define alias for number of pages 
$pdf->AliasNbPages(); 
$pdf->AddPage(); 
$pdf->SetFont('Times','',14); 

$stmt_login="SELECT `created_date`,`senderName`,`message`,`messageReceiver`,`user`,campaign FROM `internalChat` where created_date >='2024-01-16 00:00:00' and created_date <='2024-01-16 23:59:59' order by created_date asc";
$rslt_login= mysqli_query($conn,$stmt_login);
while($row = mysqli_fetch_assoc($rslt_login)){
	//$senderName = $row["senderName"];
	//$messageReceiver = $row["messageReceiver"];
	if($row["senderName"] == 'sauravi'){		
		$pdf->Cell(0, 10, $row["senderName"]. ' : ' . $row["message"], 0, 1);
		//$pdf->Cell(0, 10, 'line number ' . $i, 0, 1); 
	}else if($row["messageReceiver"] == 'sauravi'){
		$pdf->Cell(0, 10, $row["senderName"]. ' : ' . $row["message"], 0, 1);					
	}
}
$pdf->Output(); 

?>
