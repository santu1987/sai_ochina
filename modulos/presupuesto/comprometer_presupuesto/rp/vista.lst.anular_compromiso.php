<?php
if (!$_SESSION) session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
require('../../../../utilidades/pdf_js.php');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$nombre_elabora = $_SESSION[nombre]/*.' '.$_SESSION[apellido]*/;
$cod = $_GET['cod'];
$unidad = $_GET['unidad'];
//************************************************************************
$Sql="
SELECT 
	numero_orden_compra_servicio,
	\"orden_compra_servicioE\".numero_cotizacion,
	codigo_unidad_ejecutora,
	nombre AS unidad_ejecutora,
	concepto,
	(SELECT 
		SUM(cantidad*monto)
	FROM 
		\"orden_compra_servicioD\"
	WHERE
		\"orden_compra_servicioD\".numero_cotizacion = \"orden_compra_servicioE\".numero_cotizacion
	) AS monto

FROM
	\"orden_compra_servicioE\"
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora

";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$numero_orden =  $row->fields('numero_orden_compra_servicio');
	$concepto =  $row->fields('concepto');
	$unidad_ejecutora =  $row->fields('codigo_unidad_ejecutora').' '.$row->fields('unidad_ejecutora');
	$numero_cotizacion =  $row->fields('numero_cotizacion');
	$monto =  $row->fields('monto');
	class PDF_AutoPrint extends PDF_JavaScript
		{
		function AutoPrint($dialog=false)
		{
			//Open the print dialog or start printing immediately on the standard printer
			$param=($dialog ? 'true' : 'false');
			$script="print($param);";
			$this->IncludeJS($script);
		}
		
		function AutoPrintToPrinter($server, $printer, $dialog=false)
		{
			//Print on a shared printer (requires at least Acrobat 6)
			$script = "var pp = getPrintParams();";
			if($dialog)
				$script .= "pp.interactive = pp.constants.interactionLevel.full;";
			else
				$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
			$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
			$script .= "print(pp);";
			$this->IncludeJS($script);
		}
			
			//Cabecera de página
			function Header()
			{
				global $numero_orden, $concepto, $unidad_ejecutora,$numero_cotizacion,$monto;
				global $cod, $unidad;
				$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
							
				$this->SetFont('Arial','B',9);
				$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
				$this->Ln();
				$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
				$this->Ln();
				$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
				$this->Ln();			
				$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
				$this->Ln();			
				$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
				$this->Ln();
				/*$this->Ln();			
				$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
				
			}
		}
	$pdf=new PDF_AutoPrint('P','mm','Letter');	
	//$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(-110);
	
	
	$pdf->Ln();	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'COMPROMISO ANULADO',0,0,'C');
	$pdf->Ln(12);	
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(50,10,'Orden Nro: '.$numero_orden,0,0,'L');
	$pdf->Cell(140,10,$concepto,0,0,'L');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(50,10,'COTIZACIÓN: '.$numero_cotizacion,0,0,'L');
	$pdf->Cell(140,10,$unidad_ejecutora,0,0,'L');	
	$pdf->Ln(10);
	$pdf->Cell(190,10,'MONTO DEL COMPROMISO ANULADO: '.number_format($monto,2,',','.').' BsF.',0,0,'L');
	$pdf->Ln(10);
	$pdf->Cell(190,10,'UNIDAD QUE ANULA COMPROMISO: '. $cod.' '.$unidad ,0,0,'L');
	$pdf->Ln(10);
	
	
	$pdf->AutoPrint(true);
	$pdf->Output();
}
?>