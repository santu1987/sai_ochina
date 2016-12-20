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

$desde = $_GET['precompromisos_rp_desde'];
$hasta = $_GET['precompromisos_rp_hasta'];
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

//$desde = '01/01/2011';
//$hasta = '01/03/2011';


	/*$where = $where."
	AND
		id_accion_especifica = 225 
	";*/
$sql ="
SELECT
	fecha_elabora,
	numero_precompromiso,
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
	fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
AND
	numero_compromiso = '0'
AND
	\"orden_compra_servicioE\".estatus <> 3
GROUP BY
	fecha_elabora,
	numero_precompromiso,
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
	fecha_elabora,
	numero_precompromiso,
	partida,
	generica,
	especifica,
	subespecifica

	";
//die ($sql);

/*
AND
	\"presupuesto_ejecutadoD\".fecha_anula <> ''
AND
	fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
*/
$row=& $conn->Execute($sql);

if (!$row->EOF){
//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de p�gina
		function Header()
		{		
			global $unidad_ejecutora,  $ano, $codigo_unidad,  $texto;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',9);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			//$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'REPORTE DE PRECOMPROMISOS',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(20,				6,		'FECHA',						0,0,'C',1);
			$this->Cell(30,				6,		'PRECOMPROMISO',							0,0,'C',1);
			$this->Cell(20,				6,		'UNIDAD',							0,0,'C',1);
			$this->Cell(20,				6,		'PROYECTO',							0,0,'C',1);
			$this->Cell(20,				6,		'ACC. ESP.',							0,0,'C',1);
			$this->Cell(25,				6,		'CUENTA',							0,0,'C',1);
			$this->Cell(25,				6,		'MONTO',							0,0,'C',1);
			$this->Cell(55,				6,		'PROVEEDOR',							0,0,'C',1);
			$this->Cell(60,				6,		'CONCEPTO',							0,0,'C',1);
			//$this->SetXY(193,$y);
			//$this->SetFont('Arial','B',7);
			//$this->MultiCell(19,		12,		'PORCENTAJE',					0,'C',1);
			$this->Ln(6);
		}
		//Pie de p�gina
		function Footer()
		{
			//Posici�n: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//N�mero de p�gina
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
	if(strlen($row->fields('concepto'))<= 40)	
		$tamano = $tamano + 5;
	elseif((strlen($row->fields('concepto'))>40) && (strlen($row->fields('concepto'))<=80)	)
		$tamano = $tamano + 10;
	elseif((strlen($row->fields('concepto'))>80)	&& (strlen($row->fields('concepto'))<=120)	)
		$tamano = $tamano + 15;
	elseif((strlen($row->fields('concepto'))>120)	&& (strlen($row->fields('concepto'))<=160))	
		$tamano = $tamano + 20;
	elseif((strlen($row->fields('concepto'))>160)	&& (strlen($row->fields('concepto'))<=200))	
		$tamano = $tamano + 25;
	elseif((strlen($row->fields('concepto'))>200)	&& (strlen($row->fields('concepto'))<=240))	
		$tamano = $tamano + 30;
	elseif((strlen($row->fields('concepto'))>240)	&& (strlen($row->fields('concepto'))<=280))	
		$tamano = $tamano + 35;
	elseif((strlen($row->fields('concepto'))>280)	&& (strlen($row->fields('concepto'))<=320))	
		$tamano = $tamano + 40;
	elseif((strlen($row->fields('concepto'))>320)	&& (strlen($row->fields('concepto'))<=360))	
		$tamano = $tamano + 45;
	elseif((strlen($row->fields('concepto'))>360)	&& (strlen($row->fields('concepto'))<=400))	
		$tamano = $tamano + 50;
	elseif((strlen($row->fields('concepto'))>440)	&& (strlen($row->fields('concepto'))<=480))	
		$tamano = $tamano + 55;
	elseif((strlen($row->fields('concepto'))>480)	&& (strlen($row->fields('concepto'))<=520))	
		$tamano = $tamano + 60;
	/*	
	if ($tamano >= 75){
		$pdf->AddPage('L');
		$tamano  = 0;
	}*/
/*		
$sql_deta="SELECT   
	fecha_elabora 
FROM 
	\"presupuesto_ejecutadoD\"
WHERE
	numero_documento='".$row->fields('numero_orden_compra_servicio')."'
AND 
	numero_precompromiso='".$row->fields('numero_precompromiso')."'
";
$row_deta=& $conn->Execute($sql_deta);*/

$fecha_elabora=split('-',$row->fields("fecha_elabora"));
list($ano,$mes , $dia) = $fecha_elabora;
$dia = substr ($dia, 0, 2);
$fecha = $dia.'-'.$mes.'-'.$ano;
	
		$pdf->SetTextColor(0) ;
		if($compriso_antes !=$row->fields("numero_precompromiso")){
			if (($compriso_antes !=$row->fields("numero_precompromiso")) &&($compriso_antes != 0)){
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(135,	5,			'TOTAL PRECOMPROMISO',												1,0,'C',1);
				$pdf->Cell(25,	5,			number_format($suma_total,2,',','.'),												1,0,'C',1);
				$pdf->Cell(115,	5,			'',												1,0,'C',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
			}
		$suma_total = 0;
			$pdf->Cell(20,	5,			$fecha,												'T',0,'C',1);
			$pdf->Cell(30,	5,			$row->fields('numero_precompromiso'),				'T',0,'C',1);
			$pdf->Cell(20,5,			$row->fields('codigo_unidad_ejecutora'),			'T',0,'C',1);
			$pdf->Cell(20,5,			$pryecto_acc,										'T',0,'C',1);
			$pdf->Cell(20,5,			$row->fields('codigo_accion_especifica'),			'T',0,'C',1);
			$pdf->Cell(25,5,			$row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('subespecifica'),						'T',0,'C',1);
			$pdf->Cell(25,5,			number_format($row->fields('monto'),2,',','.'),		'T',0,'C',1);
			$y=$pdf->GetY();
			$pdf->MultiCell(55,	5,		utf8_decode($row->fields('proveedor')),				'T','L',1);
			$pdf->SetXY(220,$y);
			$pdf->MultiCell(65,	5,		utf8_decode($row->fields('concepto')),				'T','L',1);
			$suma_total = $row->fields('monto');
		}else{
			$pdf->Cell(110,	5,			'',												0,0,'C',1);
			$pdf->Cell(25,5,			$row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('subespecifica'),						0,0,'C',1);
			$pdf->Cell(25,5,			number_format($row->fields('monto'),2,',','.'),		0,0,'C',1);
			$pdf->MultiCell(120,	5,		'',				0,'L',1);
			$suma_total = $suma_total + $row->fields('monto');
		}
		
		$compriso_antes=$row->fields("numero_precompromiso");
		$totaliza = $totaliza + $row->fields('monto');
		
/*
		$y=$pdf->GetY(); 
		//$y= $y+6;
		$pdf->MultiCell(60,	5,		utf8_decode($row->fields('nombre')),			'T','C',1);
		$pdf->SetXY(110,$y);
		$pdf->MultiCell(70,	5,		utf8_decode($row->fields('concepto')),			'T','L',1);
		$pdf->SetXY(180,$y);
		$pdf->Cell(20,5,			$row->fields('codigo_accion_especifica'),		'T',0,'C',1);
		$pdf->Cell(20,	5,			number_format($row->fields('monto'),2,',','.'),	'T',0,'C',1);
		$pdf->SetTextColor(255) ;
		*/
		
		//$pdf->Cell(16,	5,	/*number_format($calculo_tres,2,',','.')*/'',								'L',0,'R',1);
		//$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row->MoveNext();
	}
		$pdf->SetFont('arial','B',10);
		$pdf->Cell(135,	5,			'TOTAL PRECOMPROMETIDO',												1,0,'C',1);
		$pdf->Cell(25,	5,			number_format($totaliza,2,',','.'),												1,0,'C',1);
		$pdf->Cell(115,	5,			'',												1,0,'C',1);
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
	\"presupuesto_ejecutadoD\".numero_precompromiso = \"orden_compra_servicioE\".numero_precompromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
WHERE
	\"orden_compra_servicioE\".id_organismo = 1
AND
	\"orden_compra_servicioE\".numero_precompromiso <> '0'
AND
	\"presupuesto_ejecutadoD\".fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
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
	fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso = '0'
AND
	\"orden_compra_servicioE\".estatus <> 3
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
	
	$pdf->Output();
}else{
	class PDF extends FPDF
	{
		//Cabecera de p�gina
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',11);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
		}
		//Pie de p�gina
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