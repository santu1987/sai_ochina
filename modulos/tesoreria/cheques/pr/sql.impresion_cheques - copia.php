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
			$Sql_firmas="SELECT 
							firmas_voucher.id_firmas_voucher,
							firmas_voucher.codigo_director_ochina,
							firmas_voucher.codigo_director_administracion,
							firmas_voucher.codigo_jefe_finanzas,
							firmas_voucher.codigo_preparado_por,
							firmas_voucher.comentarios,
							firmas_voucher.fecha_firma
						FROM 
							firmas_voucher
						INNER JOIN 
							organismo 
						ON 
							firmas_voucher.id_organismo = organismo.id_organismo
						WHERE
								firmas_voucher.estatus='1'
								";	 
			$row_firmas=& $conn->Execute($Sql_firmas);
			if($row_firmas->EOF)
			{
				die("firma_inactiva");
			}else
			$firmas=$row_firmas->fields("fecha_firma");
			///////////////////////////////////// verificando si el monto pagado no excede a lo comprometido/////////////////////////////////////
			//------ verificando si la cuenta y el banco tienen chequera creada
			$sql_cheque = "SELECT 
								id_cheques
						   FROM 
								cheques 
						   WHERE 
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
						   AND 
								 id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND 
								id_proveedor='$_POST[tesoreria_cheques_pr_proveedor_id]'
							AND
								numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
							AND
								cheques.tipo_cheque='1'			 
							AND		
								cheques.id_organismo=$_SESSION[id_organismo]		 
								 ";
								 
					if (!$conn->Execute($sql_cheque))die ($sql_cheque);
						$row_verificacion1= $conn->Execute($sql_cheque);
			//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			$sql_saldo_actual = "SELECT 
										 saldo_actual
								   FROM 
										 banco_cuentas
									WHERE
										cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
									AND
										estatus='1'		
								  ";
				$row_saldo_actual= $conn->Execute($sql_saldo_actual);
				if(!$row_saldo_actual->EOF)	
				{		
			
							$saldo_actual=$row_saldo_actual->fields("saldo_actual");
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
								else
								{
									///
									$sql = "UPDATE banco_cuentas 
									 SET
										saldo_actual='$saldo_total'
									WHERE cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND
										id_organismo=$_SESSION[id_organismo]
												";
								
								if (!$conn->Execute($sql)) {
								
								die ($sql);}
								////
								}
									
				}
			//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------			
			//------------------- VERIFICANDO SI LAS ORDENES DE PAGO NO FUERON CANCELADAS POR OTROS CHEQUES//----------------------------//
							$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
							$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
							$i=0;
							while($i < $contador)
							{
									$sql_orden="SELECT * 
												FROM
													orden_pago
												WHERE
													(orden_pago.id_orden_pago='$vector[$i]')
												AND
													(orden_pago.cheque='$_POST[tesoreria_cheques_db_n_precheque]')
												";
							
									//echo($sql_pago);
									$i=$i+1;	
								if (!$conn->Execute($sql_orden))die ('No_relacion');
									$row_verificacion2= $conn->Execute($sql_orden);
							}		
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////¡
			//---------------busqueda del ultimo CHEQUE----------------
			$sql_ultimo_emitido = "SELECT 
										ultimo_emitido,cantidad_cheques,secuencia
								   FROM 
										chequeras 
									WHERE
										cuenta='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
									AND
										estatus='1'		
								  ";
			if (!$conn->Execute($sql_ultimo_emitido))
					die ('Error_impresion' );
			//die ('Error 	consulta: '.$conn->ErrorMsg());
			//die($sql_ultimo_emitido);	
				$row_emitido= $conn->Execute($sql_ultimo_emitido);
				if(!$row_emitido->EOF)	
				{
					$cantidad=$row_emitido->fields("cantidad_cheques");
					$n_cheque=$row_emitido->fields("ultimo_emitido");
					$secuencia=$row_emitido->fields("secuencia");
					$secuencia2=$secuencia;
					$n_cheque_resultado=intval($n_cheque)+1;
					$n_ultimo=intval($n_cheque_resultado)+1;
							$proximo_emitir=$n_cheque_resultado;
							$estatus=1;
						//}
			//////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
							$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
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
															 $where="where	\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
															$sql_compromiso="SELECT 
																					monto,cantidad
																				FROM 
																					\"orden_compra_servicioE\"
																				INNER JOIN
																					\"orden_compra_servicioD\"
																				ON
																					\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																				$where";
															if (!$conn->Execute($sql_compromiso)) die ('Error al Registrar: '.$conn->ErrorMsg());
															$row_compromiso= $conn->Execute($sql_compromiso);
															/// calculando totales segun compromisos
																				if(!$row_compromiso->EOF)
																					{
																					  //sacando el total de la orden de compra
																					  $monto_compromiso=$row_compromiso->fields("monto");
																					  $cantidad_compromiso=$row_compromiso->fields("cantidad");
																					  $total_compromiso=$monto_compromiso*$cantidad_compromiso;
																					  //sacando el total de las facturas pagadas para ese compromiso
																					  $sql_facturas="SELECT 
																													   porcentaje_iva,
																													   porcentaje_retencion_iva, 
																													   monto_bruto,
																													   monto_base_imponible,
																													   tipo_documentocxp,
																													   amortizacion
																										 FROM
																													documentos_cxp
																										where						   
																													documentos_cxp.numero_compromiso='$compromiso'";		   
																								//die($sql_facturas);					
																								$row_factura=& $conn->Execute($sql_facturas);
																								$total_renglon=0;
																								while(!$row_factura->EOF)
																								{
																									$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
																									$monto_factura=$row_factura->fields("monto_bruto");
																									$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
																									if(($row_factura->fields("tipo_documentocxp")==$tipos_ant))
																									{
																										$monto_factura=0;
																									}
																									if((($row_factura->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura->fields("amortizacion")!='0,00'))
																									{
																										$monto_factura="";	
																										$monto_ante=($row_factura->fields("monto_bruto")+$row_factura->fields("amortizacion"));
																										$p_iva_factura=$monto_ante*$row_factura->fields("porcentaje_iva")/100;
																									//	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
																										$monto_factura=$monto_ante+$p_iva_factura;	
																									}
																									$row_factura->MoveNext();
																								}		
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
																						$total_documento=$monto_restar;
																						$orden_total=$total_compromiso;
																						$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento;
																						$fact_ord=($orden_total)-$lo_q_qeda_fact;
			
																					  ///
																					  if($fact_ord<$total_documento)
																					  {
																						die("El monto a pagar no puede superar al monto comprometido");
																					  }
																					 // $row_factura->MoveNext();
																					}
																	
			//////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
				
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
												$resumen_suma = "
																	SELECT  
																		   (monto_pagado[".date("n")."]) AS monto
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
														$monto_total = $monto_pagado + $monto_restar;
														$actu=
														"UPDATE 
																\"presupuesto_ejecutadoR\"
														SET 
																monto_pagado[".date("n")."]= '$monto_total'
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
																
															if (!$conn->Execute($actu))
															die ('Error al Actulizar: '.$conn->ErrorMsg());
								
								}								
								$i_fact++;
									}						
							$i++;							
							}			
			///////////////////////////////////////////////////////////////////////////////////////////////////		
					
			//////////////////////////////////////////////////
					$sql_def=" UPDATE chequeras
									SET
											ultimo_emitido='$proximo_emitir',
											estatus=$estatus,
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
									WHERE
										id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
									AND	
										cuenta='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND
										secuencia='$secuencia2'";
					// die($sql_def);		
					if (!$conn->Execute($sql_def))
						{	echo ('Error_impresion' );}
					//die ('Error al modificar datos chequera: '.$sql_chequeras);
					
				}
				else
					die('chequera_agotada');
				if($n_cheque!="")
				{
					//--- modificando el n_cheque en la tabla cheques
					$sql_cheques=" UPDATE cheques
							SET
									numero_cheque='$n_cheque',
									estatus='2',
									secuencia='$secuencia',
									fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
									ultimo_usuario=".$_SESSION['id_usuario'].",
									fecha_firma='$firmas',
									estado[1]='2',
									estado_fecha[1]='".date("Y-m-d H:i:s")."'
			
									
							WHERE
								id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND	
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
							AND
								numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
							AND
								cheques.tipo_cheque='1'			 
							AND		
							cheques.id_organismo=$_SESSION[id_organismo]	
							";
					//die($sql_cheques);			
					if (!$conn->Execute($sql_cheques)) 
							die ('Error_impresion' );
					//die ('Error al modificar datos de cheques: '.$sql_cheques);
					
					$sql_orden=" UPDATE orden_pago
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
						die ('Error_impresion' );
					//die ('Error al modificar orden de pago: '.$sql_orden);
				
				
					$sql_ultimo_emitido2 = $sql_cheque = "SELECT 
								numero_cheque
						   FROM 
								cheques 
						   WHERE 
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
						   AND 
								 id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND 
								id_proveedor='$_POST[tesoreria_cheques_pr_proveedor_id]'
							AND
								numero_cheque='$n_cheque'
							AND
								cheques.tipo_cheque='1'			 
							AND		
								cheques.id_organismo=$_SESSION[id_organismo]		 
								 ";
					if (!$conn->Execute($sql_ultimo_emitido2))
						die ('Error_impresion' );
					//die ('Error consulta numero de cheque: '.$conn->ErrorMsg());
					$row_emitido2= $conn->Execute($sql_ultimo_emitido2);
			
				}
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
				$responce=$n_cheque."*".$secuencia."*".$unidad_ejec."*".$proyecto."*".$partida;
				die($responce);
			
				//die($row_emitido2->fields("numero_cheque"));
				 //}
/*}else
die("cerrado");	*/			 
?>