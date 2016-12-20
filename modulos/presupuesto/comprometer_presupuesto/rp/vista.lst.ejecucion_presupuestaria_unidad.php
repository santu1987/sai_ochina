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
$unidad = $_GET['unidad'];
$desde = $_GET['ejecucion_presupuestaria_rp_desde'];
$hasta = $_GET['ejecucion_presupuestaria_rp_hasta'];
//$desde = '01/01/2011';
//$hasta = '01/03/2011';

list($anoxx,$mesxx , $diaxx) = $desde;
list($anoyy,$mesyy , $diayy) = $hasta;
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
//$dia = substr ($dia, 0, 2);
	/*$where = $where."
	AND
		id_accion_especifica = 225 
	";*/
	$sql_unidad = "
SELECT id_unidad_ejecutora, nombre
       
       
  FROM unidad_ejecutora
WHERE
codigo_unidad_ejecutora = '".$unidad."'
";
$row_unidad =& $conn->Execute($sql_unidad);
if (!$row_unidad->EOF){
	$id_unidad = $row_unidad->fields("id_unidad_ejecutora");
}else{
	$id_unidad = 0;
}

$sql ="
SELECT
	\"presupuesto_ejecutadoR\".partida,
	\"presupuesto_ejecutadoR\".generica,
	\"presupuesto_ejecutadoR\".especifica,
	\"presupuesto_ejecutadoR\".sub_especifica
FROM
	\"presupuesto_ejecutadoR\"
WHERE
	id_unidad_ejecutora = ".$id_unidad."

GROUP BY
	
	\"presupuesto_ejecutadoR\".partida,
	\"presupuesto_ejecutadoR\".generica,
	\"presupuesto_ejecutadoR\".especifica,
	\"presupuesto_ejecutadoR\".sub_especifica
ORDER BY
	\"presupuesto_ejecutadoR\".partida,
	\"presupuesto_ejecutadoR\".generica,
	\"presupuesto_ejecutadoR\".especifica,
	\"presupuesto_ejecutadoR\".sub_especifica
	";
//die ($sql);

