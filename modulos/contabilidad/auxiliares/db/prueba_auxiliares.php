<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha =date("Y-m-d H:i:s") ;

/*$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);*/

		$ano=substr($fecha,0,4);
			$mes=substr($fecha,5,2);
			//$sesion=$_SESSION[id_organismo];
			//$comprobante=$_POST[contabilidad_comp_id_comprobante];
			$sesion=1;
			$comprobante='101111';
			
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
									movimientos_contables.id_organismo = $sesion
									and
									movimientos_contables.numero_comprobante=$comprobante								
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
												$sql_suma="select 
																	*
																from
																	cuenta_contable_contabilidad
																where
																	 id='$id_sumas'			
												";
												$row_mov=& $conn->Execute($sql_suma);
												//die($sql_suma);
												
												
								if(!$row_mov->EOF)
								{
																$suma_cuenta=$row_mov->fields("id_cuenta_suma");
												if($suma_cuenta!="")
												{					$sql_mov_suma="SELECT  
																												(debe[".$mes_comprobante."])as debe,
																												(haber[".$mes_comprobante."])as haber,
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
																												saldo_inicio[".$mes_comprobante."]= '$monto_ant_saldos',
																												saldo_inicio[".$mes."]= '$monto_saldos'
																										WHERE
																												cuenta_contable='$suma_cuenta'
																										and
																											ano='$ano'
																						";
																					//	die($sql_saldo_s);
																	}//fin tipo_comprtobante==10
																	
																										$sql_sumas_c="update
																															saldo_contable
																														SET 
																																debe[".$mes_comprobante."]= '$monto_debe3',
																																haber[".$mes_comprobante."]= '$monto_haber3',
																																debe[".$mes."]='$monto_debe_cs',
																																haber[".$mes."]='$monto_haber_cs'
																														WHERE
																																	cuenta_contable='$suma_cuenta'
																														and
																																	ano='$ano';
																																$sql_saldo_s"; 
																															//	echo($sql_sumas_c);
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
											echo($id_sumas."-");

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
																	//	die($sql_mov);
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
																										saldo_inicio[".$mes_comprobante."]= '$monto_ant_saldo',
																										saldo_inicio[".$mes."]= '$monto_saldo'
																								WHERE
																										cuenta_contable='$id_cuenta'
																								and
																									ano='$ano';
																								";
																			  ///
																			}//end tipo_comprobante
																			  /////
																			  $sql_mod_cuentas="update
																									saldo_contable
																								SET 
																										debe[".$mes_comprobante."]= '$monto_debe2',
																										haber[".$mes_comprobante."]= '$monto_haber2',
																										debe[".$mes."]='$monto_debe_cambio',
																										haber[".$mes."]='$monto_haber_cambio'
																								WHERE
																											cuenta_contable='$id_cuenta'
																								and
																											ano='$ano';
																											
																								$sql_saldos1
																								"; 
			/*																			die($sql_mod_cuentas);
			*/         
																			  //////	
																		
																		
																	  }//end de if mov	
												}//end cuenta_contable!=""					
/******************************************************************************************************auxiliares*************************************/
											$id_aux=5;
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
																					
																					saldo_inicio[".$mes_comprobante."]= '$monto_saldo_aux_ant',
																					saldo_inicio[".$mes."]= '$monto_saldo_aux'
																			WHERE
																					
																					cuenta_auxiliar='$id_aux'
																			and	
																					cuenta_contable='$id_cc'			
																			and
																					ano='$ano_comprobante'
																			";		


														}
													else
													{
														
														$sql_saldo_inicial_aux="";
														
														}	
													$sql_act_aux="update
																					saldo_auxiliares
																				SET 
																						debe[".$mes_comprobante."]= '$monto_debe4',
																						haber[".$mes_comprobante."]= '$monto_haber4',
																						debe[".$mes."]='$monto_debe_cambio_aux',
																						haber[".$mes."]='$monto_haber_cambio_aux'
																				WHERE
																					
																					cuenta_auxiliar='$id_aux';
																				AND
																					cuenta_contable='$id_cc'	
																				AND	
																					ano='$ano_comprobante';
																				$sql_act_inicial2	
																					";	
													
														}else
														$sql_act_aux="";
														//die($sql_act_aux);				
										}else
										$sql_act_aux="";
									
