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
$id_sitio = '';
$fecha_desde = '';
$fecha_hasta = '';
if($_GET['fecha_desde']!='')
	$fecha_desde = $_GET['fecha_desde'];
if($_GET['fecha_hasta']!='')
	$fecha_hasta = $_GET['fecha_hasta'];
$where = " WHERE 1 = 1 ";
if($_GET['id_sitio']!='')
	$id_sitio = $_GET['id_sitio'];
if($id_sitio!='')
	$where.= " AND bienes.id_sitio_fisico = $id_sitio ";
if($fecha_desde!='' && $fecha_hasta!='')
	$where.= " AND bienes.fecha_compra BETWEEN '$fecha_desde' AND '$fecha_hasta' ";
$Sql="SELECT 
				bienes.id_bienes,
				bienes.descripcion_general,
				bienes.codigo_bienes,
				bienes.fecha_compra,
				custodio.nombre,
				\"orden_compra_servicioE\".numero_orden_compra_servicio,
				bienes.numero_factura,
				bienes.valor_compra
			FROM 
				bienes
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				\"orden_compra_servicioE\"
			ON 
				\"orden_compra_servicioE\".id_orden_compra_servicioe=bienes.id_orden_compra_servicioe
			".$where."
			AND
				bienes.id_organismo = $_SESSION[id_organismo]
			AND 
				bienes.estatus_bienes!=3 
			AND 
				bienes.estatus_bienes!=5
		";
$row=& $conn->Execute($Sql);
$sql_total = "SELECT SUM(valor_compra) as total FROM bienes ".$where." AND bienes.id_organismo = $_SESSION[id_organismo] AND bienes.estatus_bienes!=3 AND bienes.estatus_bienes!=5 ";
$row_total=& $conn->Execute($sql_total);
$sql_div = "SELECT sitio_fisico.nombre as sitio, unidad_ejecutora.nombre as unidad FROM sitio_fisico INNER JOIN unidad_ejecutora ON unidad_ejecutora.id_unidad_ejecutora=sitio_fisico.id_unidad_ejecutora WHERE sitio_fisico.id_sitio_fisico = $id_sitio AND sitio_fisico.id_organismo = $_SESSION[id_organismo]";
$row_div =& $conn->Execute($sql_div); 
$_SESSION['nom_sit'] = $row_div->fields("sitio");
$_SESSION['nom_uni'] = $row_div->fields("unidad");
$sql_exi = "SELECT 
				count(id_bienes) as exi
			FROM 
				bienes
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				\"orden_compra_servicioE\"
			ON 
				\"orden_compra_servicioE\".id_orden_compra_servicioe=bienes.id_orden_compra_servicioe
			".$where."
			AND
				bienes.id_organismo = $_SESSION[id_organismo]
			AND 
				bienes.estatus_bienes!=3 
			AND 
				bienes.estatus_bienes!=5";
$row_exi =& $conn->Execute($sql_exi);
$exi = $row_exi->fields('exi');
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
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln(10);
			if($exi!=0){
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'ACTIVOS FIJOS POR SITIO FÍSICO',0,1,'C');
			$this->SetFont('Arial','B',10);
			$this->Cell(24,10,'JEFE DIV. DE ',0,0,'L');
			$this->Cell(24,10,strtoupper($_SESSION['nom_uni']),0,1,'L');
			$this->Cell(24,10,'SITIO FISICO ',0,0,'L');
			$this->Cell(24,10,strtoupper($_SESSION['nom_sit']),0,0,'L');
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
			$this->Cell(20,		6,			'RENGLÓN',		1,0,'C',1);
			$this->Cell(60,	6,			'DESCRIPCIÓN',			1,0,'C',1);
			$this->Cell(40,	6,			'ACTIVO',			1,0,'C',1);
			$this->Cell(30,	6,			'FECHA ADQUISICION',			1,0,'C',1);
			$this->Cell(50,	6,			'CUSTODIO',			1,0,'C',1);
			$this->Cell(25,	6,			'ORDEN COMPRA',			1,0,'C',1);
			$this->Cell(25,	6,			'FACTURA',			1,0,'C',1);
			$this->Cell(30,	6,			'VALOR',			1,0,'C',1);
			$this->Ln(6);
			}
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-25);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(85,3,'Impreso por:',0,0,'R');
			$this->Cell(125,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(130,190,strtoupper($_SESSION['usuario']),40,6);
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
	$c=1;
	while (!$row->EOF) 
	{
		$c++;
		$pdf->Cell(20,		20,			$c,		1,0,'L',1);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->MultiCell(70,		3,			strtoupper($row->fields("descripcion_general")),		0,'LBR','L');
		$pdf->SetXY($x+70,$y);
		$pdf->Cell(25,		20,			$row->fields("codigo_bienes"),		1,0,'L',1);
		
		$pdf->Cell(40,		20,			$row->fields("fecha_compra"),		1,0,'L',1);
		$pdf->Cell(40,		20,			$row->fields("nombre"),		1,0,'L',1);
		$pdf->Cell(33,		20,			$row->fields("numero_orden_compra_servicio"),		1,0,'L',1);
		
		$pdf->Cell(22,		20,			$row->fields("numero_factura"),		1,0,'L',1);
		$pdf->Cell(30,		20,			substr($row->fields("valor_compra"),1,strlen($row->fields("valor_compra"))),		1,1,'L',1);
		$y = $pdf->GetY();
		$pdf->Line($x,$y,$x+70,$y);
		//$pdf->Ln(16);
		$row->MoveNext();
	}

	if($exi!=0){
		$x = $pdf->GetX();
		$y = $pdf->GetY();
	$pdf->SetFont('Times','B',8);
	$pdf->SetLineWidth(0.3);
	$pdf->SetFillColor(230) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(250,	6,			'TOTAL',			1,0,'R',1);
	$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(30,	6,			substr($row_total->fields("total"),1,strlen($row_total->fields("total"))),			1,0,'C',1);
	unset($_SESSION['nom_uni']);
	unset($_SESSION['nom_sit']);
	}
	if($exi==0){
		$pdf->Ln(60);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,70,285,70);
		$pdf->Cell(250,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",178,80,50);
		$pdf->Line(10,140,285,140);
	}
	$pdf->Output();
//}
?>