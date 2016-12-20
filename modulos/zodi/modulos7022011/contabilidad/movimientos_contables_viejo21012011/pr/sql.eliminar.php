<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[contabilidad_comp_pr_fecha];
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
$tipo_comp=$_POST[contabilidad_comp_pr_tipo];
$saldol=0;
$id_comprobante=$_POST[contabilidad_comp_id_comprobante];
$tipo_comprobante=$_POST[contabilidad_comp_pr_tipo];
$id_auxiliar=$_POST[contabilidad_comp_contabilidad_id];
$cuenta_auxiliar=$_POST[contabilidad_comp_pr_auxiliar];
$comprobante_x=$_POST['contabilidad_comp_pr_tipo'].$_POST['contabilidad_comp_pr_numero_comprobante'];
///die($id_auxiliar."-".$cuenta_auxiliar);
$sql_comprobante_monto="select 
								movimientos_contables.id_movimientos_contables,
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
								movimientos_contables.id_organismo,
								cuenta_contable_contabilidad.id,
								cuenta_contable_contabilidad.id_cuenta_suma,
								naturaleza_cuenta.codigo  AS codigo
								
						FROM
							 movimientos_contables
						INNER JOIN
						

									cuenta_contable_contabilidad
						on
							    cuenta_contable_contabilidad.cuenta_contable=movimientos_contables.cuenta_contable
						inner join
											naturaleza_cuenta
								on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id			 	 
						where
							 id_movimientos_contables='$id_comprobante'	 					
