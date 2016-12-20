<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//EN ESTE PROCESO SE ASIGNAN LOS NUMEROS DE COMPROBANTES A LAS FACTURAS SELECCIONADAS...
$id_doc=$_POST['cuentas_por_pagar_db_id'];
$id_proveedor=$_POST['cuentas_por_pagar_db_proveedor_id'];
$vector=$_POST['cuentas_por_pagar_db_facturas_lista2'];
						$facturas=split(",",$vector);
						sort($facturas);
						if($facturas!="")
						{
							$contador=count($facturas);
							$is=0;
							while($is<$contador)
							{
							   //////////////////////////
							   $sql_documentos=
							   					" SELECT
														numero_compromiso,
														fecha_documento,
														tipo_documentocxp,
														amortizacion
												  FROM
												  		documentos_cxp
												  WHERE
												 		id_documentos='$facturas[$is]'					
												
												";
								//echo($sql_documentos);
								//$rs_factura =& $conn->Execute($sql_documentos);
								$rs_factura=& $conn->Execute($sql_documentos);

								if(!$rs_factura->EOF)
								{
									$fecha_documento=$rs_factura->fields("fecha_documento");
									$numero_compromiso=$rs_factura->fields("numero_compromiso");
									//genero las variables dia/mes/ano
									$dia=substr($fecha_documento,8,2);
									$mes=substr($fecha_documento,5,2);
									$ano=substr($fecha_documento,0,4);
									//echo($dia."/".$mes."/".$ano);
									//die("fecha");
									//
															//PROCESO EN QUE VERIFICO QUE TIPO DE DOCUMENTO ES YA QUE SI ES ANTICIPÒ NO DEBE CAUSARCE
															if($_POST[cuentas_por_pagar_db_anticipos]==$rs_factura->fields("tipo_documentocxp"))//si es anticipo
															{	
																$tipo_cierre="anticipo";
																$turnos=3;	
															}		
															else
																if($_POST[cuentas_por_pagar_db_anticipos]!=$rs_factura->fields("tipo_documentocxp"))//si es anticipo
															{	
																$tipo_cierre="otro";
																$turnos=5;
															}	
															if((($rs_factura->fields("tipo_documentocxp"))==$tipos_fact)&&($rs_factura->fields("amortizacion")!=0))
															{
																$tipo_cierre="anticipo_factura";
																$turnos=5;	
															}
									
									//
									//si tipo de cierre es diefrente a anticipo si reliza el causado
									if ($tipo_cierre!="anticipo")
									{
									//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										///se quito la relacion con requisicion 29/02/2012
										//cualquier cambio entre las tablas modificar aqui:20/09/2012
										$sql="SELECT 
															tipo,
															id_orden_compra_servicioe as id,
															\"orden_compra_servicioE\".id_unidad_ejecutora, 
															\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
															\"orden_compra_servicioE\".id_accion_especifica,
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
														GROUP BY
															tipo,
										
															id_orden_compra_servicioe ,
										
															\"orden_compra_servicioE\".id_unidad_ejecutora, 
										
															\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
										
															\"orden_compra_servicioE\".id_accion_especifica,
										
															\"orden_compra_servicioE\".tipo,
															partida, 
										
															generica, 
										
															especifica, 
										
															subespecifica		
															";
													$row_orden_compra=& $conn->Execute($sql);
													//die($sql);
													$conta_tor=0;
													while(!$row_orden_compra->EOF)
													{
														//
														///comprobando que el monto de la factura no sea mayor al compromiso ...comprobandolo nuevamente
														///ya que al relacionarse con un numero de compromiso se comprueba esto
														////BUSCANDO TOTALES DE LAS ORDENES  DE COMPRA/SERVICIO COMPROMETIDAS
														$compromiso=$numero_compromiso;
														$sql_cmp="
																	SELECT 
																		\"orden_compra_servicioE\".numero_compromiso, 
																		\"orden_compra_servicioE\".numero_precompromiso,
																		\"orden_compra_servicioD\".cantidad,
																		\"orden_compra_servicioD\".monto,
																		\"orden_compra_servicioD\".impuesto
																		
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
																		\"orden_compra_servicioE\".numero_compromiso='$compromiso'
																	AND 
																						(\"orden_compra_servicioD\".ano = '".date("Y")."')
																					AND
																						\"orden_compra_servicioD\".partida = '".$row_orden_compra->fields("partida")."'  
																					AND	
																						\"orden_compra_servicioD\".generica = '".$row_orden_compra->fields("generica")."'
																					AND	
																						\"orden_compra_servicioD\".especifica = '".$row_orden_compra->fields("especifica")."'  
																					AND
																						\"orden_compra_servicioD\".subespecifica = '".$row_orden_compra->fields("subespecifica")."'	
																		";
														//	die($sql_cmp);
															$row_orden_compra_datos_monto=& $conn->Execute($sql_cmp);
															$total_renglon=0;
															//ciclo para calcular cuanto es el total del renglon de la orden compra/servicio
															while(!$row_orden_compra_datos_monto->EOF)
															{
															//
																$total=$row_orden_compra_datos_monto->fields("monto")*$row_orden_compra_datos_monto->fields("cantidad");
																$iva=$total*($row_orden_compra_datos_monto->fields("impuesto")/100);
																//$iva=0;
																$ivas=$ivas+$iva;
																$total_total=$total+$iva;
																$total_renglon=$total_renglon+$total_total;
																$total_renglon2=$total_renglon2+$total_renglon;
																//move next
																$row_orden_compra_datos_monto->MoveNext();
															//
															}//while(!$row_orden_compra_datos_monto->EOF)
														//
														////buscando la informacion para causar desde la tabla de detalle
														$partida=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
														$sql_doc_det="select monto,impuesto from doc_cxp_detalle where id_doc='$facturas[$is]' and partida='$partida'";
														//die($sql_doc_det);
														$row_doc_det=& $conn->Execute($sql_doc_det);
														if(!$row_doc_det->EOF)
														{
															//aqui si se va a causar in god we trust
															$causado=$row_doc_det->fields("monto");
															$imp=$row_doc_det->fields("impuesto");
															//	die($imp2);
															$causado_iva=(($causado*$imp)/100);
															$causado=$causado+$causado_iva;
															//
															$tipo=$row_orden_compra->fields("tipo");
															$partida_presu=$row_orden_compra->fields("partida");
															//
															//29/02/2012: se cambio nuevamente este proceso relacionandose con la orden_compra asi estaba inicialmente y fue mandando a cambiar en agosto de 2011:otro proceso revertido:S lo dejo en comentario por si acaso NO BORRAR
															/*if($row_orden_compra->fields("id_proyecto")!='')
															{
															$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto").""; 
															}else
															$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_accion_centralizada").""; */
															//
															if($tipo=='1')
															{
																$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
															}else
																if($tipo=='2')
															$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
															// selecciono de la tabla de presupuesto_ejecutadoR el monto que se ha causado por partida para sumar
															//debido a los multiples cambios de las tablas de presupuesto para el 20/09/2012 el proceso de enlace se hace asi,no se si se vlverá a cambiar pues no es la primera vez.
																$resumen_suma = "
																SELECT  
																	   (monto_causado[".$mes."]) AS monto
																FROM 
																	\"presupuesto_ejecutadoR\"
																WHERE
																	id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
																AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																AND
																	partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																$where
														";
																//die($resumen_suma);		
																$rs_resumen_suma=& $conn->Execute($resumen_suma);
															
																if (!$rs_resumen_suma->EOF) 
																	$monto_causado = $rs_resumen_suma->fields("monto");
																
																else
																	$monto_causado = 0;
															//
														//asigno el monto a causar
															$monto_total=round($monto_causado,2)+round($causado,2);
														//
														//aqui causo
															$actu1=
																	"UPDATE 
																			\"presupuesto_ejecutadoR\"
																	SET 
																			monto_causado[".$mes."]= '$monto_total'
																	WHERE
																			(id_organismo = ".$_SESSION['id_organismo'].") 
																		AND
																			(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
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
																				estatus='3'
																		WHERE
																				numero_compromiso='$numero_compromiso'
																		";	
														//
															if (!$conn->Execute($actu1))
																die ('Error al CAUSAR: '.$conn->ErrorMsg().$actu1);
																$monto_total=0;
																$monto_causado=0;
																$causado=0;
														}//fin de if(!$row_doc_det->EOF)
														
													  ////
													  //concateno las partidas a causar...
													  	$concad=$concad.";".$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
													  //
													//move next	
													$row_orden_compra->MoveNext();
													}//while(!$row_orden_compra->EOF)
										
									//
								}//fin de if ($tipo_cierre!="anticipo")
							}//fin de if(!$rs_factura->EOF)
																
								 //////////////////////////
							   $is=$is+1;
						}//fin de while($is<$contador)--- ciclo que consulta todas las facturas que estan realcionadas a ese comprobante contable....
					}//fin de 	if($facturas!="")
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
die("cerrado");
?>