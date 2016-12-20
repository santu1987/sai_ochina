<?php
session_start();

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");


$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$anio = $_GET['ano'];
$proyectos = $_GET['proyectos'];
$acciones = $_GET['acciones'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
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
$ii = 0;
while($desde<=$hasta){
	if ($ii == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
	}
$ii++;
$desde++;

}
//************************************************************************
if($proyectos == 1){
	$where = " AND \"presupuesto_ejecutadoR\".id_proyecto <> 0";
}else{
	$where = " AND \"presupuesto_ejecutadoR\".id_accion_centralizada != 0";
}

//************************************************************************

$sql = "
SELECT 
	\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador, 
	\"presupuesto_ejecutadoR\".id_unidad_ejecutora, unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora,
	\"presupuesto_ejecutadoR\".id_proyecto, proyecto.codigo_proyecto , proyecto.nombre,
	\"presupuesto_ejecutadoR\".id_accion_centralizada, accion_centralizada.codigo_accion_central , accion_centralizada.denominacion , 
	\"presupuesto_ejecutadoR\".id_accion_especifica, accion_especifica.codigo_accion_especifica , accion_especifica.denominacion AS accion_especifica,
	\"presupuesto_ejecutadoR\".ano, 
	\"presupuesto_ejecutadoR\".partida, 
	\"presupuesto_ejecutadoR\".generica, 
	\"presupuesto_ejecutadoR\".especifica, 
	\"presupuesto_ejecutadoR\".sub_especifica, 
	(monto_presupuesto[1]+monto_presupuesto[2]+monto_presupuesto[3]+
	monto_traspasado[1]+monto_traspasado[2]+monto_traspasado[3]+
	monto_modificado[1]+monto_modificado[2]+monto_modificado[3])AS primer_trimestre, 
	(monto_presupuesto[4]+monto_presupuesto[5]+monto_presupuesto[6]+
	monto_traspasado[4]+monto_traspasado[5]+monto_traspasado[6]+
	monto_modificado[4]+monto_modificado[5]+monto_modificado[6])AS segundo_trimestre,
	(monto_presupuesto[7]+monto_presupuesto[8]+monto_presupuesto[9]+
	monto_traspasado[7]+monto_traspasado[8]+monto_traspasado[9]+
	monto_modificado[7]+monto_modificado[8]+monto_modificado[9])AS tercer_trimestre,
	(monto_presupuesto[10]+monto_presupuesto[11]+monto_presupuesto[12]+
	monto_traspasado[10]+monto_traspasado[11]+monto_traspasado[12]+
	monto_modificado[10]+monto_modificado[11]+monto_modificado[12])AS cuarto_trimestre
FROM 
	\"presupuesto_ejecutadoR\"
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = \"presupuesto_ejecutadoR\".id_accion_centralizada
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = \"presupuesto_ejecutadoR\".id_proyecto
WHERE
	\"presupuesto_ejecutadoR\".ano = '$anio'
	$where
";
//die($sql);
$row=& $conn->Execute($sql);


//************************************************************************
if (!$row->EOF)
{
//die($sql);

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
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE PRESUPUESTO CORREJIDO POR TRIMESTRE',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			$this->Cell(200,10,'Año: '.date('Y'),0,0,'L');
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(45,				10,		'UNIDAD EJECUTORA',					'B',0,'C',1);
			$this->Cell(57,				10,		'PROYECTO Y/O ACCION CENTRALIZADA ','B',0,'C',1);
			$this->Cell(40,				10,		'ACCION ESPECIFICA ',				'B',0,'C',1);
			$this->Cell(23,				10,		'PARTIDA',	'B',0,'C',1);
			$this->Cell(21,				10,		'1 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'2 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'3 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'4 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'TOTAL',	'B',0,'C',1);
			
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
		$pdf->SetFont('arial','',8);
		$pdf->SetFillColor(255);
		$total=0;
		$primer_trimestre = 0;
		$segundo_trimestre = 0;
		$tercer_trimestre = 0;
		$cuarto_trimestre = 0;
		$total_t = 0;
	while (!$row->EOF)
	{
	$total = $row->fields("primer_trimestre")+$row->fields("segundo_trimestre")+$row->fields("tercer_trimestre")+$row->fields("cuarto_trimestre");
		$pdf->Cell(45,	5,	$row->fields("codigo_unidad_ejecutora")." ".$row->fields("unidad_ejecutora"),								'RLB',0,'L',1);
		if($row->fields("id_proyecto") !=0)
			$pdf->Cell(57,	5,	$row->fields("codigo_proyecto").' '.substr($row->fields("nombre"),0,60),								'LRB',0,'L',1);
		else
			$pdf->Cell(57,	5,	$row->fields("codigo_accion_central").' '.substr($row->fields("denominacion"),0,60),					'LRB',0,'L',1);
		$pdf->Cell(40,	5,	$row->fields("codigo_accion_especifica").' '.substr($row->fields("accion_especifica"),0,60),					'LRB',0,'L',1);	
		$pdf->Cell(23,	5,	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica"),		'RLB',0,'L',1);
		$pdf->Cell(21,	5,	number_format($row->fields("primer_trimestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("segundo_trimestre"),2,',','.'),																			'LRB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("tercer_trimestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("cuarto_trimestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($total,2,',','.'),		'LRB',0,'R',1);
		$pdf->Ln(5);
		
	$primer_trimestre = $primer_trimestre + $row->fields("primer_trimestre");
	$segundo_trimestre = $segundo_trimestre + $row->fields("segundo_trimestre");
	$tercer_trimestre = $tercer_trimestre + $row->fields("tercer_trimestre");
	$cuarto_trimestre = $cuarto_trimestre + $row->fields("cuarto_trimestre");
	$total_t = $total_t + $total;
	
	$row->MoveNext();
	}
	$pdf->SetFont('Arial','B',9);
		$pdf->Cell(165,	5,	Total,			1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($primer_trimestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($segundo_trimestre,2,',','.'),	1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($tercer_trimestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($cuarto_trimestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($total_t,2,',','.'),				1,0,'R',1);

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
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
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

}
$pdf->Output();
?>