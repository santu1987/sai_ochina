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
$rango = $hasta;
$hastad = split( "/", $hasta );
list($diayy,$mesyy , $anoyy) = $hastad;
//die($diayy);
if(($diayy == '31') && (($mesyy =='01') || ($mesyy == '03')  || ($mesyy == '05') || ($mesyy == '07') || ($mesyy == '08') || ($mesyy == '10'))){
	//echo 'aqui1 <br>';
	$diayy = '01';
	$mesyy = $mesyy + 1;
}elseif(($diayy == '30') && (($mesyy == '04') || ($mesyy =='06') || ($mesyy == '09')  || ($mesyy == '11') )){
//echo 'aqui2 <br>';
	$diayy = '01';
	$mesyy = $mesyy + 1;
}elseif(($diayy == '28') && ($mesyy == '02')){
//echo 'aqui3 <br>';
	$diayy = '01';
	$mesyy = $mesyy + 1;
}elseif(($diayy == '31') && ($mesyy == '12')){
//echo 'aqui4 <br>';
	$diayy = '01';
	$mesyy = '01';
	 $anoyy = $anoyy + 1;
}else{
//echo 'aqui5 <br>';
	$diayy = $diayy+ 1;
}
$hasta = $diayy.'/'.$mesyy.'/'.$anoyy;
//die($hasta);
//$desde = '01/01/2011';
//$hasta = '01/03/2011';


	/*$where = $where."
	AND
		id_accion_especifica = 225 
	";*/
$sql ="
SELECT
	\"presupuesto_ejecutadoD\".fecha_compromiso,
	numero_precompromiso,
	\"orden_compra_servicioE\".numero_compromiso,
	numero_orden_compra_servicio,
	codigo_unidad_ejecutora,
	codigo_proyecto,
	codigo_accion_central,
	codigo_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	SUM((cantidad * monto)+(((cantidad * monto)/100)*impuesto)) AS monto,
	concepto,
	proveedor.nombre AS proveedor
FROM
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON 
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
INNER JOIN
	unidad_ejecutora
ON 
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON 
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON 
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 2
LEFT JOIN
	proyecto
ON 
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 1

INNER JOIN
	proveedor
