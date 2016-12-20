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
			proveedor.nombre as provee,
			cheques.monto_cheque as monto,
			cheques.fecha_cheque as emitido,
			cheques.estado_fecha[3] as entregado,
			cheques.benef_nom
		FROM
			cheques
		INNER JOIN
			proveedor
		ON
			proveedor.id_proveedor=cheques.id_proveedor
		WHERE
			cheques.estado[3]='1'
		AND
			cheques.id_banco=$id_banco
		AND
			cheques.estado_fecha[3] between '$desde' and '$hasta'
		ORDER BY	
			cheques.numero_cheque"; 
$row= $conn->Execute($sql);	
$sql2 = "SELECT
			count(id_cheques)
		FROM
			cheques
		INNER JOIN
			proveedor
		ON
			proveedor.id_proveedor=cheques.id_proveedor
		WHERE
			cheques.estado[2]='1'
		AND
			cheques.id_banco=$id_banco
		AND
			cheques.estado_fecha[2] between '$desde' and '$hasta'
"; 
$row2= $conn->Execute($sql2);	
$sql3 = "SELECT
			SUM(monto_cheque)
		FROM
			cheques
		INNER JOIN
			proveedor
		ON
			proveedor.id_proveedor=cheques.id_proveedor
		WHERE
			cheques.estado[2]='1'
		AND
			cheques.id_banco=$id_banco
		AND
			cheques.estado_fecha[2] between '$desde' and '$hasta'
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
			global $banco;
			global $agencia;
			global $atencion;
			global $gerente;
			global $firmante;
			global $desde;
			global $hasta;
			global $mes;
			$mes=date("m");
			if($mes==1){ $mes="Enero";} if($mes==2){ $mes="Febrero";}
			if($mes==3){ $mes="Marzo";} if($mes==4){ $mes="Abril";}
			if($mes==5){ $mes="Mayo";} if($mes==6){ $mes="Junio";}
			if($mes==7){ $mes="Julio";} if($mes==8){ $mes="Agosto";}
			if($mes==9){ $mes="Septiembre";} if($mes==10){ $mes="Octubre";}
			if($mes==11){ $mes="Noviembre";} if($mes==12){ $mes="Diciembre";}
			global $fecha;
			$fecha="MAIQUETIA, ".date("d")." de ".$mes." del ".date("Y");
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",170,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(5,8,utf8_decode($banco),0,0,'L');
			$this->Ln();
			$this->Cell(0,8,utf8_decode('ATENCIÓN: '.strtoupper($atencion)),0,0,'L');
			$this->Ln();
			$this->Cell(0,8,utf8_decode('GERENTE: '.strtoupper($gerente)),0,0,'L');
			$this->Ln();			
			$this->Cell(0,8,utf8_decode('AGENCIA: '.strtoupper($agencia)),0,0,'L');		
			$this->Ln();			
			$this->Cell(0,8,utf8_decode('PRESENTE.- '),0,0,'L');			
			$this->Cell(0,8,$fecha,0,0,'R');
			$this->Ln(10);	
			$this->SetFont('Arial','B',12);
			$this->Cell(0,10,'LISTADO DE CHEQUES PAGADOS DESDE EL '.$desde.' HASTA '.$hasta,0,1,'C');
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
			global $firmante;
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','B',8);
			//Número de página
			$this->Cell(70,1,'',0,0,'L');
			$this->Cell(40,3,strtoupper(utf8_decode($firmante)),0,0,'C');
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
		if($row->fields("benef_nom")!="")
		{
			$nombre_proveedor=$row->fields("benef_nom");	
		}else
		$nombre_proveedor=$row->fields("provee");
		$ano_e=substr($row->fields("emitido"),0,4); $mes_e=substr($row->fields("emitido"),5,2);
		$dia_e=substr($row->fields("emitido"),8,2);
		$emitido=$dia_e."-".$mes_e."-".$ano_e;
		$ano_en=substr($row->fields("entregado"),0,4);
		$mes_en=substr($row->fields("entregado"),5,2);
		$dia_en=substr($row->fields("entregado"),8,2);
		$entregado=$dia_en."-".$mes_en."-".$ano_en;
		$pdf->Cell(5,6,'',0,0,'L',1);
		$pdf->Cell(20,6,$row->fields("numero_cheque"),1,0,'L',1);
		$pdf->Cell(70,6,$nombre_proveedor,1,0,'L',1);
		$pdf->Cell(25,6,number_format($row->fields("monto"),2,',','.'),1,0,'R',1);
		$pdf->Cell(30,6,$emitido,1,0,'C',1);
		$pdf->Cell(30,6,$entregado,1,1,'C',1);
		$row->MoveNext();		
	}
	$pdf->Cell(25,6,'',0,0,'L',1);
	$pdf->Cell(70,6,$row2->fields("count")."  CEQUES "."     TOTAL Bs.",1,0,'C',1);
	$pdf->Cell(25,6,number_format($row3->fields("sum"),2,',','.'),1,0,'R',1);
	$pdf->Output();
}
?>