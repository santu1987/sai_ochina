<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$vector_comprobante = split( ",",$_POST[ids_comprobante] );
sort($vector_comprobante);
$contador=count($ids_comprobante);  ///$_POST['covertir_req_cot_titulo']
$is=0;
$monto_total=0;
while($is < $contador)
{	
			$id_comprobante=$vector_comprobante[$is];
							$sql_comprobante="SELECT   movimientos_contables.id_movimientos_contables,
													   cuenta_contable_contabilidad.id,
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
													   movimientos_contables.ultimo_usuario,
													   movimientos_contables.id_organismo,
													   movimientos_contables.ultima_modificacion,
													   movimientos_contables.estatus,
													   movimientos_contables.id_accion_central,
													   movimientos_contables.id_auxiliar,
													   tipo_comprobante.codigo_tipo_comprobante,
													   naturaleza_cuenta.codigo as codigo
													
												FROM movimientos_contables
												INNER JOIN
													tipo_comprobante	
												ON
													movimientos_contables.id_tipo_comprobante=tipo_comprobante.id
												INNER JOIN
													cuenta_contable_contabilidad
												ON 
													movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable		
												INNER JOIN
													naturaleza_cuenta
												ON
													cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
												where
													movimientos_contables.id_organismo = $_SESSION[id_organismo]
												and
													id_movimientos_contables=$id_comprobante
												";
							$row_comprobante=& $conn->Execute($sql_comprobante);
										
							/********************************************************************************************************************************************************************/
			if(!$row_comprobante->EOF)
			{
																		
							////////////////////////////////////////////////////-CASO A :VERIFICANDO EN LAS TABLAS DE SALDO CONTABLES////////////////////////////////////
																		$id_cc=$row_comprobante->fields("id");
///////////////////////////////////////////-- VERIFICANDO SI LA CUENTA CONTABLE TIENE CUENTA SUMA 
							$turnos=1;
							$contadores=0;
							$id_sumas=$id_cc;
							$debe_haber=$row_comprobante->fields("debito_credito");
							$fecha2=$row_comprobante->fields("fecha_comprobante");
							$codigo=$row_comprobante->fields("codigo");
							$fecha = split( "-",$fecha2 );
							$mes=$fecha[1];
							//die($fecha[0]);
							$ano=$fecha[0];
							if($debe_haber==1)
							{
								$monto_debito=$row_comprobante->fields("monto_debito");
								$monto_credito=0;
							}else
							if($debe_haber==2)
							{
								$monto_debito=0;
								$monto_credito=$row_comprobante->fields("monto_credito");
							}
							$tipo_saldo=$row_comprobante->fields("codigo_tipo_comprobante");
									while($turnos>$contadores)
									{
												$sqlw="select 
																*
															
															from
																	cuenta_contable_contabilidad 
															
															where id='$id_sumas'  	
															";				//	die($id_cc);
							
												$rs_suma=& $conn->Execute($sqlw);
												if (!$rs_suma->EOF) 
												{
														
													$suma_cuenta=$rs_suma->fields("id_cuenta_suma");
													if($suma_cuenta!="")
													{
															$sql_mov_suma="SELECT  
																	   (debe[".$mes."])as debe,
																	   (haber[".$mes."])as haber 
																FROM 
																		saldo_contable
																WHERE
																	cuenta_contable='$suma_cuenta'
																and
													                 ano='$ano'	
																";
										//						die($sql_mov_suma);
																			$rs_mov_suma=& $conn->Execute($sql_mov_suma);
																			if (!$rs_mov->EOF) 
																			{
																				$monto_debe_suma = $rs_mov_suma->fields("debe")+$monto_debito;
																				$monto_haber_suma = $rs_mov_suma->fields("haber")+$monto_credito;$cec++;
																				if($cec==2){
																					//die($sql_mov_suma);
																				//die($monto_haber_suma);
																				//			die($rs_mov_suma->fields("haber")."+".$monto_credito);
																				}
							///////////////////////////////////////////////// realizando saldo inicial////////////////////////////////////
							///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
							if($tipo_saldo=='10')
							{
								if(($monto_debe_suma!="0")&&($monto_haber_suma=="0"))
								{
									$monto_saldon=$monto_debe_suma;	
								}
								if(($monto_haber_suma!="0")&&($monto_debe_suma=="0"))
								{
									$monto_saldon=$monto_haber_suma;	
								}
								if(($monto_haber_suma!="0")&&($monto_debe_suma!="0"))
								{
									if(($codigo=='A   ')||($codigo=='G   '))
									{
										$monto_saldon=$monto_debe_suma-$monto_haber_suma;
									}
									else
									if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
									{
										$monto_saldon=$monto_haber_suma-$monto_debe_suma;
									}
									else
									if($codigo=='R   ')
									{
										$monto_saldon=$monto_haber_suma-$monto_debe_suma;
									}
									if($codigo=='CO  ')
									{
										$monto_saldon=$monto_debe_suma-$monto_haber_suma;
							
									}
								}
								$sql_saldo_ini_suma="
																									update
																											saldo_contable
																										SET 
																												saldo_inicio[".$mes."]= '$monto_saldon'
																										WHERE
																													cuenta_contable='$suma_cuenta'	and ano='$ano'	
								
								";
								}
							else
							{
								
								$sql_saldo_ini_suma="";
								
								}	
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							
																				$sql_mod_suma="update
																								saldo_contable
																							SET 
																									debe[".$mes."]= '$monto_debe_suma',
																									haber[".$mes."]= '$monto_haber_suma'
																							WHERE
																										cuenta_contable='$suma_cuenta'
																							and
																								ano='$ano'				
																										;
																							$sql_saldo_ini_suma
																							";
																			}
																			else
																			$sql_mod_suma="";	
													
													$turnos++;
													$id_sumas=$suma_cuenta;
													
													}else
													$sql_mod_suma="";
												}else
													$sql_mod_suma="";
										if($contadores==0)$sql_mod_sumas_todas=$sql_mod_suma;
											else	
												$sql_mod_sumas_todas=$sql_mod_sumas_todas.";".$sql_mod_suma;	
										$contadores=$contadores+1;	
							
									}//fin del whiler	
							//die($sql_mod_sumas_todas);			
							
																			$sql_mov="SELECT  
																							   (debe[".$mes."])as debe,
																							   (haber[".$mes."])as haber 
																						FROM 
																								saldo_contable
																						WHERE
																							cuenta_contable='$id_cc'
																						and
																								ano='$ano'	
																						";
																						//die($sql_mov);
																									$rs_mov=& $conn->Execute($sql_mov);
																									if (!$rs_mov->EOF) 
																									{
																										$monto_debe2 = $rs_mov->fields("debe")+$monto_debito;
																										$monto_haber2 = $rs_mov->fields("haber")+$monto_credito;
																										//die($rs_mov->fields("debe")."+".$monto_debito);
							///////////////////////////////////////////////// realizando saldo inicial////////////////////////////////////
							///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
							if($tipo_saldo=='10')
							{
								if(($monto_debe2!="0")&&($monto_haber2=="0"))
								{
									$monto_saldo=$monto_debe2;	
								}
								if(($monto_haber2!="0")&&($monto_debe2=="0"))
								{
									$monto_saldo=$monto_haber2;	
								}
								if(($monto_haber2!="0")&&($monto_debe2!="0"))
								{
										if(($codigo=='A   ')||($codigo=='G   '))
											{
												$monto_saldo=$monto_debe2-$monto_haber2;
											}
											else
											if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
											{
												$monto_saldo=$monto_haber2-$monto_debe2;
											}
											else
											if($codigo=='R   ')
											{
												$monto_saldo=$monto_haber2-$monto_debe2;
											}
											if($codigo=='CO  ')
											{
												$monto_saldo=$monto_debe2-$monto_haber2;
									
											}
								}			
								$sql_saldo_inicial="
																									update
																											saldo_contable
																										SET 
																												saldo_inicio[".$mes."]= '$monto_saldo'
																										WHERE
																													cuenta_contable='$id_cc' and
													ano='$ano'	
								
								";
								}
							else
							{
								
								$sql_saldo_inicial="";
								
								}	
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////
																							$sql_mod="update
																											saldo_contable
																										SET 
																												debe[".$mes."]= '$monto_debe2',
																												haber[".$mes."]= '$monto_haber2'
																										WHERE
																												cuenta_contable='$id_cc'
																												and
													ano='$ano';
																										$sql_saldo_inicial			
																													";
																									//die($sql_mod);
																									}
																									else
																									$sql_mod="";
																					//die($sql_mod);								
							/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							////////////////////////////////////////////////////-CASOB :VERIFICANDO EN LAS TABLAS DE SALDO AUXILIARES////////////////////////////////////////////////
																		//$id_cc=$_POST[contabilidad_auxiliares_db_id_cuenta];
																		$id_aux=$row_comprobante->fields("id_auxiliar");
																		if(($id_aux!="")&&($id_aux!="0"))
																		{
																				$sql_mov2="SELECT  
																								   (debe[".$mes."])as debe,
																								   (haber[".$mes."])as haber 
																							FROM 
																									saldo_auxiliares
																							WHERE
																								cuenta_contable='$id_cc'	AND	
																								cuenta_auxiliar='$id_aux'
																								and
																									ano='$ano'	
																							";
																										$rs_mov2=& $conn->Execute($sql_mov2);								
																										if (!$rs_mov2->EOF) 
																										{
																											$monto_debe3 = $rs_mov2->fields("debe")+$monto_debito;
																											$monto_haber3 = $rs_mov2->fields("haber")+$monto_credito;
																										}
							/////////////////////////////////////////////SALDO INICIAL PARA AUXILIARES
							///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
																			
																			if($tipo_saldo=='10')
																				{
																					if(($monto_debe3!="0")&&($monto_haber3=="0"))
																					{
																						$monto_saldo=$monto_debe3;	
																					}
																					if(($monto_haber3!="0")&&($monto_debe3=="0"))
																					{
																						$monto_saldo=$monto_haber3;	
																					}
																			if(($monto_haber3!="0")&&($monto_debe3!="0"))
																			{
																				if(($codigo=='A   ')||($codigo=='G   '))
																				{
																					$monto_saldo=$monto_debe3-$monto_haber3;
																				}
																				else
																				if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
																				{
																					$monto_saldo=$monto_haber3-$monto_debe3;
																				}
																				else
																				if($codigo=='R   ')
																				{
																					$monto_saldo=$monto_debe3-$monto_debe3;
																				}
																				if($codigo=='CO  ')
																				{
																					$monto_saldo=$monto_debe3-$monto_haber3;
																		
																				}
																			}	
																					$sql_saldo_inicial_aux="
																					update
																							saldo_auxiliares
																						SET 
																								saldo_inicio[".$mes."]= '$monto_saldo'
																						WHERE
																							cuenta_contable='$id_cc'	AND	
																								cuenta_auxiliar='$id_aux'
																							and
																								ano='$ano'		
							
							";
							//
																					}
																				else
																				{
																					
																					$sql_saldo_inicial_aux="";
																					
																					}	
																				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
																										
																										
																										
																								$sql_mod2="update
																												saldo_auxiliares
																											SET 
																													debe[".$mes."]= '$monto_debe3',
																													haber[".$mes."]= '$monto_haber3'
																											WHERE
																												cuenta_contable='$id_cc'	
																												and
																												cuenta_auxiliar='$id_aux'
			and	ano='$ano'	;
																											$sql_saldo_inicial_aux	
																												
																												";	
																												//die($sql_mod2);
																	//cuenta_contable='$id_cc' AND
																	}else
																	$sql_mod2="";
							/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							
																			$sql = "UPDATE
																								movimientos_contables
																							set
																								estatus='1'
																							where	
																								id_movimientos_contables='$id_comprobante'
and	ano_comprobante='$ano'	;
																							$sql_mod;
																							$sql_mod2;
																							$sql_mod_sumas_todas
																							";
																						
																			//die($sql);
																							
																							/*UPDATE
																									cheques
																									set
																										contabilizado='1',
																										fecha_contab='".date("Y-m-d H:i:s")."',
																										usuario_contab='".$_SESSION['id_usuario']."',
																										numero_comprobante_integracion='$numero_comprobante',
																										cuenta_contable_banco='$cuenta_contable'	
																									where
																										numero_cheque='$numero_cheque';*/
																		}
																		else
																		{	
																			
																			$responce="NoActualizo"."*".$debe."*".$haber."*".$comprobante."*".$resta;	
																			die($responce);
																		
																			}			
															//		die($sql);
																						if (!$conn->Execute($sql)) 
																						{
																							$responce='Error al Actualizar: '.$conn->ErrorMsg().$monto_saldo;
																						}
																						else
																						{
																							
																								$responce="Registrado";
																						}
																		
																			
//
$is++;
}
die($responce);

			/*}else
			{
			$responce="numero_existe"."*".$debe."*".$haber;
				die($responce);
			}*/
			
?>