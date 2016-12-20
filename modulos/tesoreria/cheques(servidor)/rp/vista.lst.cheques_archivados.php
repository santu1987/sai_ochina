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
$banco=$_GET['banco'];
$agencia=$_GET['agencia'];
$atencion=$_GET['atencion'];
$gerente=$_GET['gerente'];
$firmante=$_GET['firmante'];
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$id_banco=$_GET['id_banco'];
$n_cuenta=$_GET['n_cuenta'];
if($id_banco!="")
{
	$where.=" AND cheques.id_banco=$id_banco";
}
if($n_cuenta!="")
{
	$where.=" AND cheques.cuenta_banco='$n_cuenta'";
}
if(($desde!="")&&($hasta!=""))
{
	$where.=" AND cheques.estado_fecha[4] between'$desde' and '$hasta' ";
}

$sql = "SELECT
			cheques.numero_cheque,
			cheques.id_proveedor,
			cheques.monto_cheque as monto,
			cheques.fecha_cheque as emitido,
			cheques.estado_fecha[4] as entregado,
			cheques.benef_nom,
		    cheques.cuenta_banco
		FROM
			cheques
		
		WHERE
			cheques.estado[4]='1'
		$where	
		ORDER BY	
			cheques.cuenta_banco"; 
$row= $conn->Execute($sql);	
//

//
$sql2 = "SELECT
			count(id_cheques)
		FROM
			cheques
		
		WHERE
			cheques.estado[4]='1'
		$where	
"; 
$row2= $conn->Execute($sql2);	
$sql3 = "SELECT
			SUM(monto_cheque)
		FROM
			cheques
		
		WHERE
			cheques.estado[4]='1'
		$where	
"; 
$row3= $conn->Execute($sql3);
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
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,utf8_decode('República Bolivariana de Venezuela'),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,utf8_decode('Dirección General de Empresas y Servicios'),0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,utf8_decode('Oficina Coordinadora de Hidrografía y Navegación'),0,0,'C');	
			$this->Ln(10);	
			$this->SetFont('Arial','B',12);
			$this->Cell(0,10,'LISTADO DE CHEQUES ARCHIVADOS ',0,1,'C');
			$this->SetFont('Arial','B',10);
			$this->Ln(6);
			$this->SetFont('Times','B',8);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(5,6,'',0,0,'C',0);
			$this->Cell(20,6,utf8_decode('Nº CHEQUE'),1,0,'C',1);
			$this->Cell(70,6,utf8_decode('NOMBRE PROVEEDOR'),1,0,'C',1);
			$this->Cell(25,6,utf8_decode('MONTO'),1,0,'C',1);
			$this->Cell(30,6,utf8_decode('FECHA EMISIÓN'),1,0,'C',1);
			$this->Cell(30,6,utf8_decode('FECHA ENTREGA'),1,0,'C',1);
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
			$this->Cell(40,3,'Impreso por: '.strtoupper($_SESSION['usuario']),0,0,'R');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(80,285,strtoupper($_SESSION['usuario']),40,6);
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
	//
		$id_prove=$row->fields("id_proveedor");
		if($row->fields("benef_nom")!='')
		{	
			$prove=$row->fields("benef_nom");
		}
		else
		{
			$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
			$row_proveedor=& $conn->Execute($sql_proveedor);
			$prove=$row_proveedor->fields("nombre");
		}
	//
		/*if($row->fields("benef_nom")!='')
		{
			$prove=$row->fields("benef_nom");
		}else
			$prove=$row->fields("provee");*/
		$ano_e=substr($row->fields("emitido"),0,4); $mes_e=substr($row->fields("emitido"),5,2);
		$dia_e=substr($row->fields("emitido"),8,2);
		$emitido=$dia_e."-".$mes_e."-".$ano_e;
		$ano_en=substr($row->fields("entregado"),0,4);
		$mes_en=substr($row->fields("entregado"),5,2);
		$dia_en=substr($row->fields("entregado"),8,2);
		$entregado=$dia_en."-".$mes_en."-".$ano_en;
		$pdf->Cell(5,6,'',0,0,'L',1);
		$pdf->Cell(20,6,$row->fields("numero_cheque"),1,0,'L',1);
		$pdf->Cell(70,6,utf8_decode($prove),1,0,'L',1);
		$pdf->Cell(25,6,number_format($row->fields("monto"),2,',','.'),1,0,'R',1);
		$pdf->Cell(30,6,$emitido,1,0,'C',1);
		$pdf->Cell(30,6,$entregado,1,1,'C',1);
		$row->MoveNext();		
	}
	$pdf->Cell(25,6,'',0,0,'L',1);
	$pdf->Cell(70,6,$row2->fields("count")."  CHEQUES "."     TOTAL Bs.",1,0,'C',1);
	$pdf->Cell(25,6,number_format($row3->fields("sum"),2,',','.'),1,0,'R',1);
	$pdf->Output();
}else
{
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
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
		$pdf->Cell(40,		6,"No se encuentran los datos",			0,0,'L',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}
?>