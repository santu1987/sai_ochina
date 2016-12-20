<?php
ini_set("memory_limit","20M");
function cambio_fecha($sesion,$comprobante,$fecha,$organismo,$id_cc_g,$id_aux_g,$monto_debito,$monto_credito,$debe_haber_op)
{
//die($fecha);
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);

		//	$fecha =date("Y-m-d H:i:s") ;
			/*$ano=substr($fecha,0,4);
			$mes=substr($fecha,5,2);*/
			//$sesion=$_SESSION[id_organismo];
			//$comprobante=$_POST[contabilidad_comp_id_comprobante];
		//	$sesion=1;
		//	$comprobante='100000';
$ano_comprobante2=substr($fecha,6,4);
$mes_comprobante2=substr($fecha,3,2);



			$sql_comprobante="select movimientos_contables.id_movimientos_contables,
								   movimientos_contables.ano_comprobante, 
								   movimientos_contables.mes_comprobante,
								   movimientos_contables.id_tipo_comprobante, 
								   movimientos_contables.numero_comprobante,
								   movimientos_contables.secuencia, 
								   movimientos_contables.comentario,
								   movimientos_contables.cuenta_contable, 
								   movimientos_contables.descripcion, 
								   movimientos_contables.referencia,
								   movimientos_contables.debito_credito,
								   movimientos_contables.monto_debito,
								   movimientos_contables.monto_credito,
								   movimientos_contables.fecha_comprobante, 
								   movimientos_contables.id_auxiliar,
								   movimientos_contables.id_unidad_ejecutora,
								   movimientos_contables.id_proyecto,
								   movimientos_contables.id_utilizacion_fondos, 
								   movimientos_contables.ultimo_usuario,
								   movimientos_contables.id_organismo,
								   movimientos_contables.ultima_modificacion,
								   movimientos_contables.estatus,
								   movimientos_contables.id_accion_central,
								   cuenta_contable_contabilidad.id as cuenta_contable_id,
								   cuenta_contable_contabilidad.id_cuenta_suma as cuenta_contable_id_suma,
								   naturaleza_cuenta.codigo  AS codigo,
								   tipo_comprobante.codigo_tipo_comprobante as codigo_tipo
							 from 
									movimientos_contables
							inner  join
									cuenta_contable_contabilidad
								on
									movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable		
						   inner   join
									naturaleza_cuenta
								on
									cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
						  INNER JOIN
									tipo_comprobante
								on
									movimientos_contables.id_tipo_comprobante=tipo_comprobante.id	
								where
									movimientos_contables.id_organismo = $organismo
									and
									movimientos_contables.numero_comprobante=$comprobante
									and
									movimientos_contables.estatus!='3'								
											";
											//die($sql_comprobante);
						$row_comprobante=& $conn->Execute($sql_comprobante);
						while(!$row_comprobante->EOF)
						{
						$cuenta_contable_inicial=$row_comprobante->fields("cuenta_contable_id");
						$id_auxiliar=$row_comprobante->fields("id_auxiliar");
						$cuenta_contable_inicial_suma=$row_comprobante->fields("cuenta_contable_id_suma");
						$debito_credito=$row_comprobante->fields("debito_credito");
						$tipo_comp=$row_comprobante->fields("codigo_tipo");
						$fecha_comprobante=substr($row_comprobante->fields("fecha_comprobante"),0,10);
						$ano=substr($fecha_comprobante,0,4);
						$mes=substr($fecha_comprobante,5,2);
						///////////////////////////////////////////////verificando el codig de la cuenta contable
						$sql_tipo="select 
															cuenta_contable_contabilidad.id,
															naturaleza_cuenta.codigo  AS codigo
														
														from
																cuenta_contable_contabilidad 
														inner join
																	naturaleza_cuenta
														on
													cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
													where
													 cuenta_contable_contabilidad.id='$cuenta_contable_inicial'
													"
													;
										//die($sql_tipo);			
							$rs_tipo_c=& $conn->Execute($sql_tipo);
							if (!$rs_tipo_c->EOF) 
							{
								$codigo=$rs_tipo_c->fields("codigo");
							}						
						/************************************/		
									
						$id_sumas=$cuenta_contable_inicial;
								
								
									//die($debe_ant.";".$haber_ant);
				//	die($ano_comprobante."".$mes_comprobante."/".$ano."".$mes);
								/////////////////////// PASO1:ELIMINAR EL REGISTRO A MODIFICAR//
						////////////////////////	PASO1.1 ELIMINANDO CUENTAS Q SUMAN
							$turnos=1;
							$contadores=0;
							while(($turnos>$contadores)&&($id_sumas!=""))
							{
									$sqlw="select 
											*
											from
												cuenta_contable_contabilidad 
											where 
												id='$id_sumas'";				
										//die($sqlw);
									$rs_suma=& $conn->Execute($sqlw);
									if (!$rs_suma->EOF) 
									{
											
										$suma_cuenta=$rs_suma->fields("id_cuenta_suma");
										if($suma_cuenta!="")
										{
											$sql_mov_suma="SELECT  
																				   (saldo_inicio[".$mes."])as saldo_inicio,
																				   (debe[".$mes."])as debe,
																				   (haber[".$mes."])as haber 
																			FROM 
																					saldo_contable
																			WHERE
																				cuenta_contable='$suma_cuenta'
																			and
																				ano='$ano'	
																			";	
																		//	die($sql_mov_suma);
																$rs_mov_suma=& $conn->Execute($sql_mov_suma);
																			if (!$rs_mov->EOF) 
																			{
																				if($debito_credito==1)//para eliminar la cuenta q suma si va por el debe
																				{	
																					$saldo_viejo=$row_comprobante->fields("monto_debito");
																					$monto_debe_suma = $rs_mov_suma->fields("debe")-$saldo_viejo;
																					$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																					/////////////////////////////////////////////////////////////////
																							if($tipo_comp==10)
																							{																																	                                                                                               if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																								{
																									$saldo_inicia=$saldo_inicio_suma-$saldo_viejo;																													}
																								else
																								if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																								{
																									$saldo_inicia=$saldo_inicio_suma+$saldo_viejo;																																		}																					
																								$sql_saldo_inicial_suma="
																													update
																														saldo_contable
																													SET 
																															saldo_inicio[".$mes."]= '$saldo_inicia'
																													WHERE
																														 cuenta_contable='$suma_cuenta'
																													AND	
																														 ano='$ano'";
																							}else
																							$sql_saldo_inicial_suma="";
																					////////////////////////////////////////////////////////////////
																					$sql_act_saldos_sumas="update
																												saldo_contable
																											SET 
																													debe[".$mes."]= '$monto_debe_suma'
																											WHERE
																														cuenta_contable='$suma_cuenta'
																											and
																													ano='$ano';
																											$sql_saldo_inicial_suma
																											";						
																				}//fin debito_credito==1
																				else
																				if($debito_credito==2)
																				{
																					$saldo_viejo=$row_comprobante->fields("monto_credito");
																					$monto_haber_suma = $rs_mov_suma->fields("haber")-$saldo_viejo;
																					$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																					////////////////////////////////////////////////////////////////////////	
																							if($tipo_comp==10)
																							{
																								if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																								{
																									$saldo_inicia=$saldo_inicio_suma+$saldo_viejo;																													}
																								else
																								if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																								{
																																																																																					                                                                                                    $saldo_inicia=$saldo_inicio_suma-$saldo_viejo;																													}
																																																																													$sql_saldo_inicial_suma="
																													update
																														saldo_contable
																													SET 
																															saldo_inicio[".$mes."]= '$saldo_inicia'
																													WHERE
																														 cuenta_contable='$suma_cuenta'
																													AND	
																														 ano='$ano'";
																							}else
																							$sql_saldo_inicial_suma="";
																					///////////////////////////////////////////////////////////////////////	
																					$sql_act_saldos_sumas="update
																											saldo_contable
																										SET 
																												haber[".$mes."]= '$monto_haber_suma'
																										WHERE
																													cuenta_contable='$suma_cuenta'
																										and
																												ano='$ano';
																										$sql_saldo_inicial_suma		
																												";	
																				}//fin debito_credito==2
																			}//fin de 	if (!$rs_mov->EOF) 
																				else
																				$sql_act_saldos_sumas="";
																					
														//	die($sql_act_saldos_sumas);
															$turnos++;
															$id_sumas=$suma_cuenta;
											if($contadores==0)
												$sql_mod_sumas_todas=$sql_act_saldos_sumas;
											else	
												$sql_mod_sumas_todas=$sql_mod_sumas_todas.";".$sql_act_saldos_sumas;	
							//////////////////////////////////////////////////////////////	
										}//si suma cuenta=""
										
									}//!$rs_suma->EOF)
										
																	
							
							$contadores=$contadores+1;	
							}//fin del while
							//die($sql_mod_sumas_todas);
						///////////////////////     PASO 1.2 ELIMINANDO CUENTA PRINCIPAL
						//////////////CONSULTANDO VALOR DEL SALDO DE LA CUENTA PRINCIPAL
						$sql_mov_original="SELECT  
											   (saldo_inicio[".$mes."])as saldo_inicio,
											   (debe[".$mes."])as debe,
											   (haber[".$mes."])as haber 
										FROM 
												saldo_contable
										WHERE
											cuenta_contable='$cuenta_contable_inicial'
										and
											ano='$ano'	
										";	
										$rs_mov_original=& $conn->Execute($sql_mov_original);
													if (!$rs_mov_original->EOF) 
													{
														if($row_comprobante->fields("debito_credito")==1)//SI VA PRO EL DEBE
														{
																$saldo_viejo=$row_comprobante->fields("monto_debito");
																$total_mov_debe=$rs_mov_original->fields("debe")-$saldo_viejo;
																$saldo_inicio_original=$rs_mov_original->fields("saldo_inicio");
																////////////////////////////////////////////////////////////////////////	
																if($tipo_comp==10)//si es saldo inicial
																{
																		if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																		{
																			$saldo_inicia=$saldo_inicio_original-$saldo_viejo;																													}
																		else
																		if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																		{
																			$saldo_inicia=$saldo_inicio_original+$saldo_viejo;																																	}																								
																	$sql_saldo_inicial="
																						update
																							saldo_contable
																						SET 
																								saldo_inicio[".$mes."]= '$saldo_inicia'
																						WHERE
																							 cuenta_contable='$cuenta_contable_inicial'
																						AND	
																							 ano='$ano'";
																}else
																$sql_saldo_inicial="";
																///////////////////////////////////////////////////////////////////////	
																$sql_act_saldos_ctas="update
																						saldo_contable
																					SET 
																							debe[".$mes."]= '$total_mov_debe'
																					WHERE
																								cuenta_contable='$cuenta_contable_inicial'
																					and
																							ano='$ano';
																					$sql_saldo_inicial		
																							";	
														}//FIN SI VA POR EL DEBE
														if($row_comprobante->fields("debito_credito")==2)//SI VA PRO EL HABER
														{
																$saldo_viejo=$row_comprobante->fields("monto_credito");
																$total_mov_haber=$rs_mov_original->fields("haber")-$saldo_viejo;
																$saldo_inicio_original=$rs_mov_original->fields("saldo_inicio");
																////////////////////////////////////////////////////////////////////////	
																if($tipo_comp==10)//si es saldo inicial
																{
																		if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																		{
																			$saldo_inicia=$saldo_inicio_original+$saldo_viejo;																													
																		}
																		else
																		if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																		{
																			$saldo_inicia=$saldo_inicio_original-$saldo_viejo;																																	}																		
																	$sql_saldo_inicial="
																						update
																							saldo_contable
																						SET 
																								saldo_inicio[".$mes."]= '$saldo_inicia'
																						WHERE
																							 cuenta_contable='$cuenta_contable_inicial'
																						AND	
																							 ano='$ano'";
																}else
																$sql_saldo_inicial="";
																///////////////////////////////////////////////////////////////////////	
																$sql_act_saldos_ctas="update
																						saldo_contable
																					SET 
																							haber[".$mes."]= '$total_mov_haber'
																					WHERE
																								cuenta_contable='$cuenta_contable_inicial'
																					and
																							ano='$ano';
																					$sql_saldo_inicial		
																							";	
														}//FIN SI VA POR EL HABER
														
													}//fin 	if (!$rs_mov_original->EOF) 
													
						/////////////////////////////////////////////////////////////////
						///////////////////////     PASO 1.3 ELIMINANDO AUXILIARES
							if(($id_auxiliar!='')&&($id_auxiliar!='0'))
							{
								$sql_saldo_cuenta_aux="SELECT 
															(debe[".$mes."])as debe,
															(haber[".$mes."])as haber ,
															(saldo_inicio[".$mes."])as saldo_inicio
													   FROM
															saldo_auxiliares
														WHERE
															cuenta_contable='$cuenta_contable_inicial'
														and
															cuenta_auxiliar='$id_auxiliar'	
														and
															ano='$ano'";
								$row_saldo_aux=& $conn->Execute($sql_saldo_cuenta_aux);
												if (!$row_saldo_aux->EOF) 
												{
														if($row_comprobante->fields("debito_credito")==1)//si el auxiliar va por el debe
														{
															$saldo_viejo=$row_comprobante->fields("monto_debito");
															$saldo_aux=$row_saldo_aux->fields("debe")-$saldo_viejo;
															$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
															//////////////////////////////si es saldo inicial//////////////////////////////////////////////////
															if($tipo_comp==10)
															{
																if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																{
																	$saldo1_aux=$saldo_inicio_aux-$saldo_viejo;	
																}
																else
																if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																{
																	$saldo1_aux=$saldo_inicio_aux+$saldo_viejo;																																	}																	$sql_saldo_inicial_aux="update
																							saldo_auxiliares
																						SET 
																								saldo_inicio[".$mes."]= '$saldo1_aux'
																						WHERE
																							cuenta_contable='$cuenta_contable_inicial'	
																						AND	
																								cuenta_auxiliar='$id_auxiliar'
																						AND
																									ano='$ano'	
																							";
																
															}else//FIN SALDO INICIA AUX
															$sql_saldo_inicial_aux="";
															/////////////////////////////////////////////////////////////////////////////////////////////	
															$sql_saldos_aux="update
																			saldo_auxiliares
																		SET 
																				debe[".$mes."]= '$saldo_aux'
																		WHERE
																					cuenta_contable='$cuenta_contable_inicial'
																		and
																					cuenta_auxiliar='$id_auxiliar'
																		and
																				ano='$ano';
																		$sql_saldo_inicial_aux		
																				";
																				
														}//fin de if($row_comprobante->fields("debito_credito")==1)
														if($row_comprobante->fields("debito_credito")==2)//si el auxiliar va por el HABER
														{
															$saldo_viejo=$row_comprobante->fields("monto_credito");
															$saldo_aux=$row_saldo_aux->fields("haber")-$saldo_viejo;
															$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
															//////////////////////////////si es saldo inicial//////////////////////////////////////////////////
															if($tipo_comp==10)
															{
																	if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																	{
                                                                          $saldo1_aux=$saldo_inicio_aux+$saldo_viejo;
																	}else
																	if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																	{
																		$saldo1_aux=$saldo_inicio_aux-$saldo_viejo;																																	}																		$sql_saldo_inicial_aux="update
																							saldo_auxiliares
																						SET 
																								saldo_inicio[".$mes."]= '$saldo1_aux'
																						WHERE
																							cuenta_contable='$cuenta_contable_inicial'	
																						AND	
																								cuenta_auxiliar='$id_auxiliar'
																						AND
																									ano='$ano'	
																							";
															}else//FIN SALDO INICIA AUX
															$sql_saldo_inicial_aux="";
															/////////////////////////////////////////////////////////////////////////////////////////////	
															$sql_saldos_aux="update
																			saldo_auxiliares
																		SET 
																				haber[".$mes."]= '$saldo_aux'
																		WHERE
																					cuenta_contable='$cuenta_contable_inicial'
																		and
																					cuenta_auxiliar='$id_auxiliar'
																		and
																				ano='$ano';
																		$sql_saldo_inicial_aux		
																				";
														}//fin de if($row_comprobante->fields("debito_credito")==2)
														
												}//fin de if (!$row_saldo_aux->EOF) aux consulta principal	
																								
							}//fin de if(($id_auxiliar!='')&&($id_auxiliar!='0'))
						/////////////////////////////////////////////////////////////////
						//- Luego e haber generado todos los querys para la eliminacion  se unen y se ejecutan
						$sql=$sql_act_saldos_ctas.";".$sql_mod_sumas_todas.";".$sql_saldos_aux;
						echo($sql);
											/*if (!$conn->Execute($sql)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
												die($responce);
											}
											else//si se ejecuta la resta de los saldos sin novedad se prosigue a guardar nuevamente el registro en las tablas de saldo.....
											{*/
												//die($sql);
												$turnos_g=1;
												$contadores_g=0;
///////////////////////////////////////////////verificando el codig de la cuenta contable
											$sql_tipo="select 
																			cuenta_contable_contabilidad.id,
																			naturaleza_cuenta.codigo  AS codigo
																		
																		from
																				cuenta_contable_contabilidad 
																		inner join
																					naturaleza_cuenta
																		on
																	cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
																	where
																	 cuenta_contable_contabilidad.id='$id_cc_g'
																	"
																	;
											$rs_tipo_c=& $conn->Execute($sql_tipo);
											if (!$rs_tipo_c->EOF) 
											{
												$codigo="";
												$codigo=$rs_tipo_c->fields("codigo");
											}						
/************************************/
												$id_sumas_g=$id_cc_g;
												///////////////// PASO1: CUENTAS QUE SUMAN FUNCION DE AGREGAR A LOS SALDOS
												while($turnos_g>$contadores_g)
												{
														$sqlw="select 
																*
																from
																	cuenta_contable_contabilidad 
																where 
																	id='$id_sumas_g'";				
															//die($sqlw);
														$rs_suma=& $conn->Execute($sqlw);
														if (!$rs_suma->EOF) 
														{
																
															$suma_cuenta_g=$rs_suma->fields("id_cuenta_suma");
															if($suma_cuenta_g!="")
															{
																$sql_mov_suma="SELECT  
																				   (saldo_inicio[".$mes_comprobante2."])as saldo_inicio,
																				   (debe[".$mes_comprobante2."])as debe,
																				   (haber[".$mes_comprobante2."])as haber 
																			FROM 
																					saldo_contable
																			WHERE
																				cuenta_contable='$suma_cuenta_g'
																			and
																				ano='$ano_comprobante2'	
																			";	
																		//	die($sql_mov_suma);
																		$rs_mov_suma=& $conn->Execute($sql_mov_suma);
																				if (!$rs_mov->EOF) 
																				{
																					if($debe_haber_op==1)//suma a la cuenta  si va por el debe
																					{	
																						$monto_debe2 = $rs_mov_suma->fields("debe")+$monto_debito;
																																															                                                                                        $saldo_inicio=$rs_mov_suma->fields("saldo_inicio");

																						/////////////////////////////////////////////////////////////////
																								if($tipo_comp==10)
																								{
																									if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																									{
																										$saldo_inicia=$saldo_inicio+$monto_debito;																													}
																									else
																									if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																									{
																										$saldo_inicia=$saldo_inicio-$monto_debito;
																																																										}																	
																																																						$sql_saldo_inicial_suma="
																														update
																															saldo_contable
																														SET 
																																saldo_inicio[".$mes_comprobante2."]= '$saldo_inicia'
																														WHERE
																															 cuenta_contable='$suma_cuenta_g'
																														AND	
																															 ano='$ano_comprobante2'";
																								}else
																								$sql_saldo_inicial_suma="";
																						////////////////////////////////////////////////////////////////
																						$sql_act_saldos_sumas2="update
																													saldo_contable
																												SET 
																														debe[".$mes_comprobante2."]= '$monto_debe2'
																												WHERE
																															cuenta_contable='$suma_cuenta_g'
																												and
																														ano='$ano_comprobante2';
																												$sql_saldo_inicial_suma
																												";						
																					}//fin debito_credito==1
																					else
																					if($debe_haber_op==2)
																					{
																						$monto_haber2 = $rs_mov_suma->fields("haber")+$monto_credito;
																						$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																						////////////////////////////////////////////////////////////////////////	
																								if($tipo_comp==10)
																								{
																									if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																									{
                                                                                                        $saldo_inicia=$saldo_inicio_suma-$monto_credito;
																										}																										else
																									if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																									{
																										$saldo_inicia=$saldo_inicio_suma+$monto_credito;																																	}																					
																										$sql_saldo_inicial_suma="
																														update
																															saldo_contable
																														SET 
																																saldo_inicio[".$mes_comprobante2."]= '$saldo_inicia'
																														WHERE
																															 cuenta_contable='$suma_cuenta_g'
																														AND	
																															 ano='$ano_comprobante2'";
																								}else
																								$sql_saldo_inicial_suma="";
																						///////////////////////////////////////////////////////////////////////	
																						$sql_act_saldos_sumas2="update
																												saldo_contable
																											SET 
																													haber[".$mes_comprobante2."]= '$monto_haber2'
																											WHERE
																														cuenta_contable='$suma_cuenta_g'
																											and
																													ano='$ano_comprobante2';
																											$sql_saldo_inicial_suma		
																													";	
																					}//fin debito_credito==2
																				}//fin de 	if (!$rs_mov->EOF) 
																									else
																									$sql_act_saldos_sumas2="";
																										
																			//	die($sql_act_saldos_sumas);
																				$turnos_g++;
																				$id_sumas_g=$suma_cuenta_g;
																if($contadores_g==0)
																	$sql_mod_sumas_todas2=$sql_act_saldos_sumas2;
																else	
																	$sql_mod_sumas_todas2=$sql_mod_sumas_todas2.";".$sql_act_saldos_sumas2;	
												//////////////////////////////////////////////////////////////	
															}//si suma cuenta=""
															
														}//!$rs_suma->EOF)
															
																						
												
													$contadores_g=$contadores_g+1;	
												}//fin del while
											//	die($sql_mod_sumas_todas2);
						//////////////queda pendiente  los auxliares y las cuentas contables lo demas ya esta probado
						///////////////////////     PASO 1.2  AGREGANDO LOS SALDOS A LA CUENTA PRINCIPAL
						//////////////CONSULTANDO VALOR DEL SALDO DE LA CUENTA PRINCIPAL
						$sql_mov_original="SELECT  
											   (saldo_inicio[".$mes_comprobante2."])as saldo_inicio,
											   (debe[".$mes_comprobante2."])as debe,
											   (haber[".$mes_comprobante2."])as haber 
										FROM 
												saldo_contable
										WHERE
											cuenta_contable='$id_cc_g'
										and
											ano='$ano_comprobante2'
												
										";	
										$rs_mov_original=& $conn->Execute($sql_mov_original);
													if (!$rs_mov_original->EOF) 
													{
														if($debe_haber_op==1)//SI VA PRO EL DEBE
														{
																$total_mov_debe=$rs_mov_original->fields("debe")+$monto_debito;
																$saldo_inicio_original=$rs_mov_original->fields("saldo_inicio");
																////////////////////////////////////////////////////////////////////////	
																if($tipo_comp==10)//si es saldo inicial
																{
																	if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																	{
																		$saldo_inicia=$saldo_inicio_original+$monto_debito;																													}
																	else
																	if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																	{
																		$saldo_inicia=$saldo_inicio_original-$monto_debito;																																	}																					
																			$sql_saldo_inicial_cta="
																				update
																					saldo_contable
																				SET 
																						saldo_inicio[".$mes_comprobante2."]= '$saldo_inicia'
																				WHERE
																					 cuenta_contable='$id_cc_g'
																				AND	
																					 ano='$ano_comprobante2'";
																								
																}else
																$sql_saldo_inicial_cta="";
																///////////////////////////////////////////////////////////////////////	
																$sql_cuentas_contab_s="update
																						saldo_contable
																					SET 
																							debe[".$mes_comprobante2."]= '$total_mov_debe'
																					WHERE
																								cuenta_contable='$id_cc_g'
																					and
																							ano='$ano_comprobante2';
																					$sql_saldo_inicial_cta		
																							";	
														}//FIN SI VA POR EL DEBE
														if($debe_haber_op==2)//SI VA PRO EL HABER
														{
																$total_mov_haber=$rs_mov_original->fields("haber")+$monto_credito;
																$saldo_inicio_original=$rs_mov_original->fields("saldo_inicio");
																////////////////////////////////////////////////////////////////////////	
																if($tipo_comp==10)//si es saldo inicial
																{
																	if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																	{
																		$saldo_inicia=$saldo_inicio_original-$monto_credito;																													}
																	else
																	if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																	{
																		$saldo_inicia=$saldo_inicio_original+$monto_credito;																																		}																				
																			$sql_saldo_inicial_cta="
																				update
																					saldo_contable
																				SET 
																						saldo_inicio[".$mes_comprobante2."]= '$saldo_inicia'
																				WHERE
																					 cuenta_contable='$id_cc_g'
																				AND	
																					 ano='$ano_comprobante2'";
																}else
																$sql_saldo_inicial_cta="";
																///////////////////////////////////////////////////////////////////////	
																$sql_cuentas_contab_s="update
																						saldo_contable
																					SET 
																							haber[".$mes_comprobante2."]= '$total_mov_haber'
																					WHERE
																								cuenta_contable='$id_cc_g'
																					and
																							ano='$ano_comprobante2';
																					$sql_saldo_inicial_cta	
																							";	
														}//FIN SI VA POR EL HABER
														
													}//fin 	if (!$rs_mov_original->EOF) 
						///////////////////////////////////////////////////////////////////////////////////////////////////////
						///////////////////////   PASO 1.3 AGREGANDO SALDOS A LA CUENTA AUXILIAR
										if(($id_aux_g!='')&&($id_aux_g!='0'))
											{
												$sql_saldo_cuenta_aux="SELECT 
																			(debe[".$mes_comprobante2."])as debe,
																			(haber[".$mes_comprobante2."])as haber ,
																			(saldo_inicio[".$mes_comprobante2."])as saldo_inicio
																	   FROM
																			saldo_auxiliares
																		WHERE
																			cuenta_contable='$id_cc_g'
																		and
																			cuenta_auxiliar='$id_aux_g'	
																		and
																			ano='$ano_comprobante2'";
												$row_saldo_aux=& $conn->Execute($sql_saldo_cuenta_aux);
																if (!$row_saldo_aux->EOF) 
																{
																		if($debe_haber_op==1)//si el auxiliar va por el debe
																		{
																			$saldo_aux_d=$row_saldo_aux->fields("debe")+$monto_debito;
																			$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
																			//////////////////////////////si es saldo inicial//////////////////////////////////////////////////
																			if($tipo_comp==10)
																			{
																				if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																				{
																					$saldo_inicia=$saldo_inicio_aux+$monto_debito;																												}
																				else
																				if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																				{
																					$saldo_inicia=$saldo_inicio_aux-$monto_debito;																																}																																														$sql_saldo_inicial_aux_s="update
																											saldo_auxiliares
																										SET 
																												saldo_inicio[".$mes_comprobante2."]= '$saldo_inicia'
																										WHERE
																											cuenta_contable='$id_cc_g'	
																										AND	
																												cuenta_auxiliar='$id_aux_g'
																										AND
																													ano='$ano_comprobante2'	
																											";
																			}else//FIN SALDO INICIA AUX
																			$sql_saldo_inicial_aux_s="";
																			/////////////////////////////////////////////////////////////////////////////////////////////	
																			$sql_saldos_aux_s="update
																							saldo_auxiliares
																						SET 
																								debe[".$mes_comprobante2."]= '$saldo_aux_d'
																						WHERE
																									cuenta_contable='$id_cc_g'
																						and
																									cuenta_auxiliar='$id_aux_g'
																						and
																								ano='$ano_comprobante2';
																						$sql_saldo_inicial_aux_s		
																								";
																		}//fin de if($row_comprobante->fields("debito_credito")==1)
																		if($debe_haber_op==2)//si el auxiliar va por el HABER
																		{
																			$saldo_aux=$row_saldo_aux->fields("haber")+$monto_credito;
																			$saldo_inicio_aux_h=$row_saldo_aux->fields("saldo_inicio");
																			//////////////////////////////si es saldo inicial//////////////////////////////////////////////////
																			if($tipo_comp==10)
																			{
																				if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																				{
																					$saldo_inicia=$saldo_inicio_aux-$monto_credito;																												}
																				else
																				if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																				{
																					$saldo_inicia=$saldo_inicio_aux+$monto_credito;																																		}																																								$sql_saldo_inicial_aux_s="update
																											saldo_auxiliares
																										SET 
																												saldo_inicio[".$mes_comprobante2."]= '$saldo_inicia'
																										WHERE
																											cuenta_contable='$id_cc_g'	
																										AND	
																												cuenta_auxiliar='$id_aux_g'
																										AND
																													ano='$ano_comprobante2'	
																											";
																			}else//FIN SALDO INICIA AUX
																			$sql_saldo_inicial_aux_s="";
																			/////////////////////////////////////////////////////////////////////////////////////////////	
																			$sql_saldos_aux_s="update
																							saldo_auxiliares
																						SET 
																								haber[".$mes_comprobante2."]= '$saldo_aux'
																						WHERE
																									cuenta_contable='$id_cc_g'
																						and
																									cuenta_auxiliar='$id_aux_g'
																						and
																								ano='$ano_comprobante2';
																						$sql_saldo_inicial_aux_s		
																								";
																		}//fin de if($row_comprobante->fields("debito_credito")==2)
																		
																}//fin de if (!$row_saldo_aux->EOF) aux consulta principal	
																												
											}//fin de if(($id_auxiliar!='')&&($id_auxiliar!='0'))							
						/////////////////////////////////////////////////////////////////
						//- Luego e haber generado todos los querys para la eliminacion  se unen y se ejecutan
						$sql2=$sql_cuentas_contab_s.";".$sql_mod_sumas_todas2.";".$sql_saldos_aux_s;
					echo($sql2);
											/*if (!$conn->Execute($sql2)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
												die($responce);
											}
											*/
										
										
								//}//fin del else				
								$row_comprobante->MoveNext();
								$sql_mod_cuentas="";
								$sql_mod_sumas_todas="";
								$sql_act_aux="";	
							}
						die("hola");
							$sql="
																	UPDATE	
																			movimientos_contables
																	set
																		
																		fecha_comprobante='".$fecha."',
																		ultimo_usuario= ".$sesion.",
																		ultima_modificacion= '".date("Y-m-d H:i:s")."'
																	WHERE	
																		movimientos_contables.numero_comprobante='$comprobante'								
																	;
																";
			if (!$conn->Execute($sql)) 
				die ('Error al Actualizar: '.$conn->ErrorMsg());
			
			else
											{
												$sql_sumas=" SELECT
																	SUM(monto_debito) as debe,
																	SUM(monto_credito) as haber
																from
																	movimientos_contables
																where numero_comprobante='$comprobante'
																
																and
																	movimientos_contables.estatus!='3'
																and
																	ano_comprobante='$ano'				

																	
												";
												///die($sql_sumas);
												$row_sumas=& $conn->Execute($sql_sumas);
												if(!$row_sumas->EOF)
												{
													$debe=number_format($row_sumas->fields("debe"),2,',','.');
													$haber=number_format($row_sumas->fields("haber"),2,',','.');
													$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
													$resta=number_format($resta,2,',','.');
													$responce="Registrado"."*".$debe."*".$haber."*".$resta;
													die($responce);
												}
											
												
											}	
													
																
}									
?>