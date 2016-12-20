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
$desde = $_GET['presupuesto_fiscal_rp_desde'];
$hasta = $_GET['presupuesto_fiscal_rp_hasta'];
list($diaxx,$mesxx , $anoxx) = $desde;
list($diayy,$mesyy , $anoyy) = $hasta;


//************************************************************************
if (($mesxx == 01) || ($mesxx ==1))
	$desde_mes ='Enero';
if (($mesxx == 02) || ($mesxx ==2))
	$desde_mes ='Febrero';
if (($mesxx == 03) || ($mesxx ==3))
	$desde_mes ='Marzo';
if (($mesxx== 04) || ($mesxx==4))
	$desde_mes ='Abril';
if (($mesxx== 05) || ($mesxx==5))
	$desde_mes ='Mayo';
if (($mesxx== 06) || ($mesxx==6))
	$desde_mes ='Junio';
if (($mesxx== 07) || ($mesxx==7))
	$desde_mes ='Julio';
if (($mesxx== 08) || ($mesxx==8))
	$desde_mes ='Agosto';
if (($mesxx== 09) || ($mesxx==9))
	$desde_mes =' Septiembre';
if (($mesxx== 10) || ($mesxx==10))
	$desde_mes ='Octubre';
if (($mesxx== 11) || ($mesxx==11))
	$desde_mes ='Noviembre';
if (($mesxx== 12) || ($mesxx==12))
	$desde_mes ='Diciembre';
if (($mesxx== 01) || ($mesxx==1))
//************************************************************************
if (($mesyy== 01) || ($mesyy==1))
	$hasta_mes ='Enero';
if (($mesyy== 02) || ($mesyy==2))
	$hasta_mes ='Febrero';
if (($mesyy== 03) || ($mesyy==3))
	$hasta_mes ='Marzo';
if (($mesyy== 04) || ($mesyy==4))
	$mesyy='Abril';
if (($mesyy== 05) || ($mesyy==5))
	$hasta_mes ='Mayo';
if (($mesyy== 06) || ($mesyy==6))
	$hasta_mes ='Junio';
if (($mesyy== 07) || ($mesyy==7))
	$hasta_mes ='Julio';
if (($mesyy== 08) || ($mesyy==8))
	$hasta_mes ='Agosto';
if (($mesyy== 09) || ($mesyy==9))
	$hasta_mes =' Septiembre';
if (($mesyy== 10) || ($mesyy==10))
	$hasta_mes ='Octubre';
if (($mesyy== 11) || ($mesyy==11))
	$hasta_mes ='Noviembre';
if (($mesyy== 12) || ($mesyy==12))
	$hasta_mes ='Diciembre';	
//die $ano ; 
$ii = 0;
//************************************************************************

//************************************************************************

$sql = "
SELECT 
	\"presupuesto_ejecutadoR\".partida, \"presupuesto_ejecutadoR\".generica, \"presupuesto_ejecutadoR\".especifica, \"presupuesto_ejecutadoR\".sub_especifica,
	denominacion AS clasificador_presupuestario,
	SUM(monto_presupuesto[1]+monto_presupuesto[2]+monto_presupuesto[3])AS primer_trimestre, 
	SUM(monto_presupuesto[4]+monto_presupuesto[5]+monto_presupuesto[6])AS segundo_trimestre,
	SUM(monto_presupuesto[7]+monto_presupuesto[8]+monto_presupuesto[9])AS tercer_trimestre,
	SUM(monto_presupuesto[10]+monto_presupuesto[11]+monto_presupuesto[12])AS cuarto_trimestre 
FROM 
	\"presupuesto_ejecutadoR\" 
INNER JOIN
	clasificador_presupuestario
ON
	clasificador_presupuestario.partida = \"presupuesto_ejecutadoR\".partida
	AND
	clasificador_presupuestario.generica = \"presupuesto_ejecutadoR\".generica
	AND
	clasificador_presupuestario.especifica = \"presupuesto_ejecutadoR\".especifica
	AND
	clasificador_presupuestario.subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
WHERE 
	\"presupuesto_ejecutadoR\".ano = '2011' 
GROUP BY
	\"presupuesto_ejecutadoR\".partida, 
	\"presupuesto_ejecutadoR\".generica, 
	\"presupuesto_ejecutadoR\".especifica, 
	\"presupuesto_ejecutadoR\".sub_especifica,
	clasificador_presupuestario.denominacion
ORDER BY 
	\"presupuesto_ejecutadoR\".partida, 
	\"presupuesto_ejecutadoR\".generica, 
	\"presupuesto_ejecutadoR\".especifica, 
	\"presupuesto_ejecutadoR\".sub_especifica
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
			$this->Cell(0,10,'RESUMEN DE PRESUPUESTO DE LEY POR TRIMESTRE',0,0,'C');
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
			//$this->Cell(45,				10,		'UNIDAD EJECUTORA',					'B',0,'C',1);
			$this->Cell(30,				10,		'PARTIDA',	'B',0,'C',1);
			$this->Cell(100,				10,		'DESCRIPCION ',				'B',0,'C',1);
			$this->Cell(25,				10,		'1 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(25,				10,		'2 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(25,				10,		'3 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(25,				10,		'4 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(25,				10,		'MONTO',	'B',0,'C',1);
			
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
		$id_proyecto="";
		$id_accion_centralizada="";
		$id_accion_especifica = "";
	while (!$row->EOF)// id_unidad_ejecutora $row->fields("id_unidad_ejecutora")
	{
	
	$total = $row->fields("primer_trimestre")+$row->fields("segundo_trimestre")+$row->fields("tercer_trimestre")+$row->fields("cuarto_trimestre");
		
		$pdf->Cell(30,	5,	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica"),		'RLB',0,'L',1);
		$pdf->Cell(100,	5,	utf8_decode(substr($row->fields("clasificador_presupuestario"),0,100)),					'LRB',0,'L',1);	
		$pdf->Cell(25,	5,	number_format($row->fields("primer_trimestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(25,	5,	number_format($row->fields("segundo_trimestre"),2,',','.'),																			'LRB',0,'R',1);
		$pdf->Cell(25,	5,	number_format($row->fields("tercer_trimestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(25,	5,	number_format($row->fields("cuarto_trimestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(25,	5,	number_format($total,2,',','.'),		'LRB',0,'R',1);
		$pdf->Ln(5);
		
	$primer_trimestre = $primer_trimestre + $row->fields("primer_trimestre");
	$segundo_trimestre = $segundo_trimestre + $row->fields("segundo_trimestre");
	$tercer_trimestre = $tercer_trimestre + $row->fields("tercer_trimestre");
	$cuarto_trimestre = $cuarto_trimestre + $row->fields("cuarto_trimestre");
	$total_t = $total_t + $total;
	
	$row->MoveNext();
	}
	$pdf->SetFont('Arial','B',9);
		$pdf->Cell(130,	5,	Total,			1,0,'R',1);
		$pdf->Cell(25,	5,	number_format($primer_trimestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(25,	5,	number_format($segundo_trimestre,2,',','.'),	1,0,'R',1);
		$pdf->Cell(25,	5,	number_format($tercer_trimestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(25,	5,	number_format($cuarto_trimestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(25,	5,	number_format($total_t,2,',','.'),				1,0,'R',1);

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