";
//die($sql_comprobante_monto);
$row_comprobante=& $conn->Execute($sql_comprobante_monto);
if(!$row_comprobante->EOF)
{

		$id_cuenta=$row_comprobante->fields("id");
		$id_sumas=$row_comprobante->fields("id_cuenta_suma");
		//die($sql_comprobante_monto);
		/////////////////////////////////////-eliminando de la cuenta auxiliar-/////////////////////////////////////////////
		//die($id_auxiliar);
		if(($id_auxiliar!='')&&($id_auxiliar!='0'))
		{
			if($row_comprobante->fields("debito_credito")==1)
							{
								$saldo_viejo=$row_comprobante->fields("monto_debito");
								$sql_saldo_cuenta_aux="SELECT 
															(debe[".$mes."])as debe,
															(saldo_inicio[".$mes."])as saldo_inicio
														   FROM
																saldo_auxiliares
															WHERE
																cuenta_contable='$id_cuenta'
															and
																cuenta_auxiliar='$id_auxiliar'	
															and
																ano='$ano'		
													";
													$row_saldo_aux=& $conn->Execute($sql_saldo_cuenta_aux);
													$saldo_actual_aux=$row_saldo_aux->fields("debe");
													$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
													$saldo1_aux=$saldo_actual_aux-$saldo_viejo;
													if($tipo_comp==10)
													{
														$saldo_inicia=$saldo_inicio_aux-$saldo_viejo;
														$sql_saldo_inicial_aux="
																			update
																				saldo_auxiliares
																			SET 
																					saldo_inicio[".$mes."]= '$saldo1_aux'
																			WHERE
																				cuenta_contable='$id_cuenta'	AND	
																					cuenta_auxiliar='$id_auxiliar'";
													
													}else
													$sql_saldo_inicial_aux="";
													//haciendo el update con los auxiliares
													$sql_saldos_aux="update
																			saldo_auxiliares
																		SET 
																				debe[".$mes."]= '$saldo1_aux'
																		WHERE
																					cuenta_contable='$id_cuenta'
																		and
																					cuenta_auxiliar='$id_auxiliar'
																		and
																				ano='$ano';
																		$sql_saldo_inicial_aux		
																				";

							}
								
							else
							if($row_comprobante->fields("debito_credito")==2)
							{
								$saldo_viejo=$row_comprobante->fields("monto_credito");
								$sql_saldo_cuenta_aux="SELECT 
												   (haber[".$mes."])as haber ,
												   (saldo_inicio[".$mes."])as saldo_inicio
															   FROM
													saldo_auxiliares
												WHERE
													cuenta_contable='$id_cuenta'
												and
													cuenta_auxiliar='$id_auxiliar'		
												and
													ano='$ano'	
													";
												// 	die($saldo_actual_aux);
											$row_saldo_aux=& $conn->Execute($sql_saldo_cuenta_aux);
											$saldo_actual_aux=$row_saldo_aux->fields("haber");
											$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
											$saldo1_aux=$saldo_actual_aux-$saldo_viejo;
													if($tipo_comp==10)
													{
														$saldo_inicia=$saldo_inicio_aux-$saldo_viejo;
														$sql_saldo_inicial_aux="
																			update
																				saldo_auxiliares
																			SET 
																					saldo_inicio[".$mes."]= '$saldo1_aux'
																			WHERE
																				    cuenta_contable='$id_cuenta'	
																			AND	
																					cuenta_auxiliar='$id_auxiliar'
																			and
																					ano='$ano'";
													
													}else
													$sql_saldo_inicial_aux="";
											//haciendo el update con los auxiliares
											$sql_saldos_aux="update
																	saldo_auxiliares
																SET 
																		haber[".$mes."]= '$saldo1_aux'
															WHERE
																			cuenta_contable='$id_cuenta'
															and
																			cuenta_auxiliar='$id_auxiliar'
															and				
															ano='$ano';
															$sql_saldo_inicial_aux
															";
							
							}
		}
		///////////////////////////////////////////-- VERIFICANDO SI LA CUENTA CONTABLE TIENE CUENTA SUMA 
		$turnos=1;
		$contadores=0;
		
			if (!$row_comprobante->EOF) 
			{
				$codigo=$row_comprobante->fields("codigo");
				$debito_credito=$row_comprobante->fields("debito_credito");
			}						
		/************************************/
				while(($turnos>$contadores)&&($id_sumas!=""))
				{
												$sqlw="select 
																*
															
															from
																	cuenta_contable_contabilidad 
															
															where id='$id_sumas'";				//	die($id_cc);
								//die($id_sumas);
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
																	cuenta_contable='$id_sumas'
																";
																//die($sql_mov_suma);
																			$rs_mov_suma=& $conn->Execute($sql_mov_suma);
																			if (!$rs_mov->EOF) 
																			{
																				
																				
																				if($debito_credito==1)
																				{
																					$saldo_viejo=$row_comprobante->fields("monto_debito");
																					$monto_debe_suma = $rs_mov_suma->fields("debe")-$saldo_viejo;
																					$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																					////////////////////////////////////////////////////////////////////////	
																						if($tipo_comp==10)
																						{
																							$saldo_inicia=$saldo_inicio_suma-$saldo_viejo;
																							$sql_saldo_inicial_suma="
																												update
																													saldo_contable
																												SET 
																														saldo_inicio[".$mes."]= '$saldo_inicia'
																												WHERE
																													 cuenta_contable='$id_sumas'
																												AND	
																													 ano='$ano'";
																						
																						}else
																						$sql_saldo_inicial_suma="";
																					///////////////////////////////////////////////////////////////////////	
																					$sql_act_saldos_sumas="update
																											saldo_contable
																										SET 
																												debe[".$mes."]= '$monto_debe_suma'
																										WHERE
																													cuenta_contable='$id_sumas'
																										and
																												ano='$ano';
																										$sql_saldo_inicial_suma
																										";
																												
																				}
																				else
																				if($debito_credito==2)
																				{
																					$saldo_viejo=$row_comprobante->fields("monto_credito");
																					$monto_haber_suma = $rs_mov_suma->fields("haber")-$saldo_viejo;
																					$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																					////////////////////////////////////////////////////////////////////////	
																						if($tipo_comp==10)
																						{
																							$saldo_inicia=$saldo_inicio_suma-$saldo_viejo;
																							$sql_saldo_inicial_suma="
																												update
																													saldo_contable
																												SET 
																														saldo_inicio[".$mes."]= '$saldo_inicia'
																												WHERE
																													 cuenta_contable='$id_sumas'
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
																													cuenta_contable='$id_sumas'
																										and
																												ano='$ano';
																										$sql_saldo_inicial_suma		
																												";
																				}
								
																			
													
																			}//end rs_mov_suma
																			else
																			$sql_act_saldos_sumas="";	
																					
															$turnos++;
															$id_sumas=$suma_cuenta;
													
													
													}//end suma_cuenta
													else
													$sql_act_saldos_sumas="";
												
										if($contadores==0)$sql_mod_sumas_todas=$sql_act_saldos_sumas;
											else	
												$sql_mod_sumas_todas=$sql_mod_sumas_todas.";".$sql_act_saldos_sumas;	
										$contadores=$contadores+1;	
					}//sino es fin de archivo					
		
				}//fin del whiler	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////										
						//	die($row_comprobante->fields("debito_credito"));
							if($row_comprobante->fields("debito_credito")==1)
							{
								$saldo_viejo=$row_comprobante->fields("monto_debito");
								$sql_saldo_cuenta="SELECT 
												(debe[".$mes."])as debe,
												(saldo_inicio[".$mes."])as saldo_inicio
											   FROM
													saldo_contable
												WHERE
													cuenta_contable='$id_cuenta'
												and
													ano='$ano'		
													";
								$row_saldo=& $conn->Execute($sql_saldo_cuenta);
								$saldo_actual=$row_saldo->fields("debe");
								$totem="debe";
								$saldo_inicio=$row_saldo->fields("saldo_inicio");
							}
								
							else
							if($row_comprobante->fields("debito_credito")==2)
							{
								$saldo_viejo=$row_comprobante->fields("monto_credito");
								$sql_saldo_cuenta="SELECT 
												   (haber[".$mes."])as haber,
             								       (saldo_inicio[".$mes."])as saldo_inicio 
												 FROM
													saldo_contable
												WHERE
													cuenta_contable='$id_cuenta'
												and
													ano='$ano'	
													";
								$row_saldo=& $conn->Execute($sql_saldo_cuenta);
								$saldo_actual=$row_saldo->fields("haber");
								$totem="haber";
								$saldo_inicio=$row_saldo->fields("saldo_inicio");
							}
								$saldo1=$saldo_actual-$saldo_viejo;
								$saldo_inicial=$saldo_inicio-$saldo_viejo;

								//echo($saldo1);
					
					if($totem=="debe")
					{
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						if($tipo_comp==10)
						{
							$sql_saldo_inicial="
												update
													saldo_contable
												SET 
														saldo_inicio[".$mes."]= '$saldo_inicial'
												WHERE
													 cuenta_contable='$id_cuenta'
												AND	
													 ano='$ano'";
						
						}else
						$sql_saldo_inicial_suma="";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$sql_act_saldos="update
											saldo_contable
										SET 
												debe[".$mes."]= '$saldo1'
										WHERE
													cuenta_contable='$id_cuenta'
										and
												ano='$ano';
										$sql_saldo_inicial		
												";
						
					}	
					else
					if($totem=="haber")
					{
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						if($tipo_comp==10)
						{
							$sql_saldo_inicial_suma="
												update
													saldo_contable
												SET 
														saldo_inicio[".$mes."]= '$saldo_inicial'
												WHERE
													 cuenta_contable='$id_cuenta'
												AND	
													 ano='$ano'";
						
						}else
						$sql_saldo_inicial_suma="";
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

						$sql_act_saldos="update
											saldo_contable
										SET 
												haber[".$mes."]= '$saldo1'
										WHERE
													cuenta_contable='$id_cuenta'
										and
												ano='$ano'
										;$sql_saldo_inicial_suma
										";
					}		
					$sql_eliminar_movimientos="DELETE 	from movimientos_contables where id_movimientos_contables='$id_comprobante';
												$sql_act_saldos;
												$sql_mod_sumas_todas;
												$sql_saldos_aux
												";				
					
					
				//	die($sql_eliminar_movimientos);	
		if ($conn->Execute($sql_eliminar_movimientos)) 
		{
			
			//die("Eliminado");
			$sql_sumas=" SELECT
								SUM(monto_debito) as debe,
								SUM(monto_credito) as haber
							from
								movimientos_contables
							where numero_comprobante='$comprobante_x'
							and
								
			movimientos_contables.id_tipo_comprobante='$_POST[contabilidad_comp_pr_tipo_id]'
			
								
			";
			
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
				$debe=number_format($row_sumas->fields("debe"),2,',','.');
				$haber=number_format($row_sumas->fields("haber"),2,',','.');
				$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
				$resta=number_format($resta,2,',','.');
				$responce="Eliminado"."*".$debe."*".$haber."*".$resta;
				die($responce);
			}
			else
			die("elimino_con_errores");
			
												
		
		
		}else
		{
		echo("ExisteRelacion");
		$responce=$sql_eliminar_movimientos."*".$debe."*".$haber."*".$resta;
				die($responce);
		}	
			
}
?>			