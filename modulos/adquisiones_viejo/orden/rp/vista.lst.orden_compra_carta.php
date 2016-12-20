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
	$where = $where . " AND	(\"orden_compra_servicioE\".numero_orden_compra_servicio = '$cotizacion') ";
if ($ano != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".ano = '2009') ";
//*************************************************************************
$nuevo = "SELECT 
	numero_pre_orden as pre, 	
	(partida ||'.'|| generica ||'.'||  especifica ||'.'||  subespecifica) AS partidas,
	(select sum(cantidad*monto)as suma  from \"orden_compra_servicioD\" ) 
FROM 
	\"orden_compra_servicioD\"
GROUP by
	 partidas , numero_pre_orden";

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
	$numero_precompromiso=$row->fields("numero_precompromiso");	
	$numero_orden_compra_servicio=$row->fields("numero_orden_compra_servicio");
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
			global $numero_cotizacion, $numero_orden_compra_servicio, $numero_precompromiso; 
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
			$this->Cell(175,10,'Nº Orden  ',0,0,'R');
			//$this->SetFont('Arial','',10); $numero_precompromiso
			$this->Cell(15,10,$numero_orden_compra_servicio,0,0,'R');
			$this->Ln(5);
			$this->Cell(175,10,'Nº Pre-compromiso  ',0,0,'R');
			$this->Cell(15,10,$numero_precompromiso,0,0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(20,9,'Proveedor ','LT',0,'L');
			$this->SetFont('Arial','',9);
			$this->Cell(110,9,$proveedor,'TR',0,'L');
			$this->SetFont('Arial','B',10);
			$this->Cell(45,9,'Requisicion ','LT',0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,9,$numero_requisicion,'RT',0,'R');
			$this->Ln(9);
			$this->SetFont('Arial','',8.5);
			$y=$this->GetY();
			$this->MultiCell(130,18,$direccion.'. Telf. '.$telefono,'LBR','L');
			$this->SetXY(140,$y);
			$this->MultiCell(60,9,'Telf. (0212) 303-8761 3038762   Fax. 303-8761 3038762','LBR','L'); 
			$this->SetFont('Arial','B',10);
			$this->Cell(49,8,'TIEMPO DE ENTREGA',1,0,'L');
			$this->Cell(47,8,'CONTABILIDAD C/O',1,0,'L');
			$this->Cell(47,8,'CONTABILIDAD',1,0,'L');
			$this->Cell(47,8,'PRESUPUESTO',1,0,'L');
			$this->Ln(8);
			$this->SetFont('Arial','',10);
			$this->Cell(49,8,$tiempo_entrega,1,0,'L');
			$this->Cell(47,8,'',1,0,'L');
			$this->Cell(47,8,'',1,0,'L');
			$this->Cell(47,21,'',1,0,'L');
			$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(49,7,'SOLICITUD DE COTIZACION','LRT',0,'L');
			$this->Cell(47,7,'SU PRESUPUESTO','LRT',0,'L');
			$this->Cell(47,7,'LUGAR DE ENTREGA','LRT',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',10);
			$this->Cell(49,6,$numero_cotizacion,'LRB',0,'L');
			$this->Cell(47,6,'','LRB',0,'L');
			$this->Cell(47,6,$lugar_entrega,'LRB',0,'L');
			$this->Ln(6);
			$this->SetFont('Arial','B',10);
			$this->Cell(80,7,'CONDICIONES DE PAGO','LRT',0,'L');
			$this->Cell(110,7,'ENTREGAR A:','LRT',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',10);
			$this->Cell(80,7,$condiciones_pago,'LRB',0,'L');
			$this->Cell(110,19,'','LRB',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','B',10);
			$this->Cell(80,6,'CLASIFICACION PRESUPUESTARIA',1,0,'L');
			$this->Ln(6);
			$this->SetFont('Arial','',10);
			$this->Cell(80,6,'402',1,0,'L');
			$this->Ln(6);
			$this->SetFont('Arial','B',10);
			$this->Cell(190,7,'CONCEPTO DE LA ORDEN','LTR',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',10);
			$this->MultiCell(190,7,$titulo,'LBR','L'); 
			//$this->Ln(7);
			$this->Cell(190,5,'SIRVASE DESPACHAR EL SIGUIENTE MATERIAL DE ACUERDO A LAS INSTRUCCIONES ESPECIFICADAS',1,0,'C'); 
			$this->Ln(5);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(12,		5,		'Reglon',			1,0,'C',1);
			$this->Cell(18,		5,		'Cantidad',			1,0,'C',1);
			$this->Cell(20,		5,		'Uni. Med.',		1,0,'C',1);
			$this->Cell(98,		5,		'Descripcion',		1,0,'L',1);
			$this->Cell(21,		5,		'Precio U.',		1,0,'C',1);
			$this->Cell(21,		5,		'Total Bs.',		1,0,'C',1);
			$this->Ln(5);
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
	$pdf=new PDF('P','mm','Legal');
//	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(0);
	//$pdf->SetAutoPageBreak(auto,10);
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
								(numero_cotizacion = '".$numero_cotizacion."')
							ORDER BY
								secuencia
							";

		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
		/*$pdf->Line(9.8,115,9.8,250);
		$pdf->Line(22,120,22,250);
		$pdf->Line(40,120,40,250);
		$pdf->Line(55,120,55,250);
		$pdf->Line(200,120,200,250);
		$pdf->Line(10,250,200,250);*/
		$coordenada = 4;
		$total = 0;
		$iva = 0;
	while (!$rowProyectoAccion->EOF) 
	{
	//$i++;
		$subtotal = $rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad');
		$pdf->Cell(12,			$coordenada,	$rowProyectoAccion->fields('secuencia'),		'LR',0,'C');
		$pdf->Cell(18,			$coordenada,	$rowProyectoAccion->fields('cantidad'),			'L',0,'C');
		$pdf->Cell(20,			$coordenada,	$rowProyectoAccion->fields('nombre'),			'L',0,'C');
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
	/*if ($tamano<44)
	{
	$t = 5-$tamano;
			$pdf->Cell(12,		$t,	' ',		'LR',0,'L',0);
			$pdf->Cell(18,		$t,	'',			'LR',0,'R',0);
			$pdf->Cell(20,		$t,	'',			'LR',0,'R',1);
			$pdf->Cell(98,		$t,	'',			'LR','L',0);
			$pdf->Cell(21,		$t,	'',			'L',0,'L',0);
			$pdf->Cell(21,		$t,	'',			'LR',0,'L',0);
		$pdf->Ln($t);

	}else{
		$pdf->AddPage('');
	}*/
	$pdf->SetFont('arial','',7);
	$pdf->Cell(148,		4,	'',			1,'L',0);
	$pdf->Cell(21,		4,	'SUB-TOTAL ',			1,0,'L',0);
	$pdf->Cell(21,		4,	number_format($total,2,',','.'),			1,0,'L',0);
	$pdf->Ln(4);
	$pdf->Cell(74,		4,	'CONFORMADO POR',			1,0,'L',0);
	$pdf->Cell(74,		4,	'APROBADO POR',				1,0,'L',0);
	$pdf->Cell(21,		9,	'I.V.A ',					'L',0,'L',0);
	$pdf->Cell(21,		9,	number_format($iva,2,',','.'),					'RT',0,'L',0);
	$pdf->Ln(4);
	$total = $total + $iva;
	$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
	$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
	$pdf->Cell(21,		5,	' ',			'L',0,'L',0);
	$pdf->Cell(21,		5,	' ',			'R',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		5,	'CN',			'LBR',0,'L',0);
	$pdf->Cell(74,		5,	'CN',				'LBR',0,'L',0);
	$pdf->Cell(21,		5,	'TOTAL',							'LB',0,'L',0);
	$pdf->Cell(21,		5,	number_format($total,2,',','.'),	'RB',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		4,	'DIRECTOR ADMON. FINANZAS',			'LBR',0,'L',0);
	$pdf->Cell(74,		4,	'DIRECTOR DE OCHINA',				'LBR',0,'L',0);
	$pdf->Cell(21,		4,	'',							'LB',0,'L',0);
	$pdf->Cell(21,		4,	'',	'RB',0,'L',0);
	$pdf->Ln(4);
	
	$pdf->Cell(74,		4,	'ELABORADO POR',			1,0,'L',0);
	$pdf->Cell(74,		4,	'REVISADO POR',				1,0,'L',0);
	$pdf->SetFont('arial','',5.5);
	$pdf->Cell(42,		4,	'ACEPTACION DEL PROV. FECHA Y FIRMA ',	'LBR',0,'L',0);
	$pdf->SetFont('arial','',7);
	$pdf->Ln(4);
	$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
	$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
	$pdf->Cell(21,		5,	' ',			'L',0,'L',0);
	$pdf->Cell(21,		8,	' ',			'R',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		5,	'TSU',			'LBR',0,'L',0);
	$pdf->Cell(74,		5,	'TSU',				'LBR',0,'L',0);
	$pdf->Cell(42,		5,	'',							'LR',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		4,	'COMPRADOR',			'LBR',0,'L',0);
	$pdf->Cell(74,		4,	'JEFE DE DIVISION DE ADQUISICIONES',	'LBR',0,'L',0);
	$pdf->Cell(42,		4,	'',							'LBR',0,'L',0);
	$pdf->Ln(4);
	//*************************************************************************************
	$sql_disppnible = "
	SELECT 
		SUM(cantidad*monto) ,
		partida, generica,  especifica,  subespecifica
	FROM 
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
	WHERE
		(numero_orden_compra_servicio = '$cotizacion')
	GROUP BY
		  partida, generica, especifica,  subespecifica
	";
	$row_disppnible=& $conn->Execute($sql_disppnible);
	
	if (!$row_disppnible->EOF)
	{
	$sql_saldo ="
	SELECT 
		(monto_presupuesto[9] + monto_traspasado[9] + monto_modificado[9] - monto_comprometido [9]) AS monto
	FROM 
		\"presupuesto_ejecutadoR\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		(\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
		AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
		)
	WHERE
		(numero_orden_compra_servicio = '$cotizacion')
	AND
		(partida = '".$row_disppnible->fields('partida')."')
	AND
		(generica = '".$row_disppnible->fields('generica')."')
	AND
		(especifica = '".$row_disppnible->fields('especifica')."')
	AND
		(sub_especifica = '".$row_disppnible->fields('subespecifica')."')

	";
	$row_saldo=& $conn->Execute($sql_saldo);
	
	if (!$row_saldo->EOF)
	{
		$montoo = $row_saldo->fields('monto');
	}
	$pdf->SetFont('arial','B',7);
	$part = $row_disppnible->fields('partida').".".$row_disppnible->fields('generica').".".$row_disppnible->fields('especifica').".".$row_disppnible->fields('subespecifica');
			$pdf->Ln(3);
			$pdf->Cell(50,		4,	'PARTIDA',					1,0,'L',0);
			$pdf->Cell(45,		4,	'PRESUPUESTO DISPONIBLE',	1,0,'L',0);
			$pdf->Cell(45,		4,	'MONTO PRE-COMPROMISO',		1,0,'L',0);
			//$pdf->Cell(21,		5,	'TOTAL',					1,0,'L',0);
			$pdf->Ln(4);
			$pdf->SetFont('arial','',7);
		while (!$row_disppnible->EOF) 
		{
	$part = $row_disppnible->fields('partida').".".$row_disppnible->fields('generica').".".$row_disppnible->fields('especifica').".".$row_disppnible->fields('subespecifica');
			$pdf->Cell(50,		4,	$part ,																1,0,'L',0);
			$pdf->Cell(45,		4,	number_format($montoo,2,',','.'),									1,0,'L',0);
			$pdf->Cell(45,		4,	number_format($row_disppnible->fields('sum'),2,',','.'),			1,0,'L',0);
			//$pdf->Cell(21,		5,	number_format($montoo - $row_disppnible->fields('sum'),2,',','.'),	1,0,'L',0);
			$pdf->Ln(4);
			$row_disppnible->MoveNext();
		}
	}
	$pdf->SetFont('arial','B',6);
	$pdf->Cell(22,		4,	'CLAUSULA PENAL',					'B',0,'L',0);
	$pdf->SetFont('arial','',5);
	$pdf->Cell(22,		4,	'Queda establecida la clausula penal segun la cual el Proveedor, debera pagar a Servicio Autonomo Ochina el 2% sobre el monto total de la presente Orden de Servicio por cada dia de retardo en la mercancia entrgada',					'B',0,'L',0);
		$pdf->Output();
}
?>