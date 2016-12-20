<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$fecha2 = date("Y-m-d H:i:s");
$impuesto=$_POST[cuentas_por_pagar_imp3];
if($_POST[cuentas_por_pagar_db_base_imponible2]=='')
$b2=0;
else
$b2=$_POST[cuentas_por_pagar_db_base_imponible2];
$basei2=str_replace(".","",$b2);
if($_POST[cuentas_por_pagar_db_iva2]=="")
$i2=0;
else
$i2=$_POST[cuentas_por_pagar_db_iva2];
$iva2=str_replace(".","",$i2);
if($_POST[cuentas_por_pagar_db_ret_iva2]=="")
$r_iva2=0;
else
$r_iva2=$_POST[cuentas_por_pagar_db_ret_iva2];
$ret_iva2=str_replace(".","",$r_iva2);
////////////////////////////////////////
$sustraendo=str_replace(".","",$_POST[cuentas_por_pagar_db_monto_sust]);
if($sustraendo=="")$sustraendo='0';
////////////////////////////////////////////////////////////////////////////////////////
$id_documentoss=$_POST[cuentas_por_pagar_db_id];
$monto_total=$_POST[cuentas_por_pagar_db_sub_total];
$monto_total0=str_replace(".","",$monto_total);
$monto_total1=str_replace(",",".",$monto_total0);
$monto_compromiso=$_POST[cuentas_por_pagar_db_total];
$monto_compromiso0=str_replace(".","",$monto_compromiso);
$monto_compromiso1=str_replace(",",".",$monto_compromiso0);

$sql_validar="SELECT SUM( (documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100))) as total_factura
			FROM
				documentos_cxp
			WHERE
				 numero_compromiso='$numero_compromiso_p'
			and
				estatus!='3'
			and
				id_documentos!='$id_documentoss'		 	
				";
//die($sql_validar);				
			$rs_validare=& $conn->Execute($sql_validar);
			if(!$rs_validare->EOF)
			{
				$monto_suma=$rs_validare->fields("total_factura")+$monto_total1;
			}else
			$monto_suma=$monto_total1;
			if($monto_suma>$monto_compromiso1)
					$valor="no_pasa";
				else
					$valor="pasa";
			if($valor=="no_pasa")
			{
				//echo($rs_validare->fields("total_factura")."+".$monto_total1);
				//echo($monto_suma.">".$monto_compromiso1);
				die("monto_superior");
				$monto_total1=0;
			}		

