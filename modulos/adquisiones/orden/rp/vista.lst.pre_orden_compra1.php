<?php
if (!$_SESSION) session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/fpdf.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$cotizacion = $_GET['numero_coti'];
$unidad_ejecutora = $_SESSION["id_unidad_ejecutora"];


$ano = $_GET['ano'];
$where = "WHERE (1=1) ";
if ($cotizacion != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".numero_pre_orden = '$cotizacion') ";
if ($ano != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".ano = '2009') ";

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$Sql="SELECT 
	proveedor.id_proveedor,  proveedor.nombre, proveedor.telefono, proveedor.fax,  proveedor.direccion,  
	\"orden_compra_servicioE\".numero_requisicion,\"orden_compra_servicioE\".numero_precompromiso, \"orden_compra_servicioE\".numero_orden_compra_servicio,
	\"orden_compra_servicioE\".fecha_orden_compra_servicio, \"orden_compra_servicioE\".tiempo_entrega, 
	\"orden_compra_servicioE\".condiciones_pago, \"orden_compra_servicioE\".concepto, \"orden_compra_servicioE\".lugar_entrega,
	\"orden_compra_servicioE\".numero_cotizacion, \"orden_compra_servicioE\".comentarios
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	proveedor
ON
	\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor
	".$where."
	 ORDER BY id_orden_compra_servicioe";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	//************************************************************************
	$proveedor=$row->fields("proveedor");
	$numero_cotizacion=$row->fields("numero_cotizacion");
	$numero_orden_compra_servicio=$row->fields("numero_pre_orden");
	$numero_requisicion=$row->fields("numero_requisicion");
	$direccion=$row->fields("direccion");
	$telefono=$row->fields("telefono");
	$titulo=$row->fields("concepto");
	$tiempo_entrega=$row->fields("tiempo_entrega");
	$lugar_entrega=$row->fields("lugar_entrega");
	$condiciones_pago=$row->fields("condiciones_pago");
	$comentarios=$row->fields("comentarios");
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proveedor;
			global $numero_cotizacion, $numero_orden_compra_servicio; 
			global $numero_requisicion;
			global $direccion, $tiempo_entrega, $lugar_entrega, $condiciones_pago;
			global $telefono,$titulo,$comentarios;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln(5);
			$this->SetFont('Arial','B',12);
			$this->Cell(0,10,'ORDEN DE COMPRA',0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',10);
			$this->Cell(175,10,'Nº Pre Orden  ',0,0,'R');
			//$this->SetFont('Arial','',10);
			$this->Cell(15,10,$numero_orden_compra_servicio,0,0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(20,10,'Proveedor ','LT',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(110,10,$proveedor,'TR',0,'L');
			$this->SetFont('Arial','B',10);
			$this->Cell(45,10,'Requisicion ','LT',0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,10,$numero_requisicion,'RT',0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$y=$this->GetY();
			$this->MultiCell(130,20,$direccion.'. Telf. '.$telefono,'LBR','L');
			$this->SetXY(140,$y);
			$this->MultiCell(60,10,'Telf. (0212) 303-8761 3038762   Fax. 303-8761 3038762','LBR','L'); 
			$this->SetFont('Arial','B',10);
			$this->Cell(49,10,'TIEMPO DE ENTREGA',1,0,'L');
			$this->Cell(47,10,'CONTABILIDAD C/O',1,0,'L');
			$this->Cell(47,10,'CONTABILIDAD',1,0,'L');
			$this->Cell(47,10,'PRESUPUESTO',1,0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$this->Cell(49,10,$tiempo_entrega,1,0,'L');
			$this->Cell(47,10,'',1,0,'L');
			$this->Cell(47,10,'',1,0,'L');
			$this->Cell(47,24,'',1,0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(49,7,'SOLICITUD DE COTIZACION','LRT',0,'L');
			$this->Cell(47,7,'SU PRESUPUESTO','LRT',0,'L');
			$this->Cell(47,7,'LUGAR DE ENTREGA','LRT',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',10);
			$this->Cell(49,7,$numero_cotizacion,'LRB',0,'L');
			$this->Cell(47,7,'','LRB',0,'L');
			$this->Cell(47,7,$lugar_entrega,'LRB',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','B',10);
			$this->Cell(80,7,'CONDICIONES DE PAGO','LRT',0,'L');
			$this->Cell(110,7,'ENTREGAR A:','LRT',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',10);
			$this->Cell(80,7,$condiciones_pago,'LRB',0,'L');
			$this->Cell(110,21,'','LRB',0,'L');
			$this->Ln(7);
			$this->Cell(80,7,'CLASIFICACION PRESUPUESTARIA',1,0,'L');
			$this->Ln(7);
			$this->Cell(80,7,'402',1,0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','B',10);
			$this->Cell(190,7,'CONCEPTO DE LA ORDEN','LTR',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',10);
			$this->MultiCell(190,7,$titulo,'LBR','L'); 
			//$this->Ln(7);
			$this->Cell(190,7,'SIRVASE DESPACHAR EL SIGUIENTE MATERIAL DE ACUERDO A LAS INSTRUCCIONES ESPECIFICADAS',1,0,'C'); 
			$this->Ln(7);
			$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(12,		6,		'Reglon',			0,0,'C',1);
			$this->Cell(18,		6,		'Cantidad',			0,0,'C',1);
			$this->Cell(20,		6,		'Uni. Med.',		0,0,'C',1);
			$this->Cell(98,	6,		'Descripcion',		0,0,'L',1);
			$this->Cell(21,		6,		'Precio U.',			0,0,'C',1);
			$this->Cell(21,		6,		'Total Bs.',		0,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
	/*	function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-45);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(65,3,'Observaciones ' ,0,0,'L');
			$this->Ln(20);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->SetFont('barcode','',6);
			$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			$this->SetFont('Arial','I',9);
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			/*$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}*/
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 

	$total=0;
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,25);
	$i=0;
	
		$SqlProyectoAccion="SELECT 
								secuencia,descripcion, cantidad, nombre, monto, impuesto
							FROM 
								\"orden_compra_servicioD\"
							INNER JOIN
								unidad_medida
							ON	
								\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
							WHERE
								(numero_cotizacion = '".$numero_cotizacion."')";

		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
		/*$pdf->Line(9.8,115,9.8,250);
		$pdf->Line(22,120,22,250);
		$pdf->Line(40,120,40,250);
		$pdf->Line(55,120,55,250);
		$pdf->Line(200,120,200,250);
		$pdf->Line(10,250,200,250);*/
		$coordenada = 6;
		$total = 0;
		$iva = 0;
	while (!$rowProyectoAccion->EOF) 
	{
	//$i++;
		$subtotal = $rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad');
		$pdf->Cell(12,			$coordenada,	$rowProyectoAccion->fields('secuencia'),		'LR',0,'C',1);
		$pdf->Cell(18,			$coordenada,	$rowProyectoAccion->fields('cantidad'),			'L',0,'C',1);
		$pdf->Cell(20,			$coordenada,	$rowProyectoAccion->fields('nombre'),			'L',0,'C',1);
		$y=$pdf->GetY();
		$pdf->MultiCell(98,	$coordenada,	$rowProyectoAccion->fields('descripcion'),		'LR','L');
		$pdf->SetXY(158,$y);
		$pdf->Cell(21,			$coordenada,	number_format($rowProyectoAccion->fields('monto'),2,',','.'),			'L',0,'R');
		$pdf->Cell(21,			$coordenada,	number_format($subtotal,2,',','.'),			'LR',0,'R');
		$pdf->Ln($coordenada);
		$tamano = $tamano + $coordenada;
		$total = $total + $subtotal;
		//$iva = ($rowProyectoAccion->fields('impuesto') / 100);
		$ivas=  ($rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad'))*$rowProyectoAccion->fields('impuesto')/100;
		$iva = $iva +$ivas;
		$rowProyectoAccion->MoveNext();
	}
	if ($tamano<66)
	{
	$t = 66-$tamano;
			$pdf->Cell(12,		$t,	' ',		'LRT',0,'L',0);
			$pdf->Cell(18,		$t,	'',			'LTR',0,'R',0);
			$pdf->Cell(20,		$t,	'',			'LTR',0,'R',1);
			$pdf->Cell(98,		$t,	'',			'LTR','L',0);
			$pdf->Cell(21,		$t,	'',			'LT',0,'L',0);
			$pdf->Cell(21,		$t,	'',			'LTR',0,'L',0);
		$pdf->Ln($t);

	}else{
		$pdf->AddPage('L');
	}

	$pdf->Cell(148,		5,	'',			1,'L',0);
	$pdf->Cell(21,		5,	'SUB-TOTAL ',			1,0,'L',0);
	$pdf->Cell(21,		5,	number_format($total,2,',','.'),			1,0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		5,	'CONFORMADO POR',			1,0,'L',0);
	$pdf->Cell(74,		5,	'APROBADO POR',				1,0,'L',0);
	$pdf->Cell(21,		10,	'I.V.A ',					'L',0,'L',0);
	$pdf->Cell(21,		10,	number_format($iva,2,',','.'),					'RT',0,'L',0);
	$pdf->Ln(5);
	$total = $total + $iva;
	$pdf->Cell(74,		15,	'DIRECTOR ADMON. FINANZAS',			1,0,'L',0);
	$pdf->Cell(74,		15,	'DIRECTOR DE OCHINA',				1,0,'L',0);
	$pdf->Cell(21,		15,	'TOTAL',					'LB',0,'L',0);
	$pdf->Cell(21,		15,	number_format($total,2,',','.'),					'RB',0,'L',0);
	$pdf->Ln(15);
	
	
		$pdf->Output();
}
?>