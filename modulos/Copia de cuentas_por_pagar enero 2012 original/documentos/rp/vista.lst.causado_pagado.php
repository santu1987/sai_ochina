<?php
session_start();
ini_set("memory_limit","20M");
$causados=0;
$pagados=0;
$total_causados_partida=0;
$total_pagados_partida=0;

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


$where="WHERE documentos_cxp.estatus!='3' and documentos_cxp.id_organismo=".$_SESSION["id_organismo"]."";
	if(isset($_GET["partida"]))
	{
		$partida=$_GET["partida"];
		if($partida!="")
		{
			$where.="AND doc_cxp_detalle.partida like'%$partida%' ";
			
		}
	}
	if(isset($_GET["desde_fecha"]))
	{
		$desde_fecha=$_GET["desde_fecha"];
		$where.=" and documentos_cxp.fecha_ultima_modificacion >= '$desde_fecha'";
	}
	if(isset($_GET["hasta_fecha"]))
		{	
			$hasta_fecha=$_GET["hasta_fecha"];
			$where.="AND documentos_cxp.fecha_ultima_modificacion <='$hasta_fecha'";
		}
/*if($desde_fecha!="")
	{	
		$where.=" and documentos_cxp.fecha_ultima_modificacion >= '$desde_fecha' ";
	}
	if($hasta_fecha!="")
		{	
			$where.="AND documentos_cxp.fecha_ultima_modificacion <='$hasta_fecha'";
		}
*/



/////////////////////////////////////////////////////////////////
list($dia,$mes,$ayo)=split("/",$hasta_fecha,3);
if(($dia=="30")&&($mes=="3"||$mes=='5'||$mes=='7'||$mes=='9'||$mes=='11'))
{
	$dia=1;
	$mes=$mes+1;
 }
 else
if($dia=="31")
{
	$dia=1;
	$mes=$mes+1;
	 if($mes=="12")
	 {
		$mes="1";
		$ayo=$ayo+1;
	  }	
 }
 else
 $dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$sql_doc_detalle="
									SELECT 
									
										doc_cxp_detalle.id_doc,
										doc_cxp_detalle.partida::integer,
										doc_cxp_detalle.compromiso,
										documentos_cxp.numero_documento,
										(doc_cxp_detalle.monto)+((doc_cxp_detalle.impuesto*doc_cxp_detalle.monto)/100) as monto_causado,
										doc_cxp_detalle.id_organismo,
										doc_cxp_detalle.impuesto,
										documentos_cxp.orden_pago
									FROM
									doc_cxp_detalle
									INNER JOIN
										documentos_cxp
									ON
										documentos_cxp.id_documentos=doc_cxp_detalle.id_doc
									$where	
									order by
										doc_cxp_detalle.partida::integer,
										doc_cxp_detalle.id_doc
									";
$row=& $conn->Execute($sql_doc_detalle);
if(!$row->EOF)
{



	

//************************************************************************
			class PDF extends PDF_Code128
			{
				//Cabecera de página
				function Header()
				{	global $numero_comprobante;
					global $fecha_comprobante;global $tipo_comprobante;	global $codigo_tipo_comprobante;
					global $desde_fecha;global $hasta_fecha;	
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
				$this->SetFont('times','B',8);
					$this->Cell(0,10,'RELACIÓN DE DOCUMENTOS CAUSADOS/PAGADOS POR PARTIDA',0,0,'C');
					$this->ln(4);
					$this->Cell(0,10,'Desde:'." ".$desde_fecha."   "."Hasta:"." ".$hasta_fecha,0,0,'C');
					
					$this->Ln(10);
					$this->SetFont('Arial','B',7);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(175) ;
					$this->SetTextColor(0);
					$this->Cell(20,6,		     'CUENTA',			0,0,'L',1);
					$this->Cell(25,6,			 'N COMPROMISO',		0,0,'L',1);
					$this->Cell(30,6,			 'N DOCUMENTO',		0,0,'C',1);
					$this->Cell(45,6,			 'MONTO CAUSADO',		0,0,'C',1);
					$this->Cell(25,6,			 'N CHEQUE',		0,0,'C',1);
					$this->Cell(40,6,		     'MONTO PAGADO',	0,0,'C',1);
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
						$pdf->ln(4);
				while(!$row->EOF)
				{
						
						
						/////verificar si tiene cheque y cuanto ha sido pagado
							$orden_pago=$row->fields("orden_pago");
							$sql_cheques="SELECT 
												numero_cheque 
											from
												cheques
											INNER JOIN
												orden_cheque
											ON
												orden_cheque.id_cheque=cheques.id_cheques
											where
												orden_cheque.id_orden='$orden_pago'		
												";
							$row_cheque=& $conn->Execute($sql_cheques);
							$cesc=0;
							while(!$row_cheque->EOF)
							{
								$cheque=$row_cheque->fields("numero_cheque");
								$cesc=$cesc+1;
								if($cesc==1)
									$cheques=$cheque;
								else
									$cheques=$cheques.",".$cheque;	
								$row_cheque->MoveNext();

							}				
						/////
						$id_doc=$row->fields("id_doc");
						//$monto= $row->fields("monto_causado");
						$monto=0;
						$pdf->Cell(20,6,		     $row->fields("partida"),			0,0,'L',1);
						$pdf->Cell(20,6,			 $row->fields("compromiso"),		0,0,'L',1);
						$pdf->Cell(30,6,			 $row->fields("numero_documento"),		0,0,'C',1);
						$pdf->Cell(45,6,			 number_format($row->fields("monto_causado"),2,',','.'),		0,0,'R',1);
						$pdf->Cell(30,6,			 $cheque,		0,0,'C',1);
						$pdf->Cell(40,6,		     number_format($monto,2,',','.'),	0,0,'R',1);
						$pdf->Ln(6);
						$generica_ant=substr($row->fields("partida"),0,3);
						$partida_ant= $row->fields("partida");
						$cheque="";
						///
							$causados=$causados+$row->fields("monto_causado");
							$pagados=$causados;
							$total_causados_partida=$total_causados_partida+$row->fields("monto_causado");
							$total_pagados_partida=$total_causados_partida;
						
						///
						$row->MoveNext();
						$generica2_sig=substr($row->fields("partida"),0,3);
						$partida_sig=$row->fields("partida");
						$len=strlen($partida);
						
						if($partida_ant!=$partida_sig)
						{
							$pdf->SetFont('times','B',8);
							$pdf->Cell(50,6,"TOTAL "."  ".$partida_ant.":",'LTB',0,'C',0);
							$pdf->Cell(65,6,number_format($causados,2,',','.'),'TB',0,'R',0);
							$pdf->cell(70,6,number_format($pagados,2,',','.'),'TBR',0,'R',0);
							$pdf->Ln(10);
							$pdf->SetFont('times','',6);
							$causados=0;
							$pagados=0;
						}
						if(($generica_ant!=$generica2_sig)&&($len!=9))
						{
							$pdf->SetFont('times','B',8);
							$pdf->Cell(50,6,"TOTAL "."  ".$generica_ant.":",'LTB',0,'C',0);
							$pdf->Cell(65,6,number_format($total_causados_partida,2,',','.'),'TB',0,'R',0);
							$pdf->cell(70,6,number_format($total_pagados_partida,2,',','.'),'TBR',0,'R',0);
							$pdf->Ln(10);
						
							$pdf->SetFont('times','',6);

							
						}
							//$id_utf=$row->fields("id_utilizacion_fondos");

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