/*
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
INNER JOIN
	\"orden_compra_servicioD\"
ON
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
WHERE
	\"orden_compra_servicioE\".fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'


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
			$this->Cell(0,10,'EJECUCION PRESUPUESTARIA',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(24,				6,		'CUENTA',						0,0,'C',1);
			$this->Cell(28,				6,		'PRESUPUESTO LEY',				0,0,'C',1);
			$this->Cell(28,				6,		'TRASPASO Y/O',					0,0,'C',1);
			$this->Cell(24,				6,		'MODIFICADO',					0,0,'C',1);
			$this->Cell(24,				6,		'PRESUPUESTO',					0,0,'C',1);
			$this->Cell(28,				6,		'PRECOMPROMISO',				0,0,'C',1);
			$this->Cell(24,				6,		'COMPROMISO',					0,0,'C',1);
			$this->Cell(25,				6,		'CAUSADO',						0,0,'C',1);
			$this->Cell(25,				6,		'PAGADO',						0,0,'C',1);
			$this->Cell(25,				6,		'DISPONIBILIDAD',				0,0,'C',1);
			$this->Cell(25,				6,		'DISPONIBLE',					0,0,'C',1);
			$this->Ln();
			$this->Cell(52,				6,		' ',							0,0,'C',1);
			$this->Cell(32,				6,		'REPROGRAMACION',				0,0,'C',1);
			$this->Cell(148,				6,		'',								0,0,'C',1);
			$this->Cell(24,				6,		'PRESUPUESTARIA',				0,0,'C',1);
			$this->Cell(24,				6,		'TOTAL',						0,0,'C',1);
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
	$partidas_antes = 0;
	$genericas_antes = 0;
		//****************************************************************************************************************************
	$ff = 0;
	$mest = $mesxx;
while($mest<=$mesyy){
	if ($ff == 0){
		$monoto = "monto_presupuesto [".$mest."]";
		$traspasado = "monto_traspasado [".$mest."]";
		$modificado = "monto_modificado [".$mest."]";
		$comprometido = "monto_comprometido [".$mest."]";
		$causado = "monto_causado [".$mest."]";
		$pagado = "monto_pagado [".$mest."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$mest.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$mest.']';
		$modificado = $modificado.' + monto_modificado ['.$mest.']';
		$comprometido = $comprometido.' + monto_comprometido ['.$mest.']';
		$causado = $causado.' + monto_causado ['.$mest.']';
		$pagado = $pagado.' + monto_pagado ['.$mest.']';
	}
$ff++;
$mest++;

}
	
	$sql_partito="
	SELECT 
		SUM(".$monoto.") AS presupuesto,
		SUM(".$traspasado.") AS monto_traspasado,
		SUM(".$modificado.") AS monto_modificado,
		SUM(".$causado.") AS monto_causado,
		SUM(".$pagado.") AS monto_pagado
	FROM
		\"presupuesto_ejecutadoR\"
		WHERE
		id_unidad_ejecutora = ".$id_unidad."

		";
		//die($sql_partid);
	$row_todo=& $conn->Execute($sql_partito);
//**************************************************************************
$sqlprecomprometido_todo	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_precompromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	WHERE
		numero_compromiso = '0'
	AND
		estatus <> 3
	AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
	AND
		id_unidad_ejecutora = ".$id_unidad."
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
$rowprecomprometido_todo=& $conn->Execute($sqlprecomprometido_todo);	
$sqlcomprometido_todo	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_compromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

	WHERE
		
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 0
	AND
		id_unidad_ejecutora = ".$id_unidad."
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql2 ="
SELECT
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
WHERE
	\"presupuesto_ejecutadoD\".fecha_anula BETWEEN   '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		id_unidad_ejecutora = ".$id_unidad."
	";
		
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql3 ="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

	WHERE
		
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"orden_compra_servicioD\".fecha_ajuste BETWEEN   '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 1
	AND
		id_unidad_ejecutora = ".$id_unidad."
	";
	//die($sql3);

//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
	$rowcomprometido_todo=& $conn->Execute($sqlcomprometido_todo);	
	$row2=& $conn->Execute($sql2);
	$row3_ajuste=& $conn->Execute($sql3);
	$ajuste = 0;
	$anula = 0;
	if (!$row2->EOF){
		$anula = $row2->fields('monto');
	}
	if (!$row3_ajuste->EOF){
		$ajuste = $row3_ajuste->fields('monto');
	}
	$comprometido_todo = ($rowcomprometido_todo->fields('numero_compromiso') + $ajuste) -$anula;
$correjido_monto =  $row_todo->fields("presupuesto") +$row_todo->fields("monto_traspasado") +$row_todo->fields("monto_modificado");
$dispo_presuxx = $correjido_monto - $rowcomprometido_todo->fields('numero_compromiso') ; 
$dispo_totalxx = $correjido_monto -( $rowprecomprometido_todo->fields('numero_precompromiso') + $rowcomprometido_todo->fields('numero_compromiso'));
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(23,	5,			'400.00.00.00',						'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($row_todo->fields("presupuesto"),2,',','.'),				'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($row_todo->fields("monto_traspasado"),2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(24,	5,			number_format($row_todo->fields("monto_modificado"),2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(24,	5,			number_format($correjido_monto,2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowprecomprometido_todo->fields('numero_precompromiso'),2,',','.'),	'TRL',0,'R',1);
				$pdf->Cell(24,	5,			number_format($comprometido_todo ,2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($row_todo->fields("monto_causado"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($row_todo->fields("monto_pagado"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($dispo_presuxx ,2,',','.'),												'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($dispo_totalxx,2,',','.'),												'TRL',0,'R',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
	
	
	
	
	//******************************************************************************************************************************

	while (!$row->EOF)
	{
$ii = 0;
while($mesxx<=$mesyy){
	if ($ii == 0){
		$monoto = "monto_presupuesto [".$mesxx."]";
		$traspasado = "monto_traspasado [".$mesxx."]";
		$modificado = "monto_modificado [".$mesxx."]";
		$comprometido = "monto_comprometido [".$mesxx."]";
		$causado = "monto_causado [".$mesxx."]";
		$pagado = "monto_pagado [".$mesxx."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$mesxx.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$mesxx.']';
		$modificado = $modificado.' + monto_modificado ['.$mesxx.']';
		$comprometido = $comprometido.' + monto_comprometido ['.$mesxx.']';
		$causado = $causado.' + monto_causado ['.$mesxx.']';
		$pagado = $pagado.' + monto_pagado ['.$mesxx.']';
	}
$ii++;
$mesxx++;

}
//************************************************************************
	$sqloo="
	SELECT 
		SUM(".$monoto.") AS presupuesto,
		SUM(".$traspasado.") AS monto_traspasado,
		SUM(".$modificado.") AS monto_modificado,
		SUM(".$causado.") AS monto_causado,
		SUM(".$pagado.") AS monto_pagado
		
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		especifica = '".$row->fields('especifica')."'
	AND
		sub_especifica = '".$row->fields('sub_especifica')."'
		AND
		id_unidad_ejecutora = ".$id_unidad."

		GROUP BY
	\"presupuesto_ejecutadoR\".partida,
	\"presupuesto_ejecutadoR\".generica,
	\"presupuesto_ejecutadoR\".especifica,
	\"presupuesto_ejecutadoR\".sub_especifica
	";
$rowoo=& $conn->Execute($sqloo);

$presu_correj = $rowoo->fields('presupuesto') + $rowoo->fields('monto_traspasado') + $rowoo->fields('monto_modificado');

$sqlprecomprometido	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_precompromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		especifica = '".$row->fields('especifica')."'
	AND
		subespecifica = '".$row->fields('sub_especifica')."'
	AND
		numero_compromiso = '0'
	AND
		estatus <> 3
	AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
$rowprecomprometido=& $conn->Execute($sqlprecomprometido);


$sqlcomprometido	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_compromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		especifica = '".$row->fields('especifica')."'
	AND
		subespecifica = '".$row->fields('sub_especifica')."'
	AND
		
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 0
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
$rowcomprometido=& $conn->Execute($sqlcomprometido);	
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql2_anula ="
SELECT
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
WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		especifica = '".$row->fields('especifica')."'
	AND
		subespecifica = '".$row->fields('sub_especifica')."'
	AND
	\"presupuesto_ejecutadoD\".fecha_anula BETWEEN   '".$desde."' AND '".$hasta."'
	AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
		
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql3_ajuste ="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		especifica = '".$row->fields('especifica')."'
	AND
		subespecifica = '".$row->fields('sub_especifica')."'
	AND		
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"orden_compra_servicioD\".fecha_ajuste BETWEEN   '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 1
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
	//die($sql3);

//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
	$row2_anula=& $conn->Execute($sql2_anula);
	$row4_ajuste=& $conn->Execute($sql3_ajuste);
	$ajuste = 0;
	$anula = 0;
	if (!$row2_anula->EOF){
		$anula = $row2_anula->fields('monto');
	}
	if (!$row4_ajuste->EOF){
		$ajuste = $row4_ajuste->fields('monto');
	}
	$comprometido = ($rowcomprometido->fields('numero_compromiso') + $ajuste) -$anula;


	
	$pdf->SetTextColor(0) ;
	if($partidas_antes !=$row->fields("partida")){
		
	$sql_partid="
	SELECT 
		\"presupuesto_ejecutadoR\".partida,
		SUM(".$monoto.") AS presupuesto,
		SUM(".$traspasado.") AS monto_traspasado,
		SUM(".$modificado.") AS monto_modificado,
		SUM(".$causado.") AS monto_causado,
		SUM(".$pagado.") AS monto_pagado
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		partida = '".$row->fields("partida")."'
			AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
		\"presupuesto_ejecutadoR\".partida
	ORDER BY
		\"presupuesto_ejecutadoR\".partida
		";
		//die($sql_partid);
	$rowpartid=& $conn->Execute($sql_partid);
//**************************************************************************
$sqlprecomprometido_par	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_precompromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
		
	WHERE
		partida = '".$row->fields('partida')."'
	AND
		numero_compromiso = '0'
	AND
		estatus <> 3
	AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
$rowprecomprometido_par=& $conn->Execute($sqlprecomprometido_par);	
$sqlcomprometido_par	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_compromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
		INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
	
	WHERE
		partida = '".$row->fields('partida')."'
	AND
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 0
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql2_anula_par ="
SELECT
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
WHERE
		partida = '".$row->fields('partida')."'
	AND
	\"presupuesto_ejecutadoD\".fecha_anula BETWEEN   '".$desde."' AND '".$hasta."'
	AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
		
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql3_ajuste_par ="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

	WHERE
		partida = '".$row->fields('partida')."'
	AND		
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"orden_compra_servicioD\".fecha_ajuste BETWEEN   '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 1
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
	//die($sql3);

//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
$rowcomprometido_par=& $conn->Execute($sqlcomprometido_par);	

	$row2_anul_para=& $conn->Execute($sql2_anula_par);
	$row4_ajuste_par=& $conn->Execute($sql3_ajuste_par);
	$ajuste_par = 0;
	$anula_par = 0;
	if (!$row2_anul_para->EOF){
		$anula_par = $row2_anul_para->fields('monto');
	}
	if (!$row4_ajuste_par->EOF){
		$ajuste_par = $row4_ajuste_par->fields('monto');
	}
	$comprometido_par = ($rowcomprometido_par->fields('numero_compromiso') + $ajuste_par) -$anula_par;
	

$correjido_monto =  $rowpartid->fields("presupuesto") +$rowpartid->fields("monto_traspasado") +$rowpartid->fields("monto_modificado");
$dispo_presuxx = $correjido_monto - $rowcomprometido_par->fields('numero_compromiso') ; 
$dispo_totalxx = $correjido_monto -( $rowprecomprometido_par->fields('numero_precompromiso') + $rowcomprometido_par->fields('numero_compromiso'));
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(23,	5,			$rowpartid->fields("partida").'.00'.'.00'.'.00',						'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowpartid->fields("presupuesto"),2,',','.'),				'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowpartid->fields("monto_traspasado"),2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(24,	5,			number_format($rowpartid->fields("monto_modificado"),2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(24,	5,			number_format($correjido_monto,2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowprecomprometido_par->fields('numero_precompromiso'),2,',','.'),	'TRL',0,'R',1);
				$pdf->Cell(24,	5,			number_format($comprometido_par,2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($rowpartid->fields("monto_causado"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($rowpartid->fields("monto_pagado"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($dispo_presuxx ,2,',','.'),												'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($dispo_totalxx,2,',','.'),												'TRL',0,'R',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
			$partidas_antes = $rowpartid->fields("partida");
	}
	
	if($genericas_antes !=$row->fields("generica")){
		
	$sql_gene="
	SELECT 
		\"presupuesto_ejecutadoR\".partida,
		\"presupuesto_ejecutadoR\".generica,		
		SUM(".$monoto.") AS presupuesto,
		SUM(".$traspasado.") AS monto_traspasado,
		SUM(".$modificado.") AS monto_modificado,
		SUM(".$causado.") AS monto_causado,
		SUM(".$pagado.") AS monto_pagado
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		partida = '".$row->fields("partida")."'
	AND
		generica = '".$row->fields('generica')."'
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
		\"presupuesto_ejecutadoR\".partida,
		\"presupuesto_ejecutadoR\".generica
	ORDER BY
		\"presupuesto_ejecutadoR\".partida,
		\"presupuesto_ejecutadoR\".generica
		";
		//die($sql_gene);
	$rowgene=& $conn->Execute($sql_gene);
//**************************************************************************
$sqlprecomprometido_gene	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_precompromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		numero_compromiso = '0'
	AND
		estatus <> 3
	AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '".$desde."' AND '".$hasta."'
	AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
$rowprecomprometido_gene=& $conn->Execute($sqlprecomprometido_gene);	
$sqlcomprometido_gene	="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS numero_compromiso
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio
	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 0
	AND
		id_unidad_ejecutora = ".$id_unidad."
	GROUP BY
	partida,
	generica
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011'

	*/
	//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql2_anula_gen ="
