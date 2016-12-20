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
//************************************************************************ 

$desde = $_GET['compromisos_rp_desde'];
$hasta = $_GET['compromisos_rp_hasta'];


	/*$where = $where."
	AND
		id_accion_especifica = 225 
	";*/
$sql ="
SELECT 
	\"orden_compra_servicioE\".numero_compromiso,
	\"presupuesto_ejecutadoD\".fecha_compromiso,
	proveedor.nombre, 
	concepto,
	(SELECT
		partida
	FROM
		\"orden_compra_servicioD\"
	WHERE
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
	GROUP BY
		partida
	) AS	partida,
	numero_orden_compra_servicio, 
	codigo_unidad_ejecutora, 
	codigo_accion_especifica,
	(SELECT
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto))
	FROM
		\"orden_compra_servicioD\"
	WHERE
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
	) AS monto	
	
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
	
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

WHERE
	\"orden_compra_servicioE\".id_organismo = 1
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'

AND
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'

ORDER BY
	\"orden_compra_servicioE\".numero_compromiso
	";
//die ($sql);

/*
AND
	\"presupuesto_ejecutadoD\".fecha_anula <> ''
AND
	fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
*/
$row=& $conn->Execute($sql);

if (!$row->EOF){
//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $unidad_ejecutora,  $ano, $codigo_unidad,  $texto;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',9);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			//$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'COMPROMISOS EMITIDOS',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(20,				6,		'COMPROMISO',						0,0,'C',1);
			$this->Cell(20,				6,		'FECHA',							0,0,'C',1);
			$this->Cell(60,				6,		'PROVEEDOR',						0,0,'C',1);
			$this->Cell(70,				6,		'CONCEPTO',							0,0,'C',1);
			$this->Cell(15,				6,		'PARTIDA',							0,0,'C',1);
			$this->Cell(20,				6,		'ORDEN',							0,0,'C',1);
			$this->Cell(20,				6,		'UNIDAD',							0,0,'C',1);
			$this->Cell(20,				6,		'ACC. ESP.',						0,0,'C',1);
			$this->Cell(20,				6,		'MONTO',							0,0,'C',1);
			//$this->SetXY(193,$y);
			//$this->SetFont('Arial','B',7);
			//$this->MultiCell(19,		12,		'PORCENTAJE',					0,'C',1);
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
	$tamano = 0;
	while (!$row->EOF)
	{
	if(strlen($row->fields('concepto'))<= 30)	
		$tamano = $tamano + 5;
	elseif((strlen($row->fields('concepto'))>30) && (strlen($row->fields('concepto'))<=60)	)
		$tamano = $tamano + 10;
	elseif((strlen($row->fields('concepto'))>60)	&& (strlen($row->fields('concepto'))<=90)	)
		$tamano = $tamano + 15;
	elseif((strlen($row->fields('concepto'))>90)	&& (strlen($row->fields('concepto'))<=120))	
		$tamano = $tamano + 20;
	elseif((strlen($row->fields('concepto'))>120)	&& (strlen($row->fields('concepto'))<=150))	
		$tamano = $tamano + 25;
	elseif((strlen($row->fields('concepto'))>150)	&& (strlen($row->fields('concepto'))<=180))	
		$tamano = $tamano + 30;
	elseif((strlen($row->fields('concepto'))>180)	&& (strlen($row->fields('concepto'))<=210))	
		$tamano = $tamano + 35;
	elseif((strlen($row->fields('concepto'))>240)	&& (strlen($row->fields('concepto'))<=270))	
		$tamano = $tamano + 40;
	elseif((strlen($row->fields('concepto'))>270)	&& (strlen($row->fields('concepto'))<=300))	
		$tamano = $tamano + 45;
	elseif((strlen($row->fields('concepto'))>300)	&& (strlen($row->fields('concepto'))<=330))	
		$tamano = $tamano + 50;
	elseif((strlen($row->fields('concepto'))>330)	&& (strlen($row->fields('concepto'))<=360))	
		$tamano = $tamano + 55;
	elseif((strlen($row->fields('concepto'))>360)	&& (strlen($row->fields('concepto'))<=390))	
		$tamano = $tamano + 60;
		
	if ($tamano >= 75){
		$pdf->AddPage('L');
		$tamano  = 0;
	}
/*		
$sql_deta="SELECT   
	fecha_compromiso 
FROM 
	\"presupuesto_ejecutadoD\"
WHERE
	numero_documento='".$row->fields('numero_orden_compra_servicio')."'
AND 
	numero_compromiso='".$row->fields('numero_compromiso')."'
";
$row_deta=& $conn->Execute($sql_deta);*/

$fecha_compromiso=split('-',$row->fields("fecha_compromiso"));
list($ano,$mes , $dia) = $fecha_compromiso;
$dia = substr ($dia, 0, 2);
$fecha = $dia.'-'.$mes.'-'.$ano;
	
		$pdf->SetTextColor(0) ;
		$pdf->Cell(20,	5,			$row->fields('numero_compromiso'),				'T',0,'C',1);
		$pdf->Cell(20,	5,			$fecha,											'T',0,'C',1);
		$y=$pdf->GetY();
		//$y= $y+6;
		$pdf->MultiCell(60,	5,		utf8_decode($row->fields('nombre')),			'T','C',1);
		$pdf->SetXY(110,$y);
		$pdf->MultiCell(70,	5,		utf8_decode($row->fields('concepto')),			'T','L',1);
		$pdf->SetXY(180,$y);
		$pdf->Cell(15,5,			$row->fields('partida'),						'T',0,'C',1);
		$pdf->Cell(20,5,			$row->fields('numero_orden_compra_servicio'),	'T',0,'C',1);
		$pdf->Cell(20,5,			$row->fields('codigo_unidad_ejecutora'),		'T',0,'C',1);
		$pdf->Cell(20,5,			$row->fields('codigo_accion_especifica'),		'T',0,'C',1);
		$pdf->Cell(20,	5,			number_format($row->fields('monto'),2,',','.'),	'T',0,'C',1);
		$pdf->SetTextColor(255) ;
		$pdf->MultiCell(70,	5,		utf8_decode($row->fields('concepto')),			0,'L',1);
		
		//$pdf->Cell(16,	5,	/*number_format($calculo_tres,2,',','.')*/'',								'L',0,'R',1);
		//$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row->MoveNext();
	}
	$pdf->SetTextColor(0) ;
$sql_monto ="
SELECT
	
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
WHERE
	\"orden_compra_servicioE\".id_organismo = 1
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
";	
$row_monto=& $conn->Execute($sql_monto);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(240,				6,		'TOTAL',						1,0,'R',1);
			$pdf->Cell(25,				6,		number_format($row_monto->fields('monto'),2,',','.'),							1,0,'R',1);
$pdf->SetFont('Arial','',9);
$pdf->Ln(7);
$sql_monto_partida ="
SELECT
	partida,
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
WHERE
	\"orden_compra_servicioE\".id_organismo = 1
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
GROUP BY
	partida
ORDER BY
	partida
";	
$row_monto_partida=& $conn->Execute($sql_monto_partida);

$pdf->SetFont('Arial','B',9);
		$pdf->Cell(15,5,			'Partida',						1,0,'C',1);
		$pdf->Cell(30,5,			'Monto',	1,0,'C',1);
		$pdf->Ln(5);
		$suma_partidas =  0;
		$pdf->SetFont('Arial','',9);
while (!$row_monto_partida->EOF)
	{
		$suma_partidas = $suma_partidas + $row_monto_partida->fields('monto');
		$pdf->Cell(15,5,			$row_monto_partida->fields('partida'),	1,0,'C',1);
		$pdf->Cell(30,5,			number_format($row_monto_partida->fields('monto'),2,',','.'),	1,0,'R',1);
		$pdf->Ln(5);
		$row_monto_partida->MoveNext();
	}
	$pdf->SetFont('Arial','B',9);
		$pdf->Cell(15,5,			'Total',						1,0,'C',1);
		$pdf->Cell(30,5,			number_format($suma_partidas,2,',','.'),	1,0,'R',1);
		$pdf->Ln(7);
		// $pdf->Cell(102,	5,	'TOTAL' ,													'T',0,'L',1);
$sql_monto_central ="
SELECT
	
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
WHERE
	\"orden_compra_servicioE\".id_organismo = 1
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
AND
	tipo =2";	
$row_monto_central=& $conn->Execute($sql_monto_central);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(55,5,			'TOTAL ACCION CENTRALIZADA',						1,0,'L',1);
		$pdf->Cell(40,5,			number_format($row_monto_central->fields('monto'),2,',','.'),	1,0,'R',1);
		$pdf->Ln(5);

$sql_monto_proyecto ="
SELECT
	
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	proyecto
ON
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
WHERE
	\"orden_compra_servicioE\".id_organismo = 1
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
AND
	tipo =1";	
$row_monto_proyecto=& $conn->Execute($sql_monto_proyecto);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(55,5,			'TOTAL PROYECTO',						1,0,'L',1);
		$pdf->Cell(40,5,			number_format($row_monto_proyecto->fields('monto'),2,',','.'),	1,0,'R',1);
		$pdf->Ln(5);

	$pdf->Output();
}else{
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',11);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
		}
		//Pie de página
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(200);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}
?>