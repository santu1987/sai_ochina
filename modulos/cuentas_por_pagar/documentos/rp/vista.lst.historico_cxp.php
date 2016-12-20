<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//asignando valores metodo get
$where="WHERE 1=1";
//si pasa el filtr tipo
if ( isset($_GET['tipo']))
{
	$tipo=$_GET['tipo'];
	if(($tipo!='')and($tipo!='0'))
	{
		$where.="AND tipo_documentocxp='$tipo'";
	}

}
if ( isset($_GET['proveedor']))
{
	$proveedor=$_GET['proveedor'];
	if($proveedor!='')
	{
		$where.="AND proveedor.id_proveedor='$proveedor'";
	}

}
if ( isset($_GET['desde']))
{
	$desde=$_GET['desde'];
	if($desde!='')
	{
		$where.="AND fecha_documento >='$desde'";
	}

}
if ( isset($_GET['hasta']))
{
	$hasta=$_GET['hasta'];
	if($hasta!='')
	{
		$where.="AND fecha_documento <='$hasta'";
	}

}
//
$sql="
		select 
			proveedor.nombre,
			proveedor.codigo_proveedor,			
			numero_documento,
			tipo_documento_cxp.siglas,
			movimientos_contables.referencia,
			movimientos_contables.id_movimientos_contables,
			movimientos_contables.fecha_comprobante AS fecha_proceso,
			documentos_cxp.fecha_documento AS fecha_emision,
			documentos_cxp.fecha_vencimiento,
			movimientos_contables.monto_debito,
			movimientos_contables.monto_credito,
			(documentos_cxp.retencion_ex1+documentos_cxp.retencion_ex2+documentos_cxp.porcentaje_retencion_islr+documentos_cxp.porcentaje_retencion_iva+documentos_cxp.retencion_iva2) AS aplica,
			documentos_cxp.numero_compromiso
			
		from
			documentos_cxp
		INNER JOIN
			tipo_documento_cxp
		ON
			tipo_documento_cxp.id_tipo_documento=documentos_cxp.tipo_documentocxp	
		INNER JOIN
			movimientos_contables
		ON
			movimientos_contables.numero_comprobante=documentos_cxp.numero_comprobante
		INNER JOIN
			proveedor
		ON
			proveedor.id_proveedor=documentos_cxp.id_proveedor	
		$where				
		ORDER BY
			movimientos_contables.numero_comprobante
