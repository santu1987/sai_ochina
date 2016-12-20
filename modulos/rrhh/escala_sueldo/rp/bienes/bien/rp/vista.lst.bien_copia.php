<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();
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
$where = " WHERE 1 = 1 ";
$id_mayor = "";
if(isset($_GET['id_mayor']))
$id_mayor = $_GET['id_mayor'];
$id_tipo_bien = "";
if(isset($_GET['id_tipo_bien']))
$id_tipo_bien = $_GET['id_tipo_bien'];
$id_custodio = "";
if(isset($_GET['id_custodio']))
$id_custodio = $_GET['id_custodio'];
$id_bienes = "";
if(isset($_GET['id_bienes']))
$id_bienes = $_GET['id_bienes'];
if($id_mayor!='')
	$where.= " AND bienes.id_mayor = '$id_mayor' "; 
if($id_tipo_bien!='')
	$where.= " AND bienes.id_tipo_bienes = $id_tipo_bien "; 	
if($id_custodio!='')
	$where.= " AND bienes.id_custodio = $id_custodio ";
if($id_bienes!='')
	$where.= " AND bienes.id_bienes = $id_bienes ";	

$Sql="SELECT id_bienes, codigo_bienes, serial_bien, bienes.nombre as bien, custodio.nombre as custodio, valor_compra FROM bienes INNER JOIN custodio ON bienes.id_custodio = custodio.id_custodio ".$where." AND bienes.id_organismo = $_SESSION[id_organismo] ORDER BY id_bienes";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",170,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'LISTADO DE BIENES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(37,	6,			'CODIGO',		1,0,'L',1);
			$this->Cell(37,	6,			'SERIAL',			1,0,'L',1);
			$this->Cell(37,	6,			'BIEN',			1,0,'L',1);
			$this->Cell(37,	6,			'CUSTODIO',			1,0,'L',1);
			$this->Cell(37,	6,			'VALOR',			1,0,'L',1);
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
			$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[name]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		$pdf->Cell(37,	6,		$row->fields("codigo_bienes"),	1,0,'L',1);
		$pdf->Cell(37,	6,		$row->fields("serial_bien"),	1,0,'L',1);
		$pdf->Cell(37,	6,		$row->fields("bien"),	1,0,'L',1);
		$pdf->Cell(37,	6,		$row->fields("custodio"),	1,0,'L',1);
		$pdf->Cell(37,	6,		substr($row->fields("valor_compra"),1,strlen($row->fields("valor_compra"))),	1,0,'L',1);
		$pdf->Ln(6);
		$row->MoveNext();
	}
	
	$pdf->Output();
}
?>