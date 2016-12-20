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
$id_tipo='';
$fecha_desde = '';
$fecha_hasta = '';
if($_GET['fecha_desde']!='')
	$fecha_desde = $_GET['fecha_desde'];
if($_GET['fecha_hasta']!='')
	$fecha_hasta = $_GET['fecha_hasta'];
$where = " WHERE 1 = 1 ";
if($_GET['id_tipo']!='')
	$id_tipo = $_GET['id_tipo'];
if($id_tipo!='')
	$where.= " AND bienes.id_tipo_bienes = $id_tipo ";
if($fecha_desde!='' && $fecha_hasta!='')
	$where.= " AND bienes.fecha_compra BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."'";
$Sql="SELECT 
				bienes.id_bienes,
				bienes.codigo_bienes,
				bienes.descripcion_general,
				bienes.marca,
				bienes.serial_bien,
				sitio_fisico.nombre as sitio,
				unidad_ejecutora.nombre as unidad,
				estatus_bienes.nombre as estatu
				
			FROM 
				bienes
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				estatus_bienes
			ON
				estatus_bienes.id_estatus_bienes=bienes.estatus_bienes
			INNER JOIN
				tipo_bienes
			ON
				tipo_bienes.id_tipo_bienes = bienes.id_tipo_bienes
			".$where."
			AND
				bienes.id_organismo = $_SESSION[id_organismo]
		";
$row=& $conn->Execute($Sql);
$sql_tipo = "SELECT nombre FROM tipo_bienes WHERE tipo_bienes.id_tipo_bienes = $id_tipo AND tipo_bienes.id_organismo = $_SESSION[id_organismo]";
$row_tipo=& $conn->Execute($sql_tipo);
$_SESSION['nom_tip'] = $row_tipo->fields("nombre");
$sql_exi = "SELECT 
				count(id_bienes) as exi
			FROM 
				bienes
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				estatus_bienes
			ON
				estatus_bienes.id_estatus_bienes=bienes.estatus_bienes
			INNER JOIN
				tipo_bienes
			ON
				tipo_bienes.id_tipo_bienes = bienes.id_tipo_bienes
			".$where."
			AND
				bienes.id_organismo = $_SESSION[id_organismo]"; 
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
			global $exi;
			global $fecha_desde;
			global $fecha_hasta;
			//$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
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
			if($exi!=0){
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'ACTIVOS POR TIPO DE ACTIVO',0,1,'C');
			$this->SetFont('Arial','B',10);
			$this->Cell(31,10,'TIPO DE ACTIVO. ',0,0,'L');
			$this->Cell(20,10,strtoupper($_SESSION['nom_tip']),0,0,'L');
			$x = $this->GetX();
			$y = $this->GetY();
			if($fecha_desde!='' && $fecha_hasta!=''){
				$this->SetXY($x+130,$y);
				$this->Cell(35,10,"DE LA FECHA DEL: ",0,0,'L');
				$this->Cell(20,10,$fecha_desde,0,0,'L');
				$this->Cell(20,10,"HASTA EL: ",0,0,'L');
				$this->Cell(20,10,$fecha_hasta,0,0,'L');
			}
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(30,		6,			'CODIGO',		1,0,'C',1);
			$this->Cell(90,	6,			'DESCRIPCION',			1,0,'C',1);
			$this->Cell(38,	6,			'MARCA',			1,0,'C',1);
			$this->Cell(40,	6,			'SERIAL',			1,0,'C',1);
			$this->Cell(40,	6,			'SITIO FISICO',			1,0,'C',1);
			$this->Cell(40,	6,			'ESTADO',			1,0,'C',1);
			$this->Ln(6);
			}
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
			$this->Cell(85,3,'Impreso por:',0,0,'R');
			$this->Cell(125,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(130,200,strtoupper($_SESSION['usuario']),40,6);
			//$this->SetFont('barcode','',6);
			//$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF('L');
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		$pdf->Cell(30,		20,		$row->fields("codigo_bienes"),		1,0,'L',1);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->MultiCell(90,		3,		strtoupper($row->fields("descripcion_general")),				0,'LBR','L');
		$pdf->SetXY($x+90,$y);
		$pdf->Cell(40,	20,		$row->fields("marca"),						1,0,'L',1);
		$pdf->Cell(33,	20,		$row->fields("serial_bien"),						1,0,'L',1);
		$pdf->Cell(50,	20,		$row->fields("sitio"),						1,0,'L',1);
		$pdf->Cell(35,	20,		$row->fields("estatu"),						1,1,'L',1);
		$y = $pdf->GetY();
		$pdf->Line($x,$y,$x+90,$y);
		//$pdf->Ln(16);
		$row->MoveNext();
	}
	if($exi==0){
		$pdf->Ln(60);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,70,285,70);
		$pdf->Cell(250,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",178,80,50);
		$pdf->Line(10,140,285,140);
	}
	unset($_SESSION['nom_tip']);
	$pdf->Output();
//}
?>