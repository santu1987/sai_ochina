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
$id_sitio = '';
$fecha_desde = '';
$fecha_hasta = '';
$where = " WHERE 1 = 1 ";
if($_GET['id_trabajador']!='')
	$id_trabajador = $_GET['id_trabajador'];
if($id_trabajador!='')
	$where.= " AND trabajador.id_trabajador = $id_trabajador ";
$Sql="SELECT 
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				unidad_ejecutora.nombre as unidad,
				cargos.descripcion as cargo
			FROM 
				persona
			INNER JOIN
				trabajador
			ON
				persona.id_persona = trabajador.id_persona
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
				persona.id_organismo = $_SESSION[id_organismo]
		";
$row=& $conn->Execute($Sql);

$sql_exi = "SELECT 
				count(persona.id_persona) as exi
			FROM 
				persona
			INNER JOIN
				trabajador
			ON
				persona.id_persona = trabajador.id_persona
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
				persona.id_organismo = $_SESSION[id_organismo]
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
			$this->Cell(0,10,'CONCEPTOS POR TRABAJADOR',0,1,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			//$this->Cell(35,	6,			'CEDULA',			1,0,'C',1);
			//$this->Cell(38,	6,			'NOMBRE',			1,0,'C',1);
			//$this->Cell(35,	6,			'APELLIDO',			1,0,'C',1);
			//$this->Cell(40,	6,			'UNIDAD',			1,0,'C',1);
			//$this->Cell(37,	6,			'CARGO',			1,0,'C',1);
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
			$this->Cell(30,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(70,3,'Impreso por:',0,0,'R');
			$this->Cell(80,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(85,280,strtoupper($_SESSION['usuario']),40,6);
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
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(0) ;
		$pdf->SetTextColor(255);
		$pdf->Cell(37,		6,			"CEDULA",		1,0,'L',1);
		$pdf->Cell(37,		6,			"NOMBRE",		1,0,'L',1);
		$pdf->Cell(37,		6,			"APELLIDO",		1,0,'L',1);
		$pdf->Cell(37,		6,			"UNIDAD",		1,0,'L',1);
		$pdf->Cell(37,		6,			"CARGO",		1,0,'L',1);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(255) ;
		$pdf->SetTextColor(0);
		$pdf->Ln();
		$pdf->Cell(37,		6,			$row->fields("cedula"),		1,0,'L',1);
		$pdf->Cell(37,		6,			$row->fields("nombre"),		1,0,'L',1);
		$pdf->Cell(37,		6,			$row->fields("apellido"),		1,0,'L',1);
		$pdf->Cell(37,		6,			$row->fields("unidad"),		1,0,'L',1);
		$pdf->Cell(37,		6,			$row->fields("cargo"),		1,0,'L',1);
		$pdf->Ln();
		//
		$sql_con = "SELECT 
						id_concepto,
						descripcion,
						asignacion_deduccion
					FROM
						conceptos
					WHERE 
						id_organismo = $_SESSION[id_organismo]";
		$row_con =& $conn->Execute($sql_con);
		while(!$row_con->EOF){
			$sql_cf = "SELECT 
							id_concepto_fijos,
							porcentaje,
							monto
						FROM
							conceptos_fijos
						WHERE
							id_concepto = ".$row_con->fields("id_concepto")."
						AND
							id_trabajador = ".$row->fields("id_trabajador")."
						AND
							id_organismo = $_SESSION[id_organismo]
				";
			$row_cf =& $conn->Execute($sql_cf);	
			
			$sql_cv = "SELECT
							id_concepto_variable,
							porcentaje,
							monto
						FROM
							concepto_variable
						WHERE
							id_concepto = ".$row_con->fields("id_concepto")."
						AND
							id_trabajador = ".$row->fields("id_trabajador")."
						AND
							id_organismo = $_SESSION[id_organismo]";
			$row_cv =& $conn->Execute($sql_cv);
			if($row_cf->fields("id_concepto_fijos")!='' || $row_cv->fields("id_concepto_variable")!=''){
				$pdf->SetLineWidth(0.3);
				$pdf->SetFillColor(100) ;
				$pdf->SetTextColor(255);
				$pdf->Cell(185,		6,			strtoupper($row_con->fields("descripcion")),		1,0,'L',1);
				$pdf->SetLineWidth(0.3);
				$pdf->SetFillColor(0) ;
				$pdf->SetTextColor(255);
				$pdf->Ln();
				if($row_cf->fields("id_concepto_fijos")!=''){
					$pdf->Cell(75,		6,			"CONCEPTO FIJO",		1,0,'L',1);
					$pdf->Cell(75,		6,			"PORCENTAJE",		1,0,'L',1);
					$pdf->Cell(35,		6,			"MONTO",		1,0,'L',1);
					$pdf->Ln();
					$pdf->SetLineWidth(0.3);
					$pdf->SetFillColor(255) ;
					$pdf->SetTextColor(0);
					$pdf->Cell(75,		6,			"",		1,0,'L',1);
					$pdf->Cell(75,		6,			$row_cf->fields("porcentaje"),		1,0,'L',1);
					$pdf->Cell(35,		6,			$row_cf->fields("monto"),		1,0,'L',1);
					$pdf->Ln();
				}
				if($row_cv->fields("id_concepto_variable")!=''){
					$pdf->Cell(75,		6,			"CONCEPTO VARIABLE",		1,0,'L',1);
					$pdf->Cell(75,		6,			"PORCENTAJE",		1,0,'L',1);
					$pdf->Cell(35,		6,			"MONTO",		1,0,'L',1);
					$pdf->Ln();
					$pdf->SetLineWidth(0.3);
					$pdf->SetFillColor(255) ;
					$pdf->SetTextColor(0);
					$pdf->Cell(75,		6,			"",		1,0,'L',1);
					$pdf->Cell(75,		6,			$row_cv->fields("porcentaje"),		1,0,'L',1);
					$pdf->Cell(35,		6,			$row_cv->fields("monto"),		1,0,'L',1);
					$pdf->Ln();
				}
				
			}
		$row_con->MoveNext();
		}				
		//
		$pdf->Ln();
		$row->MoveNext();
	}

	
	if($exi==0){
		$pdf->Ln(60);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,70,285,70);
		$pdf->Cell(250,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",178,80,50);
		$pdf->Line(10,140,285,140);
	}
	$pdf->Output();
//}
?>