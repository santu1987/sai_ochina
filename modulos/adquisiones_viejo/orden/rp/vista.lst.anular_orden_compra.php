<?
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

$sql_cono="
SELECT 
	\"orden_compra_servicioE\".id_orden_compra_servicioe, 
	\"orden_compra_servicioE\".id_organismo, 
	\"orden_compra_servicioE\".ano, 
	\"orden_compra_servicioE\".id_unidad_ejecutora,  unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora,
	accion_centralizada.id_accion_central, accion_centralizada.codigo_accion_central ,accion_centralizada.denominacion ,
	proyecto.id_proyecto, proyecto.codigo_proyecto, proyecto.nombre, 
	\"orden_compra_servicioE\".id_accion_especifica, accion_especifica.codigo_accion_especifica, accion_especifica.denominacion AS accion_especifica,
	numero_cotizacion, 
	numero_requisicion, 
	numero_orden_compra_servicio, 
	numero_precompromiso, 
	numero_compromiso, 

	tiempo_entrega, lugar_entrega, entregara, condiciones_pago, tipo, 
	concepto, clase_orden_compra_servicio, comentarios, estatus, 

	numero_pre_orden, orden_especial
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
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central =accion_especifica.id_accion_central
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = accion_especifica.id_proyecto
WHERE
	estatus = 3
";
$row=& $conn->Execute($sql_cono);
if (!$row->EOF){
//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',9);
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
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE ANULACION DE ORDEN DE COMPRA',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			$this->Cell(200,10,'Año: '.date('Y'),0,0,'L');
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(30,				10,		'Nro ORDEN ',					'B',0,'C',1);
			$this->Cell(30,				10,		'Nro COTIZACION ',				'B',0,'C',1);
			$this->Cell(30,				10,		'Nro REQUISION ',				'B',0,'C',1);
			$this->Cell(80,				10,		'UNIDAD SOLICITANTE',			'B',0,'C',1);
			$this->Cell(90,				10,		'PROYECTO O ACCIÓN CENTRAL',	'B',0,'C',1);
			
			$this->Ln(10);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(100,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(85,3,'Impreso por:  '.$_SESSION[apellido].' '.$_SESSION[nombre],0,0,'C');
			$this->Cell(75,3,date("d/m/Y h:m:s"),0,0,'R');					
		}
	}
	
//*************************************************************************************************************
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
	while (!$row->EOF)
	{
		$pdf->Cell(30,	5,	$row->fields("numero_orden_compra_servicio"),												'RLB',0,'C',1);
		$pdf->Cell(30,	5,	$row->fields("numero_cotizacion"),															'RLB',0,'C',1);
		$pdf->Cell(30,	5,	$row->fields("numero_requisicion"),															'LB',0,'C',1);
		$pdf->Cell(80,	5,	$row->fields("codigo_unidad_ejecutora").' '.substr($row->fields("unidad_ejecutora"),0,40),				'LRB',0,'L',1);
		if($row->fields("id_proyecto") !="")
			$pdf->Cell(90,	5,	$row->fields("codigo_proyecto").' '.substr($row->fields("nombre"),0,50),				'LRB',0,'L',1);
		else
			$pdf->Cell(90,	5,	$row->fields("codigo_accion_central").' '.substr($row->fields("denominacion"),0,50),	'LRB',0,'L',1);
		$pdf->Ln(5);
	
	$row->MoveNext();
	}
$sql_re="
	SELECT 
		COUNT(id_orden_compra_servicioe) AS contar
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
	LEFT JOIN
		accion_centralizada
	ON
		accion_centralizada.id_accion_central =accion_especifica.id_accion_central
	LEFT JOIN
		proyecto
	ON
		proyecto.id_proyecto = accion_especifica.id_proyecto
	WHERE
		estatus = 3
";
$rowi=& $conn->Execute($sql_re);
if (!$rowi->EOF)
{	$pdf->Ln(5);
	$pdf->Cell(60,	5,	'TOTAL ANULADAS ',												1,0,'C',1);
	$pdf->Cell(30,	5,	$rowi->fields("contar"),										1,0,'C',1);
}
	$pdf->Output();
}
?>