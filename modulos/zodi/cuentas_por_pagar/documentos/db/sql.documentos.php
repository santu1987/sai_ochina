<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$fecha2 = date("Y-m-d H:i:s");
$monto_causar_comp=$_POST[monto_causar_comp];
$partida_comp=$_POST[partida_comp];
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_cxp WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
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
//
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
*/					$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_ant =& $conn->Execute($sql_ant);
					$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
					//
					$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_fact =& $conn->Execute($sql_fact);
					$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
					//		$op=$_POST['cuentas_por_pagar_db_tipo_documento'];
						$op2=$_POST['cuentas_por_pagar_db_anticipos'];
						$op3=$_POST['valor_tipo_doc'];
										
					if(($_POST[cuentas_por_pagar_db_compromiso_n]=='')||($_POST[cuentas_por_pagar_db_compromiso_n]==NULL)||($_POST[cuentas_por_pagar_db_compromiso_n]==""))
					{
						$where="AND 1=1";
					}
					else
					{
						$where="	AND
									numero_compromiso='$_POST[cuentas_por_pagar_db_compromiso_n]'		
									";
					}
					$sql = "SELECT 
									id_documentos 
							FROM 
									documentos_cxp 
							WHERE 
									id_organismo=$_SESSION[id_organismo]
							AND
									ano='$_POST[cuentas_por_pagar_db_ayo]'
							AND
									numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
									
								
							$where
												   ";
					if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
					$row= $conn->Execute($sql);
								/*AND
									id_proveedor='$_POST[cuentas_por_pagar_db_proveedor_id]'
							AND
									tipo_documentocxp='$_POST[cuentas_por_pagar_db_tipo_documento]'*/
					if($row->EOF){
									$compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
									//die($_POST['cuentas_por_pagar_db_compromiso_n']);
							if(($compromiso=='')||($compromiso==NULL)||($compromiso==""))
							{
								$compromiso='0';
								
							}
							else
								{
								   $where="where	\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
									$sql_compromiso="SELECT 
														monto,cantidad
													FROM 
														\"orden_compra_servicioE\"
													INNER JOIN
														\"orden_compra_servicioD\"
													ON
														\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
													$where";
								//die($sql_compromiso);
								if (!$conn->Execute($sql_compromiso)) die ('Error al Registrar: '.$conn->ErrorMsg());
								$row_compromiso= $conn->Execute($sql_compromiso);
										
										if($row_compromiso->EOF)
										{
											//die($sql_compromiso);
											die("No_existe_compromiso");
											
										}else{
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								/*			$sql="SELECT 
																\"orden_compra_servicioE\".id_proveedor, 
																\"orden_compra_servicioE\".id_unidad_ejecutora,
																\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
																\"orden_compra_servicioE\".id_accion_especifica, 
																\"orden_compra_servicioE\".numero_compromiso, 
																\"orden_compra_servicioE\".numero_pre_orden,
																\"orden_compra_servicioE\".tipo,
																\"orden_compra_servicioD\".cantidad,
																\"orden_compra_servicioD\".monto
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
																\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
														$row_orden_compra=& $conn->Execute($sql);
														while(!$row_orden_compra->EOF)
														{
																$partida=$row_orden_compra->fields("partida");
																$generica=$row_orden_compra->fields("generica");	
																$especifica=$row_orden_compra->fields("especifica");
																$subespecifica=$row_orden_compra->fields("subespecifica");	
															//	die($partida.$generica.$especifica.$subespecifica);
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
																						   (monto_presupuesto[".date("n")."]) AS monto_p,
																						   (monto_modificado[".date("n")."]) AS monto_mod,
																						   (monto_traspasado[".date("n")."]) AS monto_tras,
																						   (monto_comprometido[".date("n")."]) AS monto_comp
																						   
																						   
																					FROM 
																						\"presupuesto_ejecutadoR\"
																					WHERE
																						id_unidad_ejecutora='$unidad_ejecutora'
																					AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																					AND
																						partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																					$where
																					";
																	//die($resumen_suma);				
																	$rs_resumen_suma=& $conn->Execute($resumen_suma);
																	
																	if (!$rs_resumen_suma->EOF) 
																	{
																		$monto_presupuesto = $rs_resumen_suma->fields("monto_p");
																		$monto_modificado = $rs_resumen_suma->fields("monto_mod");
																		$monto_traspasado = $rs_resumen_suma->fields("monto_tras");
																		$monto_comprometido = $rs_resumen_suma->fields("monto_comp");
																		$monto_total =($monto_presupuesto + $monto_modificado+$monto_traspasado)-$monto_comprometido;			
																	}
																		$total_monto=$total_monto+$monto_total;
																		$row_orden_compra->MoveNext();
											
															}
					//////////*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
											$sql_facturas="SELECT 
																			   porcentaje_iva,
																			   porcentaje_retencion_iva, 
																			   monto_bruto,
																			   monto_base_imponible,
																			   amortizacion,
																			   tipo_documentocxp
																 FROM
																			documentos_cxp
																where						   
																			documentos_cxp.numero_compromiso='$compromiso'
																AND
																			estatus='1'			
																			";	
																		//	die($sql_facturas);	   
														$row_factura=& $conn->Execute($sql_facturas);
														//$total_renglon=0;
														$ant='0';
														while(!$row_factura->EOF)
														{
															$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
															$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;
															if(($row_factura->fields("tipo_documentocxp"))==$tipos_ant)
															{
																$monto_factura=0;
																//$row_factura->fields("monto_bruto");
															}
															/*if(($row_factura->fields("tipo_documentocxp")==$tipos_ant))
																	{
																		//$tipo="anticipo";
																		/*$monto_ant=$row_factura->fields("monto_bruto");
																		$monto_factura=$row_factura->fields("monto_bruto");*/
																		/*$monto_factura=0;
																		
																	}*/
															//si es factura con anticipo
															if((($row_factura->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura->fields("amortizacion")!=0))
																	{//die($row_factura->fields("amortizacion")); 
																		//echo($tipos_fact);
																		$monto_factura="";	
																		$monto_ante=($row_factura->fields("monto_bruto")+$row_factura->fields("amortizacion"));
																		$p_iva_factura=$monto_ante*$row_factura->fields("porcentaje_iva")/100;
																	//	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
																		$monto_factura=$monto_ante+$p_iva_factura;	
																		$ant='1';
																		}
																		
															$total_renglon=$total_renglon+$monto_factura;
															
															$row_factura->MoveNext();
														}
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										//		$orden_total=$_POST['cuentas_por_pagar_db_total'];
												/*if($_POST[valor_anticipo]==1)
												{
													$mb=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
													$am=str_replace(".","",$_POST['cuentas_por_pagar_amortizacion']);
													$mb=str_replace(",",".",$mb);
													$am=str_replace(",",".",$am);
													$to=$mb-$am;
													$orden_total=$to;
												}else*/
												$orden_total=str_replace(".","",$_POST[cuentas_por_pagar_db_total]);
												$orden_total=str_replace(",",".",$orden_total);
												$iva=str_replace(".","",$_POST[cuentas_por_pagar_db_monto_iva]);
												$iva1=str_replace(",",".",$iva);
												$fact_ord=($orden_total)-$total_renglon;
												
													$bruto2 = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
													$bruto = str_replace(",",".",$bruto2);
													$bruto_total=$bruto+$iva1;
//die($bruto."+".$iva1);
											//si es factura con anticpo
										
												if($ant!='0')
												{	
													$bruto = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ant']);
											//	die(str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ant']));
													$bruto_total=$bruto+$iva1;
												
													}
										//$prueba=$fact_ord-$bruto_total;
										//	die($bruto);
					//
												/*if($op==$tipos_fact)
												{
													$bruto = str_replace(".","",$_POST['cuentas_por_pagar_db_sub_total']);*/
												//}
													
//die($fact_ord." < ".$bruto_total);
//die($fact_ord."<".$bruto_total);													
//echo($fact_ord."<".$bruto_total);
$fact_ord=$fact_ord+1;
													if($fact_ord<$bruto_total)
													{
													
	
														//die("monto_superior");
	
													}else
													$fact_ord=$fact_ord-1;
														//die("W");
											}		
								}	
					//	$monto=$_POST[cuentas_por_pagar_db_monto_bruto];
					//	$base=$_POST[cuentas_por_pagar_db_base_imponible];
												$amort='0,00';
											//die($op.$op2);
												
													$bruto = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
													$bruto=str_replace(",",".",$bruto);
													$monto=$_POST[cuentas_por_pagar_db_monto_bruto];
													$base=$_POST[cuentas_por_pagar_db_base_imponible];
													$monto=str_replace(".","",$monto);
													$base=str_replace(".","",$base);
												if($op3=="anticipo")
												{
													//$bruto = str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
													//$bruto=str_replace(",",".",$bruto);
												//	$monto=$bruto;
													$amort=str_replace(".","",$_POST['cuentas_por_pagar_amortizacion']);
												//	$base=$monto;
													//$amort=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
													//$monto=str_replace(".","",$_POST['cuentas_por_pagar_amortizacion']);
												}else
												$amort=0;
																	
						$iva=$_POST[cuentas_por_pagar_db_iva];
						$ret_iva=$_POST[cuentas_por_pagar_db_ret_iva];
						$ret_islr=$_POST[cuentas_por_pagar_db_islr];
						
						$ret1=$_POST[cuentas_por_pagar_db_ret_extra];
						$ret2=$_POST[cuentas_por_pagar_db_ret_extra2];
						$pret1=$_POST[cuentas_por_pagar_db_ret_e1];
						$pret2=$_POST[cuentas_por_pagar_db_ret_e2];
					
						//-
						if(($ret1==""))
						$ret1="0,00";
						if(($ret2==""))
						$ret2="0,00";
						
					/*	$monto=str_replace(".","",$monto);
						$base=str_replace(".","",$base);
					*/	$iva=str_replace(".","",$iva);
						$ret_iva=str_replace(".","",$ret_iva);
						$ret_islr=str_replace(".","",$ret_islr);
						$ret1=str_replace(".","",$ret1);
						$ret2=str_replace(".","",$ret2);
						$pret1=str_replace(".","",$pret1);
						$pret2=str_replace(".","",$pret2);
						//-
					
					
					//cuentas_por_pagar_db_base_imponible
					$opcion=$_POST[cuentas_por_pagar_db_op_oculto];
					$orden_compra=$_POST[cuentas_por_pagar_db_orden_compra];
					$orden_compra=substr($orden_compra,0,3);
					//***************** aplicando estatus a facturas por la 404************
															$sql_tipo="SELECT 
																			   nombre,
																			   id_tipo_documento
																			  
																 FROM
																			tipo_documento_cxp
																where						   
																			tipo_documento_cxp.id_tipo_documento='$_POST[cuentas_por_pagar_db_tipo_documento]'";		   
														//die($sql_facturas);					
														$row_tipo=& $conn->Execute($sql_tipo);
							if(strtoupper($row_tipo->fields("nombre"))=="FACTURA")
							{						
								if($orden_compra=='404')
								{
									$estatus_404="1";
								}
								else
								if($orden_compra!='404')
								{
									$estatus_404="0";
								}
							}else $estatus_404="0";
								
							if($op==$op2)//si es anticipo
							{	
							
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					$sql_facturas2="SELECT 
												   tipo_documentocxp
									 FROM
												documentos_cxp
									where						   
												documentos_cxp.numero_compromiso='$compromiso'";		   
														$row_factura2=& $conn->Execute($sql_facturas2);
														while(!$row_factura2->EOF)
														{
															if($op2==$row_factura2->fields("tipo_documentocxp"))//si es anticipo
															{	
																die("compromiso");
															}		
															$row_factura2->MoveNext();
														}	
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
							
								if ($opcion=='1')
								{
													$sql = "	
																INSERT INTO 
																	documentos_cxp
																	(
																		id_organismo,
																		ano,
																		id_proveedor,
																		tipo_documentocxp,
																		numero_documento,
																		numero_control,
																		fecha_documento,
																		fecha_vencimiento,
																		monto_bruto,
																		numero_compromiso,
																		comentarios,
																		ultimo_usuario,
																		fecha_ultima_modificacion,
																		orden_pago,
																		estatus,
																		estatus_404
																	) 
																	VALUES
																	(
																		".$_SESSION["id_organismo"].",
																		'$_POST[cuentas_por_pagar_db_ayo]',
																		'$_POST[cuentas_por_pagar_db_proveedor_id]',
																		'$_POST[cuentas_por_pagar_db_tipo_documento]',
																		'$_POST[cuentas_por_pagar_db_numero_documento]',	
																		'$_POST[cuentas_por_pagar_db_numero_control]',
																		'$_POST[cuentas_por_pagar_db_fecha_f]',
																		'$_POST[cuentas_por_pagar_db_fecha_v]',
																		 '".str_replace(",",".",$monto)."',
																		 '$compromiso',
																		 '$_POST[cuentas_por_pagar_db_comentarios]',
																		".$_SESSION['id_usuario']."	,
																		'".date("Y-m-d H:i:s")."',
																		'0',
																		'$_POST[cuentas_por_pagar_db_documentos_abrir_cerrar]',
																		'$estatus_404'
																		)
															";
										}else
												if ($opcion=='2')
												{
													$sql = "	
																INSERT INTO 
																	documentos_cxp
																	(
																		id_organismo,
																		ano,
																		cedula_rif_beneficiario,
																		beneficiario,
																		tipo_documentocxp,
																		numero_documento,
																		numero_control,
																		fecha_documento,
																		fecha_vencimiento,
																		porcentaje_iva,
																		porcentaje_retencion_iva,
																		porcentaje_retencion_islr,
																		monto_bruto,
																		numero_compromiso,
																		comentarios,
																		ultimo_usuario,
																		fecha_ultima_modificacion,
																		orden_pago,
																		estatus,
																		estatus_404													
																	) 
																	VALUES
																	(
																		".$_SESSION["id_organismo"].",
																		'$_POST[cuentas_por_pagar_db_ayo]',
																		'$_POST[cuentas_por_pagar_db_empleado_codigo]',
																		'$_POST[cuentas_por_pagar_db_empleado_nombre]',
																		'$_POST[cuentas_por_pagar_db_tipo_documento]',
																		'$_POST[cuentas_por_pagar_db_numero_documento]',	
																		'$_POST[cuentas_por_pagar_db_numero_control]',
																		'$_POST[cuentas_por_pagar_db_fecha_f]',
																		'$_POST[cuentas_por_pagar_db_fecha_v]',
																		 '".str_replace(",",".",$monto)."',
																		 '$compromiso',
																		 '$_POST[cuentas_por_pagar_db_comentarios]',
																		".$_SESSION['id_usuario']."	,
																		'".date("Y-m-d H:i:s")."',
																		'0',
																		'$_POST[cuentas_por_pagar_db_documentos_abrir_cerrar]',
																		'$estatus_404'
																	)
															";
													}		
							}else//fin op==op2
							if($op!=$op2)
							{
										if ($opcion=='1')
										{
														$sql = "	
																	INSERT INTO 
																		documentos_cxp
																		(
																			id_organismo,
																			ano,
																			id_proveedor,
																			tipo_documentocxp,
																			numero_documento,
																			numero_control,
																			fecha_documento,
																			fecha_vencimiento,
																			porcentaje_iva,
																			porcentaje_retencion_iva,
																			porcentaje_retencion_islr,
																			monto_bruto,
																			monto_base_imponible,
																			numero_compromiso,
																			comentarios,
																			ultimo_usuario,
																			fecha_ultima_modificacion,
																			orden_pago,
																			estatus,
																			estatus_404,
																			retencion_ex1,
																			retencion_ex2,
																			desc_ex1,
																			desc_ex2,
																			pret1,
																			pret2,
																			amortizacion,
																			aplica_bi_ret_ex1,
																			aplica_bi_ret_ex2
																			
																		) 
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$_POST[cuentas_por_pagar_db_ayo]',
																			'$_POST[cuentas_por_pagar_db_proveedor_id]',
																			'$_POST[cuentas_por_pagar_db_tipo_documento]',
																			'$_POST[cuentas_por_pagar_db_numero_documento]',	
																			'$_POST[cuentas_por_pagar_db_numero_control]',
																			'".date("Y-m-d H:i:s")."',
																			'$_POST[cuentas_por_pagar_db_fecha_v]',
																			 '".str_replace(",",".",$iva)."',
																			 '".str_replace(",",".",$ret_iva)."',
																			 '".str_replace(",",".",$ret_islr)."',
																			 '".str_replace(",",".",$monto)."',
																			 '".str_replace(",",".",$base)."',
																			 '$compromiso',
																			 '$_POST[cuentas_por_pagar_db_comentarios]',
																			".$_SESSION['id_usuario']."	,
																			'".date("Y-m-d H:i:s")."',
																			'0',
																			'$_POST[cuentas_por_pagar_db_documentos_abrir_cerrar]',
																			'$estatus_404',
																			'".str_replace(",",".",$ret1)."',
																			'".str_replace(",",".",$ret2)."',
																			'$_POST[cuentas_por_pagar_db_ret_extra_dsc1]',
																			'$_POST[cuentas_por_pagar_db_ret_extra_dsc2]',
																			'".str_replace(",",".",$pret1)."',
																			'".str_replace(",",".",$pret2)."',
																			'".str_replace(",",".",$amort)."',
																			'$_POST[valor_biex1]',
																			'$_POST[valor_biex2]'
																			)
																";
											}else
													if ($opcion=='2')
													{
														$sql = "INSERT INTO 
																		documentos_cxp
																		(
																			id_organismo,
																			ano,
																			cedula_rif_beneficiario,
																			beneficiario,
																			tipo_documentocxp,
																			numero_documento,
																			numero_control,
																			fecha_documento,
																			fecha_vencimiento,
																			porcentaje_iva,
																			porcentaje_retencion_iva,
																			porcentaje_retencion_islr,
																			monto_bruto,
																			monto_base_imponible,
																			numero_compromiso,
																			comentarios,
																			ultimo_usuario,
																			fecha_ultima_modificacion,
																			orden_pago,
																			estatus,
																			estatus_404,
																			retencion_ex1,
																			retencion_ex2,
																			desc_ex1,
																			desc_ex2,
																			pret1,
																			pret2,
																			amortizacion,
																			aplica_bi_ret_ex1,
																			aplica_bi_ret_ex2
																			
																		) 
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$_POST[cuentas_por_pagar_db_ayo]',
																			'$_POST[cuentas_por_pagar_db_empleado_codigo]',
																			'$_POST[cuentas_por_pagar_db_empleado_nombre]',
																			'$_POST[cuentas_por_pagar_db_tipo_documento]',
																			'$_POST[cuentas_por_pagar_db_numero_documento]',	
																			'$_POST[cuentas_por_pagar_db_numero_control]',
																			'".date("Y-m-d H:i:s")."',
																			'$_POST[cuentas_por_pagar_db_fecha_v]',
																			 '".str_replace(",",".",$iva)."',
																			 '".str_replace(",",".",$ret_iva)."',
																			 '".str_replace(",",".",$ret_islr)."',
																			 '".str_replace(",",".",$monto)."',
																			 '".str_replace(",",".",$base)."',
																			 '$compromiso',
																			 '$_POST[cuentas_por_pagar_db_comentarios]',
																			".$_SESSION['id_usuario']."	,
																			'".date("Y-m-d H:i:s")."',
																			'0',
																			'$_POST[cuentas_por_pagar_db_documentos_abrir_cerrar]',
																			'$estatus_404',
																			'".str_replace(",",".",$ret1)."',
																			'".str_replace(",",".",$ret2)."',
																			'$_POST[cuentas_por_pagar_db_ret_extra_dsc1]',
																			'$_POST[cuentas_por_pagar_db_ret_extra_dsc2]',
																			'".str_replace(",",".",$pret1)."',
																			'".str_replace(",",".",$pret2)."',
																			'".str_replace(",",".",$amort)."',
																			'$_POST[valor_biex1]',
																			'$_POST[valor_biex2]'
																			
																		)
																";
														}		
							}//fin else op!=op2			
							
					}							
					else
					//die($sql);
						die("NoRegistro".$sql);
					
					if (!$conn->Execute($sql)) 
						die ('Error al Registrar: '.$sql);
					//die ('Error al Registrar: '.$conn->ErrorMsg());
					//die($sql);
					else
					{
							$sql2="select id_documentos from documentos_cxp where numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]' and numero_control='$_POST[cuentas_por_pagar_db_numero_control]'";
							if (!$conn->Execute($sql2))
							die('error');
							else
							{
													
								$row2= $conn->Execute($sql2);
								//*cargando en la tabla de detalle de documentos*/
								$monto_causar_comp=$_POST[monto_causar_comp];
								$partida_comp=$_POST[partida_comp];
								if(($monto_causar_comp!="")&&($partida_comp!=""))
								{
									$monto_causar=str_replace(".","",$_POST[monto_causar_comp]);
									$id2=$row2->fields("id_documentos");
									$sql3="INSERT INTO 
													doc_cxp_detalle
													(
														id_organismo,
														id_doc,
														partida,
														monto
														)
													VALUES
													(
														".$_SESSION["id_organismo"].",
														'$id2',
														'$_POST[partida_comp]',
														'".str_replace(",",".",$monto_causar)."'
													
													)	
									
									
									";	
									//die($sql3);
									if (!$conn->Execute($sql3)) 
									die ('Error al Registrar: '.$sql3);
									}
								///	
								$responce="Registrado"."*".$row2->fields("id_documentos");

							}
							die($responce);
					}
					//die($sql);
						
						//die("$sql");
/*}else
die("cerrado");*/
?>