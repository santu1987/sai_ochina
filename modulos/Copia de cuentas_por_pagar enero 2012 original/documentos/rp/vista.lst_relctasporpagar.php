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
//************************************************************************
		$sql4=" SELECT	 nombre,apellido,comentario from usuario where id_usuario=".$_SESSION['id_usuario']."";			
		$row_preparado=& $conn->Execute($sql4);
		$nom_preparado=$row_preparado->fields("nombre");
		$ape_preparado=$row_preparado->fields("apellido");
		$nombre_preparado=strtoupper($nom_preparado."  ". $ape_preparado);
		$comentario=strtoupper($row_preparado->fields("comentario"));
//************************************************************************
$where="WHERE 1=1";
$conter=0;
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
if ( isset($_GET['comentarios']))
{
	$comentarios=$_GET['comentarios'];


}
//QUERY QUE CONSULTA A TODOS LOS DOCUMENTOS
$sql_factura="SELECT 
doc_cxp_detalle.partida,																							
documentos_cxp.numero_compromiso,																								documentos_cxp.numero_documento,
																								documentos_cxp.fecha_documento,
																								id_documentos,
																								porcentaje_iva,
																								porcentaje_retencion_iva, 
																								monto_bruto,
																								monto_base_imponible,
																								tipo_documentocxp,
																								amortizacion,
																								retencion_ex1,
																								retencion_ex2,
																								pret1,
																								pret2,
																								aplica_bi_ret_ex2,
																								aplica_bi_ret_ex1,
																								porcentaje_retencion_islr,
																								porcentaje_retencion_iva,
											  ((documentos_cxp.monto_bruto+
											  (documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+
											  (documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100)))-
										 ((documentos_cxp.retencion_iva2*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible2/100)/100)+
										  (documentos_cxp.porcentaje_retencion_iva*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)/100)+
										  (porcentaje_retencion_islr*documentos_cxp.monto_bruto/100)+
										  (documentos_cxp.retencion_ex1+documentos_cxp.retencion_ex2))as total_retenciones,
										  												proveedor.nombre
																						FROM
																								documentos_cxp
																						INNER JOIN
																								proveedor
																						ON
																								proveedor.id_proveedor=documentos_cxp.id_proveedor	
																						INNER JOIN
																								doc_cxp_detalle
																						ON
																								doc_cxp_detalle.id_doc=documentos_cxp.id_documentos	
																								$where					
																								";
$row=& $conn->Execute($sql_factura);
																								
//************************************************************************
if (!$row->EOF)
{ 
	
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $hasta;	
		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
				$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	$this->Ln();	
			$this->Ln(6);	
		
			$this->SetFont('Times','B',11);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(0);
			$this->Cell(280,6,'RELACIÓN DE CUENTAS POR PAGAR AL  '.$hasta,			0,0,'C',0);
			$this->Ln(10);
			
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
			$this->Cell(125,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(120,200,strtoupper($_SESSION['usuario']),40,6);		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFillColor(255);
	$suma=0;
	
	//$pdf->SetAutoPageBreak(auto,50);	
 while(!$row->EOF)
 {
    if($conter==22)
	{
		$conter=0;
	}
	if($conter==0)
	{
			$pdf->SetFillColor(100) ;
			$pdf->SetTextColor(0);
			$pdf->SetFont('Times','B',8);
			$pdf->Cell(20,6,		     '',			0,0,'L',0);
			$pdf->Cell(90,6,		     'NOMBRE DEL PROVEEDOR',			1,0,'C',1);
			$pdf->Cell(40,6,		     'NRO-DOCUMENTO',			1,0,'C',1);
			$pdf->Cell(30,6,		     'PARTIDA',			1,0,'C',1);
			$pdf->Cell(30,6,		     'COMPROMISO',			1,0,'C',1);
			$pdf->Cell(30,6,		     'FECHA EMISION',			1,0,'C',1);
			$pdf->Cell(30,6,		     'MONTO',			1,0,'C',1);
			$pdf->Ln(6);
	
	}
	$fecha_emitido=substr($row->fields("fecha_documento"),0,10);
	$fecha_emitido = substr($fecha_emitido,8,2)."".substr($fecha_emitido,4,4)."".substr($fecha_emitido,0,4);

 			$pdf->SetFont('Times','B',8);
			$pdf->Cell(20,6,		     "",0,0,'L',0);
			$pdf->Cell(90,6,		    strtoupper($row->fields("nombre")),1,0,'L',0);
			$pdf->Cell(40,6,		    $row->fields("numero_documento"),			1,0,'C',0);
			$pdf->Cell(30,6,		    $row->fields("partida"),			1,0,'C',0);
			$pdf->Cell(30,6,		    $row->fields("numero_compromiso"),			1,0,'C',0);
			$pdf->Cell(30,6,		    $fecha_emitido,		1,0,'C',0);
			$pdf->Cell(30,6,		    number_format($row->fields("total_retenciones"),2,',','.'),			1,0,'R',0);
			$pdf->Ln(6);
			$suma=$suma+$row->fields("total_retenciones");
	$row->MoveNext();
	$conter++;
 }
			$pdf->SetFillColor(100) ;
			$pdf->SetTextColor(0);
			$pdf->SetFont('Times','B',8);
			$pdf->Cell(20,6,		     "",0,0,'L',0);
			$pdf->Cell(90,6,		     "TOTAL.................",1,0,'L',1);
			$pdf->Cell(40,6,		    "",			1,0,'C',1);
			$pdf->Cell(30,6,		    "",			1,0,'C',1);
			$pdf->Cell(30,6,		    "",			1,0,'C',1);
			$pdf->Cell(30,6,		    "",			1,0,'C',1);
			$pdf->Cell(30,6,		    number_format($suma,2,',','.'),			1,0,'R',1);
			$pdf->Ln(6);
			$conter++;
$pdf->Ln(20);
	$pdf->SetFillColor(255);
	$pdf->SetFont('Arial','B',10);
	
	$pdf->Cell(20,6,		     "",0,0,'L',0);
	$pdf->Cell(100,6,$comentarios,0,0,'L',1);
	$pdf->Ln(15);
	$pdf->Cell(30,6,"Elaborado por:",0,0,'L',1);
	$pdf->Ln(15);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(30,6,$nombre_preparado,0,0,'L',1);
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30,6,$comentario,0,0,'L',1);
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
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
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
		$pdf->Cell(190,		6,'No se encontraron Datos'.$where ,			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);
	$pdf->Output();
}

?>