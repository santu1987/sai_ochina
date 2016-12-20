<?php
session_start();
ini_set("memory_limit","20M");

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
$centur=0;
////
					$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_fact =& $conn->Execute($sql_fact);
					$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
////
////
					$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
$sql_ordenes="SELECT id_orden_pago, id_organismo, fecha_orden_pago, id_banco, cuenta_banco, 
       comentarios, ultimo_usuario, fecha_ultima_modificacion, orden_pago, 
       id_proveedor, ano, documentos, secuencia, cheque, estatus, beneficiario, 
       cedula_rif_beneficiario, estatus_orden, saldo
  FROM orden_pago
  where
  estatus='2'
  and
  cheque!='0'
  ;";
//  die($sql_ordenes);
$row=& $conn->Execute($sql_ordenes);
while (!$row->EOF) 
{	
											$fecha1=$row->fields("fecha_orden_pago");
											$dia1=substr($fecha1,8,2);
											$mes1=substr($fecha1,5,2);
											$ano1=substr($fecha1,0,4);
	//////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
							
								$documentos=$row->fields("documentos");
								$doc1=str_replace("{","",$documentos);
								$doc2=str_replace("}","",$doc1);
								$facturas= split(",",$doc2);
								$contador_fact=count($facturas);
								$i_fact=0;
								while($i_fact < $contador_fact)
								{
								//////////consultando las facturas			
														$sql_facturas="
																			SELECT 
																					numero_compromiso,monto_bruto
																			FROM
																					documentos_cxp
																			where
																					id_documentos='$facturas[$i_fact]'";
																					
																								
														$row_documentos=& $conn->Execute($sql_facturas);
														$numero_compromiso=$row_documentos->fields("numero_compromiso");
														$monto_restar=$row_documentos->fields("monto_bruto");
														if(($numero_compromiso==0)or($numero_compromiso=="")or($numero_compromiso=="NULL"))
															{
																$partida=0;
																$unidad_ejecutora=0;
																$accion_central=0;
																$accion_especifica=0;
																$pre_orden=0;
																$tipo=0;
															}
														else{
																													//	die("entreo");

														///////////////////////////////////////////////////////////////////////////////////
																		$sql_facturas="SELECT 
																								id_documentos,
																								porcentaje_iva,
																								porcentaje_retencion_iva, 
																								monto_bruto,
																								monto_base_imponible,
																								tipo_documentocxp,
																								amortizacion
																						FROM
																								documentos_cxp
																								where						   
																									documentos_cxp.numero_compromiso='$numero_compromiso'
																								and	
																									id_documentos='$facturas[$i_fact]'";
																										   
																								$row_factura=& $conn->Execute($sql_facturas);
																								$total_renglon=0;
																								if(!$row_factura->EOF)
																								{
																										$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
																										$monto_factura=$row_factura->fields("monto_bruto");
																										if(($row_factura->fields("tipo_documentocxp")==$tipos_ant))
																										{
																											$monto_factura=0;
																										}
																										if((($row_factura->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura->fields("amortizacion")!='0,00'))
																										{
																											$monto_factura="";	
																											//$monto_ante=($row_factura->fields("monto_bruto")+$row_factura->fields("amortizacion"));
																											$monto_ante=$row_factura->fields("monto_bruto");
																											$p_iva_factura=$monto_ante*$row_factura->fields("porcentaje_iva")/100;
																											
																										//	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
																											$monto_factura=$monto_ante+$p_iva_factura;	
																										}
																										//echo($monto_ante."*".$row_factura->fields("porcentaje_iva")."-");
																										$p_iva_total=$p_iva_total+$p_iva_factura;
																									//	echo($p_iva_total."-");
																										$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
																								
																									//echo($monto_factura."-");
																								//echo($monto_factura."-");
																								/*$total_documento=$monto_restar; 
																								$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento; 
																								$fact_ord=($total_compromiso)-$lo_q_qeda_fact;*/
																								}	
												//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
																		//// seleccionando los datos sobre las partidas
																			$sql="SELECT 
																			\"orden_compra_servicioE\".id_proveedor, 
																			\"orden_compra_servicioE\".id_unidad_ejecutora,
																			\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
																			\"orden_compra_servicioE\".id_accion_especifica, 
																			\"orden_compra_servicioE\".numero_compromiso, 
																			\"orden_compra_servicioE\".numero_pre_orden,
																			\"orden_compra_servicioE\".tipo,
																			   partida, 
																			   generica, 
																			   especifica, 
																			   subespecifica,
																			   fecha_orden_compra_servicio
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
																			where
																				\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
																			$row_orden_compra=& $conn->Execute($sql);
																		while(!$row_orden_compra->EOF)
																		{
											$fecha_ocs=$row_orden_compra->fields("fecha_orden_compra_servicio");
											$dia_orden=substr($fecha_ocs,8,2);
											$mes_orden=substr($fecha_ocs,5,2);
											$ano_orden=substr($fecha_ocs,0,4);
																		///////////////////////////////////////////////
																			$sql_compromiso="SELECT 
																									monto,cantidad,fecha_orden_compra_servicio,impuesto
																								FROM 
																									\"orden_compra_servicioE\"
																								INNER JOIN
																									\"orden_compra_servicioD\"
																								ON
																									\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																								where
																									\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
																								AND 
																									(\"orden_compra_servicioD\".ano = '".date("Y")."')
																								AND
																									\"orden_compra_servicioD\".partida = '".$row_orden_compra->fields("partida")."'  
																								AND	
																									\"orden_compra_servicioD\".generica = '".$row_orden_compra->fields("generica")."'
																								AND	
																									\"orden_compra_servicioD\".especifica = '".$row_orden_compra->fields("especifica")."'  
																								AND
																									\"orden_compra_servicioD\".subespecifica = '".$row_orden_compra->fields("subespecifica")."'	";
																			if (!$conn->Execute($sql_compromiso)) die ('Error al Registrar: '.$conn->ErrorMsg());
																			$row_compromiso= $conn->Execute($sql_compromiso);
																			/// calculando totales segun compromisos
																								while(!$row_compromiso->EOF)
																									{
																									  //sacando el total de la orden de compra
																										  $monto_compromiso=$row_compromiso->fields("monto");
																										  $cantidad_compromiso=$row_compromiso->fields("cantidad");
																										  $total_compromiso=$monto_compromiso*$cantidad_compromiso;
																										  $imp=$row_compromiso->fields("impuesto");
									$iva=($total_compromiso*$imp)/100;
									$total_compromiso=$total_compromiso+$iva;
									
																										  $total_compromiso_a=$total_compromiso_a+total_compromiso;
																										  $fecha_ord=$row_compromiso->fields("fecha_orden_compra_servicio");
																										  $row_compromiso->MoveNext();
																									}//fin del mientras de row compromiso																					}		
				
											//////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
											/*************************/
											//proceso para sacara contra que va el causado
											$partidas=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
											$sql_doc_det="select sum(monto) as causado,partida from doc_cxp_detalle
															inner join
															documentos_cxp
															ON doc_cxp_detalle.id_doc=documentos_cxp.id_documentos
															where partida='$partidas'
															and
															documentos_cxp.numero_compromiso='$numero_compromiso'
															group by
															partida";
										
													$row_doc_det=& $conn->Execute($sql_doc_det);
												//	die($sql_doc_det);
													if(!$row_doc_det->EOF)
													{
														$causado=$row_doc_det->fields("causado");
														$iva_causado=($causado*$imp)/100;
														$causado2=$causado+$iva_causado;
														$causados=round($causados,2)+round($causado2,2);
														
													//	$causados=$causados+$causado;
														//echo($causado);
													}
											/****************************/
																$partida=$row_orden_compra->fields("partida");
																$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
																$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
																$pre_orden=$row_orden_compra->fields("id_accion_especifica");
																$tipo=$row_orden_compra->fields("tipo");
																if($tipo=='1')
																{
																$where="AND id_proyecto = '$accion_central'"; 
																}else
																$where="AND id_accion_centralizada ='$accion_central'"; 
															//	die($fecha2);
														/*		$mes_orde=substr($fecha2,5,2);
																$ano_orde=substr(fecha2,0,4);
				*/
																$resumen_suma = "
																					SELECT  
																						   (monto_pagado[".$mes_orden."]) AS monto
																					FROM 
																						\"presupuesto_ejecutadoR\"
																					WHERE
																						id_unidad_ejecutora='$unidad_ejecutora'
																					AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																					AND
																						partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																					$where
																					";
																		/*if($centur==1)
																		die($resumen_suma);*/
																	$rs_resumen_suma=& $conn->Execute($resumen_suma);
																	
																	if (!$rs_resumen_suma->EOF) 
																		$monto_pagado = $rs_resumen_suma->fields("monto");
																	else
																		$monto_pagado = 0;
																		//$monto_total = $monto_pagado + $monto_restar;
																		
																		$monto_total = $monto_pagado + $causado2;
																		$monto_total=round($monto_total,2);
																		//echo($monto_total." = ".$monto_pagado." + ".$causado);
																	//	echo($monto_total." = ".$monto_pagado." + ".$monto_restar);
																		$actu1=
																			"UPDATE 
																					\"presupuesto_ejecutadoR\"
					
																			SET 
																					monto_pagado[".$mes1."]= '$monto_total'
																			WHERE
																					(id_organismo = ".$_SESSION['id_organismo'].") 
																				AND
																					(id_unidad_ejecutora = '$unidad_ejecutora') 
																				AND 
																					(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																				AND 
																					(ano = '".date("Y")."')
																				AND
																					partida = '".$row_orden_compra->fields("partida")."'  
																				AND	
																					generica = '".$row_orden_compra->fields("generica")."'
																				AND	
																					especifica = '".$row_orden_compra->fields("especifica")."'  
																				AND
																					sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																				$where;
																				UPDATE 
																						\"presupuesto_ejecutadoD\"
																				SET
																						estatus='4'
																				WHERE
																						numero_compromiso='$numero_compromiso';										
																		";	
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////														
											
														  $actu=$actu.";".$actu1;
														$ext=$ext+1; 
													$row_orden_compra->MoveNext();
													}//fin del mientrasz
													//echo("-".$total_facturas_comprometidas."-");
									}//fin del else	
												$i_fact++;
													
	echo($row->fields("orden_pago")."-");
	echo("compromiso: ".$numero_compromiso."-");
	$row->MoveNext();
	$centur++;
}						
																	
													 
												if($ext!=0)
												{		  												//die($actu);

													
													if (!$conn->Execute($actu))
													die ('Error al Actulizar: '.$conn->ErrorMsg());
												}
								
																
									
			///////////////////////////////////////////////////////////////////////////////////////////////////		
		
}

			
?>