/////////////////////////////////////////////////////////////////////////////////////////
$monto_causar_comp=$_POST[monto_causar_comp];
$partida_comp=$_POST[partida_comp];
								if(($monto_causar_comp!="")&&($partida_comp!=""))
								{
									$monto_causar=str_replace(".","",$_POST[monto_causar_comp]);
									$monto_causado=str_replace(",",".",$monto_causar);
									$comprometido2=str_replace(".","",$_POST[monto_causar_comp2]);
									$comprometido=str_replace(",",".",$comprometido2);

									$id2=$_POST[cuentas_por_pagar_db_id];
									$sql_doc_det="select monto from doc_cxp_detalle where id_doc='$id2'
										and partida='$partida_comp'
										";
									//	die($sql_doc_det);
										$row_doc_det=& $conn->Execute($sql_doc_det);
										$monto_evaluarx=$monto_causado;
									//	die($row_doc_det->fields('monto')."+".$monto_causado);
									//	die($monto_evaluarx."==".$comprometido);
										/*if($monto_evaluarx>$comprometido)
										{
											die($monto_evaluarx.">".$comprometido);
											die('cargado_otra_fat');
										}*/
										if(!$row_doc_det->EOF)//caso para actualizar
										{
												
												$sql_doc_det2="select sum(monto) as monto from doc_cxp_detalle where 
																 partida='$partida_comp'
															
													";
											//ECHO($sql_doc_det);	
													$row_doc_det2=& $conn->Execute($sql_doc_det2);
													if(!$row_doc_det2->EOF)
													{
														
														$monto_evaluar2=$row_doc_det2->fields('monto')-$row_doc_det->fields('monto');
														$monto_evaluar=$monto_evaluar2+$monto_causado;
																/*if($monto_evaluar>$comprometido)
																{
																	//die($monto_evaluar.">".$comprometido);
																	die('cargado_otra_fat');
																}*/
													}	
										}
										else//caso para guardar
										{
											$sql_doc_det2="select sum(monto) as monto from doc_cxp_detalle where 
																 partida='$partida_comp'
															
													";
											//ECHO($sql_doc_det);	
													$row_doc_det2=& $conn->Execute($sql_doc_det2);
													if(!$row_doc_det2->EOF)
													{
														$monto_evaluar=$monto_causado+$row_doc_det2->fields('monto');
														//echo($monto_causado."+".$row_doc_det->fields("monto"));
													//	die($monto_evaluar.">".$comprometido);
														/*if($monto_evaluar>$comprometido)
															die('cargado_otra_fat');*/
													}
										
										}			
								}		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_cxp WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF){
	$fecha_cierre_anual = $row_fecha_cierre->fields('fecha_ultimo_cierre_anual');
	$fecha_cierre_mensual = $row_fecha_cierre->fields('fecha_ultimo_cierre_mensual');

}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre_mensual);
list($dia2,$mes2,$ano2)=split("-",$fecha2);
list($dia3,$mes3,$ano3)=split("-",$fecha_cierre_anual);
if(($dia2 >= $dia1) && ($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}else
if(($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}
if(($dia2 >= $dia3) && ($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}
if(($cerrado!="ano")||($cerrado!="mes"))
{
				$op=$_POST[cuentas_por_pagar_db_tipo_documento];
				$op2=$_POST[cuentas_por_pagar_db_anticipos];
				$numero_control=$_POST['cuentas_por_pagar_db_numero_control'];
				$numero_doc=$_POST['cuentas_por_pagar_db_numero_documento'];
				$compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
				$opcion=$_POST['cuentas_por_pagar_db_op_oculto'];
				//////////////////////////////////////////////////////////////////////////////
				$sql_valida2 = "
							SELECT 
								documentos_cxp.id_documentos
							FROM 
								documentos_cxp
							INNER JOIN
								organismo
							ON
							documentos_cxp.id_organismo=organismo.id_organismo	
								
							WHERE
								documentos_cxp.numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]' 
							AND	
								documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]."	
							AND	
								documentos_cxp.orden_pago!=0
							AND
								documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]'		
							AND
								id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
							AND
								documentos_cxp.estatus!='3'
							";
				$row_valida2= $conn->Execute($sql_valida2);//die($sql_valida2);
				if(!$row_valida2->EOF)
				{
						die("documento_orden");
				}
				////////////////////////////////////////////////////////////////////////////////
				
				$sql = "SELECT 
								id_documentos,documentos_cxp.estatus,documentos_cxp.numero_compromiso,porcentaje_iva 
						FROM 
								documentos_cxp 
						WHERE 
									id_organismo=$_SESSION[id_organismo]
							AND
									ano='$_POST[cuentas_por_pagar_db_ayo]'
							AND
									numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
							AND				
									numero_control='$_POST[cuentas_por_pagar_db_numero_control]'   
							AND
									documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]'
							AND
								id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
							AND
								documentos_cxp.estatus!='3'					
							";
				//die($sql);
				$row=& $conn->Execute($sql);
				
				if(!$row->EOF)
				{
					
					
					if(($row->fields("numero_compromiso")!=$compromiso)&&($row->fields("numero_compromiso")!='0'))
					{
						die("documento_comp");	
					}
					
					
					if(($row->fields("estatus"))!='1')
					{
						die("cerrado");
					}
						
						if(strlen($_POST['cuentas_por_pagar_db_compromiso_n'])=='0')
						{
							$compromiso=0;
						}
						else
						{
										$sql_compromiso="SELECT 
															monto,cantidad,impuesto
														FROM 
															\"orden_compra_servicioE\"
														INNER JOIN
															\"orden_compra_servicioD\"
														ON
															\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
														where
															\"orden_compra_servicioE\".numero_compromiso='$compromiso'
									";
									//die($sql_compromiso);
									if (!$conn->Execute($sql_compromiso)) die ('Error al Registrar: '.$conn->ErrorMsg());
									$row_compromiso= $conn->Execute($sql_compromiso);
											
											if($row_compromiso->EOF)
											{
												//die($sql_compromiso);
												die("No_existe_compromiso");
												
												}
												else{$total_monto=0;
				
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										/*$sql="SELECT 
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
															\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
														where
															\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
													$row_orden_compra=& $conn->Execute($sql);
													while(!$row_orden_compra->EOF)
													{
															$partida=$row_orden_compra->fields("partida");
															$generica=$row_orden_compra->fields("generica");	
															$especifica=$row_orden_compra->fields("especifica");
															$subespecifica=$row_orden_compra->fields("subespecifica");	
														//	die($partida.$generica.$especifica.$subespecifica);
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
															$resumen_suma = "
																				SELECT  
																					   (monto_presupuesto[".date("n")."]) AS monto_p,
																					   (monto_modificado[".date("n")."]) AS monto_mod,
																					   (monto_traspasado[".date("n")."]) AS monto_tras,
																					   (monto_comprometido[".date("n")."]) AS monto_comp
																					   
																					   
																				FROM 
																					\"presupuesto_ejecutadoR\"
																				WHERE
																					id_unidad_ejecutora='$unidad_ejecutora'
																				AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																				AND
																					partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																				$where
																				";
															//	die($resumen_suma);				
																$rs_resumen_suma=& $conn->Execute($resumen_suma);
																
																if (!$rs_resumen_suma->EOF) 
																{
																	$monto_presupuesto = $rs_resumen_suma->fields("monto_p");
																	$monto_modificado = $rs_resumen_suma->fields("monto_mod");
																	$monto_traspasado = $rs_resumen_suma->fields("monto_tras");
																	$monto_comprometido = $rs_resumen_suma->fields("monto_comp");
																	$monto_total =($monto_presupuesto + $monto_modificado+$monto_traspasado)-$monto_comprometido;			
																}
																	$total_monto=$total_monto+$monto_total;
																	$row_orden_compra->MoveNext();
										
														}*/
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
				
															//////////*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
											
															$sql_facturas="SELECT 
																				   porcentaje_iva,
																				   porcentaje_retencion_iva, 
																				   monto_bruto,
																				   monto_base_imponible
																	 FROM
																				documentos_cxp
																	where						   
																				documentos_cxp.numero_compromiso='$compromiso'
																	AND	
																				numero_documento!='$_POST[cuentas_por_pagar_db_numero_documento]'	
																	AND
																				documentos_cxp.estatus!='3'		
																				";		   
															//die($sql_facturas);					
															$row_factura=& $conn->Execute($sql_facturas);
															$total_renglon=0;
															
																while(!$row_factura->EOF)
																{
																	$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
																	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;
																	$total_renglon=$total_renglon+$monto_factura;
																	$row_factura->MoveNext();
																}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
											//$orden_total=$_POST['cuentas_por_pagar_db_total'];
											$orden_total2=str_replace(".","",$_POST[cuentas_por_pagar_db_total]);
											$orden_total=str_replace(",",".",$orden_total2);
											$fact_ord=($orden_total)-$total_renglon;
											//$orden_total."-".
										//	echo($total_renglon."*");
											$iva=str_replace(".","",$_POST[cuentas_por_pagar_db_monto_iva]);
											$iva1=str_replace(",",".",$iva);
												//$bruto2 = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
												$bruto2 = str_replace(".","",$_POST['cuentas_por_pagar_db_sub_total']);
												$bruto2=round(str_replace(",",".",$bruto2),2);
											//	$bruto=$bruto+$iva1;


													//die($bruto2.">".$fact_ord);
									$ass="$bruto2";
									$ass2="$fact_ord";
									//echo($bruto2."-");
									//echo($fact_ord."-");
									if($ass>$ass2)
									{
										//echo($bruto2."-");
										//echo($fact_ord."-");
///										die("cabra");
										//die("monto_superior");

									}
									
												//
/*												if(($bruto2)>($fact_ord))
												{
													die("monto_superior");
													
												}
*/												//die("h");
									}	
														
						}		
									/*	if($op==$op2)
											{	
												$bruto = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_neto']);
												//$bruto=str_replace(",",".",$bruto);
												$monto=$bruto;
												//$monto=str_replace(".","",$bruto);
												$base='0';
												}	
											else
											if($op!=$op2)	
											{
												$bruto = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
												$bruto=str_replace(",",".",$bruto);
												$monto=$_POST[cuentas_por_pagar_db_monto_bruto];
												$base=$_POST[cuentas_por_pagar_db_base_imponible];
											}*/
					
					$monto = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
				//	echo($monto);
					$base = str_replace(".","",$_POST['cuentas_por_pagar_db_base_imponible']);
				//	$monto=$_POST[cuentas_por_pagar_db_monto_bruto];
				//	$base=$_POST[cuentas_por_pagar_db_base_imponible];
					$iva=$_POST[cuentas_por_pagar_db_iva];
					$ret_iva=$_POST[cuentas_por_pagar_db_ret_iva];
					$ret_islr=$_POST[cuentas_por_pagar_db_islr];
					$ret1=$_POST[cuentas_por_pagar_db_ret_extra];
					$ret2=$_POST[cuentas_por_pagar_db_ret_extra2];	
					$pret1=$_POST[cuentas_por_pagar_db_ret_e1];	
					$pret2=$_POST[cuentas_por_pagar_db_ret_e2];
					$amort=$_POST[cuentas_por_pagar_amortizacion];
					
					//-
					/*$monto=str_replace(".","",$monto);
					$base=str_replace(".","",$base);
				*/	$iva=str_replace(".","",$iva);
					$ret_iva=str_replace(".","",$ret_iva);
					$ret_islr=str_replace(".","",$ret_islr);
					$ret1=str_replace(".","",$ret1);
					$ret2=str_replace(".","",$ret2);
					$pret1=str_replace(".","",$pret1);
					$pret2=str_replace(".","",$pret2);
					$amort=str_replace(".","",$amort);
					
////////////////
/*$monto_base_imponible2=str_replace(",",".",$basei2);
$porcentaje_iva2=str_replace(",",".",$iva2);
$retencion_iva2=str_replace(",",".",$ret_iva2);*///---------------------------------------------------------------------------------------------------------------------------------
				//////////////////////////////////////////////////////////////
					$monto_causar_comp=$_POST[monto_causar_comp];
					$partida_comp=$_POST[partida_comp];
								
							/*	if(($monto_causar_comp!="")&&($partida_comp!=""))
								{
									$monto_causar=str_replace(".","",$_POST[monto_causar_comp]);
									$id2=$_POST[cuentas_por_pagar_db_id];
									$sql_doc_det="select monto from doc_cxp_detalle where id_doc='$id2'
										and partida='$partida_comp'
										";
										$row_doc_det=& $conn->Execute($sql_doc_det);
										if(!$row_doc_det->EOF)
										{
											$sql3="UPDATE doc_cxp_detalle 
															 SET
																partida='$_POST[partida_comp]',
																monto='".str_replace(",",".",$monto_causar)."',
																compromiso='$_POST[cuentas_por_pagar_db_compromiso_n]',
																impuesto='$impuesto'
															where
																	 id_doc='$id2'
															and
																	 partida='$partida_comp'";
													
										}else
										{		
											$sql3="INSERT INTO 
															doc_cxp_detalle
															(
																id_organismo,
																id_doc,
																partida,
																monto,
																impuesto,
																compromiso
																)
															VALUES
															(
																".$_SESSION["id_organismo"].",
																'$id2',
																'$_POST[partida_comp]',
																'".str_replace(",",".",$monto_causar)."',
																'$impuesto',
																'$_POST[cuentas_por_pagar_db_compromiso_n]'
															
															)	
											
											";
										}	
							}*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($partida_comp!="")
			{
								
							// para guardar o modificar en la tabla de detalle
							
					
					//7consulto el id de la factura guardada		
				$sql_ext="select id_documentos from documentos_cxp where numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]' and numero_control='$_POST[cuentas_por_pagar_db_numero_control]' and id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'";
							//die($sql2);
							$rowxt=& $conn->Execute($sql_ext);
							
							if ($rowxt->EOF)
							die('error'.$sql_ext);
							else
							{
								$id2=$rowxt->fields("id_documentos");
							
							}
			
					////////////////////////////////////////////777
					$partidas_varias=$_POST[cuentas_por_pagar_imp3];
					$partida_generica=$_POST[partida_comp];
					$mnto = str_replace(".","",$_POST['monto_causar_comp']);
					$monto2=str_replace(",",".",$mnto);
					$partida1=substr($partida_generica,0,3);
					$generica1=substr($partida_generica,3,2);
					//consultando a ver cual es el monto total de esa partida
$sql_orden="SELECT 
SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)+(((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)*\"orden_compra_servicioD\".impuesto)/100)) as total_renglon,
											SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)) as base_imponible
											
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
											 requisicion_encabezado
										 ON
										 	\"orden_compra_servicioE\".numero_requisicion=requisicion_encabezado.numero_requisicion 
										where
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										and
											\"orden_compra_servicioD\".partida='".$partida1."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$generica1."'
			 	    					and 
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
											
											";
											//die($sql_orden);
									$row_orden_compra=& $conn->Execute($sql_orden);
									$total_renglon=0;
									if(!$row_orden_compra->EOF)
									{
										$total_renglon=$row_orden_compra->fields("total_renglon");
										$base_imponible=$row_orden_compra->fields("base_imponible");
									}
									
									//verifico si el monto guardado es igual al de la orden -CASO1-
									//echo($base_imponible."==".$monto2);
									if($base_imponible==$monto2)
									{
									//echo("entro1");
									
										$vector = split( ";", $partidas_varias);
										$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
										$i=0;
													
										while($i < $contador)
										{
											////consulto la orden para sacar el monto por partida y guardarlo en la tabla de factura detalle
											$t_part=$vector[$i];
											$partida2=substr($t_part,0,3);
											$generica2=substr($t_part,3,2);
											$especifica2=substr($t_part,5,2);
											$sub_especifica2=substr($t_part,7,2);
											$sql_orden2="SELECT 
SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)+(((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)*\"orden_compra_servicioD\".impuesto)/100)) as total_renglon,
											SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)) as base_imponible,impuesto
											
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
											 requisicion_encabezado
										 ON
										 	\"orden_compra_servicioE\".numero_requisicion=requisicion_encabezado.numero_requisicion 
										where
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										and
											\"orden_compra_servicioD\".partida='".$partida2."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$generica2."'
			 	    					and	
							    			\"orden_compra_servicioD\".especifica='".$especifica2."'
			 	    					and	
							    			\"orden_compra_servicioD\".subespecifica='".$sub_especifica2."'
			 	    					and 
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										group by
										impuesto	
											";
											//die($sql_orden2);
									$row_orden_compra2=& $conn->Execute($sql_orden2);
								
									if(!$row_orden_compra2->EOF)
									{
											$total_renglon2=$row_orden_compra2->fields("base_imponible");
											$impuesto=$row_orden_compra2->fields("impuesto");
											$monto_causar_comp=$_POST[monto_causar_comp];
											$partida_comp=$_POST[partida_comp];
											if(($monto_causar_comp!="")&&($partida_comp!=""))
											{
												$monto_causar=$total_renglon2;
												/* cambios orientados en la tabla detalle gracia a la ordenaicon visual de los renglones de partidas agrupados por generica */
												if($monto_causar>=0)
												{
												$sql_doc_det="select monto from doc_cxp_detalle where id_doc='$id2'
													and partida='$t_part'
													";
													//die($sql_doc_det);
													$row_doc_det=& $conn->Execute($sql_doc_det);
													if(!$row_doc_det->EOF)
													{
														$sql3="UPDATE doc_cxp_detalle 
																		 SET
																			partida='$t_part',
																			monto='".$monto_causar."'
																		where
																				 id_doc='$id2'
																		and
																				 partida='$t_part'";
																
													}else
													{		
														$sql3="INSERT INTO 
																		doc_cxp_detalle
																		(
																			id_organismo,
																			id_doc,
																			partida,
																			monto,
																			impuesto,
																			compromiso
																			)
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$id2',
																			'$t_part',
																			'".$monto_causar."',
																			'$impuesto',
																			'$_POST[cuentas_por_pagar_db_compromiso_n]'
																		
																		)";	
													}
													
									if (!$conn->Execute($sql3)) 
									die ('Error al Registrar: '.$sql3);
											
									}//fin de if CAUSAR>0

																						
									}//END IF
										}//orden compra2

								$i++;	
									
									}//fin  while
									
					}
									else
									// en e caso de q sea prorateado
									if($base_imponible >$monto2 )
									{
									//echo("entro2");
										
										
										$vector = split( ";", $partidas_varias);
										$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
										$i=0;
										$monto3=$monto2;	
										$restab=$base_imponible-$monto3;
										//echo("+++".$partidas_varias."+++");
										//echo($base_imponible."-".$monto3);
									if($restab>=0)
									{		
											while($i < $contador)
											{
												
															
														////consulto la orden para sacar el monto por partida y guardarlo en la tabla de factura detalle
														$t_part=$vector[$i];
														$partida2=substr($t_part,0,3);
														$generica2=substr($t_part,3,2);
														$especifica2=substr($t_part,5,2);
														$sub_especifica2=substr($t_part,7,2);
														$sql_orden2="SELECT 
													SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)+(((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)*\"orden_compra_servicioD\".impuesto)/100)) as total_renglon,
														SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)) as base_imponible,impuesto
														
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
														 requisicion_encabezado
													 ON
														\"orden_compra_servicioE\".numero_requisicion=requisicion_encabezado.numero_requisicion 
													where
														\"orden_compra_servicioE\".numero_compromiso='$compromiso'
													and
														\"orden_compra_servicioD\".partida='".$partida2."' 
													and	
														\"orden_compra_servicioD\".generica='".$generica2."'
													and	
														\"orden_compra_servicioD\".especifica='".$especifica2."'
													and	
														\"orden_compra_servicioD\".subespecifica='".$sub_especifica2."'
													and 
														\"orden_compra_servicioE\".numero_compromiso='$compromiso'
													group by
													impuesto	
														";
														//die($sql_orden2);
													$row_orden_compra2=& $conn->Execute($sql_orden2);
													
													if(!$row_orden_compra2->EOF)
													{
															$total_renglon2=$row_orden_compra2->fields("base_imponible");
															//echo($total_renglon2."**");
															$impuesto=$row_orden_compra2->fields("impuesto");
									//*****************************************************************************************************************
															$sql_doc_det2="select SUM(monto) AS monto from doc_cxp_detalle where compromiso='$compromiso'
																				and partida='$t_part'
																				";
																				//die($sql_doc_det);
																				$row_doc_det2=& $conn->Execute($sql_doc_det2);
																						if(!$row_doc_det2->EOF)
																						{
																							$total_det=$row_doc_det2->fields("monto");
																						}
									if($total_det<=$total_renglon2)	
									{												
									//******************************************************************************************************************						
															$monto_causar_comp=$_POST[monto_causar_comp];
															$partida_comp=$_POST[partida_comp];
															if(($monto_causar_comp!="")&&($partida_comp!=""))
															{
																	$monto_causar=$total_renglon2-$total_det;
																	// calculando cuanto puedo causar
																	$monto3_original=$monto3;
																	$monto_causar_original=$monto_causar;
																	$monto3=$monto3-$monto_causar;
																//	echo("prueba:".$monto3."-".$monto_causar);
																	if($monto3<=0)$monto_causar=$monto3_original;
																	if($monto_causar>0)
																	{	
																			/* cambios orientados en la tabla detalle gracia a la ordenaicon visual de los renglones de partidas agrupados por generica */
																			$sql_doc_det="select monto from doc_cxp_detalle where id_doc='$id2'
																				and partida='$t_part'
																				";
																				//die($sql_doc_det);
																				$row_doc_det=& $conn->Execute($sql_doc_det);
																				if(!$row_doc_det->EOF)
																				{
																					$sql3="UPDATE doc_cxp_detalle 
																									 SET
																										partida='$t_part',
																										monto='".$monto_causar."'
																									where
																											 id_doc='$id2'
																									and
																											 partida='$t_part'
																									and
																											 compromiso='$_POST[cuentas_por_pagar_db_compromiso_n]'			 
																											 ";
																							
																				}else
																				{
																				
																				
																				
																							
																							$sql3="INSERT INTO 
																											doc_cxp_detalle
																											(
																												id_organismo,
																												id_doc,
																												partida,
																												monto,
																												impuesto,
																												compromiso
																												)
																											VALUES
																											(
																												".$_SESSION["id_organismo"].",
																												'$id2',
																												'$t_part',
																												'".$monto_causar."',
																												'$impuesto',
																												'$_POST[cuentas_por_pagar_db_compromiso_n]'
																											
																											)";	
																				}
																	
																	$sqle=$sqle.";".$sql3;
																	//die($sql3);			
																	if (!$conn->Execute($sql3)) 
																	die ('Error al Registrar: '.$sql3);
															
																}//fin de if($monto_causar>0)

															}//fin de 	if(($monto_causar_comp!="")&&($partida_comp!=""))

									}//fin 	if($total_det<$total_renglon2)	
																										
														
														}//orden compra2
													
													
											
											$i++;

										}//fin dle while 
								}// fin de si restab es dif 0
									
									
									}
				
			}// fin if($partida_comp!="")
