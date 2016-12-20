<?php
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
$cotizacion = $_GET['numero_coti'];
$unidad_ejecutora = $_GET['unidad_ejecutora'];
$ano = $_GET['ano'];

$where = "WHERE (1=1) ";
if ($cotizacion != "")
	$where = $where . " AND	(\"solicitud_cotizacionE\".numero_cotizacion = '$cotizacion') ";
if ($ano != "")
	$where = $where . " AND	(\"solicitud_cotizacionE\".ano = '$ano') ";

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$Sql="SELECT 
	 proveedor.id_proveedor, proveedor.nombre AS proveedor, proveedor.direccion, proveedor.telefono, \"solicitud_cotizacionE\".*
FROM 
	\"solicitud_cotizacionE\"

INNER JOIN
	proveedor
ON
	\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
	".$where."
	 ORDER BY id_solicitud_cotizacione";
$row=& $conn->Execute($Sql);

$sql_organismo = "
SELECT nombre, codigo_area, telefono, 
       fax,  email
  FROM organismo
  WHERE (id_organismo = ".$_SESSION['id_organismo'].")";
  $row_organismo=& $conn->Execute($sql_organismo);

//************************************************************************
if (!$row->EOF)
{ 
	//************************************************************************
	$sqldocumento = "
	SELECT 
		 documento.nombre AS documento, documento_proveedor.estatus
	FROM	
		proveedor
	INNER JOIN
		documento_proveedor
	ON
		proveedor.id_proveedor = documento_proveedor.id_proveedor
	INNER JOIN
		documento
	ON
		documento_proveedor.id_documento = documento.id_documento_proveedor
	WHERE 
		proveedor.id_proveedor = ".$row->fields("id_proveedor")."
	AND
		documento_proveedor.estatus = TRUE	
	ORDER BY 
		documento.nombre
	";
	  $row_documento= $conn->Execute($sqldocumento);
	  $documento_con = "Debe consignar los siguientes documento al entregar la cotizacion: ";
	  
	while(!$row_documento->EOF){
		$documento_con = $documento_con. $row_documento->fields("documento");
		
		$row_documento->MoveNext();
		if (!$row_documento->EOF){
			$documento_con = $documento_con .",";
		}
	}
	
	$proveedor=$row->fields("proveedor");
	$numero_cotizacion=$row->fields("numero_cotizacion");
	$direccion=$row->fields("direccion");
	$telefono=$row->fields("telefono");
	$titulo=$row->fields("titulo");
	$comentarios=$row->fields("comentarios");
	$telefono_or="(" .$row_organismo->fields("codigo_area").")".$row_organismo->fields("telefono");
	$fax="(" .$row_organismo->fields("codigo_area").")".$row_organismo->fields("fax");
	$email=$row_organismo->fields("email");
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proveedor;
			global $numero_cotizacion;
			global $direccion, $documento_con ;
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
			$this->Ln(2);
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'SOLICITUD DE COTIZACIÓN',0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',10);
			$this->Cell(175,10,'Nº Cotización ',0,0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,10,$numero_cotizacion,0,0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(20,10,'Proveedor ','LT',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(85,10,$proveedor,'TR',0,'L');
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'Referencia ','LT',0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,10,$numero_cotizacion,'RT',0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$y=$this->GetY();
			$this->MultiCell(105,10,$direccion.'. Telf. '.$telefono,'LBR','L');
			$this->SetXY(115,$y);
			$this->MultiCell(85,10,' Telf. 303-8761 3038762                                               Fax. 303-8761 3038762','LBR','L'); 
			$this->SetFont('Arial','',10);
			$this->Cell(190,10,$titulo,1,0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$this->Cell(190,10,$documento_con ,1,0,'L');
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
			global $nombre;
			global $telefono_or,$fax,$email;
			//Posición: a 2,5 cm del final
			$this->SetY(-36);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(125,3,'Favor enviar su oferta a los Nº Fax '. $telefono_or.' '. $fax.'y/o al e-mail '.$email,	0,0,'L');
			$this->Ln();
			$this->Cell(125,3,'Indicar tiempo y lugar de entrega, validez de oferta y condiciones de pago',	0,0,'L');
			$this->Ln();
			$this->Cell(155,3,'COMPRADOR' ,				0,0,'R');
			$this->Cell(40,3,	strtoupper($_SESSION[nombre].' '.$_SESSION[apellido]) ,	0,0,'L');
			$this->Ln(6);
			$this->Cell(65,3,'Observaciones ' ,0,0,'L');
			$this->Ln(20);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->SetFont('barcode','',6);
			$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			$this->SetFont('Arial','I',9);
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
		}
	}
	//************************************************************************

	$total=0;
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,50);
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
		$pdf->Line(10,113,10,250);
		$pdf->Line(22,120,22,250);
		$pdf->Line(40,120,40,250);
		$pdf->Line(55,120,55,250);
		$pdf->Line(200,120,200,250);
		$pdf->Line(10,250,200,250);
		
	while (!$rowProyectoAccion->EOF) 
	{
	$i++;
		
		$pdf->Cell(12,		5,	$i,											'L',0,'C',1);
		$pdf->Cell(18,		5,	$rowProyectoAccion->fields('cantidad'),		'L',0,'C',1);
		$pdf->Cell(15,		5,	$rowProyectoAccion->fields('nombre'),		'L',0,'C',1);
		$pdf->MultiCell(145,5,	$rowProyectoAccion->fields('descripcion'),	'LR','L');
		$rowProyectoAccion->MoveNext();
	}
	$pdf->Output();
}
?>