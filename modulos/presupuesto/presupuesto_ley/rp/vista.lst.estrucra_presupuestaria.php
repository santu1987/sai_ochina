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

$desde = $_GET['ejecucion_presupuestaria_rp_desde'];
$hasta = $_GET['ejecucion_presupuestaria_rp_hasta'];
//$desde = '01/01/2011';
//$hasta = '01/03/2011';

list($anoxx,$mesxx , $diaxx) = $desde;
list($anoyy,$mesyy , $diayy) = $hasta;

//$dia = substr ($dia, 0, 2);
	/*$where = $where."
	AND
		id_accion_especifica = 225 
	";*/
$sql ="
SELECT 
	codigo_accion_central,
	accion_centralizada.denominacion AS accion_centralizada,
	codigo_proyecto,
	proyecto.nombre AS proyecto,
	codigo_accion_especifica,
	accion_especifica.denominacion AS accion_especifica
FROM 
	accion_especifica
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = accion_especifica.id_accion_central
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = accion_especifica.id_proyecto
ORDER BY
	codigo_accion_central,
	codigo_proyecto,
	codigo_accion_especifica

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
			$this->Cell(0,10,'ANTEPROYECTO & PRESUPUESTARIA',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(25,				6,		'PROY/ACCION',						0,0,'C',1);
			$this->Cell(25,				6,		'ACCION ESPECIFICA',				0,0,'C',1);
			$this->Cell(180,			6,		'DENOMINACION',						0,0,'C',1);
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
		//****************************************************************************************************************************

	
while (!$row->EOF)
	{

if (($row->fields("codigo_accion_central") != "") && ($row->fields("codigo_accion_central") != 0))
	$acc_pro = $row->fields("codigo_accion_central");
else
	$acc_pro = $row->fields("codigo_proyecto");

				$pdf->Cell(25,	5,			$acc_pro ,	1,0,'C',1);
				$pdf->Cell(25,	5,			$row->fields("codigo_accion_especifica"),				1,0,'L',1);
					$pdf->Cell(180,	5,			utf8_decode(substr($row->fields("accion_especifica"),0,110)),		1,0,'L',1);
		$pdf->Ln(5);
	
		$row->MoveNext();
		//$rowprecomprometido_todo->MoveNext();
		
		
	}

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