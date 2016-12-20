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
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");

$orden=$_GET['ordenes'];
$vector = split(",",$orden);
$tera=0;
				$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
				while($i < $contador)
				{///////////consultando las orden de pago
					$sql_orden="SELECT  orden_pago,documentos
														FROM
															orden_pago
														WHERE(orden_pago.id_orden_pago='$vector[$i]')";		
					$row_orden=$conn->Execute($sql_orden); 
					$documentos=$row_orden->fields("documentos");
					$doc1=str_replace("{","",$documentos);
					$doc2=str_replace("}","",$doc1);
					$facturas= split(",",$doc2);
					$contador_fact=count($facturas);
					$i_fact=0;
					while($i_fact < $contador_fact)
					{//////////consultando las facturas			
											$sql_facturas="
																SELECT 
																		numero_compromiso
																FROM
																		documentos_cxp
																where
																		id_documentos='$facturas[$i_fact]'";
											$row_documentos=& $conn->Execute($sql_facturas);
											$numero_compromiso=$row_documentos->fields("numero_compromiso");
											if(($numero_compromiso!="")&&($numero_compromiso!="0"))
											$tera=$tera+1;
					$i_fact ++;
					}				
			$i++;
			}			
///************************************************************************

if ($tera!=0)
{ 
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
			$this->Cell(0,10,'RESUMEN PROYECTO SEGUN CHEQUES ',0,0,'C');
			$this->Ln(10);
			//$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
//			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(30,6,		     'PARTIDA',			1,0,'L',1);
			$this->Cell(40,6,		     'CODIGO PROYECTO',			1,0,'L',1);
			$this->Cell(70,6,		     'PROYECTO',			1,0,'L',1);
			$this->Cell(40,6,		     'MONTO',			1,0,'C',1);
		//	$this->Cell(60,6,		    	 'TOTAL',			1,0,'L',1);
			$this->Ln();
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

//////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
				$vector = split( ",", $orden );
				$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
				while($i < $contador)
				{///////////consultando las orden de pago
					$sql_orden="SELECT  orden_pago,documentos
														FROM
															orden_pago
														WHERE(orden_pago.id_orden_pago='$vector[$i]')";		
					$row_orden=$conn->Execute($sql_orden); 
					$documentos=$row_orden->fields("documentos");
					$doc1=str_replace("{","",$documentos);
					$doc2=str_replace("}","",$doc1);
					$facturas= split(",",$doc2);
					$contador_fact=count($facturas);
					$i_fact=0;
					$x = $pdf->GetX();
					$y = $pdf->GetY();
					while($i_fact < $contador_fact)
					{//////////consultando las facturas			
											$sql_facturas="
																SELECT 
																		numero_compromiso,monto_bruto,monto_base_imponible,tipo_documentocxp
																FROM
																		documentos_cxp
																where
																		id_documentos='$facturas[$i_fact]'";
																		
																					
											$row_documentos=& $conn->Execute($sql_facturas);
											$numero_compromiso=$row_documentos->fields("numero_compromiso");
											$p_iva_factura=$row_documentos->fields("monto_base_imponible")*$row_documentos->fields("porcentaje_iva")/100;
											$monto_factura=$row_documentos->fields("monto_bruto");
											$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
											if(($row_documentos->fields("tipo_documentocxp")==$tipos_ant))
											{
												//$monto_factura=0;
												$q=0;
											}
											if((($row_documentos->fields("tipo_documentocxp"))==$tipos_fact)&&($row_documentos->fields("amortizacion")!='0,00'))
											{
												$monto_factura="";	
												$monto_ante=($row_documentos->fields("monto_bruto")+$row_documentos->fields("amortizacion"));
												$p_iva_factura=$monto_ante*$row_documentos->fields("porcentaje_iva")/100;
											//	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
												$monto_factura=$monto_ante+$p_iva_factura;	
											}
//////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
	
								$sql="SELECT  distinct
																			proveedor.nombre,
																			proveedor.id_proveedor as id_proveedor,
																			proveedor.codigo_proveedor as codigo_proveedor ,
																			\"orden_compra_servicioE\".id_unidad_ejecutora, 
																			\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
																			\"orden_compra_servicioE\".id_accion_especifica, 
																			\"orden_compra_servicioE\".numero_compromiso, 
																			\"orden_compra_servicioE\".numero_pre_orden,
																			\"orden_compra_servicioE\".tipo,
																			   partida, 
																			   generica, 
																			   especifica, 
																			   subespecifica
																			FROM 
																				\"orden_compra_servicioE\"
																			INNER JOIN
																				organismo
																			ON
																				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
																			INNER JOIN
																				\"orden_compra_servicioD\"
																			ON
																				\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																			INNER JOIN	
																					proveedor
																				ON
																					\"orden_compra_servicioE\".id_proveedor=proveedor.id_proveedor	 
																			where
																				\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
									$row_orden_compra=& $conn->Execute($sql);
									$partida=$row_orden_compra->fields("partida");
									$generica=$row_orden_compra->fields("generica");
									$especifica=$row_orden_compra->fields("especifica");
									$subespecifica=$row_orden_compra->fields("subespecifica");
									$partidas=$partida.".".$generica.".".$especifica.".".$subespecifica;
									$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
									$tipo=$row_orden_compra->fields("tipo");
									//************************
										//if($row_orden_compra->fields("id_proyecto")!='')
										if($tipo=='1'){
																	$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																	$where="AND id_proyecto = '$accion_central'";
																	$tipo=1; 
																	$sql_proyecto="SELECT 
																							id_proyecto,
																							id_organismo, 
																							ano,
																							id_jefe_proyecto,
																							codigo_proyecto, 
																	   						nombre,
																							comentario,
																							fecha_actualizacion,
																							ultimo_usuario,
																							usuario_windows,
																							serial_maquina
																	  				FROM 
																							proyecto
																					WHERE
																							id_proyecto='$accion_central'		
																							;";
																	$row_proyecto=& $conn->Execute($sql_proyecto);
																	if(!$row_proyecto->EOF)
																	{
																		$nombre_proyecto=$row_proyecto->fields("nombre");
																		$codigo_proyecto=$row_proyecto->fields("codigo_proyecto");
																		
																	}						

																	
																	
																}elseif($tipo=='2')
																{
																	$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																	$where="AND id_accion_centralizada ='$accion_central'"; 
																	$tipo=2;
																	$sql_proyecto="SELECT 
																						id_accion_central,
																						id_organismo,
																						id_jefe_proyecto,
																						codigo_accion_central, 
       																					ano,
																						denominacion,
																						comentario,
																						ultimo_usuario,
																						fecha_actualizacion, 
      																				 	serial_maquina,
																						usuario_windows
  																					FROM 
																						accion_centralizada
																					WHERE
																							id_accion_central='$accion_central'		
																							;";
																	$row_proyecto=& $conn->Execute($sql_proyecto);
																	if(!$row_proyecto->EOF)
																	{
																		$nombre_proyecto=$row_proyecto->fields("denominacion");
																		$codigo_proyecto=$row_proyecto->fields("codigo_accion_central");
																		
																	}
																}

									//************************
		
	
					if(!$row_orden_compra->EOF)
						{			$pdf->Cell(30,6,$partidas,1,0,'L',1);
									$pdf->Cell(40,6,$codigo_proyecto,1,0,'L',1);
									$pdf->Cell(70,6,substr(strtoupper($nombre_proyecto),0,45),1,'LBR','L');
									$pdf->Cell(40,6,number_format($monto_factura,2,',','.'),1,'LBR','R');
								    $pdf->SetFillColor(255);
						}	
							$pdf->Ln($coordena+6);
					$i_fact++;
						}						
				$i++;							
				}			
///////////////////////////////////////////////////////////////////////////////////////////////////		
		
		
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

?>