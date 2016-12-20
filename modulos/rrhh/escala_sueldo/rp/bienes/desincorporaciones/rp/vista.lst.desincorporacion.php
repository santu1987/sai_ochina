<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();
require('../../../../utilidades/fpdf153/code128.php');

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
$id_tipo = '';
$fecha_desde = '';
$fecha_hasta = '';
if($_GET['fecha_desde']!='')
	$fecha_desde = $_GET['fecha_desde'];
if($_GET['fecha_hasta']!='')
	$fecha_hasta = $_GET['fecha_hasta'];
$where = ' WHERE 1 = 1 ';
if($fecha_desde!='' && $fecha_hasta!='')
	$where.= " AND fecha_desincorporacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."'";
if($_GET['id_tipo']!='')
	$id_tipo = $_GET['id_tipo'];
if($id_tipo!='')	
	$where.= " AND tipo_desincorporaciones.id_tipo_desincorporaciones = $id_tipo";
$Sql="SELECT bienes.nombre, fecha_desincorporacion, desincorporaciones.descripcion_general FROM desincorporaciones INNER JOIN bienes ON desincorporaciones.id_bienes = bienes.id_bienes INNER JOIN tipo_desincorporaciones ON desincorporaciones.id_tipo_desincorporaciones=tipo_desincorporaciones.id_tipo_desincorporaciones ".$where." AND desincorporaciones.id_organismo = $_SESSION[id_organismo] ORDER BY id_desincorporaciones";
$row=& $conn->Execute($Sql);
$sql_exi = "SELECT COUNT(desincorporaciones.id_bienes) as exi FROM desincorporaciones INNER JOIN bienes ON desincorporaciones.id_bienes = bienes.id_bienes INNER JOIN tipo_desincorporaciones ON desincorporaciones.id_tipo_desincorporaciones=tipo_desincorporaciones.id_tipo_desincorporaciones ".$where." AND desincorporaciones.id_organismo = $_SESSION[id_organismo]";
$row_exi=& $conn->Execute($sql_exi);
$exi = $row_exi->fields("exi");
if($id_tipo!=''){
	$sql_tipo = "SELECT nombre FROM tipo_desincorporaciones WHERE id_tipo_desincorporaciones = $id_tipo AND id_organismo=$_SESSION[id_organismo]";
	$row_tipo =& $conn->Execute($sql_tipo);
	$nom_tipo = $row_tipo->fields("nombre");
}
//************************************************************************
//if (!$row->EOF)
//{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		
		//Cabecera de página
		function Header()
		{		
			global $exi;
			global $fecha_desde;
			global $fecha_hasta;
			global $id_tipo;
			global $nom_tipo;
			//$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln(10);	
			if($exi!=0){
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'LISTADO DE DESINCORPORACIONES',0,1,'C');
			$this->SetFont('Arial','B',10);
			if($id_tipo!=''){
				$x = $this->GetX();
				$y = $this->GetY();
				$this->Cell(50,10,'TIPO DESINCORPORACION: ',0,0,'L');
				$this->SetXY($x+50,$y);
				$this->Cell(24,10,$nom_tipo,0,0,'L');
			}
			$x = $this->GetX();
			$y = $this->GetY();
			if($fecha_desde!='' && $fecha_hasta!=''){
				$this->SetXY($x+20,$y);
				$this->Cell(35,10,"DE LA FECHA DEL: ",0,0,'L');
				$this->Cell(20,10,$fecha_desde,0,0,'L');
				$this->Cell(20,10,"HASTA EL: ",0,0,'L');
				$this->Cell(20,10,$fecha_hasta,0,0,'L');
			}
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(48,		6,			'BIEN',		1,0,'C',1);
			$this->Cell(43,	6,			'FECHA DESINCORPORACION',			1,0,'C',1);
			$this->Cell(99,	6,			'DESCRIPCION',			1,0,'C',1);
			$this->Ln(6);
			}
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-25);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(25,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(80,3,'Impreso por:',0,0,'R');
			$this->Cell(83,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(87,276,strtoupper($_SESSION['usuario']),40,6);
			//$this->SetFont('barcode','',6);
			//$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		$pdf->Cell(47,	20,		$row->fields("nombre"),				1,0,'L',1);
		$pdf->Cell(45,	20,		substr($row->fields("fecha_desincorporacion"),0,10),						1,0,'L',1);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->MultiCell(98,	3,		strtoupper($row->fields("descripcion_general")),						0,'LBR','L');
		$pdf->SetXY($x+98,$y);
		$pdf->Cell(0,	20,	"",1,1,'L',1);
		$y = $pdf->GetY();
		$pdf->Line($x,$y,$x+98,$y);
		//$pdf->Ln();
		$row->MoveNext();
	}
	if($exi==0){
		$pdf->Ln(90);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,90,200,90);
		$pdf->Cell(175,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",142,105,50);
		$pdf->Line(10,180,200,180);
	}
	$pdf->Output();
//}
?>