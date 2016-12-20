<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
include_once('../../../../controladores/numero_to_letras.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$check = $_POST["tesoreria_cheques_db_itf"]; 
//
$fecha2 = date("Y-m-d H:i:s");
$causado=0;
$causados=0;
$ext=0;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_tesoreria WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF)
{
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
//////////////////////////////////guardando el documento a pagar en la tabla de cheques

///////////////////////////////////////////////////////////////////////////////////////////
/*if(($cerrado!="ano")||($cerrado!="mes"))
{
*/			//DATOS PARA CARGAR DOCUMENTOS..
			$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
			$rs_tipos_ant =& $conn->Execute($sql_ant);
			$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
			//
			$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
			$rs_tipos_fact =& $conn->Execute($sql_fact);
			$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
			 //
			//------------------------------------------------------------------------------------------------------
			
			///////////////////////////////////// verificando si el monto pagado no excede a lo comprometido/////////////////////////////////////
			
			//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			
			
							$monto_cheque=$_POST[tesoreria_cheques_db_monto_pagar];
							$monto_cheque= str_replace(".","",$monto_cheque);
							$monto_cheque=str_replace(",",".",$monto_cheque);
			
							$saldo_total=($saldo_actual)-($monto_cheque);
							//die($saldo_total);
								if($saldo_total<'0')
								{
									$astre="0";
									//die("no_disponible_saldo");
								}
								/*else
								{
									///
									$sql = "UPDATE banco_cuentas 
									 SET
										saldo_actual='$saldo_total'
									WHERE cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND
										id_organismo=$_SESSION[id_organismo]
												";
								
									if (!$conn->Execute($sql))
									{
										die ($sql);
									}
								////
								}*/
									
			//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------			
			//------------------- VERIFICANDO SI LAS ORDENES DE PAGO NO FUERON CANCELADAS POR OTROS CHEQUES//----------------------------//
							//die($_POST['tesoreria_pago_db_ordenes_pago']);
							$vector = split( ",", $_POST['tesoreria_pago_db_ordenes_pago'] );
							$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
							$i=0;
							while($i < $contador)
							{
									$sql_orden="SELECT * 
												FROM
													orden_pago
												WHERE
													(orden_pago.id_orden_pago='$vector[$i]')
												
												";
							
									//echo($sql_orden);
									$i=$i+1;	
								if (!$conn->Execute($sql_orden))die ('No_relacion');
									$row_verificacion2= $conn->Execute($sql_orden);
							}		
			/////////////////////////////////guardando el documento/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////¡
					/*	$monto = str_replace(".","",$_POST[tesoreria_cheques_manual_orden_db_monto_pagar]);
						$monto_pagar=str_replace(",",".",$monto);
						$opcion=$_POST[tesoreria_pago_pr_op_oculto_orden];
						if($opcion=='1')
						{
										$sql = "	
														INSERT INTO 
															cheques
															(
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
																id_organismo,
																fecha_cheque,
																ordenes,
																usuario_cheque,
																fecha_ultima_modificacion,
																ultimo_usuario,
																porcentaje_islr,
																fecha_firma,
																base_imponible,
																contabilizado,
																estado,
																estado_fecha,
																cedula_rif_beneficiario,
																nombre_beneficiario,
																sustraendo
													
															) 
															VALUES
															(
																'$_POST[tesoreria_cheques_manual_orden_db_banco_id_banco]',
																'$_POST[tesoreria_cheques_manual_orden_db_n_cuenta]',
																'$n_precheque',
																'2',
																'$_POST[tesoreria_cheques_manual_orden_pr_proveedor_id]',
																'".str_replace(",",".",$monto)."',
																'$_POST[tesoreria_cheques_manual_orden_db_concepto]',
																'1',
																'$_POST[tesoreria_cheques_manual_orden_db_comentario]',
																'$porcentaje',
																".$_SESSION["id_organismo"].",
																'".date("Y-m-d H:i:s")."',
																'{".$_POST[tesoreria_cheques_manual_orden_db_ordenes_pago]."}',
																".$_SESSION['id_usuario'].",
																'".date("Y-m-d H:i:s")."',
																".$_SESSION['id_usuario'].",
																'".str_replace(",",".",$islr)."',
																'".$firmas."',
																'$base',
																'0',
																'{0,0,0,0,0,0,0,0}',
																'{10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010}',
																'0',
																'0',
																$_POST[tesoreria_cheques_manual_pr_sustraendo_oculto]
																)";
								}
								else
								if($opcion=='2')
								{
										$sql = "	
														INSERT INTO 
															cheques
															(
																id_banco,
																cuenta_banco,
																numero_cheque,
																tipo_cheque,
																cedula_rif_beneficiario,
																nombre_beneficiario,	
																monto_cheque,
																concepto,
																estatus,
																comentarios,
																porcentaje_itf,
																id_organismo,
																fecha_cheque,
																ordenes,
																usuario_cheque,
																fecha_ultima_modificacion,
																ultimo_usuario,
																porcentaje_islr,
																fecha_firma,
																base_imponible,
																contabilizado,
																estado,
																estado_fecha,
																id_proveedor,
																sustraendo
													
															) 
															VALUES
															(
																'$_POST[tesoreria_cheques_manual_orden_db_banco_id_banco]',
																'$_POST[tesoreria_cheques_manual_orden_db_n_cuenta]',
																'$n_precheque',
																'2',
																'$_POST[tesoreria_cheque_manual_orden_pr_empleado_codigo]',
																'$_POST[tesoreria_cheque_manual_orden_pr_empleado_nombre]',											
																'".str_replace(",",".",$monto)."',
																'$_POST[tesoreria_cheques_manual_orden_db_concepto]',
																'1',
																'$_POST[tesoreria_cheques_manual_orden_db_comentario]',
																'$porcentaje',
																".$_SESSION["id_organismo"].",
																'".date("Y-m-d H:i:s")."',
																'{".$_POST[tesoreria_cheques_manual_orden_db_ordenes_pago]."}',
																".$_SESSION['id_usuario'].",
																'".date("Y-m-d H:i:s")."',
																".$_SESSION['id_usuario'].",
																'".str_replace(",",".",$islr)."',
																'".$firmas."',
																'$base',
																'0',
																'{0,0,0,0,0,0,0,0}',
																'{10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010}',
																'0',
																$_POST[tesoreria_cheques_manual_pr_sustraendo_oculto]
																)";
								}
						
							/*}
							else
								//echo("NoRegistro");
							die($sql);*/
							/*if (!$conn->Execute($sql)) {
							die ('Error al Actualizar: '.$conn->ErrorMsg());
									die($sql);}
								//die ('Error al RegistrarHHH: '.$sql);
							else
							{*///----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
								/*	$vector = split( ",", $_POST['tesoreria_cheques_manual_orden_db_ordenes_pago'] );
									
									$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
									$i=0;
									while($i < $contador)
									{
											$sql_orden="UPDATE orden_pago
														SET
																cheque='$n_precheque',	
																id_banco='$_POST[tesoreria_cheques_manual_orden_db_banco_id_banco]',
																cuenta_banco='$_POST[tesoreria_cheques_manual_orden_db_n_cuenta]',
																estatus='2'
																
														WHERE
																(orden_pago.id_orden_pago='$vector[$i]')
																	
														";	
									
											//echo($sql_pago);
											$i=$i+1;	
											if (!$conn->Execute($sql_orden)) 
														die ('Error al registrar: '.$sql_orden);
					//									die ('Error al Actualizar: '.$conn->ErrorMsg());
															//die ("NoActualizo");
									}		*/
				//////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
							$vector = split( ",", $_POST['tesoreria_pago_db_ordenes_pago'] );
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
																														//die("entreo");

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
																				\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
																			$row_orden_compra=& $conn->Execute($sql);
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
																//die($fecha_ord);
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
																				//	die($resumen_suma);
																	$rs_resumen_suma=& $conn->Execute($resumen_suma);
																	
																	if (!$rs_resumen_suma->EOF) 
																		$monto_pagado = $rs_resumen_suma->fields("monto");
																	else
																		$monto_pagado = 0;
																		//$monto_total = $monto_pagado + $monto_restar;
																		
																		$monto_total = $monto_pagado + $causado;
																		
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
																				/*
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
																					)"
																				
																				*/	
														//////////////////////////////////causando el iva/////////////////////////////////////////////////////////////////////////
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
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////														
											
														  $actu=$actu.";".$actu1;
														$ext=$ext+1; 
													$row_orden_compra->MoveNext();
													}//fin del mientras
													//echo("-".$total_facturas_comprometidas."-");
									}//fin del else	
												$i_fact++;
													}						
											$i++;							
											}		
																	
													 $fact_ord1=str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
													 $fact_ord=str_replace(",",".",$fact_ord1);
				
													/*echo($total_facturas_comprometidas."+".$fact_ord);;*/
								//	}				
							//	die($actu);
													
												if($ext!=0)
												{	if($total_facturas_comprometidas<$fact_ord)
														  {
															//die($fact_ord."<".$total_documento);
															die("El monto a pagar no puede superar al monto comprometido");
														  }
														  												//die($actu);

												$actu=$actu.";".$sql_iva;	
													if (!$conn->Execute($actu))
													die ('Error al Actulizar: '.$conn->ErrorMsg());
												}
								
																
									
			///////////////////////////////////////////////////////////////////////////////////////////////////		
			/*$sql_orden=" UPDATE orden_pago
							SET
									cheque='$n_cheque',
									secuencia='$secuencia'
							WHERE
								id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND	
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
							AND
								cheque='$_POST[tesoreria_cheques_db_n_precheque]'
							";	
					//die($sql_orden);					
					if (!$conn->Execute($sql_orden)) 
						die ('Error_impresion' );*/
					//die ('Error al modificar orden de pago: '.$sql_orden);
				
				
					
				if($tipo==1)
				{
					$sql_proyecto="SELECT id_proyecto,codigo_proyecto,nombre FROM proyecto WHERE id_proyecto='$accion_central'";
					$row_proyecto=& $conn->Execute($sql_proyecto);
					$proyecto=$row_proyecto->fields("codigo_proyecto");
				}else
				if($tipo==2)
				{
					$sql_proyecto="SELECT codigo_accion_central,denominacion FROM accion_centralizada WHERE id_accion_central='$accion_central'";
					$row_proyecto=& $conn->Execute($sql_proyecto);
					$proyecto=$row_proyecto->fields("codigo_accion_central");
				}
				
				$sql_ejec="SELECT id_unidad_ejecutora, nombre FROM unidad_ejecutora WHERE id_unidad_ejecutora='$unidad_ejecutora'";
				$row_ejec=& $conn->Execute($sql_ejec);
				$unidad_ejec=$row_ejec->fields("nombre");
				$responce=$unidad_ejec."*".$proyecto."*".$partida;
				die($responce);
			
				//die($row_emitido2->fields("numero_cheque"));
/*				 }
}else
die("cerrado");	*/			 
?>