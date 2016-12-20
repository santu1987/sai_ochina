<?php
session_start();

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
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$codigo = $_GET['codigo'];

$Sql="
SELECT 
	id_proveedor,   codigo_proveedor, proveedor.nombre AS proveedor, ramo.nombre AS ramo, 
	telefono, fax, rif, nit, nombre_persona_contacto, cargo_persona_contacto, 
	email_contacto, paginaweb, rnc, fecha_ingreso, usuario_ingreso, 
	direccion,   fecha_vencimiento_rcn, solvencia_laboral, fecha_vencimiento_sol, 
	objeto_compania, covertura_distribucion
FROM 
	proveedor
LEFT JOIN
	ramo
ON
	ramo.id_ramo = proveedor.id_ramo

ORDER BY 
	proveedor.nombre ";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	//************************************************************************
	
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proyecto_accion, $codigo_proyecto_accion;
			global $unidad_ejecutora, $codigo_unidad;
			global $AccionE, $codigo_especifica;
			global $asunto;
			global $numero_requisicion;
			
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
			$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	
			$this->Ln();	
			$this->SetFont('Arial','B',15);
			$this->Cell(0,10,'FICHA PROVEEDOR',0,0,'C');
			$this->Ln();

		/*	$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(15,		5,			'Proveed',		0,0,'C',1);
			$this->Cell(25,		5,			'Cantidad',		0,0,'C',1);
			$this->Cell(25,		5,			'Unid. Med',	0,0,'C',1);
			$this->Cell(125,	5,			'Descripcion',	0,0,'C',1);
			$this->Ln(6);*/
		}
		//Pie de página
	
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$row->EOF) 
	while(!$row->EOF)
	{
			list($ano, $mes, $dias) = split('[/.-]', $row->fields("fecha_ingreso"));
			list($dia, $hora) = split(' ',$dias);
			
			$fecha_ingreso= $dia."-".$mes."-".$ano;
			
			$pdf=new PDF();
			$pdf->AliasNbPages();
			$pdf->AddFont('barcode');
			$pdf->AddPage();
			$pdf->SetFont('arial','',10);
			$pdf->SetFillColor(255);
			$pdf->SetDrawColor(200);
			$i=0;

		
		$row->MoveNext();
	
	//$pdf->Output();
	//
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