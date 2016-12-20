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
$sql = "SELECT
			cheques.numero_cheque,
			cheques.benef_nom,
			cheques.monto_cheque as monto,
			cheques.fecha_cheque as emitido,
			cheques.estado_fecha[4] as entregado,
			cheques.estado_fecha[5] as conformado,
			cheques.nombre_conformador as conformador,
			cheques.id_proveedor
		FROM
			cheques
		WHERE
			cheques.estado[5]='1'
		AND
			cheques.id_banco=$id_banco
		AND
			cheques.estado_fecha[5] between '$desde' AND '$hasta'
		ORDER BY	
			cheques.numero_cheque"; 
$row= $conn->Execute($sql);	

		
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
			global $banco;
			global $desde; global $hasta;
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
			$this->Cell(0,10,'LISTADO DE CHEQUES CONFORMADOS '.$banco,0,1,'C');
			$this->Cell(0,10,'DESDE: '.$desde.' HASTA: '.$hasta,0,1,'C');
			$this->SetFont('Arial','B',10);
			$this->Ln(6);
			$this->SetFont('Times','B',8);
			$this->SetFillColor(0) ;
			$this->SetTextColor(255);
			$this->Cell(20,6,utf8_decode('Nº CHEQUE'),1,0,'C',1);
			$this->Cell(60,6,utf8_decode('BENEFICIARIO/PROVEEDOR'),1,0,'C',1);
			$this->Cell(25,6,utf8_decode('MONTO'),1,0,'C',1);
			$this->Cell(20,6,utf8_decode('FECHA EMI'),1,0,'C',1);
			$this->Cell(20,6,utf8_decode('FECHA ENT'),1,0,'C',1);
			$this->Cell(20,6,utf8_decode('FECHA CONF'),1,0,'C',1);
			$this->Cell(25,6,utf8_decode('CONFORMADO'),1,0,'C',1);
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
		if($row->fields("benef_nom")!=""){
	$proveedor=$row->fields("benef_nom");
		}else
		{
				$id_prove_sig=$row->fields("id_proveedor");
				$sql_proveedor="select * from proveedor where id_proveedor='$id_prove_sig'";
				$row_proveedor=& $conn->Execute($sql_proveedor);
				$proveedor=$row_proveedor->fields("nombre");
			}
		$ano_e=substr($row->fields("emitido"),0,4); $mes_e=substr($row->fields("emitido"),5,2);
		$dia_e=substr($row->fields("emitido"),8,2);
		$emitido=$dia_e."-".$mes_e."-".$ano_e;
		$ano_en=substr($row->fields("entregado"),0,4);
		$mes_en=substr($row->fields("entregado"),5,2);
		$dia_en=substr($row->fields("entregado"),8,2);
		$entregado=$dia_en."-".$mes_en."-".$ano_en;
		//****************
		$ano_co=substr($row->fields("conformado"),0,4);
		$mes_co=substr($row->fields("conformado"),5,2);
		$dia_co=substr($row->fields("conformado"),8,2);
		$conformado=$dia_co."-".$mes_co."-".$ano_co;
		$pdf->Cell(20,6,$row->fields("numero_cheque"),1,0,'L',1);
		$pdf->Cell(60,6,$proveedor,1,0,'L',1);
		$pdf->Cell(25,6,number_format($row->fields("monto"),2,',','.'),1,0,'R',1);
		$pdf->Cell(20,6,$emitido,1,0,'C',1);
		$pdf->Cell(20,6,$entregado,1,0,'C',1);
		$pdf->Cell(20,6,$conformado,1,0,'C',1);
		$pdf->Cell(25,6,$row->fields("conformador"),1,1,'C',1);
		$row->MoveNext();	
		$proveedor="";
	}
	$pdf->Ln(5);
	//$pdf->Cell(90,6,utf8_decode("CANTIDAD DE CHEQUES: ".$row2->fields("count")),0,0,'C',1);
	
	$pdf->Output();
}
?>