ON 
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
WHERE
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"orden_compra_servicioD\".ajuste = 0
GROUP BY
	\"presupuesto_ejecutadoD\".fecha_compromiso,
	numero_precompromiso,
	\"orden_compra_servicioE\".numero_compromiso,
	numero_orden_compra_servicio,
	codigo_unidad_ejecutora,
	codigo_proyecto,
	codigo_accion_central,
	codigo_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	concepto,
	proveedor.nombre 
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
			$this->Cell(0,10,'COMPROMISOS ADQUIRIDOS',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(20,				6,		'FECHA',						0,0,'C',1);
			$this->Cell(26,				6,		'PRECOMPROMISO',							0,0,'C',1);
			$this->Cell(23,				6,		'COMPROMISO',						0,0,'C',1);
			$this->Cell(20,				6,		'ORDEN',							0,0,'C',1);
			$this->Cell(15,				6,		'UNIDAD',							0,0,'C',1);
			$this->Cell(17,				6,		'PROYECTO',							0,0,'C',1);
			$this->Cell(15,				6,		'ACC. ESP.',							0,0,'C',1);
			$this->Cell(22,				6,		'CUENTA',							0,0,'C',1);
			$this->Cell(22,				6,		'MONTO',							0,0,'C',1);
			$this->Cell(40,				6,		'PROVEEDOR',							0,0,'C',1);
			$this->Cell(50,				6,		'CONCEPTO',							0,0,'C',1);
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
	$suma_total = 0;
	$totaliza = 0;
	$compriso_antes = 0 ;
	while (!$row->EOF)
	{
	
	if($row->fields('codigo_proyecto') != "")
		$pryecto_acc = $row->fields('codigo_proyecto');
	elseif($row->fields('codigo_accion_central') != "")
		$pryecto_acc = $row->fields('codigo_accion_central');
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
	/*	
	if ($tamano >= 75){
		$pdf->AddPage('L');
		$tamano  = 0;
	}*/
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
		if($compriso_antes !=$row->fields("numero_compromiso")){
			if (($compriso_antes !=$row->fields("numero_compromiso")) &&($compriso_antes != 0)){
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(158,	5,			'TOTAL COMPROMISO',												1,0,'C',1);
				$pdf->Cell(22,	5,			number_format($suma_total,2,',','.'),												1,0,'C',1);
				$pdf->Cell(90,	5,			'',												1,0,'C',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
			}
		$suma_total = 0;
			$pdf->Cell(20,	5,			$fecha,												'T',0,'C',1);
			$pdf->Cell(26,	5,			$row->fields('numero_precompromiso'),				'T',0,'C',1);
			$pdf->Cell(23,	5,			$row->fields('numero_compromiso'),					'T',0,'C',1);
			$pdf->Cell(20,5,			$row->fields('numero_orden_compra_servicio'),		'T',0,'C',1);
			$pdf->Cell(15,5,			$row->fields('codigo_unidad_ejecutora'),			'T',0,'C',1);
			$pdf->Cell(17,5,			$pryecto_acc,										'T',0,'C',1);
			$pdf->Cell(15,5,			$row->fields('codigo_accion_especifica'),			'T',0,'C',1);
			$pdf->Cell(22,5,			$row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('subespecifica'),						'T',0,'C',1);
			$pdf->Cell(22,5,			number_format($row->fields('monto'),2,',','.'),		'T',0,'C',1);
			$y=$pdf->GetY();
			$pdf->MultiCell(40,	5,		utf8_decode($row->fields('proveedor')),				'T','L',1);
			$pdf->SetXY(230,$y);
			$pdf->MultiCell(50,	5,		utf8_decode($row->fields('concepto')),				'T','L',1);
			$suma_total = $row->fields('monto');
		}else{
			$pdf->Cell(136,	5,			'',												0,0,'C',1);
			$pdf->Cell(22,5,			$row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('subespecifica'),						0,0,'C',1);
			$pdf->Cell(22,5,			number_format($row->fields('monto'),2,',','.'),		0,0,'C',1);
			$pdf->MultiCell(90,	5,		'',				0,'L',1);
			$suma_total = $suma_total + $row->fields('monto');
		}
		
		$compriso_antes=$row->fields("numero_compromiso");
		$totaliza = $totaliza + $row->fields('monto');
		

		$row->MoveNext();
	}
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
	$pdf->SetFont('arial','B',10);
	$pdf->Cell(158,	5,			'TOTAL COMPROMISO',												1,0,'C',1);
	$pdf->Cell(22,	5,			number_format($suma_total,2,',','.'),												1,0,'C',1);
	$pdf->Cell(90,	5,			'',												1,0,'C',1);
	$pdf->Ln(5);
	$pdf->SetFont('arial','',10);
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
		$pdf->SetFont('arial','B',10);
		$pdf->Cell(155,	5,			'TOTAL COMPROMETIDO',												1,0,'C',1);
		$pdf->Cell(25,	5,			number_format($totaliza,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql2 ="
SELECT
	\"presupuesto_ejecutadoD\".fecha_anula,
	numero_precompromiso,
	\"orden_compra_servicioE\".numero_compromiso,
	numero_orden_compra_servicio,
	codigo_unidad_ejecutora,
	codigo_proyecto,
	codigo_accion_central,
	codigo_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	SUM((cantidad * monto)+(((cantidad * monto)/100)*impuesto)) AS monto,
	concepto,
	proveedor.nombre AS proveedor
FROM
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON 
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
INNER JOIN
	unidad_ejecutora
ON 
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON 
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON 
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 2
LEFT JOIN
	proyecto
ON 
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 1

INNER JOIN
	proveedor
ON 
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
WHERE
	\"presupuesto_ejecutadoD\".fecha_anula BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
GROUP BY
	\"presupuesto_ejecutadoD\".fecha_anula,
	numero_precompromiso,
	\"orden_compra_servicioE\".numero_compromiso,
	numero_orden_compra_servicio,
	codigo_unidad_ejecutora,
	codigo_proyecto,
	codigo_accion_central,
	codigo_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	concepto,
	proveedor.nombre 
ORDER BY
	\"orden_compra_servicioE\".numero_compromiso
	";
//die ($sql2);

/*
AND
	\"presupuesto_ejecutadoD\".fecha_anula <> ''
AND
	fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
*/
$row2=& $conn->Execute($sql2);
	$suma_total2 = 0;
	$tamano2=0;
	$totaliza2 = 0;
	$compriso_antes2 = 0 ;
	while (!$row2->EOF)
	{
	
	if($row2->fields('codigo_proyecto') != "")
		$pryecto_acc = $row2->fields('codigo_proyecto');
	elseif($row2->fields('codigo_accion_central') != "")
		$pryecto_acc = $row2->fields('codigo_accion_central');
	if(strlen($row2->fields('concepto'))<= 30)	
		$tamano2 = $tamano2 + 5;
	elseif((strlen($row2->fields('concepto'))>30) && (strlen($row2->fields('concepto'))<=60)	)
		$tamano2 = $tamano2 + 10;
	elseif((strlen($row2->fields('concepto'))>60)	&& (strlen($row2->fields('concepto'))<=90)	)
		$tamano2 = $tamano2 + 15;
	elseif((strlen($row2->fields('concepto'))>90)	&& (strlen($row2->fields('concepto'))<=120))	
		$tamano2 = $tamano2 + 20;
	elseif((strlen($row2->fields('concepto'))>120)	&& (strlen($row2->fields('concepto'))<=150))	
		$tamano2 = $tamano2 + 25;
	elseif((strlen($row2->fields('concepto'))>150)	&& (strlen($row2->fields('concepto'))<=180))	
		$tamano2 = $tamano2 + 30;
	elseif((strlen($row2->fields('concepto'))>180)	&& (strlen($row2->fields('concepto'))<=210))	
		$tamano2 = $tamano2 + 35;
	elseif((strlen($row2->fields('concepto'))>240)	&& (strlen($row2->fields('concepto'))<=270))	
		$tamano2 = $tamano2 + 40;
	elseif((strlen($row2->fields('concepto'))>270)	&& (strlen($row2->fields('concepto'))<=300))	
		$tamano2 = $tamano2 + 45;
	elseif((strlen($row2->fields('concepto'))>300)	&& (strlen($row2->fields('concepto'))<=330))	
		$tamano2 = $tamano2 + 50;
	elseif((strlen($row2->fields('concepto'))>330)	&& (strlen($row2->fields('concepto'))<=360))	
		$tamano2 = $tamano2 + 55;
	elseif((strlen($row2->fields('concepto'))>360)	&& (strlen($row2->fields('concepto'))<=390))	
		$tamano2 = $tamano2 + 60;

$fecha_compromiso=split('-',$row2->fields("fecha_anula"));
list($ano,$mes , $dia) = $fecha_compromiso;
$dia = substr ($dia, 0, 2);
$fecha2 = $dia.'-'.$mes.'-'.$ano;
	
		$pdf->SetTextColor(0) ;
		if($compriso_antes2 !=$row2->fields("numero_compromiso")){
			if (($compriso_antes2 !=$row2->fields("numero_compromiso")) &&($compriso_antes2 != 0)){
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(158,	5,			'TOTAL COMPROMISO',												1,0,'C',1);
				$pdf->Cell(22,	5,			'-'.number_format($suma_total2,2,',','.'),												1,0,'C',1);
				$pdf->Cell(90,	5,			'',												1,0,'C',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
			}
		$suma_total2 = 0;
			$pdf->Cell(20,	5,			$fecha2,												'T',0,'C',1);
			$pdf->Cell(26,	5,			$row2->fields('numero_precompromiso'),				'T',0,'C',1);
			$pdf->Cell(23,	5,			$row2->fields('numero_compromiso'),					'T',0,'C',1);
			$pdf->Cell(20,5,			$row2->fields('numero_orden_compra_servicio'),		'T',0,'C',1);
			$pdf->Cell(15,5,			$row2->fields('codigo_unidad_ejecutora'),			'T',0,'C',1);
			$pdf->Cell(17,5,			$pryecto_acc,										'T',0,'C',1);
			$pdf->Cell(15,5,			$row2->fields('codigo_accion_especifica'),			'T',0,'C',1);
			$pdf->Cell(22,5,			$row2->fields('partida').".".$row2->fields('generica').".".$row2->fields('especifica').".".$row2->fields('subespecifica'),						'T',0,'C',1);
			$pdf->Cell(22,5,			'-'.number_format($row2->fields('monto'),2,',','.'),		'T',0,'C',1);
			$y=$pdf->GetY();
			$pdf->MultiCell(40,	5,		utf8_decode($row2->fields('proveedor')),				'T','L',1);
			$pdf->SetXY(230,$y);
			$pdf->MultiCell(50,	5,		utf8_decode($row2->fields('concepto')),				'T','L',1);
			$suma_total2 = $row2->fields('monto');
		}else{
			$pdf->Cell(136,	5,			'',												0,0,'C',1);
			$pdf->Cell(22,5,			$row2->fields('partida').".".$row2->fields('generica').".".$row2->fields('especifica').".".$row2->fields('subespecifica'),						0,0,'C',1);
			$pdf->Cell(22,5,			'-'.number_format($row2->fields('monto'),2,',','.'),		0,0,'C',1);
			$pdf->MultiCell(90,	5,		'',				0,'L',1);
			$suma_total2 = $suma_total2 + $row2->fields('monto');
		}
		
		$compriso_antes2=$row2->fields("numero_compromiso");
		$totaliza2 = $totaliza2 + $row2->fields('monto');
		
/*
		$y=$pdf->GetY(); 
		//$y= $y+6;
		$pdf->MultiCell(60,	5,		utf8_decode($row2->fields('nombre')),			'T','C',1);
		$pdf->SetXY(110,$y);
		$pdf->MultiCell(70,	5,		utf8_decode($row2->fields('concepto')),			'T','L',1);
		$pdf->SetXY(180,$y);
		$pdf->Cell(20,5,			$row2->fields('codigo_accion_especifica'),		'T',0,'C',1);
		$pdf->Cell(20,	5,			number_format($row2->fields('monto'),2,',','.'),	'T',0,'C',1);
		$pdf->SetTextColor(255) ;
		*/
		
		//$pdf->Cell(16,	5,	/*number_format($calculo_tres,2,',','.')*/'',								'L',0,'R',1);
		//$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row2->MoveNext();
	}
		$pdf->SetFont('arial','B',10);
		$pdf->Cell(158,	5,			'TOTAL ANULACION',												1,0,'C',1);
		$pdf->Cell(22,	5,			'-'.number_format($suma_total2,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);

		$pdf->SetFont('arial','B',10);
		$pdf->Cell(155,	5,			'TOTAL COMPROMISOS ANULADOS',												1,0,'C',1);
		$pdf->Cell(25,	5,			number_format($totaliza2,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql3 ="
SELECT
	\"orden_compra_servicioD\".fecha_ajuste,
	numero_precompromiso,
	\"orden_compra_servicioE\".numero_compromiso,
	numero_orden_compra_servicio,
	codigo_unidad_ejecutora,
	codigo_proyecto,
	codigo_accion_central,
	codigo_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	SUM((cantidad * monto)+(((cantidad * monto)/100)*impuesto)) AS monto,
	concepto,
	proveedor.nombre AS proveedor
FROM
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON 
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
INNER JOIN
	unidad_ejecutora
ON 
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON 
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON 
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 2
LEFT JOIN
	proyecto
ON 
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 1

INNER JOIN
	proveedor
ON 
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
WHERE
	\"orden_compra_servicioD\".fecha_ajuste BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
GROUP BY
	\"orden_compra_servicioD\".fecha_ajuste,
	numero_precompromiso,
	\"orden_compra_servicioE\".numero_compromiso,
	numero_orden_compra_servicio,
	codigo_unidad_ejecutora,
	codigo_proyecto,
	codigo_accion_central,
	codigo_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	concepto,
	proveedor.nombre 
ORDER BY
	\"orden_compra_servicioE\".numero_compromiso
	";
//die ($sql2);

/*
AND
	\"presupuesto_ejecutadoD\".fecha_anula <> ''
AND
	fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
*/

$row3=& $conn->Execute($sql3);
if (!$row3->EOF){
	$suma_total3 = 0;
	$tamano3=0;
	$totaliza3 = 0;
	$compriso_antes3 = 0 ;
	while (!$row3->EOF)
	{
	
	if($row3->fields('codigo_proyecto') != "")
		$pryecto_acc = $row3->fields('codigo_proyecto');
	elseif($row3->fields('codigo_accion_central') != "")
		$pryecto_acc = $row3->fields('codigo_accion_central');
	if(strlen($row3->fields('concepto'))<= 30)	
		$tamano3 = $tamano3 + 5;
	elseif((strlen($row3->fields('concepto'))>30) && (strlen($row3->fields('concepto'))<=60)	)
		$tamano3 = $tamano3 + 10;
	elseif((strlen($row3->fields('concepto'))>60)	&& (strlen($row3->fields('concepto'))<=90)	)
		$tamano3 = $tamano3 + 15;
	elseif((strlen($row3->fields('concepto'))>90)	&& (strlen($row3->fields('concepto'))<=120))	
		$tamano3 = $tamano3 + 20;
	elseif((strlen($row3->fields('concepto'))>120)	&& (strlen($row3->fields('concepto'))<=150))	
		$tamano3 = $tamano3 + 25;
	elseif((strlen($row3->fields('concepto'))>150)	&& (strlen($row3->fields('concepto'))<=180))	
		$tamano3 = $tamano3 + 30;
	elseif((strlen($row3->fields('concepto'))>180)	&& (strlen($row3->fields('concepto'))<=210))	
		$tamano3 = $tamano3 + 35;
	elseif((strlen($row3->fields('concepto'))>240)	&& (strlen($row3->fields('concepto'))<=270))	
		$tamano3 = $tamano3 + 40;
	elseif((strlen($row3->fields('concepto'))>270)	&& (strlen($row3->fields('concepto'))<=300))	
		$tamano3 = $tamano3 + 45;
	elseif((strlen($row3->fields('concepto'))>300)	&& (strlen($row3->fields('concepto'))<=330))	
		$tamano3 = $tamano3 + 50;
	elseif((strlen($row3->fields('concepto'))>330)	&& (strlen($row3->fields('concepto'))<=360))	
		$tamano3 = $tamano3 + 55;
	elseif((strlen($row3->fields('concepto'))>360)	&& (strlen($row3->fields('concepto'))<=390))	
		$tamano3 = $tamano3 + 60;

$fecha_compromiso=split('-',$row3->fields("fecha_ajuste"));
list($ano,$mes , $dia) = $fecha_compromiso;
$dia = substr ($dia, 0, 2);
$fecha3 = $dia.'-'.$mes.'-'.$ano;
	
		$pdf->SetTextColor(0) ;
		if($compriso_antes3 !=$row3->fields("numero_compromiso")){
			if (($compriso_antes3 !=$row3->fields("numero_compromiso")) &&($compriso_antes3 != 0)){
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(158,	5,			'TOTAL AJUSTE',												1,0,'C',1);
				$pdf->Cell(22,	5,			number_format($suma_total3,2,',','.'),												1,0,'C',1);
				$pdf->Cell(90,	5,			'',												1,0,'C',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
			}
		$suma_total3 = 0;
			$pdf->Cell(20,	5,			$fecha3,												'T',0,'C',1);
			$pdf->Cell(26,	5,			$row3->fields('numero_precompromiso'),				'T',0,'C',1);
			$pdf->Cell(23,	5,			$row3->fields('numero_compromiso'),					'T',0,'C',1);
			$pdf->Cell(20,5,			$row3->fields('numero_orden_compra_servicio'),		'T',0,'C',1);
			$pdf->Cell(15,5,			$row3->fields('codigo_unidad_ejecutora'),			'T',0,'C',1);
			$pdf->Cell(17,5,			$pryecto_acc,										'T',0,'C',1);
			$pdf->Cell(15,5,			$row3->fields('codigo_accion_especifica'),			'T',0,'C',1);
			$pdf->Cell(22,5,			$row3->fields('partida').".".$row3->fields('generica').".".$row3->fields('especifica').".".$row3->fields('subespecifica'),						'T',0,'C',1);
			$pdf->Cell(22,5,			number_format($row3->fields('monto'),2,',','.'),		'T',0,'C',1);
			$y=$pdf->GetY();
			$pdf->MultiCell(40,	5,		utf8_decode($row3->fields('proveedor')),				'T','L',1);
			$pdf->SetXY(230,$y);
			$pdf->MultiCell(50,	5,		utf8_decode($row3->fields('concepto')),				'T','L',1);
			$suma_total3 = $row3->fields('monto');
		}else{
			$pdf->Cell(136,	5,			'',												0,0,'C',1);
			$pdf->Cell(22,5,			$row3->fields('partida').".".$row3->fields('generica').".".$row3->fields('especifica').".".$row3->fields('subespecifica'),						0,0,'C',1);
			$pdf->Cell(22,5,			number_format($row3->fields('monto'),2,',','.'),		0,0,'C',1);
			$pdf->MultiCell(90,	5,		'',				0,'L',1);
			$suma_total3 = $suma_total3 + $row3->fields('monto');
		}
		
		$compriso_antes3=$row3->fields("numero_compromiso");
		$totaliza3 = $totaliza3 + $row3->fields('monto');
		
/*
		$y=$pdf->GetY(); 
		//$y= $y+6;
		$pdf->MultiCell(60,	5,		utf8_decode($row3->fields('nombre')),			'T','C',1);
		$pdf->SetXY(110,$y);
		$pdf->MultiCell(70,	5,		utf8_decode($row3->fields('concepto')),			'T','L',1);
		$pdf->SetXY(180,$y);
		$pdf->Cell(20,5,			$row3->fields('codigo_accion_especifica'),		'T',0,'C',1);
		$pdf->Cell(20,	5,			number_format($row3->fields('monto'),2,',','.'),	'T',0,'C',1);
		$pdf->SetTextColor(255) ;
		*/
		
		//$pdf->Cell(16,	5,	/*number_format($calculo_tres,2,',','.')*/'',								'L',0,'R',1);
		//$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row3->MoveNext();
	}
		$pdf->SetFont('arial','B',10);
		$pdf->Cell(158,	5,			'TOTAL AJUSTE',												1,0,'C',1);
		$pdf->Cell(22,	5,			number_format($suma_total3,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);

		$pdf->SetFont('arial','B',10);
		$pdf->Cell(155,	5,			'TOTAL COMPROMISOS AJUSTADO',												1,0,'C',1);
		$pdf->Cell(25,	5,			number_format($totaliza3,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);
		$totaliza = $totaliza + $totaliza3; 
}
	if ($totaliza2 <> 0)
		$total_total = $totaliza - $totaliza2;
	else
		$total_total = $totaliza;
		$pdf->SetFont('arial','B',10);
		$pdf->Cell(155,	5,			'TOTAL GENERAL COMPROMETIDO',												1,0,'C',1);
		$pdf->Cell(25,	5,			number_format($total_total,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);

/*	$pdf->SetTextColor(0) ;
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
$pdf->SetFont('Arial','',9);*/
//$pdf->Ln(7);
$pdf->AddPage('L');
$sql_monto_partida ="
SELECT
	partida,
	SUM((cantidad * monto)+(((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON 
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
INNER JOIN
	unidad_ejecutora
ON 
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON 
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON 
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 2
LEFT JOIN
	proyecto
ON 
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 1
INNER JOIN
	proveedor
ON 
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
WHERE
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	ajuste = 0
GROUP BY
	partida
ORDER BY
	partida
";
$row_monto_partida=& $conn->Execute($sql_monto_partida);
//*************************************************************************
$pdf->SetFont('Arial','B',9);
		$pdf->Cell(15,5,			'Partida',						1,0,'C',1);
		$pdf->Cell(30,5,			'Monto',	1,0,'C',1);
		$pdf->Ln(5);
		$suma_partidas =  0;
		$pdf->SetFont('Arial','',9);
while (!$row_monto_partida->EOF)
	{
	$sql_monto_partida2 ="
SELECT
	partida,
	SUM((cantidad * monto)+(((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON 
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
INNER JOIN
	unidad_ejecutora
ON 
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON 
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON 
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 2
LEFT JOIN
	proyecto
ON 
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 1
INNER JOIN
	proveedor
ON 
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
WHERE
	\"presupuesto_ejecutadoD\".fecha_anula BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"orden_compra_servicioD\".partida = '".$row_monto_partida->fields('partida')."'
GROUP BY
	partida
ORDER BY
	partida	
	
";
$row_monto_partida2=& $conn->Execute($sql_monto_partida2);
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_monto_partida3 ="
SELECT
	partida,
	SUM((cantidad * monto)+(((cantidad * monto)/100)*impuesto)) AS monto
FROM
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON 
	\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
INNER JOIN
	\"presupuesto_ejecutadoD\"
ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
INNER JOIN
	unidad_ejecutora
ON 
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON 
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON 
	accion_centralizada.id_accion_central = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 2
LEFT JOIN
	proyecto
ON 
	proyecto.id_proyecto = \"orden_compra_servicioE\".id_proyecto_accion_centralizada
	AND
	\"orden_compra_servicioE\".tipo = 1
INNER JOIN
	proveedor
ON 
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor
WHERE
	\"orden_compra_servicioD\".fecha_ajuste BETWEEN  '".$desde."' AND '".$hasta."'
AND
	ajuste = 1
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
AND
	\"orden_compra_servicioD\".partida = '".$row_monto_partida->fields('partida')."'
GROUP BY
	partida
ORDER BY
	partida
";

$row_monto_partida3=& $conn->Execute($sql_monto_partida3);
////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
$mont = $row_monto_partida->fields('monto');
if(!$row_monto_partida3->EOF){
	$mont = ($mont + $row_monto_partida3->fields('monto'));
}
if(!$row_monto_partida2->EOF){
	$mont = $mont  - $row_monto_partida2->fields('monto');
}
		$suma_partidas = $suma_partidas +$mont;
		$pdf->Cell(15,5,			$row_monto_partida->fields('partida'),	1,0,'C',1);
		$pdf->Cell(30,5,			number_format($mont,2,',','.'),	1,0,'R',1);
		$pdf->Ln(5);
		$row_monto_partida->MoveNext();
	}
	$pdf->SetFont('Arial','B',9);
		$pdf->Cell(15,5,			'Total',						1,0,'C',1);
		$pdf->Cell(30,5,			number_format($suma_partidas,2,',','.'),	1,0,'R',1);
		$pdf->Ln(7);

		
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