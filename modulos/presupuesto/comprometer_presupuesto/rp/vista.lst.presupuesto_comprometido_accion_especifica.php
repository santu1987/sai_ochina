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
$ano = $_GET['ano'];
$id_accion_es = $_GET['accion_es'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$unidad_ejecutora = $_GET['unidad_ejecutora'];
//************************************************************************
if (($desde == 01) || ($desde ==1))
	$desde_mes ='Enero';
if (($desde == 02) || ($desde ==2))
	$desde_mes ='Febrero';
if (($desde == 03) || ($desde ==3))
	$desde_mes ='Marzo';
if (($desde == 04) || ($desde ==4))
	$desde_mes ='Abril';
if (($desde == 05) || ($desde ==5))
	$desde_mes ='Mayo';
if (($desde == 06) || ($desde ==6))
	$desde_mes ='Junio';
if (($desde == 07) || ($desde ==7))
	$desde_mes ='Julio';
if (($desde == 08) || ($desde ==8))
	$desde_mes ='Agosto';
if (($desde == 09) || ($desde ==9))
	$desde_mes =' Septiembre';
if (($desde == 10) || ($desde ==10))
	$desde_mes ='Octubre';
if (($desde == 11) || ($desde ==11))
	$desde_mes ='Noviembre';
if (($desde == 12) || ($desde ==12))
	$desde_mes ='Diciembre';
if (($desde == 01) || ($desde ==1))
//************************************************************************
if (($hasta == 01) || ($hasta ==1))
	$hasta_mes ='Enero';
if (($hasta == 02) || ($hasta ==2))
	$hasta_mes ='Febrero';
if (($hasta == 03) || ($hasta ==3))
	$hasta_mes ='Marzo';
if (($hasta == 04) || ($hasta ==4))
	$hasta ='Abril';
if (($hasta == 05) || ($hasta ==5))
	$hasta_mes ='Mayo';
if (($hasta == 06) || ($hasta ==6))
	$hasta_mes ='Junio';
if (($hasta == 07) || ($hasta ==7))
	$hasta_mes ='Julio';
if (($hasta == 08) || ($hasta ==8))
	$hasta_mes ='Agosto';
if (($hasta == 09) || ($hasta ==9))
	$hasta_mes =' Septiembre';
if (($hasta == 10) || ($hasta ==10))
	$hasta_mes ='Octubre';
if (($hasta == 11) || ($hasta ==11))
	$hasta_mes ='Noviembre';
if (($hasta == 12) || ($hasta ==12))
	$hasta_mes ='Diciembre';	
//die $ano ; 
if($hasta_mes == $desde_mes)
	$texto='Mes de '.$hasta_mes;
else
	$texto='Desde '. $desde_mes.' Hasta '.$hasta_mes;
//************************************************************************
$ii = 0;
while($desde<=$hasta){
	if ($ii == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
		$comprometido = "monto_comprometido [".$desde."]";
		$causado = "monto_causado [".$desde."]";
		$pagado = "monto_pagado [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
		$comprometido = $comprometido.' + monto_comprometido ['.$desde.']';
		$causado = $causado.' + monto_causado ['.$desde.']';
		$pagado = $pagado.' + monto_pagado ['.$desde.']';
	}
$ii++;
$desde++;

}
//************************************************************************
if ($unidad_ejecutora <> "" or $unidad_ejecutora <> 0)
	$where = " AND unidad_ejecutora.id_unidad_ejecutora = $unidad_ejecutora" ;
//************************************************************************
$sql ="
SELECT 
	id_presupuesto_ejecutador, 
	unidad_ejecutora.id_unidad_ejecutora, unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora, 
	proyecto.id_proyecto, proyecto.codigo_proyecto, proyecto.nombre, 
	accion_centralizada.id_accion_central, accion_centralizada.codigo_accion_central ,accion_centralizada.denominacion ,
	accion_especifica.id_accion_especifica, accion_especifica.codigo_accion_especifica, accion_especifica.denominacion AS accion_especifica,  
	clasificador_presupuestario.partida, clasificador_presupuestario.generica, clasificador_presupuestario.especifica, sub_especifica,
	clasificador_presupuestario.denominacion AS clasificador_presupuestario,
	($monoto)AS monto_presupuesto, 
	($traspasado)AS monto_traspasado,
	($modificado)AS monto_modificado,
	($comprometido)AS monto_comprometido, 
	($causado)AS monto_causado, 
	($pagado)AS monto_pagado
	
FROM 
	\"presupuesto_ejecutadoR\"
INNER JOIN
	clasificador_presupuestario
ON
	(clasificador_presupuestario.partida = \"presupuesto_ejecutadoR\".partida)
	AND
	(clasificador_presupuestario.generica = \"presupuesto_ejecutadoR\".generica)
	AND
	(clasificador_presupuestario.especifica = \"presupuesto_ejecutadoR\".especifica)
	AND
	(clasificador_presupuestario.subespecifica = \"presupuesto_ejecutadoR\".sub_especifica)
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = \"presupuesto_ejecutadoR\".id_accion_centralizada
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = \"presupuesto_ejecutadoR\".id_proyecto
WHERE
	\"presupuesto_ejecutadoR\".id_organismo = 1
AND
	\"presupuesto_ejecutadoR\".ano = '$ano'
AND
	accion_especifica.id_accion_especifica= $id_accion_es
	$where
ORDER BY 
	id_unidad_ejecutora, id_proyecto, id_accion_centralizada, id_accion_especifica, partida, generica
";
$row=& $conn->Execute($sql);
$unidad_anetrior =0;
$accion_especifica = $row->fields('codigo_accion_especifica')." ".utf8_decode($row->fields('accion_especifica'));
if (!$row->EOF){
//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $unidad_ejecutora,  $ano, $accion_especifica, $partida,  $texto;
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
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE EJECUCIÓN DE PRESUPUESTO POR ACCION ESPECÍFICA',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',13);
			$this->Cell(90,10,'Año: '.$ano,0,0,'L');
			$this->Ln(8);
			$this->Cell(150,10,'Accion Especifica: '.$accion_especifica,0,0,'L');
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(22,				12,		'CUENTA ',						0,0,'L',1);
			$this->Cell(80,				12,		'DESCRIPCION',					0,0,'L',1);
			$this->SetFont('Arial','B',8);
			$this->Cell(22,				12,		'CORREGIDO',					'RL',0,'C',1);
			$y=$this->GetY();
			$this->SetFont('Arial','B',7);
			$this->MultiCell(24,		12,		'COMPROMETIDO',					0,'C',1);
			$this->SetXY(158,$y);
			$this->MultiCell(24,		4,		'DISPONIBLE SEGUN COMPROMETIDO',0,'C',1);
			$this->SetXY(182,$y);
			$this->SetFont('Arial','B',8);
			$this->MultiCell(22,		12,		'CAUSADO',						0,'C',1);
			$this->SetXY(204,$y);
			$this->MultiCell(24,		4,		'DISPONIBLE SEGUN CAUSADO',		0,'C',1);
			$this->SetXY(228,$y);
			$this->MultiCell(22,		12,		'PAGADO',			0,'C',1);
			$this->SetXY(250,$y);
			$this->MultiCell(23,		4,		'DISPONIBLE SEGUN PAGADO',		0,'C',1);
			$this->SetXY(273,$y);
			$this->SetFont('Arial','B',7);
			$this->MultiCell(19,		12,		'PORCENTAJE',					0,'C',1);
			//$this->Ln(6);
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
		$suma_coreegido = 0;
		$suma_comprometido = 0;
		$suma_pagado = 0;
		$suma_causado = 0;
		$suma_calculo_uno = 0;
		$suma_calculo_dos = 0;
		
	while (!$row->EOF)
	{
		if ($row->fields('id_proyecto') <> 0){
			$proyecto_accion_codigo = $row->fields('codigo_proyecto'); 
			$proyecto_accion_nombre = utf8_decode($row->fields('nombre')); 
		}elseif($row->fields('id_accion_central') <> 0){
			$proyecto_accion_codigo = $row->fields('codigo_accion_central'); 
			$proyecto_accion_nombre = utf8_decode($row->fields('denominacion')); 
		}
		$codigo_especifica = $row->fields('codigo_accion_especifica'); 
		$accion_especifica = utf8_decode($row->fields('accion_especifica')); 
		
		/*$id_unidad = $row->fields('id_unidad_ejecutora'); 
		$codigo_unidad = $row->fields('codigo_unidad_ejecutora'); 
		$unidad_ejecutora = $row->fields('unidad_ejecutora'); 
		
		if ($unidad_anetrior <> $id_unidad){
		$unidad_anetrior = $id_unidad;
			//echo ("<br>".$codigo_unidad."&nbsp;".$unidad_ejecutora."<br>");
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(282,	6,	$codigo_unidad ." ".$unidad_ejecutora,								'TB',0,'L',1);
			$pdf->Ln(7);	
			$pdf->SetFont('Arial','',10);
		}
		*/
		$partidas = $row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('sub_especifica');
		$presupuesto_aprobado = $row->fields('monto_presupuesto');
		$presupuesto_traspaso = $row->fields('monto_traspasado') ;
		$presupuesto_modificado = $row->fields('monto_modificado');
		$presupuesto_comprometido = $row->fields('monto_comprometido');
		$presupuesto_causado = $row->fields('monto_causado');
		$presupuesto_pagado = $row->fields('monto_pagado');
		
		$presupuesto_corregido = $presupuesto_aprobado + $presupuesto_modificado + $presupuesto_traspaso;
		$calculo_uno = $presupuesto_corregido - $presupuesto_comprometido;
		$calculo_dos = $presupuesto_corregido - $presupuesto_causado;
		$calculo_tres = ($presupuesto_pagado * 100);
		if($calculo_tres != 0)
			$calculo_tres = $calculo_tres/$presupuesto_corregido;
		$suma_coreegido = $suma_coreegido + $presupuesto_corregido;
		$suma_comprometido = $suma_comprometido + $presupuesto_comprometido;
		$suma_pagado = $suma_pagado + $presupuesto_pagado;
		$suma_causado = $suma_causado + $presupuesto_causado;
		$suma_calculo_uno = $suma_calculo_uno + $calculo_uno;
		$suma_calculo_dos = $suma_calculo_dos + $calculo_dos;
		
		$pdf->Cell(22,	5,	$partidas ,															0,0,'L',1);
		$pdf->Cell(80,	5,	$row->fields("clasificador_presupuestario"),						0,0,'L',1);
		$pdf->Cell(22,	5,	number_format($presupuesto_corregido,2,',','.'),					'RL',0,'R',1);
		$pdf->Cell(24,	5,	number_format($presupuesto_comprometido,2,',','.'),					'L',0,'R',1);
		$pdf->Cell(24,	5,	number_format($calculo_uno,2,',','.'),								'L',0,'R',1);
		$pdf->Cell(22,	5,	number_format($presupuesto_causado,2,',','.'),						'L',0,'R',1);
		$pdf->Cell(24,	5,	number_format($calculo_dos,2,',','.'),								'L',0,'R',1);
		$pdf->Cell(22,	5,	number_format($presupuesto_pagado,2,',','.'),						'L',0,'R',1);
		$pdf->Cell(23,	5,	number_format($calculo_uno,2,',','.'),								'L',0,'R',1);
		$pdf->Cell(16,	5,	number_format($calculo_tres,2,',','.'),								'L',0,'R',1);
		$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row->MoveNext();
	}
		$pdf->Cell(102,	5,	'TOTAL' ,													'T',0,'L',1);
		$pdf->Cell(22,	5,	number_format($suma_coreegido,2,',','.'),					'RTL',0,'R',1);
		$pdf->Cell(24,	5,	number_format($suma_comprometido,2,',','.'),				'LT',0,'R',1);
		$pdf->Cell(24,	5,	number_format($suma_calculo_uno,2,',','.'),					'LT',0,'R',1);
		$pdf->Cell(22,	5,	number_format($suma_causado,2,',','.'),						'LT',0,'R',1);
		$pdf->Cell(24,	5,	number_format($suma_calculo_dos,2,',','.'),					'LT',0,'R',1);
		$pdf->Cell(22,	5,	number_format($suma_pagado,2,',','.'),						'LT',0,'R',1);
		$pdf->Cell(23,	5,	number_format($suma_calculo_uno,2,',','.'),					'LT',0,'R',1);
		$pdf->Cell(16,	5,	number_format($calculo_tres,2,',','.'),						'LT',0,'R',1);
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