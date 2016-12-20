<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$custodio=$_GET['acti_custo_rp_id_custodio'];
$Sql="SELECT 
		codigo_bienes,
		serial_bien,
		descripcion_general,
		valor_compra
	  FROM
	  	bienes
	  WHERE 
	  	id_organismo = $_SESSION[id_organismo]
	  AND
	  	bienes.id_custodio=$custodio
	ORDER BY 
		id_bienes";
$row=& $conn->Execute($Sql);
$sql="SELECT 
		nombre,
		cedula
	  FROM
	  	custodio
	  WHERE 
	  	id_organismo = $_SESSION[id_organismo]
	  AND
	  	id_custodio=$custodio
	";
$row2=& $conn->Execute($sql);
$sql_sum="SELECT 
		SUM(valor_compra) as total
	  FROM
	  	bienes
	  WHERE 
	  	id_organismo = $_SESSION[id_organismo]
	  AND
	  	id_custodio=$custodio
	";
$row3=& $conn->Execute($sql_sum);
$total=$row3->fields("total");
$custo=$row2->fields("nombre");
$cedula=$row2->fields("cedula");
$subtitulo="CUSTODIO:".$cedula." ".$custo;
//************************************************************************
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			global $subtitulo;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
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
			$this->Cell(0,10,'ACTIVOS FIJOS POR CUSTODIO',0,0,'C');
			$this->Ln(10);	
			$this->SetFont('Arial','B',12);
			$this->Cell(0,10,$subtitulo,0,0,'L');
			$this->Ln(8);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,0,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(120,0,'Impreso por: '.str_replace('<br />',' ',$_SESSION[name]),0,0,'C');
			$this->Cell(80,0,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(115,200,strtoupper($_SESSION['usuario']),40,6);
		}
	}
	//************************************************************************
	$pdf=new PDF('L');
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Times','B',8);
	$pdf->SetLineWidth(0.3);
	$pdf->SetFillColor(0);
	$pdf->SetTextColor(255);
	$pdf->Cell(30,	6, 'ACTIVO',	1,0,'C',1);
	$pdf->Cell(30,	6, 'SERIAL',	1,0,'C',1);
	$pdf->Cell(180,	6,	'DESCRIPCIÓN',	1,0,'C',1);
	$pdf->Cell(30,	6,	'VALOR',	1,0,'C',1);
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetTextColor(0);
	while (!$row->EOF) 
	{
		$valor=$row->fields("valor_compra");
		$valor=substr($valor,1,20);
		$pdf->Cell(30,		6,		$row->fields("codigo_bienes"),1,0,'L',1);
		$pdf->Cell(30,		6,		$row->fields("serial_bien"),1,0,'L',1);
		$pdf->Cell(180,	6,		$row->fields("descripcion_general"),1,0,'L',1);
		$pdf->Cell(30,	6,		$valor,1,0,'L',1);
		$pdf->Ln(6);
		$row->MoveNext();
	}
	$pdf->SetFillColor(230);
		$pdf->SetTextColor(0);
		$pdf->Cell(240,	6,	'TOTAL CUSTODIO',	1,0,'R',1);
		$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);
		$pdf->Cell(30,	6,	substr($total,1,20),	1,0,'L',1);
	$pdf->Output();

?>