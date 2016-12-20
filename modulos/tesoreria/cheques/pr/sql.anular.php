<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$fecha2 = date("Y-m-d H:i:s");
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$ext=0;
$n_cheque=$_POST['tesoreria_cheque_anular_pr_n_cheque'];
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
*** SE TUVO QUE MODIFICAR NUEVAMENTE ESTE SCRIPT FECHA 29/02/2012 CAMBIANDO LAS RELACIONES CON LAS TABLAS REQUISICION ENCABEZADO  A ORDEN ENCABEZADO, PARA AGOSTO DE 2011 ESTABA ASI PERO DEBIDO A INDICACIONES DADAS PARA CREA UN PROCESO QUE PERMITIERA CREAR ORDENES A CONTABILIDAD (PROGRAMA PRESUPUESTARIO) SE REALIZÓ EL CAMBIO Y AHORA DEBE REVERTIRSE EN TODOS LOS PROGRAMAS DE LOS MODULOS DE CXP Y TESORERIA...
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
				substring(cheques.ordenes::character varying,2,length(cheques.ordenes::character varying)-2 ) as ordenes,
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
	$id_cheqes=$_POST[tesoreria_cheque_anular_pr_id_cheque];
////////////////////////////////////////////////////////////////////////////////////////////////////////////
$vector = split( ",", $row->fields("ordenes"));

								//sort($vector);
								$contador=count($vector);  
								$i=0;
								//echo($vector);
								while($i < $contador)
								{
										
									
									
									$orden=$vector[$i];
												$Sql2="
														SELECT 
															documentos_cxp.numero_documento,
															documentos_cxp.monto_bruto,
															documentos_cxp.monto_base_imponible,
															documentos_cxp.porcentaje_iva,
															documentos_cxp.porcentaje_retencion_iva,
															documentos_cxp.porcentaje_retencion_islr,
															documentos_cxp.tipo_documentocxp,
															documentos_cxp.amortizacion,
															documentos_cxp.retencion_ex1,
															documentos_cxp.retencion_ex2,
															documentos_cxp.monto_base_imponible2,
															documentos_cxp.porcentaje_iva2 ,
															documentos_cxp.retencion_iva2
														FROM 
															documentos_cxp
														WHERE
															documentos_cxp.orden_pago='$orden'
											";//die($Sql2);
											$row_orden=& $conn->Execute($Sql2);
											$monto_bruto=$row_orden->fields("monto_bruto");
											$iva=$row_orden->fields("porcentaje_iva");
											$ret1=$row_orden->fields("retencion_ex1");
											$ret2=$row_orden->fields("retencion_ex2");
											if(($retislr2==0)||($retislr2=='0.00'))
											{
											$retislr2=$row_orden->fields("porcentaje_retencion_islr");
											}
											
											$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
											//-/si es factura con anticipo
											if(($row_orden->fields("tipo_documentocxp")==$tipos_fact)&&($row_orden->fields("amortizacion")!='0'))
											{
												$monto_bruto=$row_orden->fields("monto_bruto");
												$amort=$row_orden->fields("amortizacion");
												$base_imponible=$monto_bruto;//+$amort
												$islr=((($base_imponible)*($retislr2))/100);
											}else
											{
													$base_imponible=$row_orden->fields("monto_base_imponible");
													$islr=((($monto_bruto)*($retislr2))/100);
											}
											//----calculos
											
											if($row_orden->fields("monto_base_imponible2")!='0')
											{
											$base2=$row_orden->fields("monto_base_imponible2");
											$iva2=$row_orden->fields("porcentaje_iva2");
											$retiva2=$row_orden->fields("retencion_iva2");
											
											$base_iva=(($base_imponible)*($iva))/100;
											$base_iva2=(($base2)*($iva2))/100;
											
											
											$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
											$monto_restar2=(($base_iva2)*($retiva2))/100;
											
											$bruto_iva=($monto_bruto)+($base_iva+$base_iva2)-($monto_restar+$monto_restar2);//
											//-
											//die($monto_bruto);
											
											if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
											{
											
											$islr=$islr-138;
											
											}
											//die(islr);	
											$retenciones=$ret1+$ret2;
											$monto_def=($bruto_iva)-($islr+$retenciones);
											//	echo($monto_def);
											$total_orden=$total_orden+$monto_def;
											$monto_def=0;
											$islr=0;
											$base_imponible=0;
											$monto_bruto=0;
											//die($monto_det);
											}
											else
											{
											$base_iva=(($base_imponible)*($iva))/100;
											if($porcentaje_iva_ret!='0')
											{
											$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
											$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);//
											}else
											$bruto_iva=($monto_bruto)+($base_iva);
											//-
											//die($monto_bruto);
											
											if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
											{
											
											$islr=$islr-138;
											
											}
											//die(islr);	
											$retenciones=$ret1+$ret2;
											$monto_def=($bruto_iva)-($islr+$retenciones);
											//	echo($monto_def);
											$total_orden=$total_orden+$monto_def;
											$monto_def=0;
											$islr=0;
											$base_imponible=0;
											$monto_bruto=0;
											
											}
											$row_orden->MoveNext();	
											
										
											$pagar=$pagar+$total_orden;
											$total_orden=0;
											//$retislr2=0;
											$retenciones=0;
											$ret1=0;
											$ret2=0;
														
											//$retislr2=0;
								           ///////////////////////actualizando ordenees
										/*   if($contador==1)
										   {
												$pagar=$n_saldo;  
											}*/
										
								           ///////////////////////actualizando ordenees
										  //quite del sql:cheque='0',id_banco='0',cuenta_banco='0',saldo='$pagar',
 
											
										   $sql_ordenes_pago="
										   						UPDATE
																		orden_pago
																SET
																		
																		
																		secuencia='0'
																where
																		orden_pago='$orden'
										   ";
								$pagar=0;
								if($i==0)
								$sql_orden1=$sql_ordenes_pago;
								else
								$sql_orden1=$sql_orden1.";".$sql_ordenes_pago;
								$i=$i+1;
								}//fin del while