SELECT
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
WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND
	\"presupuesto_ejecutadoD\".fecha_anula BETWEEN   '".$desde."' AND '".$hasta."'
	AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'
		AND
		id_unidad_ejecutora = ".$id_unidad."

	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
		//die($sql2_anula_gen);
//*****************************************************************************************************************************	
//*****************************************************************************************************************************	


	$sql3_ajuste_gen ="
SELECT 
		SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS monto
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso =\"orden_compra_servicioD\".numero_pre_orden
	INNER JOIN
	\"presupuesto_ejecutadoD\"
	ON 
	\"presupuesto_ejecutadoD\".numero_compromiso = \"orden_compra_servicioE\".numero_compromiso
	AND
	\"presupuesto_ejecutadoD\".numero_documento = \"orden_compra_servicioE\".numero_orden_compra_servicio

	WHERE
		partida = '".$row->fields('partida')."'
	AND
		generica = '".$row->fields('generica')."'
	AND		
		\"orden_compra_servicioE\".numero_compromiso <> '0'
	AND
		\"orden_compra_servicioD\".fecha_ajuste BETWEEN   '".$desde."' AND '".$hasta."'
	AND
		\"orden_compra_servicioD\".ajuste = 1
	AND
		id_unidad_ejecutora = ".$id_unidad."
	GROUP BY
	partida,
	generica,
	especifica,
	subespecifica
	";
	//die($sql3);

