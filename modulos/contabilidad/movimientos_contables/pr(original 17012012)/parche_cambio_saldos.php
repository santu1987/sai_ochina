<?php
ini_set("memory_limit","20M");
//die($fecha);
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$ano=2010;
$mes=12;
$fecha='31/12/2010';

		//	$fecha =date("Y-m-d H:i:s") ;
			/*$ano=substr($fecha,0,4);
			$mes=substr($fecha,5,2);*/
			//$sesion=$_SESSION[id_organismo];
			//$comprobante=$_POST[contabilidad_comp_id_comprobante];
		//	$sesion=1;
			$comprobante='20110131101000';
			
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
								   cuenta_contable_contabilidad.id,
								   cuenta_contable_contabilidad.id_cuenta_suma,
								   naturaleza_cuenta.codigo  AS codigo,
								   tipo_comprobante.codigo_tipo_comprobante as tipo_comprobante
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
									movimientos_contables.id_organismo = '1'
									and
									movimientos_contables.numero_comprobante='$comprobante'								
											";
											//die($sql_comprobante);
						$row_comprobante=& $conn->Execute($sql_comprobante);
						while(!$row_comprobante->EOF)
						{
									$id_cuenta=$row_comprobante->fields("id");
									$id_cuenta_suma=$row_comprobante->fields("cuenta_suma");
									$id_sumas=$id_cuenta;
									$debe_ant=$row_comprobante->fields("monto_debito");
									$haber_ant=$row_comprobante->fields("monto_credito");
									$tipo_comprobante=$row_comprobante->fields("tipo_comprobante");
									$fecha_comprobante=$row_comprobante->fields("fecha_comprobante") ;
									$ano_comprobante=substr($fecha_comprobante,0,4);
									$mes_comprobante=substr($fecha_comprobante,5,2);
				//	die($ano_comprobante."".$mes_comprobante."/".$ano."".$mes);
			
									/****************************************cat q suma**************************************/
										$turnos=1;
										$contadores=0;
											
										while($turnos>$contadores)
										{
												//echo($turnos."-");
												$sql_suma="select *	from  cuenta_contable_contabilidad	where id='$id_sumas'			
												";
												$row_mov=& $conn->Execute($sql_suma);
												
												
								if(!$row_mov->EOF)
								{
												$suma_cuenta=$row_mov->fields("id_cuenta_suma");
												if($suma_cuenta!="")
												{					$sql_mov_suma="SELECT  
																											
																												(debe[".$mes."])as debe_cambio,
																												(haber[".$mes."])as haber_cambio 
																									FROM 
																											saldo_contable
																									WHERE
																										cuenta_contable='$suma_cuenta'";//die($sql_mov_suma);
																	$row_mov2=& $conn->Execute($sql_mov_suma);
																	if(!$row_mov2->EOF)
																	{//echo($suma_cuenta."-".$sql_mov_suma);
																		if($row_mov2->fields("debe")!=0)//-($debe_ant_suma;
																			$monto_debe3 = $row_mov2->fields("debe")-($debe_ant);
																		else
																			$monto_debe3=0;
																		if($row_mov2->fields("haber")!=0)//);
																			$monto_haber3 = $row_mov2->fields("haber")-($haber_ant);
																		else
																			$monto_haber3=0;
																		if($row_mov2->fields("debe_cambio")!=0)
																			$monto_debe_cs=($row_mov2->fields("debe_cambio"))+($debe_ant);
																		else
																			$monto_debe_cs=$debe_ant;
																		if($row_mov2->fields("haber_cambio")!=0)
																			$monto_haber_cs=($row_mov2->fields("haber_cambio"))+($haber_ant);
																		else
																			$monto_haber_cs=$haber_ant;
																			
																		//	echo($monto_debe_cs."-".$monto_haber_cs);
																			if($tipo_comprobante=='10')
																			{
																					if(($monto_debe_cs!="0")&&($monto_haber_cs=="0"))
																					{
																						$monto_saldos=$monto_debe_cs;
																						$monto_ant_saldos=$monto_debe3;		
																					}
																					if(($monto_haber_cs!="0")&&($monto_debe_cs=="0"))
																					{
																						$monto_saldos=$monto_haber_cs;
																						$monto_ant_saldos=$monto_haber3;		
																					
																					}
																						//
																					if(($monto_haber_cs!="0")&&($monto_debe_cs!="0"))
																					{
																							if(($codigo=='A   ')||($codigo=='G   '))
																								{
																									
																									$monto_saldos=$monto_debe_cs-$monto_haber_cs;
																									$monto_ant_saldos=$monto_debe2-$monto_haber2;		
																								}
																								else
																								if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
																								{
																									$monto_saldos=$monto_haber_cs-$monto_debe_cs;
																									$monto_ant_saldos=$monto_haber2-$monto_debe2;		
																					
																								}
																								else
																								if($codigo=='R   ')
																								{
																									$monto_saldos=$monto_haber_cambio-$monto_debe_cs;
																									$monto_ant_saldos=$monto_haber2-$monto_debe2;		
																								}
																								if($codigo=='CO  ')
																								{
																									$monto_saldos=$monto_debe_cs-$monto_haber_cs;
																									$monto_ant_saldos=$monto_debe2-$monto_haber2;		
																								}
																					}//
																						$sql_saldo_s="update
																											saldo_contable
																										SET 
																																																						saldo_inicio[".$mes."]= '$monto_saldos'
																										WHERE
																												cuenta_contable='$suma_cuenta'
																										and
																											ano='2010'
																						";
																					//	die($sql_saldo_s);
																	}//fin tipo_comprtobante==10
																	
																										$sql_sumas_c="update
																															saldo_contable
																														SET 
																																debe[".$mes_comprobante."]= '$monto_debe3',
																																haber[".$mes_comprobante."]= '$monto_haber3'
																																
																														WHERE
																																	cuenta_contable='$suma_cuenta'
																														and
																																	ano='2011';
																														$sql_saldo_s";																															//	echo($sql_sumas_c);
															$turnos=$turnos+1;		
														//	echo($turnos);		
															  }
														//row_suna}]!=""
														}	  
												}//row_mov
										/*if($contadores==0)
											$sql_mod_sumas_todas=$sql_sumas_c;
										else	*/
											$sql_mod_sumas_todas=$sql_mod_sumas_todas.";".$sql_sumas_c;	
											

										$sql_sumas_c="";	
										$contadores=$contadores+1;	
										$id_sumas=$suma_cuenta;

										}//fin del whiler*/
									//echo($sql_mod_sumas_todas);
								//	
									/****************************************cta contable***********************************/
										if($id_cuenta!="")
												{
														$sql_mov="SELECT  
																   (debe[".$mes_comprobante."])as debe,
																   (haber[".$mes_comprobante."])as haber,
																   (debe[".$mes."])as debe_cambio,
																   (haber[".$mes."])as haber_cambio 
																	FROM 
																			saldo_contable
																	WHERE
																		cuenta_contable='$id_cuenta'";
															//		die($sql_mov);
																		$rs_mov=& $conn->Execute($sql_mov);
																		if (!$rs_mov->EOF) 
																		{
																			//
																			if($rs_mov->fields("debe")!=0)
																				$monto_debe2 =$rs_mov->fields("debe")-($debe_ant);
																			else
																				$monto_debe2=0;
																			//die($rs_mov->fields("debe")."-".($debe_ant));																	
																			if($rs_mov->fields("haber")!=0)
																				$monto_haber2=$rs_mov->fields("haber")-($haber_ant);
																			else
																				$monto_haber2=0;
																			if($rs_mov->fields("debe_cambio")!=0)
																				{
																				 $monto_debe_cambio=($rs_mov->fields("debe_cambio"))+($debe_ant);
																				}
																			else
																				$monto_debe_cambio=$debe_ant;
																			//die($rs_mov->fields("debe")."-".($debe_ant));																	
																			if($rs_mov->fields("haber_cambio")!=0)
																				$monto_haber_cambio=$rs_mov->fields("haber_cambio")+($haber_ant);
																			else
																				$monto_haber_cambio=$haber_ant;
																			//////////
																		//	die($rs_mov->fields("debe_cambio")."+".($debe_ant));
																		//die($rs_mov->fields("debe")."-".($debe_ant));
																	//	die($tipo_comprobante);
																				if($tipo_comprobante=='10')
																				{
																						if(($monto_debe_cambio!="0")&&($monto_haber_cambio=="0"))
																						{
																							$monto_saldo=$monto_debe_cambio;
																							$monto_ant_saldo=$monto_debe2;		
																						}
																						if(($monto_haber_cambio!="0")&&($monto_debe_cambio=="0"))
																						{
																							$monto_saldo=$monto_haber_cambio;
																							$monto_ant_saldo=$monto_haber2;		
																						
																						}
																						if(($monto_haber_cambio!="0")&&($monto_debe_cambio!="0"))
																						{
																								if(($codigo=='A   ')||($codigo=='G   '))
																									{
																										
																										$monto_saldo=$monto_debe_cambio-$monto_haber_cambio;
																										$monto_ant_saldo=$monto_debe2-$monto_haber2;		
																									}
																									else
																									if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
																									{
																										$monto_saldo=$monto_haber_cambio-$monto_debe_cambio;
																										$monto_ant_saldo=$monto_haber2-$monto_debe2;		
																						
																									}
																									else
																									if($codigo=='R   ')
																									{
																										$monto_saldo=$monto_haber_cambio-$monto_debe_cambio;
																										$monto_ant_saldo=$monto_haber2-$monto_debe2;		
																									}
																									if($codigo=='CO  ')
																									{
																										$monto_saldo=$monto_debe_cambio-$monto_haber_cambio;
																										$monto_ant_saldo=$monto_debe2-$monto_haber2;		
																									}
																						}			
																																	
																			///sql para saldos cuentas normales
																						$sql_saldos1="update
																									saldo_contable
																								SET 
																								
																										saldo_inicio[".$mes."]= '$monto_saldo'
																								WHERE
																										cuenta_contable='$id_cuenta'
																								and
																									ano='$ano';
																								";
																			  ///
																			}//end tipo_comprobante
																			else
																			$sql_saldos1="";
																			  /////
																			  $sql_mod_cuentas="update
																									saldo_contable
																								SET 
																										debe[".$mes_comprobante."]= '$monto_debe2',
																										haber[".$mes_comprobante."]= '$monto_haber2'
																									
																								WHERE
																											cuenta_contable='$id_cuenta'
																								and
																											ano='2011';
																								$sql_saldos1			
																								"; 
																			 
																		
																		
																	  }//end de if mov	
												}//end cuenta_contable!=""					
									/******************************************************************************************************auxiliares*************************************/
											$id_aux=$_POST[contabilidad_comp_contabilidad_id];
											$id_cc=$id_cuenta;
											if($id_aux!="")
											{
														$sql_mov2="SELECT  
																		   (debe[".$mes_comprobante."])as debe,
																		   (haber[".$mes_comprobante."])as haber,
																		   (debe[".$mes."])as debe_cambio,
																		   (haber[".$mes."])as haber_cambio 
																FROM 
																		saldo_auxiliares
																WHERE
																	cuenta_contable='$id_cc'	
																AND	
																	cuenta_auxiliar='$id_aux'"	
																;
															
																//die($sql_mov2);
																			$rs_mov2=& $conn->Execute($sql_mov2);
																			if (!$rs_mov2->EOF) 
																		{
																			//
																			$debe_ant2=$row_comprobante->fields("monto_debito");
																			$haber_ant2=$row_comprobante->fields("monto_credito");
																			//
																			if($rs_mov2->fields("debe")!=0)
																				$monto_debe4 = $rs_mov2->fields("debe")-($debe_ant2);
																			else
																				 $monto_debe4=0;
																			if($rs_mov2->fields("haber")!=0)
																				$monto_haber4 = $rs_mov2->fields("haber")-($haber_ant2);
																			else
																				$monto_haber4=0;
																			if($rs_mov->fields("debe_cambio")!=0)
																			{
																			 	$monto_debe_cambio_aux=($rs_mov->fields("debe_cambio"))+($debe_ant2);
																			}
																			else
																				$monto_debe_cambio_aux=$debe_ant2;
																			//die($rs_mov->fields("debe")."-".($debe_ant));																	
																			if($rs_mov->fields("haber_cambio")!=0)
																				$monto_haber_cambio_aux=$rs_mov->fields("haber_cambio")+($haber_ant2);
																			else
																				$monto_haber_cambio_aux=$haber_ant2;
																			

												if($tipo_comprobante=='10')
													{
														if(($monto_debe_cambio_aux!="0")&&($monto_haber_cambio_aux=="0"))
														{
															$monto_saldo_aux=$monto_debe_cambio_aux;	
															$monto_saldo_aux_ant=$monto_debe4;	

														}
														if(($monto_haber_cambio_aux!="0")&&($monto_debe_cambio_aux=="0"))
														{
															$monto_saldo_aux=$monto_haber_cambio_aux;
															$monto_saldo_aux_ant=$monto_haber4;	
	
														}
														if(($monto_haber_cambio_aux!="0")&&($monto_debe_cambio_aux!="0"))
														{
																if(($codigo=='A   ')||($codigo=='G   '))
																{
																	$monto_saldo_aux=$monto_debe_cambio_aux-$monto_haber_cambio_aux;
																	$monto_saldo_aux_ant=$monto_debe4-$monto_haber4;	
			
																}
																else
																if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
																{
																	$monto_saldo_aux=$monto_haber_cambio_aux-$monto_debe_cambio_aux;
																	$monto_saldo_aux_ant=$monto_haber4-$monto_debe4;	
			
																}
																else
																if($codigo=='R   ')
																{
																	$monto_saldo_aux=$monto_haber_cambio_aux-$monto_debe_cambio_aux;
																	$monto_saldo_aux_ant=$monto_haber4-$monto_debe4;	
			
																}
																if($codigo=='CO  ')
																{
																	$monto_saldo_aux=$monto_debe_cambio_aux-$monto_haber_cambio_aux;
																	$monto_saldo_aux_ant=$monto_debe4-$monto_haber4;	
			
														
																}
														}	
//////////////////////////////////////sql_borrar_aux1/borrar5///////////////////////////////////////////////////////////////////////////////////////////////////////////
															$sql_act_inicial2="
																		update
																				saldo_auxiliares
																			SET 
																					
																				saldo_inicio[".$mes."]= '$monto_saldo_aux'
																			WHERE
																					
																					cuenta_auxiliar='$id_aux'
																			and	
																					cuenta_contable='$id_cc'			
																			and
																					ano='$ano'
																			";		


														}
													else
													{
														
														$sql_saldo_inicial_aux="";
														
														}	
													////////////////////
													$sql_act_aux="update
																					saldo_auxiliares
																				SET 
																						debe[".$mes_comprobante."]= '$monto_debe4',
																						haber[".$mes_comprobante."]= '$monto_haber4'
																						
																				WHERE
																					
																					cuenta_auxiliar='$id_aux'
																				AND
																					cuenta_contable='$id_cc'	
																				AND	
																					ano='2011';
																				$sql_act_inicial2	
																					";
																				
													///////////////////	
												
													
														}else
														$sql_act_aux="";
														//die($sql_act_aux);				
										}else
										$sql_act_aux="";
									
/******************************************************************************************************************************************************/

									$sql_mod_cuentas_todas=$sql_mod_cuentas_todas." ".$sql_mod_cuentas;
						$row_comprobante->MoveNext();	
			
					
						}
							$sql="
																	UPDATE	
																			movimientos_contables
																	set
																		fecha_comprobante='".$fecha."',
																		ultimo_usuario= '1',
																		ultima_modificacion= '".date("Y-m-d H:i:s")."'
																	WHERE	
																		movimientos_contables.numero_comprobante='$comprobante'								
																	;
																	$sql_mod_sumas_todas;
																	$sql_mod_cuentas_todas;
																	$sql_act_aux			
																	";	
														//	die($sql);
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
																	
movimientos_contables.id_tipo_comprobante='23'
and
																	movimientos_contables.estatus!='3'		

																	
												";
												
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
													
																
									
?>
<br>
