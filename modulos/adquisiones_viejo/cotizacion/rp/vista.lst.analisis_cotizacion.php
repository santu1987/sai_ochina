<?php
//************************************************************************
//		../../../../							INCLUYENDO LIBRERIAS Y CONEXION A BD
require('../../../../utilidades/fpdf153/fpdf.php');
/*					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
 //session_start();

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);*/
//$numero_requisicion = $_GET['numero_requisicion'];

/************************************************************************

$sql= "SELECT 
	\"solicitud_cotizacionE\".numero_cotizacion,
	\"solicitud_cotizacionD\".secuencia,
	\"solicitud_cotizacionD\".cantidad,
	\"solicitud_cotizacionD\".descripcion,
	\"solicitud_cotizacionD\".monto
FROM 
	requisicion_encabezado
INNER JOIN
	\"solicitud_cotizacionE\"
ON
	requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionE\".numero_cotizacion = \"solicitud_cotizacionD\".numero_cotizacion
WHERE
	(numero_requisicion = '090002')
ORDER BY 
	numero_requisicion";
	
$sql_proveedor = "SELECT 
	requisicion_encabezado.numero_requisicion,
	requisicion_encabezado.asunto,
	proveedor.nombre
FROM 
	requisicion_encabezado
INNER JOIN
	\"solicitud_cotizacionE\"
ON
	requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
INNER JOIN
	proveedor
ON
	\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
WHERE
	(numero_requisicion = '090002')
ORDER BY 
	numero_cotizacion";
$sql_requsicion = "
	SELECT 
		requisicion_encabezado.numero_requisicion,
		requisicion_encabezado.asunto
	FROM
		requisicion_encabezado
	WHERE
		(numero_requisicion = '090002')
		";

$row=& $conn->Execute($sql_requsicion);

$numero_requisicion = $row->fields("numero_requisicion");
$asunto = $row->fields("asunto");
*/
//************************************************************************


	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			//global $asunto, $numero_requisicion;
			$this->Image("imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln(10);
			$this->SetFillColor(200) ;
			$this->SetFont('Arial','B',10);
			$this->Cell(150,8,'ANALISIS DE COTIZACION'	,'LTB',0,'C',1);
			$this->Cell(125,8,'FECHA: '.date('d-m-y')	,'RTB',0,'C',1);
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->Cell(149,10,'REFERENCIA '/*.$numero_requisicion.' '.$asunto*/,1,0,'L');
			$this->Cell(42,10,'PROVEEDOR 1'	,1,0,'C');
			$this->Cell(42,10,'PROVEEDOR 2'	,1,0,'C');
			$this->Cell(42,10,'PROVEEDOR 3'	,1,0,'C');
			$this->Ln(10);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(14,		6,		'Reglon',			1,0,'C',1);
			$this->Cell(18,		6,		'Cantidad',			1,0,'C',1);
			$this->Cell(18,		6,		'Uni. Med.',		1,0,'C',1);
			$this->Cell(99,		6,		'  Descripcion',	1,0,'L',1);
			$this->Cell(21,		6,		'Precio Unit.',		1,0,'C',1);
			$this->Cell(21,		6,		'Precio Total',		1,0,'C',1);
			$this->Cell(21,		6,		'Precio Unit.',		1,0,'C',1);
			$this->Cell(21,		6,		'Precio Total',		1,0,'C',1);
			$this->Cell(21,		6,		'Precio Unit.',		1,0,'C',1);
			$this->Cell(21,		6,		'Precio Total',		1,0,'C',1);
			$this->Ln(6);
		}
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont('Arial','',9);
			//Número de página
			$this->Cell(50,3,'TSU. RENGIFO KEY RAFAEL' ,				0,0,'C');
			$this->Cell(70,3,'AN. INYER BARRIOS PERDOMO' ,				0,0,'C');
			$this->Cell(70,3,'CC. JOAO FERREIRA PEREIRA' ,				0,0,'C');
			$this->Cell(70,3,'CF. EDGAR BERNARDO PARRA DUQUE' ,			0,0,'C');
			$this->Ln();
			$this->Cell(50,3,'COMPRADOR' ,								0,0,'C');
			$this->Cell(70,3,'JEFE DE DIVISION DE ADQUISIONES' ,		0,0,'C');
			$this->Cell(70,3,'DIRECTOR DE ADMINISTRACION Y FINANZAS',	0,0,'C');
			$this->Cell(70,3,'DIRECTOR GENERAL DE OCHINA' ,				0,0,'C');
		}
	}

	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,35);
	/*$pdf->Line(10,72,10,180);
	$pdf->Line(24,72,24,180);
	$pdf->Line(42,72,42,180);
	$pdf->Line(60,72,60,180);
	$pdf->Line(145,72,145,180);
	$pdf->Line(166,72,166,180);
	$pdf->Line(187.1,72,187.1,180);
	$pdf->Line(208,72,208,180);
	$pdf->Line(229,72,229,180);	
	$pdf->Line(250,72,250,180);	
	$pdf->Line(271,72,271,180);	
	$pdf->Line(10,180,271,180);	*/
	$i = 0;
	while($i<4)	{	
		$i++;

			$pdf->Cell(14,		6,	$i,		'LR',0,'C',0);
			$pdf->Cell(18,		6,	'1.000',	'LR',0,'C',0);
			$pdf->Cell(18,		6,	'Kilos',	'LR',0,'C',0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$x = $x +99;
			$y = $y +0.1;
			$pdf->MultiCell(99,	6,	'STC fondo anti alcalino para exterior aplicacion de dos manos a una altura no mayor de 15mts',	'LR'	,'L');
			$pdf->SetXY($x,$y);
			$pdf->Cell(21,		6,	'50',	'LR',0,'C',0);
			$pdf->Cell(21,		6,	'50',	'LR',0,'C',0);
			$pdf->Cell(21,		6,	'60',	'LR',0,'C',0);
			$pdf->Cell(21,		6,	'60',	'LR',0,'C',0);
			$pdf->Cell(21,		6,	'70',	'LR',0,'C',0);
			$pdf->Cell(21,		6,	'70',	'LR',0,'C',0);
			$pdf->Ln(14);
		$i++;
			$pdf->Cell(14,		6,	$i,		'LR',0,'C',1);
			$pdf->Cell(18,		6,	'1.000',	'LR',0,'C',1);
			$pdf->Cell(18,		6,	'Kilos',	'LR',0,'C',1);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$x = $x +99;
			$y = $y +0.1;
			$pdf->MultiCell(99,	6,	'Puerta de madera maciza de medidas aproximadas (0.89 x 2.055 m) incluye aplicacionde barniz transparente',	'LR'	,'L');
			$pdf->SetXY($x,$y);
			$pdf->Cell(21,		6,	'50',	'LR',0,'C',1);
			$pdf->Cell(21,		6,	'50',	'LR',0,'C',1);
			$pdf->Cell(21,		6,	'60',	'LR',0,'C',1);
			$pdf->Cell(21,		6,	'60',	'LR',0,'C',1);
			$pdf->Cell(21,		6,	'70',	'LR',0,'C',1);
			$pdf->Cell(21,		6,	'70',	'LR',0,'C',1);
			$pdf->Ln(14);
	}		
			$pdf->Cell(149,		5,	'SUB-TOTAL:',	'LTR',0,'L',0);
			$pdf->Cell(42,		5,	'100',			'LTR',0,'R',0);
			$pdf->Cell(42,		5,	'120',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'140',			'LTR',0,'R',1);
			$pdf->Ln(10);
			$pdf->Cell(149,		5,	'IVA(12%)',		'LTR',0,'L',1);
			$pdf->Cell(42,		5,	'0',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'0',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'0',			'LTR',0,'R',1);
			$pdf->Ln(10);
			$pdf->Cell(149,		5,	'TOTAL $',		'LTR',0,'L',1);
			$pdf->Cell(42,		5,	'100',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'120',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'140',			'LTR',0,'R',1);
			$pdf->Ln(10);
			$pdf->Cell(149,		5,	'TOTAL Bs',		'LTR',0,'L',1);
			$pdf->Cell(42,		5,	'215',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'258',			'LTR',0,'R',1);
			$pdf->Cell(42,		5,	'301',			'LTR',0,'R',1);
			$pdf->Ln(10);
			$pdf->MultiCell(275,5,	'RECOMENDACIONES',	'LTR','L');
			
			
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
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