<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//$fecha = date("Y-m-d H:i:s");
$fecha=$_POST['fecha_oculta'];
function strpost($cadena,$cadena2)
{
	
	$vector=split(";",$cadena);
	$cuantos= count($vector);
	$is=0;
	while($is<$cuantos)
	{
		//echo($vector[$is]);
		if($cadena2==$vector[$is])
		{
			$cont_cadena++;
			//echo($vector[$is]);
		}	
    	$is++;
	}
	
return($cont_cadena);

}
/*$ano=substr($fecha,0,4);
$mes=substr($fecha,5,2);*/
$mes=substr($fecha,3,2);
$ano=substr($fecha,6,4);

$sql_act_saldos2="";
//$monto = str_replace(".","",$_POST[cuentas_por_pagar_db_facturas_total]);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$tipo_comp=$_POST[cuentas_por_pagar_integracion_tipo_id];
	if($tipo_comp!="")
	{
	$sql_comprobante="select numero_comprobante_integracion,codigo_tipo_comprobante
												 from 
														tipo_comprobante
						where
												id='$tipo_comp'								
											";
						//die($sql_comprobante);
								$row_comprobante=& $conn->Execute($sql_comprobante);
								if(!$row_comprobante->EOF)
								{
									$numero_comprobantex=$row_comprobante->fields("numero_comprobante_integracion");	
									$cod_tipo1=$row_comprobante->fields("codigo_tipo_comprobante");
									if(($numero_comprobantex!="")&&($numero_comprobantex!="0000"))
										$numero_comprobante3=$numero_comprobantex-1;			
									else
									if($numero_comprobantex=="0000")
										$numero_comprobante3="0001";
										//echo($numero_comprobantex);
									$valor_medida1=strlen($numero_comprobante3);													//echo($numero_comprobantex3);
																			
																			//	echo($valor_medida);
																			if($valor_medida1==1)
																			{
																				$numero_comprobante3="000".$numero_comprobante3;
																			}
																			else
																			if($valor_medida1==2)
																			{
																				$numero_comprobante3="00".$numero_comprobante3;
																			}
																			else	
																			if($valor_medida1==3)
																			{
																						$numero_comprobante3="0".$numero_comprobante3;
																			}
																			
																			$numero_comprobante=$cod_tipo1.$numero_comprobante3;
																			//die($numero_comprobante);

								$sql_actualizar_comprobante="UPDATE
																			tipo_comprobante	
																		set
																			numero_comprobante_integracion='$numero_comprobante3'
																	where
																		id='$tipo_comp'";
								}else
								die("no_comp_int");
          }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$numero_compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
if($numero_compromiso!="")
{
$where="AND numero_compromiso='$numero_compromiso'";
}			
$numero_comprobante_mov=$_POST['cuentas_por_pagar_numero_comprobante_cuenta_orden'];

