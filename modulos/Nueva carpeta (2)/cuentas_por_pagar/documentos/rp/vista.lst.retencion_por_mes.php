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
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//$desde=$_GET['desde'];
//$hasta=$_GET['hasta'];
if(isset($_GET['ret_tipo']))
{
	$ret_tipo=$_GET['ret_tipo'];

}

if((isset($_GET['desde']))&&(isset($_GET['hasta'])))
{
	$desde=$_GET['desde'];
	$hasta=$_GET['hasta'];
}
list($dia,$mes,$ayo)=split("/",$hasta,3);
if(($dia=="30")&&($mes=="3"||$mes=='5'||$mes=='7'||$mes=='9'||$mes=='11'))
{
	$dia=01;
	$mes=$mes+1;
 }
 else
if($dia=="31")
{
	$dia=01;
	$mes=$mes+1;
	 if($mes=="12")
	 {
		$mes="10";
		$ayo=$ayo+1;
	  }	
 }
 else
 $dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;
 if(isset($_GET['desde']))
{
	$where=" WHERE
				 documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]."
				AND documentos_cxp.fecha_vencimiento>='$desde' AND documentos_cxp.fecha_vencimiento<='$hasta'
";
}
 if(isset($_GET['proveedor']))
{
	$proveedor=$_GET['proveedor'];
	if($proveedor!="")
	{
		$where.=" AND documentos_cxp.id_proveedor='$proveedor'";
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(($proveedor=="")and ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="VACIO";
}else
if(($proveedor!="")and ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="proveedor";
}
if($ret_tipo!='OTRAS')
{
				$Sql="
							SELECT 
								 documentos_cxp.id_documentos,	
								 documentos_cxp.id_organismo,
								 documentos_cxp.id_proveedor,
								 documentos_cxp.tipo_documentocxp,
								 tipo_documento_cxp.nombre as doc,
								 documentos_cxp.tipo_documentocxp,
								 documentos_cxp.numero_documento,
								 documentos_cxp.numero_control,
								 documentos_cxp.fecha_vencimiento,
								 documentos_cxp.porcentaje_iva,
								 documentos_cxp.porcentaje_retencion_iva,
								 documentos_cxp.porcentaje_retencion_islr,
								 documentos_cxp.monto_bruto,
								 documentos_cxp.monto_base_imponible,
								 documentos_cxp.numero_compromiso,
								 tipo_documento_cxp.nombre as doc,
								 proveedor.codigo_proveedor,
								 proveedor.nombre AS proveedor,
								 documentos_cxp.retencion_ex1,
								 documentos_cxp.retencion_ex2,
								 desc_ex1,
								 desc_ex2,
								 pret1,
								 pret2
							FROM 
								 documentos_cxp
							INNER JOIN
								organismo
							ON
								documentos_cxp.id_organismo=organismo.id_organismo
							INNER JOIN
								tipo_documento_cxp
							ON
								documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento
							INNER JOIN
								usuario
							ON	
								documentos_cxp.ultimo_usuario=usuario.id_usuario			
							INNER JOIN
								proveedor
							ON	
								documentos_cxp.id_proveedor=proveedor.id_proveedor		
							$where
							ORDER BY
								 documentos_cxp.id_documentos
				";
							
				$row=& $conn->Execute($Sql);
				/*if($tipo_de_reporte!="VACIO")
				{
				$id_prove=$row->fields("id_proveedor");
				if(($id_prove=="")||($id_prove==NULL)||($id_prove=='0'))
						{	
							$proveedor=strtoupper($row->fields("beneficiario"));
						}
						else
						{
							$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
							$row_proveedor=& $conn->Execute($sql_proveedor);
							$proveedor=strtoupper($row_proveedor->fields("nombre"));
						}
				}
				*///************************************************************************
				
				if (!$row->EOF)
				{ 
					$codigo_prove=$row->fields("codigo_proveedor");
					$proveedor_enc=strtoupper($row->fields("proveedor"));
					$retencion1=$row->fields("retencion_ex1");
					$retencion2=$row->fields("retencion_ex2");
					require('../../../../utilidades/fpdf153/fpdf.php');
					//************************************************************************
					class PDF extends PDF_Code128
					{
						//Cabecera de página
						function Header()
						{	global $nombre_usuario;	
							global $row;
							global $desde;
							global $hasta;
							global $tipo_de_reporte;
							global $proveedor;
							global $nombre_documento;
							global $a;
							global $codigo_prove;
							global $proveedor_enc;
							global $ret_tipo;
						//	global $ret_tipo;
						///	global $retencion1;
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
							$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
							$this->Ln(10);	
							$this->SetFont('Arial','B',11);
							if($tipo_de_reporte=="VACIO")
							{
				///////////////////////////////////////////////////////////////////////////////////////////////////////////
							/*	if(($ret_tipo=='OTRAS')&&($retencion1!=0))
								{
										$this->Cell(0,10,'RETENCIONES'." ".$ret_tipo,0,0,'C');
										$this->Ln();
										$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
										$this->Ln(10);
										$this->SetFont('Times','B',8);
										$this->SetLineWidth(0.3);
										$this->SetFillColor(0) ;
										$this->SetTextColor(255);
										$this->Cell(30,6,		     'CODIGO',			1,0,'L',1);
										$this->Cell(45,6,		     'PROVEEDOR',			1,0,'L',1);
										$this->Cell(30,6,		     'FECHA RET',			1,0,'L',1);
										$this->Cell(35,6,		     'DESCRIPCION',			1,0,'L',1);
										$this->Cell(30,6,		     'DOCUMENTO',			1,0,'L',1);
										$this->Cell(30,6,		     'BASE.I.',			1,0,'L',1);
										$this->Cell(30,6,		     '% RET IVA',			1,0,'L',1);
										$this->Cell(30,6,		     '% VALOR RETENIDO',			1,0,'L',1);
									
								}
				//////////////////////////////////////////////////////////////////////////////////////////////////////////			
								else
									{*/	$this->Cell(0,10,'RETENCIONES'." ".$ret_tipo,0,0,'C');
										$this->Ln();
										$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
										$this->Ln(10);
										$this->SetFont('Times','B',8);
										$this->SetLineWidth(0.3);
										$this->SetFillColor(175) ;
										$this->SetTextColor(0);
										$this->Cell(20,6,		     'CODIGO',			0,0,'L',1);
										$this->Cell(45,6,		     'PROVEEDOR',			0,0,'L',1);
										$this->Cell(35,6,		     'FECHA RET',			0,0,'L',1);
										$this->Cell(35,6,		     'DOCUMENTO',			0,0,'L',1);
										$this->Cell(35,6,		     'BASE.I.',			0,0,'L',1);
										$this->Cell(35,6,		     '% RETENIDO',			0,0,'L',1);
										$this->Cell(35,6,		     ' VALOR RETENIDO',			0,0,'L',1);
								//	}
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
							}else
							if($tipo_de_reporte=="proveedor")
							{
				//////////////////////////////////////////////////////////////////////////////////////////////////////////			
							$this->Cell(0,10,'RETENCIONES '." ".$ret_tipo,0,0,'C');
							$this->Ln();
							$this->Cell(0,10,'PROVEEDOR: '." ".$proveedor_enc." ".'CÓDIGO:'.$codigo_prove,0,0,'C');
							$this->Ln();
							$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
							$this->Ln(10);
							$this->SetFont('Times','B',8);
							$this->SetLineWidth(0.3);
							$this->SetFillColor(175) ;
							$this->SetTextColor(0);
							$this->Cell(50,6,		     'FECHA RET',			0,0,'L',1);
							$this->Cell(50,6,		     'DOCUMENTO',			0,0,'L',1);
							$this->Cell(50,6,		     'BASE.I.',			0,0,'L',1);
							$this->Cell(50,6,		     '% RETENIDO',			0,0,'L',1);
							$this->Cell(50,6,		     'VALOR RETENIDO',			0,0,'L',1);
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
							}
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
							$this->Cell(135,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
							$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');						
							$this->Ln();
							$this->SetFillColor(0);
							$this->Code128(120,200,strtoupper($_SESSION['usuario']),40,6);		}
						}	
					//************************************************************************
					$pdf=new PDF();
					$pdf->AliasNbPages();
					$pdf->AddFont('barcode');
					$pdf->AddPage('L');
					$pdf->SetFont('arial','',10);
					$pdf->SetFillColor(255);
					//$pdf->SetAutoPageBreak(auto,50);	
					
					while (!$row->EOF) 
					{	
						$nom=$row->fields("nombre");
						$ape=$row->fields("apellido");
						$nombre_usuario=$nom."  ".$ape;
						$fechas=substr($row->fields("fecha_vencimiento"),0,10);
						$dia=substr($row->fields("fecha_vencimiento"),8,2);
						$mes=substr($row->fields("fecha_vencimiento"),5,2);
						$ayo=substr($row->fields("fecha_vencimiento"),0,4);
						
						$fecha=$dia."/".$mes."/".$ayo;
						$tipo=$row->fields("doc"); 
						$tipo=substr($tipo,0,15);
						$base=$row->fields("monto_base_imponible");
						$bruto=$row->fields("monto_bruto");
						$iva=$row->fields("porcentaje_iva");
						$porcentaje_iva_ret=$row->fields("porcentaje_retencion_iva");
						$porcentaje_islr_ret=$row->fields("porcentaje_retencion_islr");
						if((($row->fields("tipo_documentocxp"))==$tipos_fact)&&($row->fields("amortizacion")!='0'))
						{
							$bruto=$row->fields("monto_bruto");
							$amortizacion=$row->fields("amortizacion");
							$base=($bruto+$amortizacion);
							$monto_restar2=($base)*(($porcentaje_islr_ret)/100);
						}else
						$monto_restar2=($bruto)*(($porcentaje_islr_ret)/100);

						
						//$retislr=$islr;
						$base_iva=($base)*($iva/100);
						$monto_restar1=($base_iva)*(($porcentaje_iva_ret)/100);
						if($ret_tipo=='IVA')
						{
						$porc=$porcentaje_iva_ret;
						$monto_retener=$monto_restar1;
						}else
						if($ret_tipo=='ISLR')
						{
						$porc=$porcentaje_islr_ret;
						$monto_retener=$monto_restar2;
						}
				if($tipo_de_reporte=="VACIO")
							{
				//////////////////////////////////////////////////////////////////////////////////////////////////////		
							/*if($ret_tipo=='OTRAS')
								{*/
										/*$pdf->Cell(30,6,$row->fields("codigo_proveedor"),0,0,'L',1);
										$pdf->Cell(45,6,strtoupper($row->fields("proveedor")),0,0,'L',1);
										$pdf->Cell(30,6,$fecha,0,0,'L',1);
										$pdf->Cell(35,6,,0,0,'L',1);
										$pdf->Cell(30,6,$row->fields("numero_documento"),0,0,'L',1);
										$pdf->Cell(30,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
										$pdf->Cell(30,6,number_format($porc,2,',','.'),0,0,'L',1);
										$pdf->Cell(30,6,number_format($monto_retener,2,',','.'),0,0,'L',1);
										*/
										
							/*	}
							else
							{*/
								$pdf->SetFont('Arial','B',10);
								$pdf->Cell(20,6,$row->fields("codigo_proveedor"),0,0,'L',1);
								$pdf->Cell(45,6,strtoupper($row->fields("proveedor")),0,0,'L',1);
								$pdf->Cell(35,6,$fecha,0,0,'L',1);
								$pdf->Cell(35,6,$row->fields("numero_documento"),0,0,'L',1);
								$pdf->Cell(35,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
								$pdf->Cell(35,6,number_format($porc,2,',','.'),0,0,'L',1);
								$pdf->Cell(35,6,number_format($monto_retener,2,',','.'),0,0,'L',1);
							//	}
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
							}
				if($tipo_de_reporte=="proveedor")
							{
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								$pdf->SetFont('Arial','B',10);
								$pdf->Cell(50,6,$fecha,0,0,'L',1);
								$pdf->Cell(50,6,$row->fields("numero_documento"),0,0,'L',1);
								$pdf->Cell(50,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
								$pdf->Cell(50,6,number_format($porc,2,',','.'),0,0,'L',1);
								$pdf->Cell(50,6,number_format($monto_retener,2,',','.'),0,0,'L',1);
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
							}		
											$pdf->Ln();
											$row->MoveNext();
										
						
					}
					//	$pdf->SetFont('Arial','B',12);
					//	$pdf->Cell(265,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
					//	$pdf->Ln();
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
						$pdf->Cell(190,		6,'No se encontraron Datos' ,			0,0,'C',1);
						$pdf->Ln(16);
						$pdf->Cell(190,		6,'',			'B',0,'C',1);
				
					$pdf->Output();
				}
}				
///
else
if($ret_tipo=='OTRAS')
{
	$where.="
			AND
			(	retencion_ex1!=0
			AND
				retencion_ex2!=0
			)	
			OR
			(
				(retencion_ex1=0
				AND
				retencion_ex2!=0
				)
			)
			OR
			(
				(retencion_ex1!=0
				AND
				retencion_ex2=0
				)
			)
					
		
";
			$Sql="
							SELECT 
								 documentos_cxp.id_documentos,	
								 documentos_cxp.id_organismo,
								 documentos_cxp.id_proveedor,
								 documentos_cxp.tipo_documentocxp,
								 tipo_documento_cxp.nombre as doc,
								 documentos_cxp.tipo_documentocxp,
								 documentos_cxp.numero_documento,
								 documentos_cxp.numero_control,
								 documentos_cxp.fecha_vencimiento,
								 documentos_cxp.porcentaje_iva,
								 documentos_cxp.porcentaje_retencion_iva,
								 documentos_cxp.porcentaje_retencion_islr,
								 documentos_cxp.monto_bruto,
								 documentos_cxp.monto_base_imponible,
								 documentos_cxp.numero_compromiso,
								 tipo_documento_cxp.nombre as doc,
								 proveedor.codigo_proveedor,
								 proveedor.nombre AS proveedor,
								 documentos_cxp.retencion_ex1,
								 documentos_cxp.retencion_ex2,
								 desc_ex1,
								 desc_ex2,
								 pret1,
								 pret2
							FROM 
								 documentos_cxp
							INNER JOIN
								organismo
							ON
								documentos_cxp.id_organismo=organismo.id_organismo
							INNER JOIN
								tipo_documento_cxp
							ON
								documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento
							INNER JOIN
								usuario
							ON	
								documentos_cxp.ultimo_usuario=usuario.id_usuario			
							INNER JOIN
								proveedor
							ON	
								documentos_cxp.id_proveedor=proveedor.id_proveedor		
							$where
							ORDER BY
								 documentos_cxp.id_documentos
				";
							
				$row=& $conn->Execute($Sql);
				/*if($tipo_de_reporte!="VACIO")
				{
				$id_prove=$row->fields("id_proveedor");
				if(($id_prove=="")||($id_prove==NULL)||($id_prove=='0'))
						{	
							$proveedor=strtoupper($row->fields("beneficiario"));
						}
						else
						{
							$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
							$row_proveedor=& $conn->Execute($sql_proveedor);
							$proveedor=strtoupper($row_proveedor->fields("nombre"));
						}
				}
				*///************************************************************************
				
				if (!$row->EOF)
				{ 
					$codigo_prove=$row->fields("codigo_proveedor");
					$proveedor_enc=strtoupper($row->fields("proveedor"));
					$retencion1=$row->fields("retencion_ex1");
					$retencion2=$row->fields("retencion_ex2");
					require('../../../../utilidades/fpdf153/fpdf.php');
					//************************************************************************
					class PDF extends PDF_Code128
					{
						//Cabecera de página
						function Header()
						{	global $nombre_usuario;	
							global $row;
							global $desde;
							global $hasta;
							global $tipo_de_reporte;
							global $proveedor;
							global $nombre_documento;
							global $a;
							global $codigo_prove;
							global $proveedor_enc;
							global $ret_tipo;
						//	global $ret_tipo;
						///	global $retencion1;
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
							$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
							$this->Ln(10);	
							$this->SetFont('Arial','B',11);
							if($tipo_de_reporte=="VACIO")
							{
				///////////////////////////////////////////////////////////////////////////////////////////////////////////
							
										$this->Cell(0,10,'RETENCIONES'." ".$ret_tipo,0,0,'C');
										$this->Ln();
										$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
										$this->Ln(10);
										$this->SetFont('Times','B',8);
										$this->SetLineWidth(0.3);
										$this->SetFillColor(0) ;
										$this->SetTextColor(255);
										$this->Cell(30,6,		     'CODIGO',			1,0,'L',1);
										$this->Cell(45,6,		     'PROVEEDOR',			1,0,'L',1);
										$this->Cell(30,6,		     'FECHA RET',			1,0,'L',1);
										$this->Cell(35,6,		     'DESCRIPCION',			1,0,'L',1);
										$this->Cell(30,6,		     'DOCUMENTO',			1,0,'L',1);
										$this->Cell(30,6,		     'BASE.I.',			1,0,'L',1);
										$this->Cell(30,6,		     '% RETENIDO',			1,0,'L',1);
										$this->Cell(30,6,		     '% VALOR RETENIDO',			1,0,'L',1);
									
								
							
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
							}else
							if($tipo_de_reporte=="proveedor")
							{
				//////////////////////////////////////////////////////////////////////////////////////////////////////////			
							$this->Cell(0,10,'RETENCIONES '." ".$ret_tipo,0,0,'C');
							$this->Ln();
							$this->Cell(0,10,'PROVEEDOR: '." ".$proveedor_enc." ".'CÓDIGO:'.$codigo_prove,0,0,'C');
							$this->Ln();
							$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
							$this->Ln(10);
							$this->SetFont('Times','B',8);
							$this->SetLineWidth(0.3);
							$this->SetFillColor(0) ;
							$this->SetTextColor(255);
							$this->Cell(40,6,		     'FECHA RET',			1,0,'L',1);
							$this->Cell(45,6,		     'DESCRIPCION',			1,0,'L',1);
							$this->Cell(40,6,		     'DOCUMENTO',			1,0,'L',1);
							$this->Cell(40,6,		     'BASE.I.',			1,0,'L',1);
							$this->Cell(40,6,		     '% RETENIDO',			1,0,'L',1);
							$this->Cell(40,6,		     'VALOR RETENIDO',			1,0,'L',1);
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
							}
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
							$this->Cell(135,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
							$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');						
							$this->Ln();
							$this->SetFillColor(0);
							$this->Code128(120,200,strtoupper($_SESSION['usuario']),40,6);		}
						}	
					//************************************************************************
					$pdf=new PDF();
					$pdf->AliasNbPages();
					$pdf->AddFont('barcode');
					$pdf->AddPage('L');
					$pdf->SetFont('arial','',10);
					$pdf->SetFillColor(255);
					//$pdf->SetAutoPageBreak(auto,50);	
					
					while (!$row->EOF) 
					{	
						$nom=$row->fields("nombre");
						$ape=$row->fields("apellido");
						$nombre_usuario=$nom."  ".$ape;
						$fechas=substr($row->fields("fecha_vencimiento"),0,10);
						$dia=substr($row->fields("fecha_vencimiento"),8,2);
						$mes=substr($row->fields("fecha_vencimiento"),5,2);
						$ayo=substr($row->fields("fecha_vencimiento"),0,4);
						$fecha=$dia."/".$mes."/".$ayo;
						$tipo=$row->fields("doc"); 
						$tipo=substr($tipo,0,15);
					//--- si es factura con anticipo
						$base=$row->fields("monto_base_imponible");
						$bruto=$row->fields("monto_bruto");
						if((($row->fields("tipo_documentocxp"))==$tipos_fact)&&($row->fields("amortizacion")!='0'))
						{
							$bruto=$row->fields("monto_bruto");
							$amortizacion=$row->fields("amortizacion");
							$base=($bruto+$amortizacion);
						}
						//-
							$ret1=$row->fields("retencion_ex1");
							$porcentaje_ret1=$row->fields("pret1");
							$ret2=$row->fields("retencion_ex2");
							$porcentaje_ret2=$row->fields("pret2");
		if($tipo_de_reporte=="VACIO")
							{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
										$pdf->Cell(30,6,$row->fields("codigo_proveedor"),0,0,'L',1);
										$pdf->Cell(45,6,strtoupper($row->fields("proveedor")),0,0,'L',1);
										$pdf->Cell(30,6,$fecha,0,0,'L',1);
										$pdf->Cell(35,6,$row->fields("desc_ex1"),0,0,'L',1);
										$pdf->Cell(30,6,$row->fields("numero_documento"),0,0,'L',1);
										$pdf->Cell(30,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
										$pdf->Cell(30,6,number_format($porcentaje_ret1,2,',','.'),0,0,'L',1);
										$pdf->Cell(30,6,number_format($ret1,2,',','.'),0,0,'L',1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
										if($row->fields("retencion_ex2")!=0)
										{
												$pdf->Ln();
												$pdf->Cell(30,6,$row->fields("codigo_proveedor"),0,0,'L',1);
												$pdf->Cell(45,6,strtoupper($row->fields("proveedor")),0,0,'L',1);
												$pdf->Cell(30,6,$fecha,0,0,'L',1);
												$pdf->Cell(35,6,$row->fields("desc_ex2"),0,0,'L',1);
												$pdf->Cell(30,6,$row->fields("numero_documento"),0,0,'L',1);
												$pdf->Cell(30,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
												$pdf->Cell(30,6,number_format($porcentaje_ret2,2,',','.'),0,0,'L',1);
												$pdf->Cell(30,6,number_format($ret2,2,',','.'),0,0,'L',1);
										}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
							}
				if($tipo_de_reporte=="proveedor")
							{
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										$pdf->Cell(40,6,$fecha,0,0,'L',1);
										$pdf->Cell(45,6,$row->fields("desc_ex1"),0,0,'L',1);
										$pdf->Cell(40,6,$row->fields("numero_documento"),0,0,'L',1);
										$pdf->Cell(40,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
										$pdf->Cell(40,6,number_format($porcentaje_ret1,2,',','.'),0,0,'L',1);
										$pdf->Cell(40,6,number_format($ret1,2,',','.'),0,0,'L',1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
										if($row->fields("retencion_ex2")!=0)
										{
												$pdf->Ln();
												$pdf->Cell(40,6,$fecha,0,0,'L',1);
												$pdf->Cell(45,6,$row->fields("desc_ex2"),0,0,'L',1);
												$pdf->Cell(40,6,$row->fields("numero_documento"),0,0,'L',1);
												$pdf->Cell(40,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
												$pdf->Cell(40,6,number_format($porcentaje_ret2,2,',','.'),0,0,'L',1);
												$pdf->Cell(40,6,number_format($ret2,2,',','.'),0,0,'L',1);
										}
								
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
							}		
											$pdf->Ln();
											$row->MoveNext();
										
						
					}
					//	$pdf->SetFont('Arial','B',12);
					//	$pdf->Cell(265,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
					//	$pdf->Ln();
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
						$pdf->Cell(190,		6,'No se encontraron Datos' ,			0,0,'C',1);
						$pdf->Ln(16);
						$pdf->Cell(190,		6,'',			'B',0,'C',1);
				
					$pdf->Output();
				}
}				

?>