<?php
if (!$_SESSION) session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$Sql="SELECT 
	 proveedor.nombre AS proveedor, proveedor.direccion, proveedor.telefono, \"solicitud_cotizacionE\".*
FROM 
	\"solicitud_cotizacionE\"

INNER JOIN
	proveedor
ON
	\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
	 ORDER BY id_solicitud_cotizacione";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	$proveedor=$row->fields("proveedor");
	$numero_cotizacion=$row->fields("numero_cotizacion");
	$direccion=$row->fields("direccion");
	$telefono=$row->fields("telefono");
	$titulo=$row->fields("titulo");
	$comentarios=$row->fields("comentarios");
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proveedor;
			global $numero_cotizacion;
			global $direccion;
			global $telefono,$titulo,$comentarios;
			
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
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'SOLICITUD DE COTIZACIÓN',0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',10);
			$this->Cell(175,10,'Nº Cotización ',0,0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,10,$numero_cotizacion,0,0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(20,10,'Proveedor ',1,0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(85,10,$proveedor,1,0,'L');
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'Referencia ',1,0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,10,$numero_cotizacion,1,0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$y=$this->GetY();
			$this->MultiCell(105,10,$direccion.'. Telf. '.$telefono,1,'L');
			$this->SetXY(115,$y);
			$this->MultiCell(85,20,' Telf. 303-8761 3038762',1,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(190,10,$titulo,1,0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$this->Cell(190,10,$comentarios,1,0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(12,		6,		'Reglon',			0,0,'C',1);
			$this->Cell(18,		6,		'Cantidad',			0,0,'C',1);
			$this->Cell(15,		6,		'Uni. Med.',		0,0,'C',1);
			$this->Cell(145,	6,		'  Descripcion',	0,0,'L',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-40);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(65,3,'Observaciones ' ,0,0,'L');
			$this->Ln(10);
			$this->Ln(5);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->SetFont('barcode','',6);
			$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			$this->SetFont('Arial','I',9);
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			/*$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');*/
		}
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 

	$total=0;
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(200);
	$i=0;
	
		$SqlProyectoAccion="SELECT 
								descripcion, cantidad, nombre   
							FROM 
								\"solicitud_cotizacionD\"
							INNER JOIN
								unidad_medida
							ON	
								\"solicitud_cotizacionD\".id_unidad_medida = unidad_medida.id_unidad_medida
							WHERE
								(numero_cotizacion = '".$numero_cotizacion."')";

		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);		
		while (!$rowProyectoAccion->EOF) 
	{
	$i++;
		
		$pdf->Cell(12,		6,	$i,											0,0,'C',1);
		$pdf->Cell(18,		6,	$rowProyectoAccion->fields('cantidad'),		'L',0,'C',1);
		$pdf->Cell(15,		6,	$rowProyectoAccion->fields('nombre'),		'L',0,'C',1);
		$pdf->MultiCell(145,		6,	$rowProyectoAccion->fields('descripcion'),	'L','L');
		$pdf->Ln(6);
		$rowProyectoAccion->MoveNext();
	}
	$pdf->Output();
}
?>