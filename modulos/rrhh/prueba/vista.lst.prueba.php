<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD
session_start();
require('../../../utilidades/fpdf153/code128.php');

require_once('../../../controladores/db.inc.php');
require_once('../../../utilidades/adodb/adodb.inc.php');
require_once('../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$monto=$_GET['monto'];
$dias=$_GET['dias'];
$Sql="SELECT 
			sso($monto,$dias) as seguro,
			pf($monto,$dias) as paro,
			caja($monto) as caja,
			lph($monto) as ley,
			fesp($monto) as especial,
			fondo($monto) as fondo
		";
$row=& $conn->Execute($Sql);
//************************************************************************

	require('../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{		
			$this->Image("../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'PRUEBA FUNCIONES',0,0,'C');
			$this->Ln(15);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(30,6,'SEGURO SOCIAL',1,0,'C',1);
			$this->Cell(30,6,'PARO FORZOSO',1,0,'C',1);
			$this->Cell(30,6,'CAJA',1,0,'C',1);
			$this->Cell(30,6,'FONDO ESPECIAL',1,0,'C',1);
			$this->Cell(30,6,'FONDO',1,0,'C',1);
			$this->Cell(30,6,'LEY POLITICA HAB.',1,0,'C',1);
			$this->Cell(15,6,'TOTAL',1,0,'C',1);
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
			$this->Cell(25,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(80,3,'Impreso por:',0,0,'R');
			$this->Cell(83,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(87,286,strtoupper($_SESSION['usuario']),40,6);
			//$this->SetFont('barcode','',6);
			//$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	/*while (!$row->EOF) 
	{
		$pdf->Cell(40,6,$row->fields("seguro"),1,0,'L',1);
		$pdf->Ln(6);
		$row->MoveNext();
	}
	$this->Cell(30,6,'PARO FORZOSO',1,0,'C',1);
			$this->Cell(30,6,'CAJA',1,0,'C',1);
			$this->Cell(30,6,'FONDO ESPECIAL',1,0,'C',1);
			$this->Cell(30,6,'FONDO',1,0,'C',1);
			$this->Cell(30,6,'LEY POLITICA HAB.',1,0,'C',1);
	*/
	//round($valor * 100) / 100;
	$pdf->Cell(30,6,round($row->fields("seguro")*100)/100,1,0,'L',1);
	$pdf->Cell(30,6,round($row->fields("paro")*100)/100,1,0,'L',1);
	$pdf->Cell(30,6,round($row->fields("caja")*100)/100,1,0,'L',1);
	$pdf->Cell(30,6,round($row->fields("especial")*100)/100,1,0,'L',1);
	$pdf->Cell(30,6,round($row->fields("fondo")*100)/100,1,0,'L',1);
	$pdf->Cell(30,6,round($row->fields("ley")*100)/100,1,0,'L',1);
	$pdf->Cell(15,6,(round($row->fields("seguro")*100)/100)+(round($row->fields("paro")*100)/100)+(round($row->fields("caja")*100)/100)+(round($row->fields("especial")*100)/100)+(round($row->fields("fondo")*100)/100)+(round($row->fields("ley")*100)/100),1,0,'L',1);
	$pdf->Ln(6);
	$pdf->Output();
?>