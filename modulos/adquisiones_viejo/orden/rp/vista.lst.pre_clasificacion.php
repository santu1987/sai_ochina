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


$sql = "
	SELECT 
		\"orden_compra_servicioE\".ano, 
		\"orden_compra_servicioE\".id_proveedor, 
		proveedor.nombre AS proveedor,
		\"orden_compra_servicioE\".id_unidad_ejecutora, 
		unidad_ejecutora.codigo_unidad_ejecutora,
		unidad_ejecutora.nombre AS unidad_ejecutora,
		\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
		proyecto.codigo_proyecto,
		proyecto.nombre AS proyecto,
		accion_centralizada.codigo_accion_central,
		accion_centralizada.denominacion AS accion_centralizada,
		\"orden_compra_servicioE\".id_accion_especifica, 
		accion_especifica.codigo_accion_especifica,
		accion_especifica.denominacion AS accion_especifica,
		\"orden_compra_servicioE\".numero_cotizacion, 
		\"orden_compra_servicioE\".numero_pre_orden, 
		\"orden_compra_servicioE\".tipo, 
		\"orden_compra_servicioE\".numero_requisicion, 
		\"orden_compra_servicioE\".numero_orden_compra_servicio, 
		\"orden_compra_servicioE\".fecha_orden_compra_servicio, 
		\"orden_compra_servicioE\".fecha_elabora, 
		\"orden_compra_servicioE\".ultimo_usuario
	FROM 
		\"orden_compra_servicioE\"
	INNER JOIN
		accion_especifica
	ON
		accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
	INNER JOIN
		unidad_ejecutora
	ON
		unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
	INNER JOIN
		proveedor
	ON
		proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
	LEFT JOIN
		proyecto
	ON
		accion_especifica.id_proyecto = proyecto.id_proyecto
	LEFT JOIN
		accion_centralizada
	ON
		accion_centralizada.id_accion_central = accion_especifica.id_accion_central
	WHERE
	\"orden_compra_servicioE\".numero_pre_orden = '".$cotizacion."'
";

$row_en=& $conn->Execute($sql);

