<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$where="WHERE 1=1 ";
/*	$ayo=date("Y");
	$mes=date("m");
	$where="where	saldo_contable.ano=$ayo";	*/
if((isset($_GET[desde]))&&(isset($_GET[hasta])))
{
	$desde=$_GET[desde];
	$hasta=$_GET[hasta];
	if(($desde!="")and($hasta!=""))
	{
		$where.=" and reverso_integracion.fecha>='$desde' and reverso_integracion.fecha<='$hasta'";
	}
}	
if(isset($_GET[modulo]))
{
	$modulo=$_GET[modulo];
	$where.="and reverso_integracion.id_unidad='$modulo'";
}

if(isset($_GET[usua]))
{
	$usua=$_GET[usua];
	$where.="and reverso_integracion.usuario='$usua'";
}
$sql_cuenta="				
						SELECT
							   reverso_integracion.id as id_integracion,
							   reverso_integracion.id_unidad,
							   reverso_integracion.numero_comprobante_mov,
							   reverso_integracion.numero_comprobante_integracion, 
       						   reverso_integracion.fecha,
							   reverso_integracion.usuario,
							   reverso_integracion.organismo,
							   unidad_ejecutora.nombre as nombre_unidad,
							   usuario.nombre as nom_us,
							   usuario.apellido as ape_us
							   
                       FROM 
					   		    reverso_integracion
						INNER JOIN
								unidad_ejecutora
						ON
								unidad_ejecutora.id_unidad_ejecutora=reverso_integracion.id_unidad
						INNER JOIN 
								usuario
						ON
								usuario.id_usuario=reverso_integracion.usuario
						$where										
						order by
						fecha";

			
$row=& $conn->Execute($sql_cuenta);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
		global $fecha;
		$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln(10);	
			$this->SetFont('Arial','B',8);
			$this->Cell(0,10,'LISTADOS DE REVERSOS A INTEGRACIÓN REALIZADOS POR USUARIOS AL '." ".$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',7);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(10,6,		     'N º',			0,0,'C',1);
			$this->Cell(50,6,			 'UNIDAD ORIGEN',		0,0,'C',1);
			$this->Cell(30,6,		     'Nº COMP.INT',			0,0,'C',1);
			$this->Cell(30,6,			 'Nº COMP.CONT',		0,0,'C',1);
			$this->Cell(40,6,			 'USUARIO',		0,0,'C',1);
			$this->Cell(20,6,			 'FECHA',		0,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(88,285,strtoupper($_SESSION['usuario']),40,6);
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
		
		$pdf->Cell(10,6,				$row->fields("id_integracion"),0,0,'C',1);
		$pdf->Cell(50,6,				$row->fields("nombre_unidad"),0,0,'L',1);
	    $pdf->Cell(30,6,				$row->fields("numero_comprobante_integracion"),0,0,'C',1);
	    $pdf->Cell(30,6,				$row->fields("numero_comprobante_mov"),0,0,'C',1);
		
		$ano=substr($row->fields("fecha"),0,4);
		$mes=substr($row->fields("fecha"),5,2);
		$dia=substr($row->fields("fecha"),8,2);
		
		 $pdf->Cell(40,6,				$row->fields("nom_us")." ".$row->fields("ape_us"),0,0,'C',1);
		$pdf->Cell(20,6,				$dia."/".$mes."/".$ano,0,0,'C',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$row->MoveNext();
		$pdf->Ln();
	}
	
	$pdf->Output();

}
else
{	
	require('../../../../utilidades/fpdf153/fpdf.php');
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
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
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