<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$ext=0;
$n_cheque=$_POST['tesoreria_cheque_anular_pr_n_cheque'];
$sql = "
			SELECT 
				id_cheques,
				id_banco,
				cuenta_banco,
				numero_cheque,
				tipo_cheque,
				id_proveedor,	
				monto_cheque,
				concepto,
				estatus,
				comentarios,
				porcentaje_itf,
				cheques.id_organismo,
				fecha_cheque,
				cheques.ordenes,
				fecha_ultima_modificacion,
				cheques.contabilizado
			FROM 
				cheques
			INNER JOIN
				organismo
			ON
			cheques.id_organismo=organismo.id_organismo	
			WHERE
				cheques.id_cheques='$_POST[tesoreria_cheque_anular_pr_id_cheque]' 
			AND
				cheques.numero_cheque='$_POST[tesoreria_cheque_anular_pr_n_cheque]'
			AND
				cheques.secuencia='$_POST[tesoreria_cheque_anular_pr_secuencia]'
			AND
				cheques.id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'	
			AND	
				cheques.cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
			AND	
				cheques.id_organismo=".$_SESSION["id_organismo"]."	
			";
		
$row= $conn->Execute($sql);

						
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	if(!$row->EOF)
	{
		if($row->fields("contabilizado")==1)					
				die("integrado");
			else
				$contabilizado=$row->fields("contabilzado");
		if (strlen($contabilizado)==0)
		{
			$contabilizado=0;
		}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			$sql_saldo_actual = "SELECT 
										 saldo_actual
								   FROM 
										 banco_cuentas
									WHERE
										cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'
									AND
										estatus='1'		
								  ";
				$row_saldo_actual= $conn->Execute($sql_saldo_actual);
				if(!$row_saldo_actual->EOF)	
				{		
			
							$saldo_actual=$row_saldo_actual->fields("saldo_actual");
							$monto_cheque=$_POST[tesoreria_cheque_anular_pr_monto_pagar];
							$monto_cheque= str_replace(".","",$monto_cheque);
							$monto_cheque=str_replace(",",".",$monto_cheque);
			
							$saldo_total=($saldo_actual)+($monto_cheque);
								
						
								}
									
		
 //----------------------revirtiendo proceso de pagado en tablas de presupuesto-------------------------------------------------------------------------------------
 //////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
				/*$ordenes=$row->fields("ordenes");
				$numero=$row->fields("numero_cheque");
				$ord1=str_replace("{","",$ordenes);
				$ord2=str_replace("}","",$ord1);
				$vector = split(",",$ord2);
				$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;*/
				$vector = split( ",", $_POST['tesoreria_cheque_anular_pr_ordenes']);
				$contadores=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
if(($_POST['tesoreria_cheque_anular_pr_ordenes']!=null)&&($_POST['tesoreria_cheque_anular_pr_ordenes']!=0))	
{
/////////////////////		
//////////////////////////////////independientemente de la opcion q se elija  al anular... ////////////////////////////////////////////////////////////////////////////////////////////// 
	
							while($i < $contadores)
							{///////////consultando las orden de pago
						//	echo($i." < ".$contadores);
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
																$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;
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
														$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
														$p_iva_total=$p_iva_total+$p_iva_factura;
//echo($p_iva_total."-");
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
															where
																\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
															group by
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
																";
															$row_orden_compra=& $conn->Execute($sql);
															//die($sql);
														while(!$row_orden_compra->EOF)
														{
														///////////////////////////////////////////////
															$sql_compromiso="SELECT 
																					monto,cantidad,fecha_orden_compra_servicio
																				FROM 
																					\"orden_compra_servicioE\"
																				INNER JOIN
																					\"orden_compra_servicioD\"
																				ON
																					\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																			where	\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
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
						$causados=$causados+$causado;
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
												$mes_orde=substr($fecha_ord,5,2);
												$ano_orde=substr($fecha_ord,0,4);

												$resumen_suma = "
																	SELECT  
																		   (monto_pagado[".$mes_orde."]) AS monto
																	FROM 
																		\"presupuesto_ejecutadoR\"
																	WHERE
																		id_unidad_ejecutora='$unidad_ejecutora'
																	AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																	AND
																		partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																	$where
																	";
													$rs_resumen_suma=& $conn->Execute($resumen_suma);
													
													if (!$rs_resumen_suma->EOF) 
														$monto_pagado = $rs_resumen_suma->fields("monto");
													else
														$monto_pagado = 0;
														//$monto_total = $monto_pagado + $monto_restar;
														
														$monto_total = $monto_pagado - $causado;
														
														//echo($monto_total." = ".$monto_pagado." + ".$causado);
													//	echo($monto_total." = ".$monto_pagado." + ".$monto_restar);
														$actu1=
															"UPDATE 
																	\"presupuesto_ejecutadoR\"
	
															SET 
																	monto_pagado[".$mes_orde."]= '$monto_total'
															WHERE
																	(id_organismo = ".$_SESSION['id_organismo'].") 
																AND
																	(id_unidad_ejecutora = '$unidad_ejecutora') 
																AND 
																	(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																AND 
																	(ano = '".$ano_orde."')
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
																INSERT INTO \"presupuesto_ejecutadoD\"(
																		id_tipo_documento, 
																		id_organismo, 
																		ano, 
																		numero_documento, 
																		numero_compromiso, 
																		fecha_compromiso, 
																		ultimo_usuario, 
																		fecha_modificacion
																	)VALUES (
																		'6', 
																		".$_SESSION['id_organismo'].",
																		'".date("Y")."', 
																		'".$n_cheque."', 
																		'".$numero_compromiso."', 
																		'".date("Y-m-d H:i:s")."', 
																		".$_SESSION['id_usuario'].",
																		'".date("Y-m-d H:i:s")."'
																	)";
//////////////////////////////////causando el iva/////////////////////////////////////////////////////////////////////////
										$resumen_suma = "
														SELECT  
															   (monto_causado[".$mes_orde."]) AS monto
														FROM 
															\"presupuesto_ejecutadoR\"
														WHERE
																	(id_unidad_ejecutora = '$unidad_ejecutora') 
																AND 
																	(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																AND
															partida = '403'  AND	generica = '18'  AND	especifica = '01'  AND	sub_especifica = '00'
														$where
														";
										//hodie($resumen_suma);		
										$rs_resumen_suma=& $conn->Execute($resumen_suma);
									
										if (!$rs_resumen_suma->EOF) 
											$monto_causado_iva = $rs_resumen_suma->fields("monto");
										
										else
											$monto_causado_iva = 0;
											//$monto_total = $monto_causado + $monto_restar;	
											$monto_total=$monto_causado_iva-$p_iva_total;
											$sql_iva="UPDATE 
													\"presupuesto_ejecutadoR\"
											SET 
													monto_causado[".$mes_orde."]= '$monto_total'
											WHERE
													(id_organismo = ".$_SESSION['id_organismo'].") 
												AND
													(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
												AND 
													(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
												AND 
													(ano = '".$ano_orde."')
												AND
													partida = '403'  
												AND	
													generica = '18'
												AND	
													especifica = '01'  
												AND
													sub_especifica = '00'
												$where;";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////																														
								$partidas=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
									 if($i_fact==0) 
										 	$partidas_vector=$partidas;
										 else
										    {
												$partidas_vector=$partidas_vector.";".$partidas;
												$vectorp = split( ";", $partidas_vector);
												$contadorpart=count($vectorp);  ///$_POST['covertir_req_cot_titulo']
												$coco=0;
												while($coco<$contadorpart)
												{
													if($partidas==$vectorp[$coco])	
													{
														$actu1="";
													}
													$coco=$coco+1;
											}

											}
							    	$actu2=$actu2.";".$actu1;
									$row_orden_compra->MoveNext();
									}//fin del mientras
									//echo("-".$total_facturas_comprometidas."-");
								$ext=$ext+1;	
							}//fin del else
							$i_fact++;
									}						
							$i++;							
							}		
	 		           				            	
									 $fact_ord1=str_replace(".","",$_POST[tesoreria_cheque_anular_pr_monto_pagar]);
									 $fact_ord=str_replace(",",".",$fact_ord1);
								if($ext!=0)
								{
									$actu2=$actu2.";".$sql_iva;

	       				       //  die($actu2);
									if($total_facturas_comprometidas<$fact_ord)
										  {
											//die($fact_ord."<".$total_documento);
											die("El monto a pagar no puede superar al monto comprometido");
										  }
										
										if (!$conn->Execute($actu2))
										die ('Error al Actulizar: '.$conn->ErrorMsg().$actu2);
								
								}
								//	echo($total_facturas_comprometidas."<".$fact_ord);
						    	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
							
///////////////////////////////////////////////////////////////////////////////////////////////////		
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$sql_contab="UPDATE cheques
					SET
						
						fecha_anula='".date("Y-m-d H:i:s")."',
						usuario_anula=".$_SESSION['id_usuario'].",
						estatus='5',
						ordenes='{0}'
					WHERE
						cheques.id_cheques='$_POST[tesoreria_cheque_anular_pr_id_cheque]' 
					AND
						cheques.numero_cheque='$_POST[tesoreria_cheque_anular_pr_n_cheque]'
					AND
						cheques.secuencia='$_POST[tesoreria_cheque_anular_pr_secuencia]'
					AND
						cheques.id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'	
					AND	
						cheques.cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
					AND	
						cheques.id_organismo=".$_SESSION["id_organismo"].";
					UPDATE banco_cuentas 
						 SET
						 	saldo_actual='$saldo_total'
						WHERE cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
						AND
							id_organismo=$_SESSION[id_organismo]		
					";		
				if (!$conn->Execute($sql_contab)) 
				//die($sql_contab);
					die ('Error al Actualizar: '.$conn->ErrorMsg());
					//die ("NoActualizo");
		}																	
		else
			$bloqueado=true;
//echo($sql_pago);
//if (!$conn->Execute($sql)||$bloqueado){
//die($sql);
if ($bloqueado){	
	echo (($bloqueado)?$msgBloqueado:'Error al Anular Cheque: '.$conn->ErrorMsg().'<br />');
	}
	else
		{
				if($_POST['tesoreria_cheque_anular_pr_ordenes']=="")
				{
					die ('ANULADO');
				}	
				$sql_orden="SELECT 
						count(orden_pago.id_orden_pago) 
						FROM 
							orden_pago
						INNER JOIN 
							organismo 
						ON 
							orden_pago.id_organismo =organismo.id_organismo
						INNER JOIN 
							documentos_cxp 
						ON 
							orden_pago.orden_pago=documentos_cxp.orden_pago 
						WHERE
							orden_pago.cheque='$_POST[tesoreria_cheque_anular_pr_n_cheque]'
						AND
							orden_pago.secuencia='$_POST[tesoreria_cheque_anular_pr_secuencia]'
						AND
							orden_pago.id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'	
						AND	
							orden_pago.cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
						AND	
							orden_pago.id_organismo=".$_SESSION["id_organismo"]."	
						";
						//die($sql_orden);
			$row_orden= $conn->Execute($sql_orden);
		
			if(!$row_orden->EOF)
			{
					
					
					
					
					$sql_pago=
						"UPDATE orden_pago
						SET
								cheque='0',
								id_banco='0',
								cuenta_banco='0',
								secuencia='0'
						WHERE 
							orden_pago.cheque='$_POST[tesoreria_cheque_anular_pr_n_cheque]'
						AND
							orden_pago.secuencia='$_POST[tesoreria_cheque_anular_pr_secuencia]'
						AND
							orden_pago.id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'	
						AND	
							orden_pago.cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
						AND	
							orden_pago.id_organismo=".$_SESSION["id_organismo"]."	
						";
						//die($sql_pago);	 
				if (!$conn->Execute($sql_pago))
						echo ('Error al anular');
						
				else
					die ('ANULADO');
			}
			
		}	
?>