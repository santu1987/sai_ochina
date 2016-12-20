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

$sql ="
SELECT 
	numero_compromiso, 
	nombre,
	SUM( (cantidad * monto)+ (((cantidad * monto)*impuesto)/100) ) AS monto,
	concepto	
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	\"orden_compra_servicioD\"
ON
	\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
INNER JOIN
	proveedor
ON
	\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor

WHERE
	numero_compromiso <> '0'
AND
	anulada = 0
GROUP BY
	numero_compromiso,
	nombre,
	concepto
ORDER BY
	numero_compromiso	";
//die ($sql);
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
			$this->Cell(0,10,'RESUMEN DE COMPROMISOS',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(24,				6,		'COMPROMISO ',						0,0,'L',1);
			//$this->Cell(80,				12,		'DESCRIPCION',					0,0,'L',1);
			$this->SetFont('Arial','B',8);
			$this->Cell(24,				6,		'FECHA',							0,0,'C',1);
			$y=$this->GetY();
			$this->SetFont('Arial','B',7);
			$this->MultiCell(100,		6,		'PROVEEDOR',						0,'C',1);
			$this->SetXY(158,$y);
			$this->Cell(24,				6,		'MONTO',							0,0,'C',1);
			$y=$this->GetY();
			$this->SetFont('Arial','B',8);
			$this->MultiCell(100,		6,		'DESCRIPCION',						0,'C',1);
			//$this->SetXY(193,$y);
			//$this->SetFont('Arial','B',7);
			//$this->MultiCell(19,		12,		'PORCENTAJE',					0,'C',1);
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
		
	while (!$row->EOF)
	{
		

			
		$pdf->Cell(24,	5,	$row->fields('numero_compromiso') ,									'T',0,'C',1);
		$pdf->Cell(24,	5,	' ',																'T',0,'R',1);
		$y=$pdf->GetY();
		$pdf->MultiCell(100,	5,	utf8_decode($row->fields('nombre')),						'T','C',1);
		$pdf->SetXY(158,$y);
		$pdf->Cell(24,	5,	number_format($row->fields('monto'),2,',','.'),						'T',0,'C',1);
		$y=$pdf->GetY();
		$pdf->MultiCell(100,	5,	utf8_decode($row->fields('concepto')),						'T','L',1);
		//$pdf->Cell(16,	5,	/*number_format($calculo_tres,2,',','.')*/'',								'L',0,'R',1);
		//$pdf->Ln(5);

		//echo ("&nbsp;&nbsp;&nbsp;".$proyecto_accion_codigo."&nbsp;".$proyecto_accion_nombre."<br>");
		$row->MoveNext();
	}
		// $pdf->Cell(102,	5,	'TOTAL' ,													'T',0,'L',1);
		
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