//echo($sqle);
///////////////////////////////////////////////////////////////////////////////////////////////////////
					if(($row->fields("numero_compromiso")=='0')&&($_POST['cuentas_por_pagar_db_compromiso_n']!=''))
					{
						$sql_comp = "		UPDATE documentos_cxp 
														 SET
															numero_compromiso='$_POST[cuentas_por_pagar_db_compromiso_n]',
															ultimo_usuario=".$_SESSION['id_usuario'].", 
															fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
															
														WHERE 
																	id_organismo=$_SESSION[id_organismo]
															AND
																	ano='$_POST[cuentas_por_pagar_db_ayo]'
															AND
																	numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
															AND				
																	numero_control='$_POST[cuentas_por_pagar_db_numero_control]'
															AND
																	id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
															AND
																	documentos_cxp.estatus!='3'						
															";
							if (!$conn->Execute($sql_comp)) {
							die ('Error al Actualizar: '.$sql_comp);}
					}		
				//---------------------------------------------------------------------------------------------------------
					//-
				if($op==$op2)//si es anticipo
					{	
						if($opcion=='1')
								{
										$sql = "		UPDATE documentos_cxp 
															 SET
																ano='$_POST[cuentas_por_pagar_db_ayo]',
																id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]',
																fecha_documento='$_POST[cuentas_por_pagar_db_fecha_f]',
																fecha_vencimiento='$_POST[cuentas_por_pagar_db_fecha_v]',
																monto_bruto='".str_replace(",",".",$monto)."',
																porcentaje_iva='".str_replace(",",".",$iva)."',
																comentarios='$_POST[tesoreria_banco_db_comentarios]',
																ultimo_usuario=".$_SESSION['id_usuario'].", 
																fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
																

															WHERE 
																		id_organismo=$_SESSION[id_organismo]
																AND
																		ano='$_POST[cuentas_por_pagar_db_ayo]'
																AND
																		numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
																AND				
																		numero_control='$_POST[cuentas_por_pagar_db_numero_control]'   
																AND
																		id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'		
																AND
																		documentos_cxp.estatus!='3'						
																		";
									}
									if($opcion=='2')
									{
										$sql = "		UPDATE documentos_cxp 
															 SET
																ano='$_POST[cuentas_por_pagar_db_ayo]',
																beneficiario='$_POST[cuentas_por_pagar_db_empleado_nombre]',
																cedula_rif_beneficiario='$_POST[cuentas_por_pagar_db_empleado_codigo]',
																fecha_documento='$_POST[cuentas_por_pagar_db_fecha_f]',
																fecha_vencimiento='$_POST[cuentas_por_pagar_db_fecha_v]',
																monto_bruto='".str_replace(",",".",$monto)."',
																porcentaje_iva='".str_replace(",",".",$iva)."',
																comentarios='$_POST[tesoreria_banco_db_comentarios]',
																ultimo_usuario=".$_SESSION['id_usuario'].", 
																fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																saldo='".str_replace(",",".",$monto)."'

															WHERE 
																		id_organismo=$_SESSION[id_organismo]
																AND
																		ano='$_POST[cuentas_por_pagar_db_ayo]'
																AND
																		numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
																AND				
																		numero_control='$_POST[cuentas_por_pagar_db_numero_control]'   
																AND
																		id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
																AND
																		documentos_cxp.estatus!='3'								
																		";
						
										}
					}
					else
					if($op!=$op2)//si no es anticipo
					{	
						$sql_amort=" UPDATE documentos_cxp
										SET
											amortizacion='".str_replace(",",".",$amort)."'
										WHERE 
																	id_organismo=$_SESSION[id_organismo]
															AND
																	ano='$_POST[cuentas_por_pagar_db_ayo]'
															AND
																	numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
															AND				
																	numero_control='$_POST[cuentas_por_pagar_db_numero_control]'
															AND
																	id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
															AND
																	documentos_cxp.estatus!='3'								
																	";
								if($opcion=='1')
								{
										$sql = "		UPDATE documentos_cxp 
															 SET
																ano='$_POST[cuentas_por_pagar_db_ayo]',
																tipo_documentocxp='$_POST[cuentas_por_pagar_db_tipo_documento]',
																id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]',
																fecha_documento='$_POST[cuentas_por_pagar_db_fecha_f]',
																fecha_vencimiento='$_POST[cuentas_por_pagar_db_fecha_v]',
																porcentaje_iva='".str_replace(",",".",$iva)."',
																porcentaje_retencion_iva='".str_replace(",",".",$ret_iva)."',
																porcentaje_retencion_islr='".str_replace(",",".",$ret_islr)."',
																monto_bruto='".str_replace(",",".",$monto)."',
																monto_base_imponible= '".str_replace(",",".",$base)."',
																comentarios='$_POST[tesoreria_banco_db_comentarios]',
																ultimo_usuario=".$_SESSION['id_usuario'].", 
																fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																retencion_ex1='".str_replace(",",".",$ret1)."',
																retencion_ex2='".str_replace(",",".",$ret2)."',
																desc_ex1='$_POST[cuentas_por_pagar_db_ret_extra_dsc1]',
																desc_ex2='$_POST[cuentas_por_pagar_db_ret_extra_dsc2]',
																pret1='".str_replace(",",".",$pret1)."',
																pret2='".str_replace(",",".",$pret2)."',
																aplica_bi_ret_ex1='$_POST[valor_biex1]',
																aplica_bi_ret_ex2='$_POST[valor_biex2]',
																monto_base_imponible2='".str_replace(",",".",$basei2)."',
																porcentaje_iva2='".str_replace(",",".",$iva2)."',
																retencion_iva2='".str_replace(",",".",$ret_iva2)."',
																sustraendo='".str_replace(",",".",$sustraendo)."'

															WHERE 
																		id_organismo=$_SESSION[id_organismo]
																AND
																		ano='$_POST[cuentas_por_pagar_db_ayo]'
																AND
																		numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
																AND				
																		numero_control='$_POST[cuentas_por_pagar_db_numero_control]'
																AND
																		id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
																AND
																		documentos_cxp.estatus!='3'								
																		;
																		$sql_amort";
									}
									if($opcion=='2')
									{
										$sql = "		UPDATE documentos_cxp 
															 SET
																ano='$_POST[cuentas_por_pagar_db_ayo]',
																beneficiario='$_POST[cuentas_por_pagar_db_empleado_nombre]',
																cedula_rif_beneficiario='$_POST[cuentas_por_pagar_db_empleado_codigo]',
																fecha_documento='$_POST[cuentas_por_pagar_db_fecha_f]',
																fecha_vencimiento='$_POST[cuentas_por_pagar_db_fecha_v]',
																porcentaje_retencion_iva='".str_replace(",",".",$iva)."',
																porcentaje_retencion_islr='".str_replace(",",".",$ret_islr)."',
																monto_bruto='".str_replace(",",".",$monto)."',
																monto_base_imponible= '".str_replace(",",".",$base)."',
																comentarios='$_POST[tesoreria_banco_db_comentarios]',
																ultimo_usuario=".$_SESSION['id_usuario'].", 
																fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
																retencion_ex1='".str_replace(",",".",$ret1)."',
																retencion_ex2='".str_replace(",",".",$ret2)."',
																desc_ex1='$_POST[cuentas_por_pagar_db_ret_extra_dsc1]',
																desc_ex2='$_POST[cuentas_por_pagar_db_ret_extra_dsc2]',
																pret1='".str_replace(",",".",$pret1)."',
																pret2='".str_replace(",",".",$pret2)."',
																aplica_bi_ret_ex1='$_POST[valor_biex1]',
																aplica_bi_ret_ex2='$_POST[valor_biex2]',
																monto_base_imponible2='".str_replace(",",".",$basei2)."',
																porcentaje_iva2='".str_replace(",",".",$iva2)."',
																retencion_iva2='".str_replace(",",".",$ret_iva2)."',
																sustraendo='".str_replace(",",".",$sustraendo)."'
																
															WHERE 
																		id_organismo=$_SESSION[id_organismo]
																AND
																		ano='$_POST[cuentas_por_pagar_db_ayo]'
																AND
																		numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
																AND				
																		numero_control='$_POST[cuentas_por_pagar_db_numero_control]'
																AND
																		id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
																AND
																		documentos_cxp.estatus!='3'								
																		;
																	$sql_amort";
												}
						}				
				//
				//*cargando en la tabla de detalle de documentos*/
										
				//
				//$sql=$sql;
			//	echo(str_replace(",",".",$monto));
					//	die($sql);
				}
				else
					die ("NoActualizo");
			
				if (!$conn->Execute($sql)) {
					
					die ('Error al Actualizar: '.$sql);}
				else {
						die ('Actualizado');
					}
}
else
die("cerrados");					
?>
								