if($numero_comprobante_mov!="")
{
	
	/*$sql_prinicial="SELECT movimientos_contables.id_movimientos_contables,
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
						   tipo_comprobante.codigo_tipo_comprobante as codigo
					FROM movimientos_contables
					
					inner join
						 cuenta_contable_contabilidad
					on
					movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
					inner join
					tipo_comprobante
					on
					tipo_comprobante.id=movimientos_contables.id_tipo_comprobante	
					where
						movimientos_contables.id_organismo=".$_SESSION["id_organismo"]."
					and
						movimientos_contables.numero_comprobante='$numero_comprobante_mov'";
	//die($sql_prinicial);
					$row_principal=& $conn->Execute($sql_prinicial);
					while(!$row_principal->EOF)
					{
							///////////////////////////////////////////-- VERIFICANDO SI LA CUENTA CONTABLE TIENE CUENTA SUMA 
							$turnos=1;
							$contadores=0;
							
							
								$codigo=$row_principal->fields("codigo");
								$debito_credito=$row_principal->fields("debito_credito");
								$id_cuenta=$row_principal->fields("id");
								$id_sumas=$row_principal->fields("id_cuenta_suma");
	//die($id_sumas);
							$monto_iva_causar=($row_fields->fields("porcentaje_iva")*$row_fields->fields("monto_base_imponible"))/100;

							
							while(($turnos>$contadores)&&($id_sumas!=""))
							{
															$sqlw="select 
																			*
																		
																		from
																				cuenta_contable_contabilidad 
																		
																		where id='$id_sumas'";				
																		//die($sqlw);
											//die($id_sumas);
															$rs_suma=& $conn->Execute($sqlw);
															if (!$rs_suma->EOF) 
															{
																	
																$suma_cuenta=$rs_suma->fields("id_cuenta_suma");
																//echo($suma_cuenta."-");
																if($id_sumas!="")
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
																			/*if($suma_cuenta=='936')
																			die($sql_mov_suma);
																						$rs_mov_suma=& $conn->Execute($sql_mov_suma);
																						if (!$rs_mov->EOF) 
																						{
																							
																							
																							if($debito_credito==1)
																							{
																								$saldo_viejo=$row_principal->fields("monto_debito");
																								$monto_debe_suma = $rs_mov_suma->fields("debe")-$saldo_viejo;
																								///////////////////////////////////////////////////////////////////////	
																								$sql_act_saldos_sumas="update
																														saldo_contable
																													SET 
																															debe[".$mes."]= '$monto_debe_suma'
																													WHERE
																																cuenta_contable='$id_sumas'
																													and
																															ano='$ano';
																													";
																													
																								//	die($sql_act_saldos_sumas);						
																							}
																							else
																							if($debito_credito==2)
																							{
																								$saldo_viejo=$row_principal->fields("monto_credito");
																								$monto_haber_suma = $rs_mov_suma->fields("haber")-$saldo_viejo;
																							///////////////////////////////////////////////////////////////////////	
																								$sql_act_saldos_sumas="update
																														saldo_contable
																													SET 
																															haber[".$mes."]= '$monto_haber_suma'
																													WHERE
																																cuenta_contable='$id_sumas'
																													and
																															ano='$ano';
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
							$sql_mod_sumas_todas2=$sql_mod_sumas_todas2.";".$sql_mod_sumas_todas;
							if($row_principal->fields("debito_credito")==1)
							{
								$saldo_viejo=$row_principal->fields("monto_debito");
								$total_mov_debe=$saldo_viejo-$saldo_pag;
								
								$sql_saldo_cuenta="SELECT 
												(debe[".$mes."])as debe
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
							}
								
							else
							if($row_principal->fields("debito_credito")==2)
							{
								$saldo_viejo=$row_principal->fields("monto_credito");
								$total_mov_haber=$saldo_viejo-$saldo_pag;
								
								$sql_saldo_cuenta="SELECT 
												   (haber[".$mes."])as haber
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
							}	
							$saldo1=$saldo_actual-$saldo_viejo;
							if($totem=="debe")
							{									
							     $sql_act_saldos="update
													saldo_contable
												SET 
														debe[".$mes."]= '$saldo1'
												WHERE
															cuenta_contable='$id_cuenta'
												and
														ano='$ano';
														";
							}	
							else
							if($totem=="haber")
							{
								$sql_act_saldos="update
													saldo_contable
												SET 
														haber[".$mes."]= '$saldo1'
												WHERE
															cuenta_contable='$id_cuenta'
												and
														ano='$ano'
												";
							}		
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////										
						$sql_act_saldos2=$sql_act_saldos2.";".$sql_act_saldos;
						//die($sql_act_saldos2);
						$row_principal->MoveNext();
					}*/
	$sql_eliminar="delete from integracion_contable where numero_comprobante='$numero_comprobante_mov'";
	//die($sql_eliminar);
	/*if (!$conn->Execute($sql_eliminar)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());*/
}
$sql_docu="select documentos_cxp.id_documentos
				from
					 documentos_cxp
				where 
						numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'	 
					 ";
	$row_docu=& $conn->Execute($sql_docu);
	$id_documento=$row_docu->fields("id_documentos");
/// verificando si el documento esta integrado
$sql_docu="select id,integracion_contable.numero_comprobante
				from
					
					 integracion_contable
				inner join
					 documentos_cxp
				on
					documentos_cxp.numero_comprobante=integracion_contable.numero_comprobante	 
				where 
						documentos_cxp.id_documentos='$id_documento'";	 
			
$row_docu=& $conn->Execute($sql_docu);
if($row_docu->EOF)
{
	die("documento_integrado");
}
else
if(!$row_docu->EOF)
{
	$n_comprobante=$row_docu->fields("numero_comprobante");
	$sql_eliminar2="delete from integracion_contable where numero_comprobante='$n_comprobante'";
	/*if (!$conn->Execute($sql_eliminar)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());*/
}
			 
/////////////////////////////////////verificando que no tenga orden cerrada///////////
$sql_doc1="SELECT
					*	
			FROM	
					documentos_cxp 
			INNER JOIN
					organismo
				ON
					documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
					orden_pago
				ON
					documentos_cxp.orden_pago=orden_pago.orden_pago 					
			WHERE
						
					
					documentos_cxp.id_organismo=".$_SESSION["id_organismo"]."
			AND
					orden_pago.estatus='2'
			AND
					numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'	
			$where					
			";													