/******************************************************************************************************************************************************/
									$sql_mod_cuentas_todas=$sql_mod_cuentas_todas." ".$sql_mod_cuentas;
						$row_comprobante->MoveNext();	
			
					
						}//die($sql_mod_sumas_todas);
																/*		
																	;*/
							$sql="UPDATE	
																			movimientos_contables
																	set
																		
																		fecha_comprobante='".$fecha."',
																		ultimo_usuario= ".$sesion.",
																		ultima_modificacion= '".date("Y-m-d H:i:s")."'
																	WHERE	
																		movimientos_contables.numero_comprobante='$comprobante'						
																	
																	$sql_mod_sumas_todas;
																	$sql_mod_cuentas_todas;
																	$sql_act_aux			
																	";	
																	die($sql);
			/*if (!$conn->Execute($sql)) 
				die ('Error al Actualizar: '.$conn->ErrorMsg());
			
			else
				die("Actualizado");*/
													
																//
															//die($sql);		
											//	die($sql_mod_cuentas_todas);
			
			
			/*$Sql="
			select * from saldo_auxiliares order by id_saldo_auxiliar";
			
			$row=& $conn->Execute($Sql);
			while (!$row->EOF)
			{
				$id=$row->fields('cuenta_auxiliar');
			
				$Sql2="
				select * from auxiliares where id_auxiliares='$id'
				 order by cuenta_auxiliar";
				$row2=& $conn->Execute($Sql2);
				if($row2->EOF)
				{
					echo($id."-");	
					echo($Sql2);
				}
			$row->MoveNext();
			}*/
			
			//////SELECCIONANDO TODA LA INFORMACION QUE NECESITO;
			/*$sql_rel="SELECT id, id_auxiliar, id_contab
			  FROM rel_aux_cont";
			$row=& $conn->Execute($sql_rel);
			while (!$row->EOF)
			{
				$id_cuenta=$row->fields('id_contab');
				$id_auxiliar=$row->fields('id_auxiliar');
				$sql_saldos="SELECT * FROM saldo_auxiliares
							where
							cuenta_auxiliar='$id_auxiliar'
							and
							cuenta_contable='$id_cuenta'
				";
				//die($sql_saldos);
					$row2=& $conn->Execute($sql_saldos);
					if($row2->EOF)
					{
			
			
						$Sql_intermedio="INSERT INTO
											saldo_auxiliares
											(
												id_organismo,
												ano,
												cuenta_contable,
												cuenta_auxiliar,
												saldo_inicio,
												debe,
												haber,
												comentarios,
												ultimo_usuario,
												ultima_modificacion
											)
											values
											(
												 1,
												 2010,
												 '$id_cuenta',
												 '$id_auxiliar',
												 '{0,0,0,0,0,0,0,0,0,0,0,0}',
												 '{0,0,0,0,0,0,0,0,0,0,0,0}',
												 '{0,0,0,0,0,0,0,0,0,0,0,0}',
												 '',
												 53,
												'".date("Y-m-d H:i:s")."'			
												);
												";
					
							if (!$conn->Execute($Sql_intermedio)) 
								//die ('Error al Registrar: '.$sql);
							die ('Error al Registrar: '.$conn->ErrorMsg().$Sql_intermedio);
							else
							$cont=$cont+1;
					}							
				$row->MoveNext();
				$cont2=$cont2+1;
			
				
			}
			die("Cantidad de registros registrados : ".$cont." de ".$cont2);		*/						
									
?>