if (!$row_en->EOF)
{
	$proveedor=$row_en->fields("proveedor");
	$cod_uni=$row_en->fields("codigo_unidad_ejecutora");
	$unidad=$row_en->fields("unidad_ejecutora");
	if($row_en->fields("tipo") == 1){
		$cod_ac_pro=$row_en->fields("codigo_proyecto");
		$proyecto_ac=$row_en->fields("proyecto");
	}else{
		$cod_ac_pro=$row_en->fields("codigo_accion_central");
		$proyecto_ac=$row_en->fields("accion_centralizada");
	}
	$id_accion_especifica=$row_en->fields("id_accion_especifica");
	$codigo_especifica=$row_en->fields("codigo_accion_especifica");
	$accion_especifica=$row_en->fields("accion_especifica");
	$fecha_elabora=$row_en->fields("fecha_elabora");
	$numero_pre_orden=$row_en->fields("numero_pre_orden");
	
	
	class PDF extends FPDF	{
		 
	
		//Cabecera de página 
		function Header()
		{		
			global $proveedor, $fecha_elabora;
			global $cod_uni, $unidad;
			global $cod_ac_pro, $proyecto_ac;
			global $codigo_especifica, $accion_especifica;
			
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
			$this->Cell(0,10,'CLASIFICACION PRESUPUESTARIA',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$this->Cell(37,6,'UNIDAD EJEC. :',0,0,'R');
			$this->Cell(170,6,$cod_uni.' '.$unidad,0,0,'L');
			$this->Ln(6);
			$this->Cell(37,6,'PROY. / A.C. :',0,0,'R');
			$this->Cell(170,6,$cod_ac_pro.' '.$proyecto_ac,0,0,'L');
			$this->Ln(6);
			$this->Cell(37,6,'ACCIÓN ESP. :',0,0,'R');
			$this->Cell(170,6,$codigo_especifica.' '.$accion_especifica,0,0,'L');
			$this->Ln(6);
			$this->Cell(37,6,'PROVEEDOR :',0,0,'R');
			$this->Cell(170,6,$proveedor,0,0,'L');
			$this->Ln(6);
			$y=$this->GetY();
			$this->MultiCell(37,5,'FECHA DE PRECOMPROMISO :',0,0,'C');
			$this->SetXY(47,$y);
			$this->Cell(170,8,$fecha_elabora,0,0,'L');
			$this->Ln(12);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			
			$this->Cell(20,		10,		'PARTIDA',					1,0,'C',1);
			$this->Cell(80,		10,		'DESCRIPCIÓN',				1,0,'C',1);
			$y=$this->GetY();
			$this->MultiCell(28,5,		'PRESUPUESTO A LA FECHA',	1,'C',1);
			$this->SetXY(138,$y);
			$this->MultiCell(29,5,		'PRESUPUESTO COMPROMETIDO',	1,'L',1);
			$this->SetXY(167,$y);
			$this->MultiCell(28,5,		'PRESUPUESTO DISPONIBLE',	1,'C',1);
			$this->SetXY(195,$y);
			$this->MultiCell(27,5,		'ORDEN DE COMPRA',			1,'C',1);
			$this->SetXY(222,$y);
			$this->Cell(20,		10,		'MONTO IVA',				1,0,'C',1);
			$this->Cell(20,		10,		'TOTAL',					1,0,'C',1);
			$this->Cell(20,		10,		'SOLICITUD',				1,0,'C',1);
			$this->Ln(10);
			//$this->Ln(10);
		}
	}


	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,25);
	$sql_detalle= "
	SELECT  
		\"orden_compra_servicioD\".partida, \"orden_compra_servicioD\".generica, \"orden_compra_servicioD\".especifica, \"orden_compra_servicioD\".subespecifica, 
		\"orden_compra_servicioD\".descripcion,
		\"orden_compra_servicioD\".id_organismo, 
		\"orden_compra_servicioD\".ano,
		\"orden_compra_servicioD\".monto, impuesto,cantidad
	FROM 
		\"orden_compra_servicioD\"
	WHERE
		\"orden_compra_servicioD\".numero_pre_orden = '".$numero_pre_orden."'		
	";
	$row_detalle=& $conn->Execute($sql_detalle);
while (!$row_detalle->EOF) 
	{
		$sql_pre= "
	SELECT  

		(monto_presupuesto[1]+monto_presupuesto[2]+monto_presupuesto[3]+
		 monto_presupuesto[4]+monto_presupuesto[5]+monto_presupuesto[6]+
		 monto_presupuesto[7]+monto_presupuesto[8]+monto_presupuesto[9]) AS presupuesto_hoy,
		(monto_comprometido[1]+monto_comprometido[2]+monto_comprometido[3]+
		 monto_comprometido[4]+monto_comprometido[5]+monto_comprometido[6]+
		 monto_comprometido[7]+monto_comprometido[8]+monto_comprometido[9]) AS presupuesto_comprometido
	FROM 
		\"presupuesto_ejecutadoR\"
	WHERE
		\"presupuesto_ejecutadoR\".partida = '".$row_detalle->fields('partida')."' AND
		\"presupuesto_ejecutadoR\".generica = '".$row_detalle->fields('generica')."'   AND
		\"presupuesto_ejecutadoR\".especifica = '".$row_detalle->fields('especifica')."' AND
		\"presupuesto_ejecutadoR\".sub_especifica = '".$row_detalle->fields('subespecifica')."' AND
		\"presupuesto_ejecutadoR\".id_accion_especifica = $id_accion_especifica		
	";
	$row_pre=& $conn->Execute($sql_pre);
	$monto_total = $row_detalle->fields('monto')*$row_detalle->fields('cantidad');
	$monto_iva = $monto_total*($row_detalle->fields('impuesto')/100);
	$partida = $row_detalle->fields('partida').'.'.$row_detalle->fields('generica').'.'.$row_detalle->fields('especifica').'.'.$row_detalle->fields('subespecifica');
	$disponible = 	$row_pre->fields('presupuesto_hoy') - $row_pre->fields('presupuesto_comprometido');
		$pdf->Cell(20,	6,	$partida,																		'LR',0,'C',1);
		$pdf->Cell(80,	6,	$row_detalle->fields('descripcion'),											'L',0,'C',1);
		$pdf->Cell(28,	6,	number_format($row_pre->fields('presupuesto_hoy'),2,',','.'),					'L',0,'C',1);
		$pdf->Cell(29,	6,	number_format($row_pre->fields('presupuesto_comprometido'),2,',','.'),			'L',0,'C',1);
		$pdf->Cell(28,	6,	number_format($disponible,2,',','.'),											'L',0,'C',1);
		$pdf->Cell(27,	6,	number_format($monto_total,2,',','.'),											'L',0,'C',1);
		$pdf->Cell(20,	6,	number_format($monto_iva,2,',','.'),											'L',0,'C',1);
		$pdf->Cell(20,	6,	number_format($monto_total+$monto_iva,2,',','.'),								'L',0,'C',1);
		$pdf->Cell(20,	6,	'',																				'LR',0,'C',1);
		$pdf->Ln(6);
		$row_detalle->MoveNext();
	}
	$pdf->Output();
}
?>