///////////////////////////////////////////////////////////////////////////////////////////////////////////	
////limpiando la tabla de orden_cheque
			//die($sql_orden1);
			if (!$conn->Execute($sql_orden1))
			echo ('Error al anular'.$sql_orden1);
									$sql_limpiar_orden="DELETE FROM orden_cheque where id_cheque='$id_cheqes'";
								//	die($sql_limpiar_orden);
									if (!$conn->Execute($sql_limpiar_orden)){die("error limpiado tabla orden_cheque");}
									
/////////////////////////////////////////		
		
		if($row->fields("contabilizado")==1)					
				die("integrado");
			else
				$contabilizado=$row->fields("contabilzado");
		if (strlen($contabilizado)==0)
		{
			$contabilizado=0;
		}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			$fecha2=$row->fields("fecha_cheque");
			$mes_orde=substr($fecha2,5,2);
			$ano_orde=substr(fecha2,0,4);
				
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
				$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
if(($_POST['tesoreria_cheque_anular_pr_ordenes']!=null)&&($_POST['tesoreria_cheque_anular_pr_ordenes']!=0))	
{
/////////////////////		
//////////////////////////////////independientemente de la opcion q se elija  al anular... ////////////////////////////////////////////////////////////////////////////////////////////// 
	
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
								//echo($contador_fact);
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
																									$monto_factura=$monto_factura+$p_iva_total;
																										$total_facturas_comprometidas=round($total_facturas_comprometidas,2)+round($monto_factura,2);
																								
																									//echo($monto_factura."-");
																								//echo($monto_factura."-");
																								/*$total_documento=$monto_restar; 
																								$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento; 
																								$fact_ord=($total_compromiso)-$lo_q_qeda_fact;*/
																								}	
												//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
																		//// seleccionando los datos sobre las partidas
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
																		//	die($sql);
																		while(!$row_orden_compra->EOF)
																		{
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
																									}//fin del mientras de row compromiso																				
																									
											//////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
											/*************************/
											//proceso para sacara contra que va el causado
											$partidas=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
											$sql_doc_det="select sum(monto) as causado,partida,impuesto from doc_cxp_detalle
															inner join
															documentos_cxp
															ON doc_cxp_detalle.id_doc=documentos_cxp.id_documentos
															where partida='$partidas'
															and
															documentos_cxp.numero_compromiso='$numero_compromiso'
															and
															id_doc='$facturas[$i_fact]'
															group by
															partida,
															impuesto";
										
													$row_doc_det=& $conn->Execute($sql_doc_det);
												//	die($sql_doc_det);
													if(!$row_doc_det->EOF)
													{
														$causado=$row_doc_det->fields("causado");
														$impuesto=$row_doc_det->fields("impuesto");
														$iva_causado=($causado*$impuesto)/100;
														$causado2=round($causado,2)+round($iva_causado,2);
														$causados=round($causados,2)+round($causado2,2);
														//echo($causado);
													
											/****************************/
																$partida=$row_orden_compra->fields("partida");
																$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
																$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
																$pre_orden=$row_orden_compra->fields("id_accion_especifica");
																$tipo=$row_orden_compra->fields("tipo");
																/*if($row_orden_compra->fields("id_proyecto")!='')
																{
																	$accion_central=$row_orden_compra->fields("id_proyecto");
																	$where="AND id_proyecto = '$accion_central'";
																	$tipo=1; 
																}else
																{
																	$accion_central=$row_orden_compra->fields("id_accion_centralizada");
																	$where="AND id_accion_centralizada ='$accion_central'"; 
																	$tipo=2;
																}*/
															//	die($fecha2);
															//
																if($tipo=='1')
																{
																	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
																}else
																	if($tipo=='2')
																$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada")."";
																$mes_orde2=substr($fecha_ord,5,2);
																$ano_orde2=substr($fecha_ord,0,4);
				
																//die($mes_orde);
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
																					//die($resumen_suma);
																	$rs_resumen_suma=& $conn->Execute($resumen_suma);
																	
																	if (!$rs_resumen_suma->EOF) 
																		$monto_pagado = $rs_resumen_suma->fields("monto");
																	else
																		$monto_pagado = 0;
																		//$monto_total = $monto_pagado + $monto_restar;
																		//$causado=round($causado,2)+round($p_iva_total,2);
																		$monto_total =round($monto_pagado,2)-round($causado2,2);
																		$monto_total=round($monto_total,2);
																		//echo($monto_total." = ".$monto_pagado." + ".$causado2);
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
		/*												//////////////////////////////////causando el iva/////////////////////////////////////////////////////////////////////////
														$resumen_suma = "
																		SELECT  
																			   (monto_causado[".$mes_orde."]) AS monto
																		FROM 
																			\"presupuesto_ejecutadoR\"
																		WHERE
																					(id_organismo = ".$_SESSION['id_organismo'].") 
																				AND
																					(id_unidad_ejecutora = '$unidad_ejecutora') 
																				AND 
																					(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																				AND 														
																					partida = '403'  AND	generica = '18'  AND	especifica = '01'  AND	sub_especifica = '00'
																		$where
																		";
														//die($resumen_suma);		
														$rs_resumen_suma=& $conn->Execute($resumen_suma);
													
														if (!$rs_resumen_suma->EOF) 
															$monto_causado_iva = $rs_resumen_suma->fields("monto");
														
														else
															$monto_causado_iva = 0;
															//$monto_total = $monto_causado + $monto_restar;	
															$monto_total=$monto_causado_iva+$p_iva_total;
															//die($monto_total);
															$sql_iva="UPDATE 
																	\"presupuesto_ejecutadoR\"
															SET 
																	monto_causado[".$mes_orde."]= '$p_iva_total'
															WHERE
																	(id_organismo = ".$_SESSION['id_organismo'].") 
																AND
																	(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
																AND 
																	(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																AND 
																	(ano = '".date("Y")."')
																AND
																	partida = '403'  
																AND	
																	generica = '18'
																AND	
																	especifica = '01'  
																AND
																	sub_especifica = '00'
																$where;";
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		*/												
											if (!$conn->Execute($actu1))
										die ('Error al Actulizar: '.$conn->ErrorMsg().$actu1);
								
														  $actu=$actu.";".$actu1;
														//$ext=$ext+1; 
													}//fin de if doc detalle
													$row_orden_compra->MoveNext();
													
													}//fin del mientras
													//echo("-".$total_facturas_comprometidas."-");
									}//fin del else	
												$i_fact++;
												//if($i_fact==2)
												///die($actu);
													}						
											$i++;							
											}		
																	
													 $fact_ord1=str_replace(".","",$_POST[tesoreria_cheque_anular_pr_monto_pagar]);
													 $fact_ord=str_replace(",",".",$fact_ord1);
				
													/*echo($total_facturas_comprometidas."+".$fact_ord);
													die($actu);*/
							/*	if($ext!=0)
								{
									$total_facturas_comprometidas=$total_facturas_comprometidas+1;
									if($total_facturas_comprometidas<$fact_ord)
										  {
											die($total_facturas_comprometidas."<".$fact_ord);
											die("El monto a pagar no puede superar al monto comprometido");
										  }else
										  $total_facturas_comprometidas=$total_facturas_comprometidas-1;
								
								} */
								
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
			
						
					die ('ANULADO');
			
		}	
?>