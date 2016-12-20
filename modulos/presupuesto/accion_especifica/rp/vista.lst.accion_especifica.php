<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$Sql="SELECT * FROM accion_especifica WHERE ano = '".date('Y')."' ORDER BY id_accion_especifica ";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	//require('../../../../utilidades/fpdf153/fpdf.php');
	require('../../../../utilidades/fpdf153/code128.php');
	//************************************************************************
	class PDF extends PDF_Code128 
	{
		//Cabecera de página
		function Header()
		{		
			//$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",170,10,29);
						
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'LISTADO DE ACCIONES ESPECÍFICAS',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Arial','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(87,		6,			'ACCIÓN ESPECÍFICA',		1,0,'L',1);
			$this->Cell(104,	6,			'OBSERVACIÓN',			1,0,'L',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(62,3,'Impreso por ',0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			/*$this->SetTextColor(0);
			$this->SetFont('barcode','',11);
			
			$this->Cell(65,3,'',0,0,'L');*/
			$this->SetFillColor(0);
			$this->Code128(90,285,strtoupper($_SESSION[usuario].$_SESSION[id_unidad_ejecutora]),30,5);
			// $this->SetXY(50,95);
			//$this->SetFillColor(255);
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	//$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(0);
		while (!$row->EOF) 
	{
		$contar_letra = strlen($row->fields("denominacion"));
		$contar_comentario = strlen($row->fields("comentario"));
		$salto1 = ceil($contar_letra / 62);
		$salto2 = ceil($contar_comentario / 82);
		if ($salto1 >= $salto2){
			$coordena = ($salto1 * 6);
			$coordena1 = 6;
		}else{
			$coordena = ($salto2 * 6);
			$coordena1 = $coordena;
		}
		
		$pdf->Cell(10,		$coordena,		$row->fields("codigo_accion_especifica"),		1,0,'C',1);
		if($salto2 ==0){
			$y=$pdf->GetY();
			$pdf->MultiCell(77,		$coordena1,		utf8_decode($row->fields("denominacion"))/*$contar_letra.'  '.$salto1*/,				'TRL','L',1);
			$pdf->SetXY(97,$y);
			$pdf->MultiCell(104,	$coordena,		utf8_decode($row->fields("comentario"))/*$contar_comentario.'  '.$salto2*/,						1,'L',1);
		}else{
			$y=$pdf->GetY();
			
			$pdf->MultiCell(77,		6,		utf8_decode($row->fields("denominacion"))/*$contar_letra.'  '.$salto1*/,				'TRL','L',1);
			$pdf->SetXY(97,$y);
			$y=$pdf->GetY();
			
			$pdf->MultiCell(104,	6,		utf8_decode($row->fields("comentario"))/*$contar_comentario.'  '.$salto2*/,						1,'L',1);
			
		}
		//$pdf->Ln(0);
		//$coordena = ($salto * 6);
		$x=$pdf->GetX();
	$y_line = $y + $coordena;
	//$pdf->Line(211,100,241,50);
	$pdf->Line($x,$y_line,97,$y_line);
		
		$row->MoveNext();
	}
/*	$pdf->SetFont('Arial','',4);
	$pdf->SetFillColor(0);
	$code='CODE 128';
	$pdf->SetTextColor(255);
	$pdf->Code128(50,270,$code,80,20);*/


	
	$pdf->Output();
}
?>