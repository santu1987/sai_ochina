<? // vista.lst.cotizacion_proveedor.php
if (!$_SESSION) session_start();
//$nombre = $_SESSION[nombre].' '.$_SESSION[apellido];
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
$id = $_GET['id_prove'];


$sql="
SELECT 	
	DISTINCT 
	numero_cotizacion,
	proveedor.id_proveedor,  
	proveedor.codigo_proveedor, 
	proveedor.nombre,
	numero_requisicion
FROM 
	proveedor
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionD\".id_proveedor = proveedor.id_proveedor
WHERE
	proveedor.id_proveedor = $id
ORDER BY
	nombre
";
//echo ($sql);
$row=& $conn->Execute($sql);


//-----------------------------------------------------
if (!$row->EOF)
{
	$codigo_proveedor=$row->fields("codigo_proveedor");
	$nombres=$row->fields("nombre");
	$numero_cotizacion=$row->fields("numero_cotizacion");

		class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			/*global $asunto, $numero_requisicion;*/
			global $codigo_proveedor, $nombre;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,8,29);
			$this->SetFont('Arial','B',10);
			$this->Cell(0,4,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,4,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,4,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,4,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,4,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();			
			$this->Cell(0,4,'RIF G-20000451-7',0,0,'C');
			$this->Ln(8);
			$this->SetFillColor(250) ;
			$this->SetFont('Arial','B',10);
			$this->Cell(185,6,'COTIZACIONES POR PROVEEDORES'		,0,0,'C',1);
			$this->Ln(6);		
			$this->Cell(100,6,$codigo_proveedor.' '.$nombres	,0,0,'L',1);
			$this->Cell(85,6,'FECHA: '.date('d-m-Y')				,0,0,'R',1);
			$this->Ln(6);
			/*$this->SetFont('Arial','B',7);
			$this->Cell(128,10,'REFERENCIA '.$numero_requisicion.' '.$asunto,1,0,'L');
			$this->SetFont('arial','',9);
			$this->Cell(49,10, $proveedor1	,1,0,'C');
			$this->Cell(49,10, $proveedor2	,1,0,'C');
			$this->Cell(49,10, $proveedor3	,1,0,'C');
			$this->Ln(10);*/
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
		//	$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(20,		6,		'Cotización',			1,0,'C',1);
			$this->Cell(20,		6,		'Requisición',			1,0,'C',1);
			$this->Cell(15,		6,		'Reglon',		1,0,'C',1);
			$this->Cell(85,		6,		'  Descripción',	1,0,'L',1);
			$this->Cell(20,		6,		'Cantida',		1,0,'C',1);
			$this->Cell(25,		6,		'Partida',				1,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//global  $jefe_unidad, $unidad;
			//Posición: a 2,5 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont('Arial','',9);
			//Número de página
			$this->Cell(80,3,	'ELABORADO POR: '.strtoupper( $_SESSION['nombre'].' '.$_SESSION['apellido']) ,				0,0,'C');
			/*$this->Cell(70,3,	'TF. HECTOR TORREALBA' ,					0,0,'C');
			$this->Cell(70,3,	'CF. ERICA COROMOTO  VIRGUEZ' ,				0,0,'C');
			$this->Cell(70,3,	'CN. MAURICIO PIETROBON SANZONE' ,			0,0,'C');*/
			/*$this->Ln();
			$this->Cell(50,3,'ELABORADO ' ,									0,0,'C');
			$this->Cell(70,3,'JEFE DE ADQUISICIONES' ,						0,0,'C');
			$this->Cell(70,3,'DIRECTOR DE ADMINISTRACION Y FINANZAS',		0,0,'C');
			$this->Cell(70,3,'DIRECTOR GENERAL DE OCHINA' ,					0,0,'C');*/
			//$nombres = "";
		}
	}
//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,30);
	
	$sql_coti="
	SELECT  
		numero_cotizacion,
		numero_requisicion,
		secuencia,
		descripcion,
		cantidad,
		partida,
		generica,
		especifica,
		subespecifica
	FROM
		\"solicitud_cotizacionD\"
	WHERE
		id_proveedor = $id
	ORDER BY
		numero_cotizacion, numero_requisicion,secuencia
	
	";
	$row_coti=& $conn->Execute($sql_coti);
	$cotiz = 0;
	$requis = 0;
	while (!$row_coti->EOF){
		$partida = $row_coti->fields('partida').'.'.$row_coti->fields('generica').'.'.$row_coti->fields('especifica').'.'.$row_coti->fields('subespecifica');
		if ($cotiz != $row_coti->fields('numero_cotizacion')){
			$pdf->Cell(20,		6,	$row_coti->fields('numero_cotizacion'),		1,0,'C',0);
			$cotiz = $row_coti->fields('numero_cotizacion');
		}else{
			$pdf->Cell(20,		6,	'',		1,0,'C',0);
		}
		if ($requis != $row_coti->fields('numero_requisicion')){
			$pdf->Cell(20,		6,	$row_coti->fields('numero_requisicion'),		1,0,'C',0);
			$requis = $row_coti->fields('numero_requisicion');
		}else{
			$pdf->Cell(20,		6,	'',		1,0,'C',0);
		}
		$pdf->Cell(15,		6,	$row_coti->fields('secuencia'),				1,0,'C',0);
		$pdf->Cell(85,		6,	$row_coti->fields('descripcion'),			1,0,'C',0);
		$pdf->Cell(20,		6,	$row_coti->fields('cantidad'),				1,0,'C',0);
		$pdf->Cell(25,		6,	$partida,									1,0,'C',0);
		
		$pdf->Ln(6);
		$row_coti->MoveNext();
	}
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
		$pdf->Cell(190,		6,'No se encontraron datos ',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}
$pdf->Output();
?>