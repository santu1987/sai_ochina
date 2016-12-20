<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
////////////////////////

					//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------			
					
					//---------------busqueda del ultimo CHEQUE----------------
					$sql_ultimo_emitido = "SELECT 
												ultimo_emitido,cantidad_cheques,secuencia,ultimo_emitido,primer_cheque
										   FROM 
												chequeras 
											WHERE
												cuenta='$_POST[tesoreria_cheques_manual_orden_db_n_cuenta]'
											AND 
												id_banco='$_POST[tesoreria_cheques_manual_orden_db_banco_id_banco]'
											AND
												estatus='1'		
										  ";
					
						$row_emitido= $conn->Execute($sql_ultimo_emitido);
						if(!$row_emitido->EOF)	
						{		
					
									$cantidad=$row_emitido->fields("cantidad_cheques");
									$n_cheque=$row_emitido->fields("ultimo_emitido");
									$secuencia=$row_emitido->fields("secuencia");
									$secuencia2=$secuencia;
									$n_cheque_resultado=intval($n_cheque)+1;
									$n_ultimo=intval($n_cheque_resultado)+1;
									
									$primer_cheque=$row_emitido->fields("primer_cheque");
									$primer=intval($primer_cheque);
									$total=	$n_cheque_resultado-$primer;
									//verificando en el cas que se termine la chequera n_cheque>cantidad
										//if($n_cheque>$cantidad)
										if($total>$cantidad)
										{
										/*echo($total);
										die($cantidad);		
										*/		$proximo_emitir=0;
												$estatus='3';
												
												$secuencia=$secuencia+1;
												
												//
												$sql_inactivar_chequeras=" UPDATE chequeras
																			SET
																					ultimo_emitido='$proximo_emitir',
																					estatus=$estatus,
																					fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																					ultimo_usuario=".$_SESSION['id_usuario']."
																			WHERE
																				id_banco='$_POST[tesoreria_cheques_manual_orden_db_banco_id_banco]'
																			AND	
																				cuenta='$_POST[tesoreria_cheques_manual_orden_db_n_cuenta]'
																			AND
																				secuencia='$secuencia2'";
																	
															if (!$conn->Execute($sql_inactivar_chequeras))
																{	die ('Error inactivando' );}
															$inactiva2=true;
												//
												$sql_secuencia="SELECT
																	*
																FROM 
																	chequeras 
																WHERE
																	id_banco='$_POST[tesoreria_cheques_manual_orden_db_banco_id_banco]'
																AND	
																	cuenta='$_POST[tesoreria_cheques_manual_orden_db_n_cuenta]'
																AND
																	secuencia='$secuencia'";
													$row_secuencia= $conn->Execute($sql_secuencia);
													//echo($sql_secuencia);
												if(!$row_secuencia->EOF)	
												{	
														/*$sql_activar_chequeras=" UPDATE chequeras
																				SET
																						estatus='1',
																						fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																						ultimo_usuario=".$_SESSION['id_usuario']."
																				WHERE
																					id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
																				AND	
																					cuenta='$_POST[tesoreria_cheques_db_n_cuenta]'
																				AND
																					secuencia='$secuencia'
																				";
														
															if (!$conn->Execute($sql_activar_chequeras))
																{	die ('Error activando chequera' );}*/
															
														$inactiva=true;
															//////////////////////////////////////////////////
															
												
												}
															
										}
									if(($inactiva==true)&&($inactiva2==true))
									{
									$responce="inactiva"."*".$secuencia;
									die($responce);
					//				 die('inactiva');
									}else
									if((!$inactiva)&&($inactiva2==true))
									{
					//					die('inactiva2');	
										$responce="inactiva2"."*".$secuencia;
										die($responce);
									}
						}

?>