$row_doc1=& $conn->Execute($sql_doc1);
	
if(!$row_doc1->EOF)
{
	die("tiene_orden_cerrada");
}
//////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////verificando que no tenga cheque//////////////////////////////////
$sql_doc1="SELECT
					*	
			FROM	
					documentos_cxp 
			INNER JOIN
					organismo
				ON
					documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
					orden_pago
				ON
					documentos_cxp.orden_pago=orden_pago.orden_pago 					
			WHERE
						
					numero_compromiso='$numero_compromiso'
			AND
					documentos_cxp.id_organismo=".$_SESSION["id_organismo"]."
			AND
					orden_pago.cheque!='0'
			AND
					numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'				
			";													
$row_doc1=& $conn->Execute($sql_doc1);
	
if(!$row_doc1->EOF)
{
	die("tiene_cheque");
}
//////////////////////////////////////////////////////////////////////
if($numero_compromiso!="")
{
	$sql_doc="SELECT
						*	
				FROM	
						documentos_cxp 
				INNER JOIN
						organismo
					ON
						documentos_cxp.id_organismo=organismo.id_organismo
				WHERE
							
						numero_compromiso='$numero_compromiso'
				AND
						documentos_cxp.id_organismo=".$_SESSION["id_organismo"]."
				AND
						numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'		
							
	";	
}
else
{
	$sql_doc="SELECT
						*	
				FROM	
						documentos_cxp 
				INNER JOIN
						organismo
					ON
						documentos_cxp.id_organismo=organismo.id_organismo
				WHERE
							
						documentos_cxp.id_organismo=".$_SESSION["id_organismo"]."
				AND
						numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'";		

}
$row_doc=& $conn->Execute($sql_doc);
	
