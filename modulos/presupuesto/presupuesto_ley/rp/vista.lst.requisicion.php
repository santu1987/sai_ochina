<?php
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
$numero = $_GET['x'];
$Sql="SELECT * FROM  requisicion_encabezado WHERE numero_requisicion = '".$numero."' ORDER BY id_requisicion_encabezado";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	$SqlUnida="SELECT nombre FROM  unidad_ejecutora WHERE (id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").") ORDER BY id_unidad_ejecutora ";
	$rowUnida=& $conn->Execute($SqlUnida);
	$SqlRequiDetalle="SELECT numero_requision, secuencia, cantidad, nombre, descripcion FROM  requisicion_detalle INNER JOIN unidad_medida ON	unidad_medida.id_unidad_medida= requisicion_detalle.id_unidad_medida WHERE (numero_requision = '".$row->fields("numero_requisicion")."')";
	$rowRequiDetalle=& $conn->Execute($SqlRequiDetalle);


	if(!$rowUnida->EOF){
		$unidad_ejecutora = $rowUnida->fields('nombre');
	}
	if($row->fields("id_proyecto") == 0)
		$SqlProyectoAccion="SELECT nombre AS proyectopccion FROM  proyecto WHERE (id_proyecto = ".$row->fields("id_proyecto").") ORDER BY id_proyecto ";
	else
		$SqlProyectoAccion="SELECT denominacion AS proyectopccion  FROM  accion_centralizada WHERE (id_accion_central = ".$row->fields("id_accion_centralizada").") ORDER BY id_accion_central ";
	$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
	if(!$rowProyectoAccion->EOF){
		$ProyectoAccion = $rowProyectoAccion->fields('proyectopccion');
	}
	$SqlAccionE="SELECT denominacion FROM  accion_especifica WHERE (id_accion_especifica = ".$row->fields("id_accion_especifica").") ORDER BY id_accion_especifica ";
	$rowAccionE=& $conn->Execute($SqlAccionE);
	if(!$rowAccionE->EOF){
		$AccionE = $rowAccionE->fields('denominacion');
	}
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
			$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",170,10,25);
						
			$this->SetFont('Arial','B',12);
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
			$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	
			$this->Ln();	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'REQUISICION',0,0,'C');
			$this->Cell(0,5,'Nro ',0,0,'R');
			$this->Ln();	
			$this->SetFont('Arial','B',12);
			$this->Cell(0,10,'Unidad Solicitante'.$unidad_ejecutora,0,0,'L');
			$this->Ln(5);
			$this->Cell(0,10,'Proyecto/A.C.',0,0,'L');
			$this->Ln(5);
			$this->Cell(0,10,'Accion Especifica',0,0,'L');
			$this->Ln(5);
			$this->Cell(0,10,'Asunto',0,0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(15,		6,			'Renglon',		1,0,'C',1);
			$this->Cell(25,		6,			'Cantidad',		1,0,'C',1);
			$this->Cell(25,		6,			'Unid. Med',	1,0,'C',1);
			$this->Cell(120,	6,			'Descripcion',	1,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-60);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(65,3,'Observaciones ' ,0,0,'L');
			$this->Ln(10);
			$this->Cell(65,3,'Preparado Por: ' ,0,0,'C');
			$this->Cell(65,3,'Solicitado Por: ' ,0,0,'C');
			$this->Cell(65,3,'Vo Bo: ' ,0,0,'C');
			$this->Ln();
			$this->Cell(65,3,'TF. MAGLY LEON RODRIGUEZ' ,0,0,'C');
			$this->Cell(65,3,'TF. MAGLY LEON RODRIGUEZ' ,0,0,'C');
			$this->Cell(65,3,'TF. LUIS EDUARDO ARRCHEDERA' ,0,0,'C');
			$this->Ln();
			$this->Cell(65,3,'Solicitante' ,0,0,'C');
			$this->Cell(65,3,'Jefe Unidad Solicitante' ,0,0,'C');
			$this->Cell(65,3,'Jefe del Proyecto / A.C.' ,0,0,'C');
			$this->Ln(10);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
			$this->Cell(62,3,' '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'C');					
			$this->Ln();
			$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 


	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	while (!$rowRequiDetalle->EOF) 
	{
		$pdf->Cell(15,		6,$rowRequiDetalle->fields("secuencia"),			1,0,'C',1);
		$pdf->Cell(25,		6,$rowRequiDetalle->fields("cantidad"),			1,0,'C',1);
		$pdf->Cell(25,		6,$rowRequiDetalle->fields("nombre"),				1,0,'C',1);
		$pdf->Cell(120,		6,$rowRequiDetalle->fields("descripcion"),		1,0,'L',1);
		$pdf->Ln(6);
		$rowRequiDetalle->MoveNext();
	}
	
	$pdf->Output();
}
?>