//*****************************************************************************************************************************	
//*****************************************************************************************************************************	
$rowcomprometido_gene=& $conn->Execute($sqlcomprometido_gene);	

	$row2_anul_gen=& $conn->Execute($sql2_anula_gen);
	$row4_ajuste_gen=& $conn->Execute($sql3_ajuste_gen);
	$ajuste_gen = 0;
	$anula_gen = 0;
	if (!$row2_anul_gen->EOF){
		$anula_gen = $row2_anul_gen->fields('monto');
	}
	if (!$row4_ajuste_gen->EOF){
		$ajuste_gen = $row4_ajuste_gen->fields('monto');
	}
	$comprometido_gen = ($rowcomprometido_gene->fields('numero_compromiso') + $ajuste_gen) -$anula_gen;


$correjido_montoxl =  $rowgene->fields("presupuesto") + $rowgene->fields("monto_traspasado") +$rowgene->fields("monto_modificado");
$dispo_presuxxly = $correjido_montoxl - $rowcomprometido_gene->fields('numero_compromiso') ; 
$dispo_presuxxl = $correjido_montoxl -( $rowprecomprometido_gene->fields('numero_precompromiso') + $rowcomprometido_gene->fields('numero_compromiso'));
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(23,	5,			$rowgene->fields("partida").'.'.$rowgene->fields("generica").'.00'.'.00',		'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowgene->fields("presupuesto"),2,',','.'),							'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowgene->fields("monto_traspasado"),2,',','.'),					'TRL',0,'C',1);
				$pdf->Cell(24,	5,			number_format($rowgene->fields("monto_modificado"),2,',','.'),					'TRL',0,'C',1);
				$pdf->Cell(24,	5,			number_format($correjido_montoxl,2,',','.'),		'TRL',0,'C',1);
				$pdf->Cell(28,	5,			number_format($rowprecomprometido_gene->fields('numero_precompromiso'),2,',','.'),	'TRL',0,'R',1);
				$pdf->Cell(24,	5,			number_format($comprometido_gen,2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($rowgene->fields("monto_causado"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($rowgene->fields("monto_pagado"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($dispo_presuxxly ,2,',','.'),												'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($dispo_presuxxl,2,',','.'),												'TRL',0,'R',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
			$genericas_antes = $rowgene->fields("generica");
	}
			$dispo_presu = $presu_correj - $rowcomprometido->fields('numero_compromiso');
			$dispo_total = $presu_correj - ($rowprecomprometido->fields('numero_precompromiso') + $rowcomprometido->fields('numero_compromiso'));
		$suma_total = 0;
			$pdf->Cell(23,	5,			$row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('sub_especifica'),	'TRL',0,'C',1);
			$pdf->Cell(28,	5,			number_format($rowoo->fields('presupuesto'),2,',','.'),								'TRL',0,'R',1);
			$pdf->Cell(28,	5,			number_format($rowoo->fields('monto_traspasado'),2,',','.'),						'TRL',0,'R',1);
			$pdf->Cell(24,	5,			number_format($rowoo->fields('monto_modificado'),2,',','.'),						'TRL',0,'R',1);
			$pdf->Cell(24,	5,			number_format($presu_correj,2,',','.'),												'TRL',0,'R',1);
			$pdf->Cell(28,	5,			number_format($rowprecomprometido->fields('numero_precompromiso'),2,',','.'),		'TRL',0,'R',1);
			$pdf->Cell(24,	5,			number_format($comprometido,2,',','.'),				'TRL',0,'R',1);
			$pdf->Cell(25,	5,			number_format($rowoo->fields("monto_causado"),2,',','.'),		'TRL',0,'R',1);
			$pdf->Cell(25,	5,			number_format($rowoo->fields("monto_pagado"),2,',','.'),		'TRL',0,'R',1);
			$pdf->Cell(25,	5,			number_format($dispo_presu ,2,',','.'),												'TRL',0,'R',1);
			$pdf->Cell(25,	5,			number_format($dispo_total,2,',','.'),												'TRL',0,'R',1);
			/*$pdf->Cell(23,	5,			$row->fields('numero_compromiso'),					'T',0,'C',1);
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
		$totaliza = $totaliza + $row->fields('monto');  */	
		$pdf->Ln(5);
	
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
		$rowprecomprometido_todo->MoveNext();
		
		
	}
		/*$pdf->SetFont('arial','B',10);
		$pdf->Cell(155,	5,			'TOTAL COMPROMETIDO',												1,0,'C',1);
		$pdf->Cell(25,	5,			number_format($totaliza,2,',','.'),												1,0,'C',1);
		$pdf->Cell(90,	5,			'',												1,0,'C',1);
		$pdf->Ln(5);*/

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
/*
$pdf->AddPage('L');
$sql_monto_partida ="
SELECT
	\"presupuesto_ejecutadoR\".partida,
	\"presupuesto_ejecutadoR\".generica,
	\"presupuesto_ejecutadoR\".especifica,
	\"presupuesto_ejecutadoR\".sub_especifica
FROM
	\"presupuesto_ejecutadoR\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
INNER JOIN
	\"orden_compra_servicioD\"
ON
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
WHERE
	\"presupuesto_ejecutadoD\".fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
AND
	\"orden_compra_servicioE\".numero_compromiso <> '0'

GROUP BY
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
		
		*/
		// $pdf->Cell(102,	5,	'TOTAL' ,													'T',0,'L',1);
		/*
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
*/
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