if(!$row_doc->EOF)
{									
	$doc=$row_doc->fields("id_documentos");				
////////////////////////////////////////////////////////-ACTUALIZANDO LOS DOCUMENTOS SELECCIONADOS-///////////////////////////////////////////////////////////
if($_POST['cuentas_por_pagar_db_compromiso_n']!="")
{
$numero_compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
$monto_restar0= str_replace(".","",$_POST[cuentas_por_pagar_db_monto_bruto]);		
$monto_restar=str_replace(",",".",$monto_restar0);	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						id_unidad_ejecutora,
						id_proyecto_accion_centralizada,
						id_accion_especifica,
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
						\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
					where
						\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
				$row_orden_compra=& $conn->Execute($sql);
			//die($sql);
$conta_tor=0;
	while(!$row_orden_compra->EOF)
	{				
					
					//////////////////////////////comprobando que el monto de la factura no sea mayor al compromiso ...comprobandolo nuevamente
					/////////////////////////////ya que al relacionarse con un numero de compromiso se comprueba esto
					//////////////////////////A/////////////////////BUSCANDO TOTALES DE LAS ORDENES  DE COMPRA/SERVICIO COMPROMETIDAS
								$compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
								$sql_cmp="SELECT 
									\"orden_compra_servicioE\".numero_compromiso, 
									\"orden_compra_servicioE\".numero_pre_orden,
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
									\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
								where
									\"orden_compra_servicioE\".numero_compromiso='$compromiso'
								AND 
													(\"orden_compra_servicioD\".ano = '".$ano."')
												AND
													\"orden_compra_servicioD\".partida = '".$row_orden_compra->fields("partida")."'  
												AND	
													\"orden_compra_servicioD\".generica = '".$row_orden_compra->fields("generica")."'
												AND	
													\"orden_compra_servicioD\".especifica = '".$row_orden_compra->fields("especifica")."'  
												AND
													\"orden_compra_servicioD\".subespecifica = '".$row_orden_compra->fields("subespecifica")."'	
												
									";
				//die($sql_cmp);
							$row_orden_compra_datos_monto=& $conn->Execute($sql_cmp);
							$total_renglon=0;
							while(!$row_orden_compra_datos_monto->EOF)
							{
								$total=$row_orden_compra_datos_monto->fields("monto")*$row_orden_compra_datos_monto->fields("cantidad");
								//$iva=$total*($row_orden_compra_datos_monto->fields("impuesto")/100);
								$iva=0;
								$total_total=$total+$iva;
								
								$total_renglon=$total_renglon+$total_total;
								$total_renglon2=$total_renglon2+$total_renglon;
								$row_orden_compra_datos_monto->MoveNext();
							}
				
		//elaboro ahora el causado directamente co nsultando en presupuesto ejecutador y realizando el update funciona in god we trust
		if(($tipo_cierre!="anticipo")&&('$numero_compromiso'!=""))
		{
			$partida=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
			$ids=$_POST[cuentas_por_pagar_db_id];
			$sql_doc_det="select monto from doc_cxp_detalle where id_doc='$ids'
					and partida='$partida'
					";
					$row_doc_det=& $conn->Execute($sql_doc_det);
					//die($sql_doc_det);
					if(!$row_doc_det->EOF)
					{
						$causado=$row_doc_det->fields("monto");
					//	die($causado);
					}//else
//					die("error");
			//	$row_orden_compra->fields("id_unidad_ejecutora");
				$tipo=$row_orden_compra->fields("tipo");
				$partida_presu=$row_orden_compra->fields("partida");
				if($requiere_uf==true)
					$partida=$row_orden_compra->fields("partida");
				else
					$partida=0;
				if($requiere_ue==true)
					$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
				else
					$unidad_ejecutora=0;
				if($requiere_pr==true)
				{
					if($tipo==1)
					{
						$proyecto=$row_orden_compra->fields("id_proyecto_accion_centralizada");
						$accion_central=0;
					}else
					if($tipo==2)
					{
						$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
						$proyecto=0;
					}
				}
				else
					$unidad_ejecutora=0;
///////////////////////////////////////----- realizando el causado en las tablas de presupuesto$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
									$pre_orden=$row_orden_compra->fields("id_accion_especifica");
									$tipo=$row_orden_compra->fields("tipo");
									if($tipo=='1')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									}else
									$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
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
	/*************************************************************/
$monto_iva=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ret_iva']);
$monto_iva_causar2=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_iva']);
$monto_iva_causar=str_replace(",",".",$monto_iva_causar2);

	/*************************************************************/										
											$causado=$causado+$monto_iva_causar;
											//$monto_total = $monto_causado + $monto_restar;	
											$monto_total=$monto_causado-$causado;
											//echo($monto_causado."+".$causado);	
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
													(ano = '".$ano."')
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
											//////////////////////////////////causando el iva/////////////////////////////////////////////////////////////////////////
										$resumen_suma = "
														SELECT  
															   (monto_causado[".$mes."]) AS monto
														FROM 
															\"presupuesto_ejecutadoR\"
														WHERE
															id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
														AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
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
											$causado=$monto_causado_iva+$causado;
											$monto_total=$monto_causado_iva-$monto_iva_causar;
											$sql_iva="UPDATE 
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
													partida = '403'  
												AND	
													generica = '18'
												AND	
													especifica = '01'  
												AND
													sub_especifica = '00'
												$where;";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////														
		
											//	die($actu1);
											$conta_tor++;
											$cadena1=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");	
											if($conta_tor==1)
											{$concad=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
											//die($concad);
											}
											else
											{
												//die("hola");
												//verificar cadena1 dentro de concat
												$asss=strpost($concad,$cadena1);
												//if es uno entonces $actu1=""
												if($asss!=0)
												{
													$actu1="";
													//echo($asss);
													$asss=0;
												}
												
												//por ultimo se una la cadena1  con cad
											$concad=$concad.";".$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
											}
											$actu=$actu.";".$actu1;

												
		}//end if											

	$row_orden_compra->MoveNext();

	}//fin del while
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//die($actu);
						$actu=$actu.";".$sql_iva;
						//die($actu);
						if (!$conn->Execute($actu))
							die ('Error al CAUSAR: '.$conn->ErrorMsg());
						$actu="";
}					

											
															$sql = "UPDATE documentos_cxp 
															 		SET
																	estatus='1',
																	ultimo_usuario=".$_SESSION['id_usuario'].", 
																	fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
																	WHERE 
																		id_organismo=$_SESSION[id_organismo]
																	AND
																	id_documentos='$doc';
																	$sql_act_saldos2;
																	$sql_mod_sumas_todas2;
																	$sql_eliminar;
																	$sql_eliminar2;	
																	$sql_actualizar_comprobante;																	
																	";
																	//die($sql);
																	if (!$conn->Execute($sql)) {
																	die ('Error al Actualizar: '.$conn->ErrorMsg());}
									
											
							//--- CERRANDO ELSE//
							die("Actualizado");
}
else
	die("no_documento");
	
?>