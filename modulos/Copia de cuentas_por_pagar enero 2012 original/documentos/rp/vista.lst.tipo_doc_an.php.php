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
if(isset($_GET[fecha]))
{
	$fecha=$_GET[fecha];
	list($dia,$mes,$ayo)=split("/",$fecha,3);
	$where_fecha="AND documentos_cxp.fecha_documento<='$fecha'";	
}
$proveedor=$_GET[proveedor];
if($proveedor!='')
{
	
	$where="WHERE documentos_cxp.id_proveedor='$proveedor'";	
}

$sql_provee="
				SELECT distinct
						   proveedor.id_proveedor,
						   proveedor.codigo_proveedor,
						   proveedor.nombre,
						    tipo_documento_cxp.nombre as doc
		  		FROM proveedor
						INNER JOIN
							 documentos_cxp
						ON
							proveedor.id_proveedor=documentos_cxp.id_proveedor
						INNER JOIN
							tipo_documento_cxp
						ON
							documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento
					$where			
						order by
							id_proveedor
					;

";
$row=& $conn->Execute($sql_provee);
if(!$row->EOF)
{
	//************************************************************************
				class PDF extends PDF_Code128
				{
					//Cabecera de página
					function Header()
					{	
						global $fecha;
						global $proveedor;
						$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
						$this->SetFont('times','B',7);
						$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
						$this->Ln(4);			
						$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
						$this->Ln(4);			
						$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
						$this->Ln(6);
						$this->SetFont('Arial','B',9);
						$this->Cell(0,10,'ANALISIS DETALLADO POR TIPO DOC'."  "."AL"." ".$fecha ,0,0,'C');
						$this->Ln(12);
						$this->SetFont('Times','B',8);
						$this->SetLineWidth(0.3);
						$this->SetFillColor(175) ;
						$this->SetTextColor(0);
						$this->Cell(25,6,		     'TIPO DOC',			0,0,'L',1);
						$this->Cell(50,6,		     'NÚMERO DE DOCUMENTO',			0,0,'L',1);
						$this->Cell(15,6,			 'FECHA',		0,0,'L',1);
						$this->Cell(15,6,		     'REFERENCIA',	0,0,'L',1);
						$this->Cell(30,6,		     'MONTO ORIGINAL',	0,0,'R',1);
						$this->Cell(30,6,		     'SALDO_DOCUMENTO',	0,0,'R',1);
						$this->Cell(10,6,		     'VCTO',	0,0,'R',1);
						
						$this->Ln(8);
					
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
$id_proveedor=$row->fields("id_proveedor");
if($id_proveedor!='')
{
			//	$pdf->SetFont('arial','B',7);
				$pdf->Cell(25,6,				"TIPO DOC.",					0,0,'L',1);
				$pdf->Cell(50,6,				$row->fields("doc"),					0,0,'L',1);
				$pdf->Ln(6);
				$pdf->Cell(25,6,				"PROVEEDOR",					0,0,'L',1);
				$pdf->Cell(5,6,				$row->fields("codigo_proveedor"),					0,0,'L',1);
				$pdf->Cell(60,6,		      	$row->fields("nombre"),					0,0,'L',1);
				$pdf->Ln(6);
			//	$pdf->SetFont('arial','',7);
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		$sql_cuenta="
						SELECT DISTINCT
									 documentos_cxp.id_documentos,	
									 documentos_cxp.id_organismo,
									 documentos_cxp.id_proveedor,
									 documentos_cxp.ano,
									 documentos_cxp.tipo_documentocxp,
									 documentos_cxp.numero_documento,
									 documentos_cxp.numero_control,
									 documentos_cxp.fecha_vencimiento,
									 documentos_cxp.numero_compromiso,
									 documentos_cxp.comentarios,
									 documentos_cxp.estatus,
									 documentos_cxp.beneficiario,
									 documentos_cxp.cedula_rif_beneficiario,
									 tipo_documento_cxp.nombre as doc,
									 tipo_documento_cxp.siglas,
									 documentos_cxp.amortizacion,
									 aplica_bi_ret_ex1,
									 aplica_bi_ret_ex2,
									 documentos_cxp.fecha_documento,
									 documentos_cxp.n_comprobante_co,
									 documentos_cxp.numero_comprobante,
									 documentos_cxp.monto_base_imponible2,
									 documentos_cxp.porcentaje_iva2,
									 documentos_cxp.retencion_iva2,
									 proveedor.nombre as nombre,
									 proveedor.codigo_proveedor as codigo,
									 documentos_cxp.sustraendo,
 									((documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100))) as total_fact,
									((documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100)))-((documentos_cxp.porcentaje_retencion_iva*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)/100)+(documentos_cxp.retencion_iva2*(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100)/100)+((porcentaje_retencion_islr*documentos_cxp.monto_bruto/100)-documentos_cxp.sustraendo)+(documentos_cxp.retencion_ex1+documentos_cxp.retencion_ex2))as total_retenciones
		
								FROM 
									 documentos_cxp
								INNER JOIN
									 proveedor
								ON
									proveedor.id_proveedor=documentos_cxp.id_proveedor
								INNER JOIN
									organismo
								ON
									documentos_cxp.id_organismo=organismo.id_organismo
								INNER JOIN
									tipo_documento_cxp
								ON
									documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento
								WHERE
									documentos_cxp.id_proveedor='$id_proveedor'
								$where_fecha		
								ORDER BY
									 documentos_cxp.id_documentos	
		";
		$row_doc=& $conn->Execute($sql_cuenta);
		while(!$row_doc->EOF)
		{
		//$pdf->Cell(50,6,				$id_proveedor,					0,0,'L',1);
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		$fecha_documento=substr($row_doc->fields("fecha_documento"),0,10);
		$dia= substr($fecha_documento,8,2);
		$mes=substr($fecha_documento,5,2);
		$ano=substr($fecha_documento,0,4);
		/*fecha de vencmiento*/
		$fecha_vcto=substr($row_doc->fields("fecha_vencimiento"),0,10);
		$dia2= substr($fecha_vcto,8,2);
		$mes2=substr($fecha_vcto,5,2);
		$ano2=substr($fecha_vcto,0,4);
		
		/**/
		$fecha_documento =$dia."-".$mes."-".$ano;
		/***calculando dias vencidos*/
						$valor_fecha1=mktime(0,0,0,$mes,$dia,$ayo);
						
						$valor_fecha2=mktime(0,0,0,$mes2,$dia2,$ayo2);
						$valor_fecha_d=round(($valor_fecha2-$valor_fecha1)/ (60 * 60 * 24));
		/***/		
				
				$pdf->Cell(25,6,				$row_doc->fields("siglas"),					0,0,'L',1);
				$pdf->Cell(50,6,				$row_doc->fields("numero_documento"),					0,0,'L',1);
				$pdf->Cell(15,6,				$fecha_documento,					0,0,'L',1);
				$pdf->Cell(15,6,				substr($row_doc->fields("numero_comprobante"),8),					0,0,'C',1);
				$pdf->Cell(30,6,				number_format($row_doc->fields("total_fact"),2,',','.'),					0,0,'R',1);
				$pdf->Cell(30,6,				number_format($row_doc->fields("total_retenciones"),2,',','.'),					0,0,'R',1);
				$pdf->cell(10,6,                'a',0,0,'R',1);
				
		$pdf->Ln(6);
		$suma=$suma+$row_doc->fields("total_retenciones");
		$row_doc->MoveNext();
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		$pdf->SetFont('arial','B',8);

		$pdf->Cell(160,6,				"TOTAL PROVEEDOR",					0,0,'R',1);
		$pdf->Cell(20,6,				number_format($suma,2,',','.'),					0,0,'R',1);	
}
	$pdf->Ln(6);
	$pdf->SetFont('arial','',7);

	$row->MoveNext();

	
	}
	/*$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Ln(6);
	$pdf->Cell(90,6,"TOTAL",				0,0,'L',1);
	$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(40,6,				$acu_debe,	0,0,'L',1);
	$pdf->Cell(50,6,				$acu_haber,				0,0,'L',1);*/

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
