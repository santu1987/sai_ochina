<?
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

$sql_cono="
SELECT 
	requisicion_encabezado.id_requisicion_encabezado, 
	requisicion_encabezado.id_organismo, 
	requisicion_encabezado.ano, 
	requisicion_encabezado.id_unidad_ejecutora,  unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora,
	accion_centralizada.id_accion_central, accion_centralizada.codigo_accion_central ,accion_centralizada.denominacion ,
	proyecto.id_proyecto, proyecto.codigo_proyecto, proyecto.nombre, 
	requisicion_encabezado.id_accion_especifica, accion_especifica.codigo_accion_especifica, accion_especifica.denominacion AS accion_especifica,
	numero_requisicion
FROM 
	requisicion_encabezado
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = requisicion_encabezado.id_accion_especifica
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = requisicion_encabezado.id_unidad_ejecutora
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central =accion_especifica.id_accion_central
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = accion_especifica.id_proyecto
WHERE
	requisicion_encabezado.usuario_anula <>'0'
";
$row=& $conn->Execute($sql_cono);
if (!$row->EOF){
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE ANULACION DE REQUISICION',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			$this->Cell(200,10,'Año: '.date('Y'),0,0,'L');
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(30,				10,		'Nro REQUISION ',				'B',0,'C',1);
			$this->Cell(120,			10,		'UNIDAD SOLICITANTE',			'B',0,'C',1);
			$this->Cell(110,			10,		'PROYECTO O ACCIÓN CENTRAL',	'B',0,'C',1);
			
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
	while (!$row->EOF)
	{
		$pdf->Cell(30,	5,	$row->fields("numero_requisicion"),															'LB',0,'C',1);
		$pdf->Cell(120,	5,	$row->fields("codigo_unidad_ejecutora").' '.substr($row->fields("unidad_ejecutora"),0,45),				'LRB',0,'L',1);
		if($row->fields("id_proyecto") !="")
			$pdf->Cell(110,	5,	$row->fields("codigo_proyecto").' '.substr($row->fields("nombre"),0,60),				'LRB',0,'L',1);
		else
			$pdf->Cell(110,	5,	$row->fields("codigo_accion_central").' '.substr($row->fields("denominacion"),0,60),	'LRB',0,'L',1);
		$pdf->Ln(5);
	
	$row->MoveNext();
	}
$sql_re="
	SELECT 
		COUNT(id_requisicion_encabezado) AS contar
	FROM 
		requisicion_encabezado
	INNER JOIN
		accion_especifica
	ON
		accion_especifica.id_accion_especifica = requisicion_encabezado.id_accion_especifica
	INNER JOIN
		unidad_ejecutora
	ON
		unidad_ejecutora.id_unidad_ejecutora = requisicion_encabezado.id_unidad_ejecutora
	LEFT JOIN
		accion_centralizada
	ON
		accion_centralizada.id_accion_central =accion_especifica.id_accion_central
	LEFT JOIN
		proyecto
	ON
		proyecto.id_proyecto = accion_especifica.id_proyecto
	WHERE
		requisicion_encabezado.usuario_anula <>'0'
";
$rowi=& $conn->Execute($sql_re);
if (!$rowi->EOF)
{	$pdf->Ln(5);
	$pdf->Cell(60,	5,	'TOTAL ANULADAS ',												1,0,'C',1);
	$pdf->Cell(30,	5,	$rowi->fields("contar"),										1,0,'C',1);
}
	$pdf->Output();
}
?>