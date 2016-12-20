<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();
require('../../../../utilidades/fpdf153/code128.php');

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
//
$where = " WHERE 1 = 1 ";
$id_tipo_bien = "";
if(isset($_REQUEST['id_tipo_bien']))
	$id_tipo_bien = $_REQUEST['id_tipo_bien'];
$id_bienes = "";
if(isset($_REQUEST['id_bienes']))
	$id_bienes = $_REQUEST['id_bienes'];
	
if($id_tipo_bien!='')
	$where.= " AND id_tipo_bienes = $id_tipo_bien ";
if($id_bienes)
	$where.=" AND id_bienes = $id_bienes ";
//
$Sql="SELECT id_bienes, nombre, valor_compra, valor_rescate, fecha_compra, codigo_bienes, vida_util FROM bienes ". $where ." AND estatus_bienes !=3 AND estatus_bienes!=5 AND id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($Sql);
$sql_exi = "SELECT COUNT(id_bienes) as exi FROM bienes ".$where." AND estatus_bienes !=3 AND estatus_bienes !=5 AND id_organismo = $_SESSION[id_organismo]";
$row_exi =& $conn->Execute($sql_exi);
$exi = $row_exi->fields("exi");
//************************************************************************
//if (!$row->EOF)
//{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{		
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
			$this->Cell(0,10,'LISTADO DEPRECIACION MENSUAL DE BIEN(ES)',0,0,'C');
			$this->Ln(10);
			
			
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-20);
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
	while (!$row->EOF) 
	{
		
		$pdf->SetFont('Times','B',8);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(0) ;
		$pdf->SetTextColor(255);
		
		$pdf->Cell(30,	6,			'CODIGO',		1,0,'C',1);
		$pdf->Cell(40,	6,			'BIEN',		1,0,'C',1);
		$pdf->Cell(30,6,			'VALOR COMPRA',			1,0,'C',1);
		$pdf->Cell(30,6,			'VALOR RESCATE',			1,0,'C',1);
		$pdf->Cell(30,6,			'FECHA COMPRA',			1,0,'C',1);
		$pdf->Cell(30,6,			'VIDA UTIL',			1,0,'C',1);
		$pdf->Ln(6);
		
		$pdf->SetFont('arial','',7);
		$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);
		
		
		$pdf->Cell(30,	6,		$row->fields("codigo_bienes"),		1,0,'L',1);
		$pdf->Cell(40,	6,		$row->fields("nombre"),		1,0,'L',1);
		$pdf->Cell(30,	6,		substr($row->fields("valor_compra"),1,strlen($row->fields("valor_compra"))),				1,0,'L',1);
		$pdf->Cell(30,	6,		substr($row->fields("valor_rescate"),1,strlen($row->fields("valor_rescate"))),						1,0,'L',1);
		$pdf->Cell(30,	6,		substr($row->fields("fecha_compra"),8,2)."-".substr($row->fields("fecha_compra"),5,2)."-".substr($row->fields("fecha_compra"),0,4),						1,0,'L',1);
		$pdf->Cell(30,	6,		$row->fields("vida_util"),						1,0,'L',1);
		$id_bienes= $row->fields("id_bienes");
		$pdf->Ln(6);
		$row->MoveNext();
		
		
		$sql = "SELECT valor_depreciacion_mensual, valor_depreciacion_acumula, valor_libros, fecha_depreciacion FROM depreciacion_mensual WHERE id_bienes = $id_bienes AND id_organismo = $_SESSION[id_organismo]";
		$bus =& $conn->Execute($sql);
		
		if($bus->fields("valor_depreciacion_mensual")!=''){
			
			$pdf->SetFont('Times','B',8);
			$pdf->SetLineWidth(0.3);
			$pdf->SetFillColor(0) ;
			$pdf->SetTextColor(255);
		
			$pdf->Cell(40,	6,			'DEPRECIACION MENSUAL',		1,0,'C',1);
			$pdf->Cell(50,6,			'DEPRECIACION ACUMULADA',			1,0,'C',1);
			$pdf->Cell(50,6,			'VALOR EN LIBROS',			1,0,'C',1);
			$pdf->Cell(50,6,			'FECHA DEPRECIACION',			1,0,'C',1);
			$pdf->Ln(6);	
			
		//
	
		$pdf->SetFont('arial','',7);
		$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);		
		
		while (!$bus->EOF){		
			
			$pdf->Cell(42,	6,		substr($bus->fields("valor_depreciacion_mensual"),1,strlen($bus->fields("valor_depreciacion_mensual"))),		1,0,'C',1)
;
			$pdf->Cell(50,	6,		substr($bus->fields("valor_depreciacion_acumula"),1,strlen($bus->fields("valor_depreciacion_acumula"))),		1,0,'C',1);
			$pdf->Cell(50,	6,		substr($bus->fields("valor_libros"),1,strlen($bus->fields("valor_libros"))),		1,0,'C',1);
			$pdf->Cell(48,	6,		substr($bus->fields("fecha_depreciacion"),8,2)."-".substr($bus->fields("fecha_depreciacion"),5,2)."-".substr($bus->fields("fecha_depreciacion"),0,4),		1,1,'C',1);
			$bus->MoveNext();
		} 
		
		}
		$pdf->Ln(2);
	}
	
	if($exi==0){
		$pdf->Ln(90);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,90,200,90);
		$pdf->Cell(175,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.JPG",142,105,50);
		$pdf->Line(10,180,200,180);
	}
	$pdf->Output();
//}
?>