";
$row=& $conn->Execute($sql);
if(!$row->EOF)
{
		$proveedor=$row->fields("codigo_proveedor")."   ".strtoupper($row->fields("nombre"));
		$fechas="DESDE "." ".$desde."   "."AL"." ".$hasta;
		//************************************************************************
			class PDF extends PDF_Code128
			{
				//Cabecera de página
				function Header()
				{global $proveedor;
					global $fechas;global $tipo_comprobante;	global $codigo_tipo_comprobante;	
					
					$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
					$this->SetFont('times','B',7);
					$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
					$this->Ln(4);
					$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
					$this->Ln(4);
					$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
					$this->Ln(4);			
					$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
					$this->Ln(4);			
					$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
					$this->Ln(6);
					$this->SetFont('times','B',8);
					$this->Cell(0,10,'ESTADO DE CUENTA HISTÓRICO'." ".$fechas,0,0,'C');
					$this->Ln(6);
					$this->Cell(0,10,'Proveedor:'." ".$proveedor,0,0,'L');
					$this->Ln(10);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(100) ;
					$this->SetTextColor(0);
					$this->SetFont('Times','B',6);
					$this->Cell(20,6,		     'NÚMERO DE',			1,0,'L',1);
					$this->Cell(10,6,			 'TIP',		1,0,'L',1);
					$this->Cell(10,6,			 '',		1,0,'L',1);
					$this->Cell(10,6,			 '',		1,0,'L',1);
					$this->Cell(20,6,		     'FECHA ',	1,0,'C',1);
					$this->Cell(20,6,		     'FECHA ',	1,0,'C',1);
					$this->Cell(23,6,		     'FECHA ',	1,0,'C',1);
					$this->Cell(20,6,		     '',	1,0,'C',1);
					$this->Cell(20,6,		     '',	1,0,'C',1);
					$this->Cell(20,6,		     '',	1,0,'C',1);
					$this->Cell(20,6,		     '',	1,0,'C',1);
					$this->Ln();
					$this->Cell(20,6,		     'DOCUMENTO','LBR',0,'L',1);
					$this->Cell(10,6,			 'DOC',		'LBR',0,'L',1);
					$this->Cell(10,6,			 'REF',		'LBR',0,'L',1);
					$this->Cell(10,6,			 'LOTE',		'LBR',0,'L',1);
					$this->Cell(20,6,		     'PROCESO ',	'LBR',0,'C',1);
					$this->Cell(20,6,		     'EMISION',	'LBR',0,'C',1);
					$this->Cell(23,6,		     'VENCIMIENTO',	'LBR',0,'C',1);
					$this->Cell(20,6,		     'DEBE',	'LBR',0,'C',1);
					$this->Cell(20,6,		     'HABER',	'LBR',0,'C',1);
					$this->Cell(20,6,		     'APLICA',	'LBR',0,'C',1);
					$this->Cell(20,6,		     'COMP.',	'LBR',0,'C',1);
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
$pdf->SetFont('arial','',6);
$pdf->SetFillColor(255);
 while(!$row->EOF)
 {
 // 	VERIFICANDO SI SE APLICAN RETENCIONES EN EL DOCUMENTO **/
	if($row->fields("aplica")!='0')
	
		$aplica='RETENCIÓN';
//		VERIFICANDO S HA MONTO EN EL DEBE		
	if( $row->fields("monto_debito")!='0')
		$monto_debito=number_format( $row->fields("monto_debito"),2,',','.');
	else
		$monto_debito='';
//		VERIFICANDO S HA MONTO EN EL HABER		
	if( $row->fields("monto_credito")!='0')
		$monto_credito=number_format( $row->fields("monto_credito"),2,',','.');
	else
		$monto_credito='';
//totalizando debe		
$acum_debe=$acum_debe+$row->fields("monto_debito");
//totalizando haber
$acum_haber=$acum_haber+$row->fields("monto_credito");	
//sacando el saldo
$resta=$acum_debe-$acum_haber;
// aplicando formatos a las fechas
//fecha proceso:
$fecha_proceso = substr($row->fields("fecha_proceso"),0,10);
$fecha_proceso= substr($fecha_proceso,8,2)."/".substr($fecha_proceso,5,2)."/".substr($fecha_proceso,0,4);
//fecha emision:
$fecha_emision = substr($row->fields("fecha_emision"),0,10);
$fecha_emision= substr($fecha_emision,8,2)."/".substr($fecha_emision,5,2)."/".substr($fecha_emision,0,4);
//fecha vencimiento:
$fecha_vencimiento = substr($row->fields("fecha_vencimiento"),0,10);
$fecha_vencimiento= substr($fecha_vencimiento,8,2)."/".substr($fecha_vencimiento,5,2)."/".substr($fecha_vencimiento,0,4);
		

				 	$pdf->Cell(20,6,		    $row->fields("numero_documento"),'LBR',0,'L',1);
					$pdf->Cell(10,6,			$row->fields("siglas"),		'LBR',0,'L',1);
					$pdf->Cell(10,6,			$row->fields("referencia"),		'LBR',0,'L',1);
					$pdf->Cell(10,6,			$row->fields("id_movimientos_contables"),		'LBR',0,'L',1);
					$pdf->Cell(20,6,		    $fecha_proceso,	'LBR',0,'C',1);
					$pdf->Cell(20,6,		    $fecha_emision,	'LBR',0,'C',1);
					$pdf->Cell(23,6,		    $fecha_vencimiento,	'LBR',0,'C',1);
					$pdf->Cell(20,6,		    $monto_debito,	'LBR',0,'C',1);
					$pdf->Cell(20,6,		    $monto_credito,	'LBR',0,'C',1);
					$pdf->Cell(20,6,		    $aplica,	'LBR',0,'C',1);
					$pdf->Cell(20,6,		    $row->fields("numero_compromiso"),	'LBR',0,'C',1);
					
 $pdf->Ln(); 
 $aplica='';
 $row->MoveNext();
 
 }						
//si es fin de archivo: imprimo totales...	
					$pdf->Cell(20,6,		    '',0,0,'L',0);
					$pdf->Cell(10,6,			'',		0,0,'L',0);
					$pdf->Cell(10,6,			'',		0,0,'L',0);
					$pdf->Cell(10,6,			'',		0,0,'L',0);
					$pdf->Cell(20,6,		    '',	0,0,'C',0);
					$pdf->Cell(20,6,		    '',	0,0,'C',0);
					$pdf->Cell(23,6,		    '',	0,0,'C',0);
					$pdf->Cell(20,6,		    number_format($acum_debe,2,',','.'),	1,0,'C',1);
					$pdf->Cell(20,6,		    number_format($acum_haber,2,',','.'),	1,0,'C',1);
					$pdf->Cell(20,6,		    '',	0,0,'C',0);
					$pdf->Cell(20,6,		   '',	0,0,'C',0);
//			
    $pdf->Ln(10);
	$pdf->Cell(20,6,		    'SALDO AL:',0,0,'L',0); 
	$pdf->Cell(20,6,		     $hasta,0,0,'L',0); 
	$pdf->Cell(40,6,			 number_format($resta,2,',','.'),		0,0,'L',0);
	$pdf->Ln(6);
	$pdf->Ln(15);
	$pdf->SetFont('arial','B',8);

						$pdf->Cell(140,6,"______________________________________________",0,0,'R',1);
						$pdf->Ln();
						$pdf->Cell(200,6,"POR SERVICIO AUTONOMO OCHINA ",0,0,'C',1);
					    $pdf->Ln();
						$pdf->Cell(200,6,"DIRECTOR DE OCHINA",0,0,'C',1);
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