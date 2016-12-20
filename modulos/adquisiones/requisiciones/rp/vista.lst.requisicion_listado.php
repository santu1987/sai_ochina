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
$numero = $_GET['numero_requi'];
$ano=$_GET['ano'];
$Sql="SELECT * FROM  requisicion_encabezado WHERE ano = '".$ano."' ORDER BY id_requisicion_encabezado";
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
	$unidad_ejecutora=$rowUnida->fields("nombre");
	$proyecto_accion = $rowProyectoAccion->fields('proyectopccion');
	$asunto = $row->fields('asunto');
	$numero_requisicion = $row->fields('numero_requisicion');
	
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proyecto_accion;
			global $unidad_ejecutora;
			global $AccionE;
			global $asunto;
			global $numero_requisicion;
			
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
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
			$this->Ln();	
			$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(10,		5,			'Nro Requision',	0,0,'C',1);
			$this->Cell(20,		5,			'Unidad Solicitante',	0,0,'C',1);
			$this->Cell(20,		5,			'Proyecto/A.C.',		0,0,'C',1);
			$this->Cell(20,		5,			'Accion Especifica',	0,0,'C',1);
			$this->Cell(120,	5,			'Asunto',			0,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			//$this->SetY(-36);
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
var $col=0;

function SetCol($col)
{
//Move position to a column
	$this->col=$col;
	$x=20+$col;
	$this->SetLeftMargin($x);
	$this->SetX($x);
}

	function AcceptPageBreak()
	{
		if($this->col<0)
		{
		//Go to next column
		$this->SetCol($this->col+1);
		$this->SetY(40);
		return false;
		}
		else
		{

		return true;
		}
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
	$pdf->SetDrawColor(200);
	$i=0;
	while (!$rowRequiDetalle->EOF) 
	{
	$i++;
		$pdf->Cell(15,		6,$i,											'B',0,'C',1);
		$pdf->Cell(25,		6,$rowRequiDetalle->fields("cantidad"),			'B',0,'C',1);
		$pdf->Cell(25,		6,$rowRequiDetalle->fields("nombre"),			'B',0,'C',1);
		$pdf->Cell(125,		6,$rowRequiDetalle->fields("descripcion"),		'B',0,'C',1);
		$pdf->Ln(6);
		$rowRequiDetalle->MoveNext();
	}
	
	$pdf->Output();
}
?>