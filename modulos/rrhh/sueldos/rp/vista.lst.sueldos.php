<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$id_unidad = '';
$fecha_desde = '';
$fecha_hasta = '';
$id_trabajador = '';
if($_GET['fecha_desde']!='')
	$fecha_desde = $_GET['fecha_desde'];
if($_GET['fecha_hasta']!='')
	$fecha_hasta = $_GET['fecha_hasta'];
$where = " WHERE 1 = 1 ";
if($_GET['id_unidad']!='')
	$id_unidad = $_GET['id_unidad'];
if($_GET['id_trabajador']!='')
	$id_trabajador = $_GET['id_trabajador'];
if($id_unidad!='')
	$where.= " AND trabajador.id_unidad = $id_unidad ";
if($id_trabajador!='')
	$where.= " AND trabajador.id_trabajador = $id_trabajador ";
//if($fecha_desde!='' && $fecha_hasta!='')
	//$where.= " AND bienes.fecha_compra BETWEEN '$fecha_desde' AND '$fecha_hasta' ";
$Sql="SELECT 
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				unidad_ejecutora.nombre as unidad,
				cargos.descripcion
			FROM 
				persona
			INNER JOIN
				trabajador
			ON
				persona.id_persona = trabajador.id_persona
			INNER JOIN
				unidad_ejecutora
			ON
				trabajador.id_unidad=unidad_ejecutora.id_unidad_ejecutora
			INNER JOIN
				cargos
			ON	
				trabajador.id_cargo = cargos.id_cargos
			".$where."
			AND
				persona.id_organismo = $_SESSION[id_organismo]
		";
$row=& $conn->Execute($Sql);
$sql_exi = "SELECT 
				count(id_trabajador) as exi
			FROM 
				trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona = persona.id_persona
			INNER JOIN
				unidad_ejecutora
			ON
				trabajador.id_unidad = unidad_ejecutora.id_unidad_ejecutora
			INNER JOIN
				cargos
			ON
				trabajador.id_cargo = cargos.id_cargos
				
			".$where."
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
			";
$row_exi =& $conn->Execute($sql_exi);
$exi = $row_exi->fields('exi');
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
			//$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'REPÚBLICA BOLIVARIANA DE VENEZUELA',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'MINISTERIO DEL PODER POPULAR PARA LA DEFENSA',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'VICEMINISTRO DE SERVICIOS',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'DIRECCIÓN GENERAL DE EMPRESAS Y SERVICIOS',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'OFICINA COORDINADORA DE HIDROGRAFÍA Y NAVEGACIÓN',0,0,'C');	
			$this->Ln(10);
			if($exi!=0){
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'AUMENTO DE SUELDO POR TRABAJADOR',0,1,'C');
			$this->SetFont('Arial','B',10);
			//$this->Cell(24,10,'JEFE DIV. DE ',0,0,'L');
			//$this->Cell(24,10,strtoupper($_SESSION['nom_uni']),0,1,'L');
			//$this->Cell(24,10,'SITIO FISICO ',0,0,'L');
			//$this->Cell(24,10,strtoupper($_SESSION['nom_sit']),0,0,'L');
			$x = $this->GetX();
			$y = $this->GetY();
			if($fecha_desde!='' && $fecha_hasta!=''){
				$this->SetXY($x+130,$y);
				$this->Cell(35,10,"DE LA FECHA DEL: ",0,0,'L');
				$this->Cell(20,10,$fecha_desde,0,0,'L');
				$this->Cell(20,10,"HASTA EL: ",0,0,'L');
				$this->Cell(20,10,$fecha_hasta,0,0,'L');
			}
			//
			
			
			
			//
			}
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(25,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(80,3,'Impreso por:',0,0,'R');
			$this->Cell(83,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(87,286,strtoupper($_SESSION['usuario']),40,6);
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
		$pdf->Ln(6);
		$pdf->SetFont('Times','B',8);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(0) ;
		$pdf->SetTextColor(255);
		$pdf->Cell(20,	6,			'CÉDULA',			1,0,'C',1);
		$pdf->Cell(35,	6,			'NOMBRE',			1,0,'C',1);
		$pdf->Cell(50,	6,			'APELLIDO',			1,0,'C',1);
		$pdf->Cell(25,	6,			'UNIDAD',			1,0,'C',1);
		$pdf->Cell(44,	6,			'CARGO',			1,1,'C',1);
		//$pdf->Ln(6);
		$pdf->SetFillColor(255) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(20,		6,			$row->fields("cedula"),		1,0,'L',1);
		$pdf->Cell(40,		6,			utf8_decode($row->fields("nombre")),		1,0,'L',1);
		
		$pdf->Cell(40,		6,			utf8_decode($row->fields("apellido")),		1,0,'L',1);
		$pdf->Cell(40,		6,			utf8_decode($row->fields("unidad")),		1,0,'L',1);
		$pdf->Cell(34,		6,			utf8_decode($row->fields("descripcion")),		1,1,'L',1);
		//$pdf->SetLineWidth(0.3);
		
		$sql_suel = "SELECT fecha_aumento, sueldo_aumento, observacion FROM aumento_sueldo WHERE id_trabajador = ".$row->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo]";
		$row_suel =& $conn->Execute($sql_suel); 
		//
		$suel = str_replace('{','',$row_suel->fields("sueldo_aumento"));
		$suel = str_replace('}','',$suel);
		$arreglo = split(',',$suel);
		$arreglo2 = split('"',$row_suel->fields("fecha_aumento"));
		$tam=strlen($row_suel->fields("observacion"));
		$obs = substr($row_suel->fields("observacion"),1,$tam-2);
		$arreglo3 = split(',',$obs);
		$count = 0;
		for($i=0; $i<=39; $i++){
			if($i%2!=0){
				$tam = strlen($arreglo[$i]);
				
				$fecha[$count] =$arreglo2[$i];
				$count++;
			}
		}
		
		if($arreglo[0]!=0){
		$pdf->SetFillColor(0) ;
		$pdf->SetTextColor(255);
		$pdf->Cell(33,		6,			'FECHA DE AUMENTO',		1,0,'L',1);
		$pdf->Cell(33,		6,			'MONTO',		1,0,'C',1);
		$pdf->Cell(108,		6,			'OBSERVACIÓN',		1,1,'C',1);
		$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);
		
		//$pdf->Ln(6);
		}
		
		for($i=0; $i<=19; $i++){
			if($arreglo[$i]!='0' && $arreglo[$i]!=''){
				$pdf->Cell(33,		6,			substr($fecha[$i],8,2)."-".substr($fecha[$i],5,2)."-".substr($fecha[$i],0,4),		1,0,'L',1);
				$pdf->Cell(33,		6,			$arreglo[$i],		1,0,'L',1);
				$pdf->Cell(108,		6,			utf8_decode($arreglo3[$i]),		1,1,'L',1);
			}
			else
				break;
		}
		//
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