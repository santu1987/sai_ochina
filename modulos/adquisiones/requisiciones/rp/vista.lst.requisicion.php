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
$numero = $_GET['numero_requi'];
$ano=$_GET['ano'];
$Sql="
SELECT 
	id_requisicion_encabezado,
	numero_requisicion,
	requisicion_encabezado.id_unidad_ejecutora, 
	unidad_ejecutora.codigo_unidad_ejecutora AS codigo_unidad,
	unidad_ejecutora.nombre AS unidad_ejecutora,
	unidad_ejecutora.jefe_unidad,	
	requisicion_encabezado.id_proyecto,
	proyecto.codigo_proyecto,
	proyecto.nombre AS proyecto,
	proyecto.id_jefe_proyecto AS jefe_proyecto, 
	requisicion_encabezado.id_accion_centralizada, 
	accion_centralizada.codigo_accion_central, 
	accion_centralizada.denominacion AS accion_centralizada, 
	accion_centralizada.id_jefe_proyecto AS jefe_accion, 
	requisicion_encabezado.id_accion_especifica,
	accion_especifica.codigo_accion_especifica, 
	accion_especifica.denominacion AS accion_especifica, 
	accion_especifica.id_jefe_proyecto AS responsable, 
	fecha_requisicion, 
	asunto, 
	cedula_jefe_proyecto, 
	usuario_elabora_requisicion, 
	estatus
FROM 
	requisicion_encabezado
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = requisicion_encabezado.id_unidad_ejecutora
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = requisicion_encabezado.id_accion_centralizada
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = requisicion_encabezado.id_proyecto
LEFT JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = requisicion_encabezado.id_accion_especifica
WHERE 
	(numero_requisicion = '".$numero."') 
AND 
	requisicion_encabezado.ano = '".$ano."' 
ORDER BY 
	id_requisicion_encabezado";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	$SqlRequiDetalle="SELECT numero_requision, secuencia, cantidad, nombre, descripcion FROM  requisicion_detalle INNER JOIN unidad_medida ON	unidad_medida.id_unidad_medida= requisicion_detalle.id_unidad_medida WHERE (numero_requision = '".$row->fields("numero_requisicion")."') ORDER BY secuencia";
	$rowRequiDetalle=& $conn->Execute($SqlRequiDetalle);

	if($row->fields("id_proyecto") != 0){
		$proyecto_accion = $row->fields('proyecto');
		$codigo_proyecto_accion = $row->fields('codigo_proyecto');
		$jefe_proyecto = $row->fields('jefe_proyecto');
	}else{
		$proyecto_accion = $row->fields('accion_centralizada');
		$codigo_proyecto_accion = $row->fields('codigo_accion_central');
		$jefe_proyecto = $row->fields('jefe_accion');
	}
	
	
		$SqlProyectoAccionJefe="SELECT nombre_jefe_proyecto
							  FROM jefe_proyecto
							WHERE
								(id_jefe_proyecto =".$jefe_proyecto.")";
								
		$rowProyectoAccionJefe=& $conn->Execute($SqlProyectoAccionJefe);
		$nombre_jefe_proyecto = $rowProyectoAccionJefe->fields('nombre_jefe_proyecto');
	
	
	//************************************************************************
	$AccionE = $row->fields('accion_especifica');
	$codigo_especifica = $row->fields("codigo_accion_especifica");
	$codigo_unidad = $row->fields("codigo_unidad");
	$unidad_ejecutora = $row->fields("unidad_ejecutora");
	$jefe_unidad = $row->fields("jefe_unidad");
	$asunto = $row->fields('asunto');
	$numero_requisicion = $row->fields('numero_requisicion');
	$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
	
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
			$this->Cell(0,10,'REQUISICION',0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',10);
			$this->Cell(175,10,'Nº Requisición ',0,0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(0,10,$numero_requisicion,0,0,'R');
			$this->Ln(10);	
			$this->SetFont('Arial','B',10);
			$this->Cell(33,10,'Unidad Solicitante  ','LBT',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(0,10,$codigo_unidad." ".utf8_decode($unidad_ejecutora),'TBR',0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(33,10,'Proyecto/A.C.','LB',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(0,10,$codigo_proyecto_accion." ".utf8_decode($proyecto_accion),'BR',0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(33,10,'Accion Especifica','LB',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(0,10,$codigo_especifica." ".utf8_decode($AccionE),'BR',0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(33,10,'Asunto  ','LB',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(0,10,utf8_decode($asunto),'BR',0,'L');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(15,		5,			'Renglon',		0,0,'C',1);
			$this->Cell(25,		5,			'Cantidad',		0,0,'C',1);
			$this->Cell(25,		5,			'Unid. Med',	0,0,'C',1);
			$this->Cell(125,	5,			'Descripcion',	0,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			global $jefe_unidad, $nombre, $unidad_ejecutora, $nombre_jefe_proyecto;
			//Posición: a 2,5 cm del final
			$this->SetY(-30);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(65,3,'' ,0,0,'L');
			$this->Ln(10);
			$this->Cell(65,3,strtoupper( utf8_decode($_SESSION[nombre]).' '.utf8_decode($_SESSION[apellido]))  ,0,0,'C');
			$this->Cell(65,3,strtoupper($jefe_unidad) ,0,0,'C');
			$this->Cell(65,3,strtoupper($nombre_jefe_proyecto) ,0,0,'C');
			$this->Ln();
			$this->Cell(65,3,'Elabarado por' ,0,0,'C');
			$this->Cell(65,3,'Jefe de la '.utf8_decode($unidad_ejecutora) ,0,0,'C');
			$this->Cell(65,3,'Responsable del Proyecto / A.C.' ,0,0,'C');
			$this->Ln(5);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->SetFont('barcode','',6);
			$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			/*$this->SetFont('Arial','I',9);
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');	*/				
			$this->Ln();
			/*$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');*/
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
		$pdf->Cell(15,		6,$i,											0,0,'C',1);
		$pdf->Cell(25,		6,number_format($rowRequiDetalle->fields("cantidad"),0,',','.'),		0,0,'C',1);
		$pdf->Cell(25,		6,utf8_decode($rowRequiDetalle->fields("nombre")),			0,0,'C',1);
		$pdf->MultiCell(125,6,utf8_decode($rowRequiDetalle->fields("descripcion")),		0,'L',0);
		$pdf->Ln(6);
		$rowRequiDetalle->MoveNext();
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