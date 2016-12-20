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
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$proyectos = $_GET['proyectos'];
$acciones = $_GET['acciones'];
$unidad = $_GET['unidad'];
$id_accion_cen = $_GET['accion_cen'];
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

	if ($ii == 0){
		$modificado = " and  (lower(modificacion_ley.mes_modificado) LIKE lower('%$desde_mes%'))";
	}elseif($desde ==$hasta){
		$modificado = $modificado." or (lower(modificacion_ley.mes_modificado) LIKE lower('%$hasta_mes%'))";
	}else{
		$modificado = $modificado." or  (lower(modificacion_ley.mes_modificado) LIKE lower('%$desde_mes%'))";
	}
$ii++;
$desde++;

}
//************************************************************************
if(($desde <> 0) && ($hasta <> 0)){
	$where = $where .$modificado ;
}
if(($unidad <> 0) && ($unidad <> "")){
	$where = $where ." AND 	unidad_ejecutora.id_unidad_ejecutora = $unidad  ";
}

//************************************************************************ @desde=9@hasta=9

$sql ="
SELECT 
	unidad_ejecutora.id_unidad_ejecutora,  unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora, 
	modificacion_ley.id_proyecto, proyecto.codigo_proyecto, proyecto.nombre, 
	modificacion_ley.id_accion_centralizada, accion_centralizada.codigo_accion_central ,accion_centralizada.denominacion ,
	modificacion_ley.id_accion_especifica, accion_especifica.codigo_accion_especifica, accion_especifica.denominacion AS accion_especifica, 
	modificacion_ley.ano, 
	modificacion_ley.partida, modificacion_ley.generica, modificacion_ley.especifica, modificacion_ley.sub_especifica, 
	secuencia, 
	monto, monto_total,  mes_modificado
FROM 
	modificacion_ley
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = modificacion_ley.id_accion_especifica
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = modificacion_ley.id_unidad_ejecutora
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = modificacion_ley.id_accion_centralizada
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = modificacion_ley.id_proyecto
WHERE
	modificacion_ley.id_organismo =1
AND
	modificacion_ley.ano = '$ano'
and
	accion_centralizada.id_accion_central = $id_accion_cen
	$where
ORDER BY 
	modificacion_ley.id_unidad_ejecutora, modificacion_ley.id_proyecto, 
	modificacion_ley.id_accion_centralizada, modificacion_ley.id_accion_especifica,
	 modificacion_ley.partida, modificacion_ley.generica,  modificacion_ley.especifica, mes_modificado

";
//echo ($sql);
$row=& $conn->Execute($sql);
$unidad_anetrior =0;
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
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE MODIFICACION DE PRESUPUESTO POR ACCION CENTRALIZADA',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			$this->Cell(200,10,'Año: '.$ano,0,0,'L');
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(25,				10,		'CUENTA ',						'LB',0,'L',1);
			$this->Cell(136,			10,		'ACCIÓN ESPECIFICA',	'B',0,'L',1);
			$this->Cell(25,				10,		'SECUENCIA',					'B',0,'L',1);
			$this->Cell(25,				10,		'MES',							'B',0,'C',1);
			$this->Cell(44,				10,		'MONTO',						'RB',0,'C',1);
			
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
		$suma_coreegido = 0;
		$suma_comprometido = 0;
		$suma = 0;
		$resta = 0;
		$positivo = 0;
		$negativo = 0;
		
	while (!$row->EOF)
	{
		
			$id_accion_central = $row->fields('id_accion_centralizada'); 
			$proyecto_accion_codigo = $row->fields('codigo_accion_central'); 
			$proyecto_accion_nombre = $row->fields('denominacion'); 
		
		$codigo_especifica = $row->fields('codigo_accion_especifica'); 
		$accion_especifica = $row->fields('accion_especifica'); 
		
		$id_unidad = $row->fields('id_unidad_ejecutora'); 
		$codigo_unidad = $row->fields('codigo_unidad_ejecutora'); 
		$unidad_ejecutora = $row->fields('unidad_ejecutora'); 
		
		if ($unidad_anetrior <> $id_unidad){
		$unidad_anetrior = $id_unidad;
			$pdf->SetFont('Arial','B',10);
			//echo ("<br>".$codigo_unidad."&nbsp;".$unidad_ejecutora."<br>");
			$pdf->Cell(255,	6,	$codigo_unidad ." ".$unidad_ejecutora,								'RTBL',0,'L',1);
			$pdf->Ln(7);
			$pdf->SetFont('arial','',10);	
		}
		
	
		$partidas = $row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('sub_especifica');
		/*$presupuesto_aprobado = $row->fields('monto_presupuesto');
		$presupuesto_traspaso = $row->fields('monto_traspasado') ;*/
		$presupuesto_modificado = $row->fields('monto_modificado');
		/*$presupuesto_comprometido = $row->fields('monto_comprometido');
		$presupuesto_causado = $row->fields('monto_causado');
		$presupuesto_pagado = $row->fields('monto_pagado');*/
		
		$presupuesto_corregido =  $presupuesto_modificado + $presupuesto_traspaso;
		/*$calculo_uno = $presupuesto_corregido - $presupuesto_comprometido;
		$calculo_dos = $presupuesto_corregido - $presupuesto_causado;
		$calculo_tres = ($presupuesto_pagado * 100);
		if($calculo_tres != 0)
			$calculo_tres = $calculo_tres/$presupuesto_corregido;
		$suma_coreegido = $suma_coreegido + $presupuesto_corregido;
		$suma_comprometido = $suma_comprometido + $presupuesto_comprometido;
		$suma_pagado = $suma_pagado + $presupuesto_pagado;
		$suma_causado = $suma_causado + $presupuesto_causado;
		$suma_calculo_uno = $suma_calculo_uno + $calculo_uno;
		$suma_calculo_dos = $suma_calculo_dos + $calculo_dos;*/
		//$suma_calculo_tres
			$suma_coreegido = $suma_coreegido + $row->fields("monto");
			if($row->fields("monto") < 0){
				$resta= $resta + $row->fields("monto");
				$negativo = $row->fields("monto");
				$positivo = 0;
			}else{
				$suma= $suma + $row->fields("monto");
				$positivo = $row->fields("monto");
				$negativo = 0;
			}
			
			
		$pdf->Cell(25,	5,	$partidas ,															'L',0,'L',1);
		
		
		$pdf->Cell(136,	5,	$codigo_especifica ." ".$accion_especifica,			'RL',0,'L',1);
		$pdf->Cell(25,	5,	$row->fields("secuencia"),							'RL',0,'C',1);
		$pdf->Cell(25,	5,	strtoupper($row->fields("mes_modificado")),			'RL',0,'R',1);
		$pdf->Cell(22,	5,	number_format(	$negativo,2,',','.'),				'L',0,'R',1);
		$pdf->Cell(22,	5,	number_format(	$positivo,2,',','.'),				'LR',0,'R',1);
		$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row->MoveNext();
	}
		$pdf->Cell(211,	5,	'SUBTOTAL' ,													'LTB',0,'R',1);
		$pdf->Cell(22,	5,	number_format($resta,2,',','.'),					1,0,'C',1);
		$pdf->Cell(22,	5,	number_format($suma,2,',','.'),					1,0,'C',1);
		$pdf->Ln(5);
		$pdf->Cell(211,	5,	'TOTAL' ,													'LTB',0,'R',1);
		$pdf->Cell(44,	5,	number_format($suma_coreegido,2,',','.'),					1,0,'C',1);
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