<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sql_cuenta_orden2="";
$numero_comprobante_corden=0;
/////////////////////////////////////////////-/creando comprobante contable debe y haber/-////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
$ano=date("Y");
$mes=substr($fecha,5,2);
$numero_documento=$_POST['cuentas_por_pagar_db_numero_documento'];
$id_tipo_comprobante=$_POST['cuentas_por_pagar_integracion_tipo_id'];
$id_proveedor=$_POST['cuentas_por_pagar_db_proveedor_id'];
$referencia=$_POST['cuentas_por_pagar_db_numero_documento'];
$monto= str_replace(".","",$_POST['cuentas_por_pagar_db_sub_total']);
$monto_iva=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ret_iva']);
$unidad_ejecutora=0;
$accion_central=0;
$partida=0;
$auxiliar=0;
$co=$_POST['check_invisible'];

////
					$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_fact =& $conn->Execute($sql_fact);
					$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
////
////
					$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_ant =& $conn->Execute($sql_ant);
					$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
////
//sacando el id del documento
 $sql_id="select  id_documentos from documentos_cxp where numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'";
if(!$conn->Execute($sql_id))die('Error al Registrar:'.$conn->ErrorMsg());
		$row_id=$conn->Execute($sql_id);
		if(!$row->EOF)
		$id_documento=$row_id->fields("id_documentos");	
////comprobando si es anticipo AND documentos_cxp.numero_compromiso='$_POST[cuentas_por_pagar_db_compromiso_n]'
		$sql_facturas2="SELECT 
									   tipo_documentocxp,amortizacion
						 FROM
									documentos_cxp
						where						   
									
									numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'			
									";	
	   
														$row_factura2=& $conn->Execute($sql_facturas2);
														if(!$row_factura2->EOF)
														{
															if($_POST[cuentas_por_pagar_db_anticipos]==$row_factura2->fields("tipo_documentocxp"))//si es anticipo
															{	
																$tipo_cierre="anticipo";
																		$turnos=3;	
															}		
															else
																if($op2!=$row_factura2->fields("tipo_documentocxp"))//si es anticipo
															{	
																$tipo_cierre="otro";
																$turnos=4;
															}	
															if((($row_factura2->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura2->fields("amortizacion")!=0))
															{
																$tipo_cierre="anticipo_factura";
																$turnos=5;	
															}
														}	
									//	die($sql_facturas2);				
									//	echo($tipo_cierre);die($turnos);				
/////////////////////////////	
$secuencia=0;
$error=0;
$secuencia=$secuencia+1;
$cuenta_contable_iva=0;
/////buscando datos para causar, y elaborar comprobantes unidad ejecutora, partida etc
if($_POST['cuentas_por_pagar_db_compromiso_n']!="")
	{					
							$numero_compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];	
							$monto_restar0= str_replace(".","",$_POST[cuentas_por_pagar_db_monto_bruto]);		
							$monto_restar=str_replace(",",".",$monto_restar0);	
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
											\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
								
									$row_orden_compra=& $conn->Execute($sql_cmp);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);
										$total_total=$total+$iva;
										
										$total_renglon=$total_renglon+$total_total;
										$row_orden_compra->MoveNext();
									}
/////////////////////B///////////////BUSCANDO LOS TOTALES DE ESAS FACTURAS CERRADAS CON ESE MISMO NUMERO DE COMPROMISO///////////////////////////////////////////////////////////////			
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
																													documentos_cxp.numero_compromiso='$compromiso'
																												
																													";		   
																								//die($sql_facturas);					
																								$row_factura=& $conn->Execute($sql_facturas);
																								//die($sql_facturas);
																								//$total_renglon=0;
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
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////c/////////////////////// ultimo paso verificar si el compromiso no es menor que lo que se causara /////////////////////////////////////////////////////////////////////////////
//$total_documento=$monto_restar;
//$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento;
$fact_ord=($total_renglon);

									if($fact_ord<$monto_restar)
									{die($total_facturas_comprometidas);	
										die("excede");
									}		
///
if($fact_ord<$total_documento)
{
die("El monto a pagar no puede superar al monto comprometido");
}
///fin de la elaboracion del cuasado
if(($tipo_cierre!="anticipo")&&('$numero_compromiso'!=""))
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
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
				die($sql);
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
															   (monto_causado[".date("n")."]) AS monto
														FROM 
															\"presupuesto_ejecutadoR\"
														WHERE
															id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
														AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
														AND
															partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
														$where
														";
										//	die($resumen_suma);		
										$rs_resumen_suma=& $conn->Execute($resumen_suma);
									
										if (!$rs_resumen_suma->EOF) 
											$monto_causado = $rs_resumen_suma->fields("monto");
										
										else
											$monto_causado = 0;
											$monto_total = $monto_causado + $monto_restar;	
											//echo($monto_causado."+".$monto_restar);	
											$actu=
											"UPDATE 
													\"presupuesto_ejecutadoR\"
											SET 
													monto_causado[".date("n")."]= '$monto_total'
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
												";	//die($actu);
												if (!$conn->Execute($actu))
												die ('Error al CAUSAR: '.$conn->ErrorMsg());
												
											//die($actu);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}											
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
			{
				$accion_central=0;
				$unidad_ejecutora=0;
				$partida=0;
				$iva=0;
				$retencion=0;
			}		
////
/*while(($finalizado!="finalizado")&&($error==0))
{	*/
	///////
	$cont=1;
	//$turnos=4;	
	//buscando numero_de_comprobante
	$tipo_comp=$_POST[cuentas_por_pagar_integracion_tipo_id];
	$sql_comprobante="select numero_comprobante_integracion,codigo_tipo_comprobante
												 from 
														tipo_comprobante
						where
												id='$tipo_comp'								
											";
						//	die($sql_comprobante);
								$row_comprobante=& $conn->Execute($sql_comprobante);
								if(!$row_comprobante->EOF)
								{
									$numero_comprobantex=$row_comprobante->fields("numero_comprobante_integracion");	
									$cod_tipo1=$row_comprobante->fields("codigo_tipo_comprobante");
									if(($numero_comprobantex!="")&&($numero_comprobantex!="0000"))
										$numero_comprobante3=$numero_comprobantex+1;			
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

								}else
								die("no_comp_int");
					
	while(($cont!=$turnos)&&($error==0))
	{

		$auxiliar=0;
		
		if($tipo_cierre=="anticipo")
		{
		$monto_anticipo= str_replace(".","",$_POST['cuentas_por_pagar_db_sub_total']);
							if($cont==1)
							{	
								$cuenta_contable=$_POST['cuentas_por_pagar_integracion_cuenta'];
								$sql_cta_debe="	SELECT 										
														cuenta_contable_contabilidad.requiere_auxiliar,
														cuenta_contable_contabilidad.requiere_unidad_ejecutora,
														cuenta_contable_contabilidad.requiere_proyecto,
														cuenta_contable_contabilidad.requiere_utilizacion_fondos,
														cuenta_contable_contabilidad.nombre	as descripcion,
														cuenta_contable_contabilidad.id as cuenta_contable_id 
													FROM
														cuenta_contable_contabilidad
													INNER JOIN
														organismo
													ON
														cuenta_contable_contabilidad.id_organismo=organismo.id_organismo
													where
														(organismo.id_organismo =".$_SESSION['id_organismo'].")
													AND
														cuenta_contable='$cuenta_contable'";										
											//if (!$conn->Execute($sql_cta_debe)) die ('Error al Registrar: '.$conn->ErrorMsg());
											$row_cta_debe=& $conn->Execute($sql_cta_debe);
											if(!$row_cta_debe->EOF)
											{
												$id_cuenta_contable=$row_cta_debe->fields("cuenta_contable_id");
												$requiere_ue=$row_cta_debe->fields("requiere_unidad_ejecutora");
												$requiere_uf=$row_cta_debe->fields("requiere_utilizacion_fondos");
												$requiere_pr=$row_cta_debe->fields("requiere_proyecto");
												$requiere_aux=$row_cta_debe->fields("requiere_auxiliar");
										  }
										 $monto_debito=str_replace(",",".",$monto_anticipo);
										 $monto_credito=0;
										 $debito_credito=1;
										// $descripcion="prueba FACTURAS CXP debe";
								}	
							if($cont==2)
							{	
										$cuenta_contable="2110309";
										if($_POST['cuentas_por_pagar_db_op_oculto']==2)
										{
											$nombre_prove=$_POST['cuentas_por_pagar_db_empleado_nombre'];
										}else
										if($_POST['cuentas_por_pagar_db_op_oculto']==1)
										{
											$sql_prove="select codigo_proveedor,nombre as nombre_proveedor  from proveedor where id_proveedor='$id_proveedor'";
											$row_prove=$conn->Execute($sql_prove);
											$nombre_prove=$row_prove->fields("nombre_proveedor");
											//$cuenta_contable="211030100";
										}
											$sql_cta="select 
															id,
															cuenta_contable_contabilidad.requiere_auxiliar,
															cuenta_contable_contabilidad.requiere_unidad_ejecutora,
															cuenta_contable_contabilidad.requiere_proyecto,
															cuenta_contable_contabilidad.requiere_utilizacion_fondos
														 from 
															cuenta_contable_contabilidad 
														where cuenta_contable='$cuenta_contable'";
											$row_cta=& $conn->Execute($sql_cta);
											if(!$row_cta->EOF)
											{
												$id_cuenta_contable=$row_cta->fields("id");
												$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
												$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
												$requiere_pr=$row_cta->fields("requiere_proyecto");
												$requiere_aux=$row_cta->fields("requiere_auxiliar");
											}
								$debito_credito=2;
								$monto_debito=0;
								$monto_credito=str_replace(",",".",$monto_anticipo);
								//$descripcion="prueba FACTURAS CXP haber";
							}
						
				/////////////////verificando si requiere auxliar la cuenta contable/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if($requiere_aux==true)
					{
						$sql_auxiliar="select 
											id_auxiliares
										from 
												auxiliares 
										where cuenta_contable='$id_cuenta_contable'";
										
						if (!$conn->Execute($sql_auxiliar)) die ('Error al Registrar: '.$conn->ErrorMsg());
		
						$row_aux=& $conn->Execute($sql_auxiliar);
						if(!$row_aux->EOF)
						{
							$auxiliar=$row_aux->fields("id_auxiliares");
							//die($auxiliar);
						}
						else
						$auxiliar=0;	
					}else
						if($requiere_aux==false)
							$auxiliar=0;
		////////////////////// verificando si requiere unidad ejecutora//////////////////////
					if($requiere_ue==true)
					{
						$unidad_ejecutora2=$unidad_ejecutora;
					}else
					$unidad_ejecutora2=0;
		////////////////////// verificando si requiere utilizacion fondo//////////////////////
					if($requiere_uf==true)
					{
						$partida2=$partida;
					}else
					$partida2=0;
		////////////////////// verificando si requiere proyecto//////////////////////
					if($requiere_pr==true)
					{
						$accion_central2=$accion_central;
					}else
					$accion_central2=0;
					///
					///
					$debe=round($monto_debito * 100) / 100; 
					$haber=round($monto_credito * 100) / 100; 
					$acu_debe=$acu_debe+$debe;
					$acu_haber=$acu_haber+$haber;
		}
		else
		if($tipo_cierre=="anticipo_factura")
		{
			$monto_ant= str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ant']);
			$monto_amortizacion= str_replace(".","",$_POST['cuentas_por_pagar_amortizacion']);
			$monto_cxp= str_replace(".","",$_POST['cuentas_por_pagar_db_monto_bruto']);
					if($cont==1)
					{	
									$cuenta_contable=$_POST['cuentas_por_pagar_integracion_cuenta'];
									$sql_cta_debe="	SELECT 										
															cuenta_contable_contabilidad.requiere_auxiliar,
															cuenta_contable_contabilidad.requiere_unidad_ejecutora,
															cuenta_contable_contabilidad.requiere_proyecto,
															cuenta_contable_contabilidad.requiere_utilizacion_fondos,
															cuenta_contable_contabilidad.nombre	as descripcion,
															cuenta_contable_contabilidad.id as cuenta_contable_id 
														FROM
															cuenta_contable_contabilidad
														INNER JOIN
															organismo
														ON
															cuenta_contable_contabilidad.id_organismo=organismo.id_organismo
														where
															(organismo.id_organismo =".$_SESSION['id_organismo'].")
														AND
															cuenta_contable='$cuenta_contable'";										
												//if (!$conn->Execute($sql_cta_debe)) die ('Error al Registrar: '.$conn->ErrorMsg());
												$row_cta_debe=& $conn->Execute($sql_cta_debe);
												if(!$row_cta_debe->EOF)
												{
													$id_cuenta_contable=$row_cta_debe->fields("cuenta_contable_id");
													$requiere_ue=$row_cta_debe->fields("requiere_unidad_ejecutora");
													$requiere_uf=$row_cta_debe->fields("requiere_utilizacion_fondos");
													$requiere_pr=$row_cta_debe->fields("requiere_proyecto");
													$requiere_aux=$row_cta_debe->fields("requiere_auxiliar");
												}
											$monto_2=str_replace(",",".",$monto_ant);
											
											$monto_iva_2=str_replace(",",".",$monto_iva);
											$monto_debito=($monto_2)+($monto_iva_2);
											$monto_credito=0;
	    									$debito_credito=1;
											$descripcion="prueba FACTURAS CXP debe";
 										}	
								if($cont==2)
								{	
											$sql_doc="
														SELECT
																*
																FROM
																	documentos_cxp
																inner join 
																	organismo	
																ON
																	documentos_cxp.id_organismo=organismo.id_organismo
																where
																	(organismo.id_organismo =".$_SESSION['id_organismo'].")
																and
																	tipo_documentocxp='$tipos_ant'
																and
																	documentos_cxp.numero_compromiso='$numero_compromiso'
																";	
																//die($sql_doc);
											$row_doc=& $conn->Execute($sql_doc);
											if(!$row_doc->EOF)
											{
														$numero_comprobante_anticipos=$row_doc->fields("numero_comprobante");			
														$sql_cta="select 
																	cuenta_contable_contabilidad.id,
																	cuenta_contable_contabilidad.cuenta_contable,
																	cuenta_contable_contabilidad.requiere_auxiliar,
																	cuenta_contable_contabilidad.requiere_unidad_ejecutora,
																	cuenta_contable_contabilidad.requiere_proyecto,
																	cuenta_contable_contabilidad.requiere_utilizacion_fondos
																 from 
																	cuenta_contable_contabilidad
																inner join 
																	organismo	
																ON
																	cuenta_contable_contabilidad.id_organismo=organismo.id_organismo
																inner join
																	integracion_contable
																ON
																	integracion_contable.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
																where
																	(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
																and 
																integracion_contable.numero_comprobante='$numero_comprobante_anticipos'";
													$row_cta=& $conn->Execute($sql_cta);
													if(!$row_cta->EOF)
													{
														$id_cuenta_contable=$row_cta->fields("id");
														$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
														$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
														$requiere_pr=$row_cta->fields("requiere_proyecto");
														$requiere_aux=$row_cta->fields("requiere_auxiliar");
														$cuenta_contable=$row_cta->fields("cuenta_contable");
													}
											
											}					
														$monto_credito=str_replace(",",".",$monto_amortizacion);
														$debito_credito=2;
														$monto_debito=0;
														//$descripcion="prueba FACTURAS CXP haber";
						  }
						if($cont==3)
								{	
											$cuenta_contable="2110301";
											if($_POST['cuentas_por_pagar_db_op_oculto']==2)
											{
												$nombre_prove=$_POST['cuentas_por_pagar_db_empleado_nombre'];
											}else
											if($_POST['cuentas_por_pagar_db_op_oculto']==1)
											{
												$sql_prove="select codigo_proveedor,nombre as nombre_proveedor  from proveedor where id_proveedor='$id_proveedor'";
												$row_prove=$conn->Execute($sql_prove);
												$nombre_prove=$row_prove->fields("nombre_proveedor");
											}
												$sql_cta="select 
																id,
																cuenta_contable_contabilidad.requiere_auxiliar,
																cuenta_contable_contabilidad.requiere_unidad_ejecutora,
																cuenta_contable_contabilidad.requiere_proyecto,
																cuenta_contable_contabilidad.requiere_utilizacion_fondos
															 from 
																cuenta_contable_contabilidad 
															where cuenta_contable='$cuenta_contable'";
												$row_cta=& $conn->Execute($sql_cta);
												if(!$row_cta->EOF)
												{
													$id_cuenta_contable=$row_cta->fields("id");
													$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
													$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
													$requiere_pr=$row_cta->fields("requiere_proyecto");
													$requiere_aux=$row_cta->fields("requiere_auxiliar");
												}
																	 
			   						 $monto_credito=str_replace(",",".",$monto_cxp);
									 $debito_credito=2;
									$monto_debito=0;
									//$descripcion="prueba FACTURAS CXP haber";
						  }		
				
						if($cont==4)
						{
							$sql_cta="select 
											id,
											cuenta_contable_contabilidad.cuenta_contable,
											cuenta_contable_contabilidad.requiere_auxiliar,
											cuenta_contable_contabilidad.requiere_unidad_ejecutora,
											cuenta_contable_contabilidad.requiere_proyecto,
											cuenta_contable_contabilidad.requiere_utilizacion_fondos
										 from 
											cuenta_contable_contabilidad 
										where nombre='RETENCIONES DE IVA'";
										$row_cta=& $conn->Execute($sql_cta);
										if(!$row_cta->EOF)
										{
											$id_cuenta_contable=$row_cta->fields("id");
											$cuenta_contable=$row_cta->fields("cuenta_contable");
											$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
											$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
											$requiere_pr=$row_cta->fields("requiere_proyecto");
											$requiere_aux=$row_cta->fields("requiere_auxiliar");
										}
							$debito_credito=2;
							$monto_debito=0;
							$monto_credito=str_replace(",",".",$monto_iva);
							//$descripcion="prueba FACTURAS CXP retenciones iva";
							
						}
		/////////////////verificando si requiere auxliar la cuenta contable/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if($requiere_aux==true)
					{
						$sql_auxiliar="select 
											id_auxiliares
										from 
												auxiliares 
										where cuenta_contable='$id_cuenta_contable'";
										
						if (!$conn->Execute($sql_auxiliar)) die ('Error al Registrar: '.$conn->ErrorMsg());
		
						$row_aux=& $conn->Execute($sql_auxiliar);
						if(!$row_aux->EOF)
						{
							$auxiliar=$row_aux->fields("id_auxiliares");
							//die($auxiliar);
						}
						else
						$auxiliar=0;	
					}else
						if($requiere_aux==false)
							$auxiliar=0;
		////////////////////// verificando si requiere unidad ejecutora//////////////////////
					if($requiere_ue==true)
					{
						$unidad_ejecutora2=$unidad_ejecutora;
					}else
					$unidad_ejecutora2=0;
		////////////////////// verificando si requiere utilizacion fondo//////////////////////
					if($requiere_uf==true)
					{
						$partida2=$partida;
					}else
					$partida2=0;
		////////////////////// verificando si requiere proyecto//////////////////////
					if($requiere_pr==true)
					{
						$accion_central2=$accion_central;
					}else
					$accion_central2=0;
					///
					///
					$debe=round($monto_debito * 100) / 100; 
					$haber=round($monto_credito * 100) / 100; 
					$acu_debe=$acu_debe+$debe;
					$acu_haber=$acu_haber+$haber;
					
		}
		else
		{			
				if($cont==1)
					{	
									$cuenta_contable=$_POST['cuentas_por_pagar_integracion_cuenta'];
									$sql_cta_debe="	SELECT 										
															cuenta_contable_contabilidad.requiere_auxiliar,
															cuenta_contable_contabilidad.requiere_unidad_ejecutora,
															cuenta_contable_contabilidad.requiere_proyecto,
															cuenta_contable_contabilidad.requiere_utilizacion_fondos,
															cuenta_contable_contabilidad.nombre	as descripcion,
															cuenta_contable_contabilidad.id as cuenta_contable_id 
														FROM
															cuenta_contable_contabilidad
														INNER JOIN
															organismo
														ON
															cuenta_contable_contabilidad.id_organismo=organismo.id_organismo
														where
															(organismo.id_organismo =".$_SESSION['id_organismo'].")
														AND
															cuenta_contable='$cuenta_contable'";										
												//if (!$conn->Execute($sql_cta_debe)) die ('Error al Registrar: '.$conn->ErrorMsg());
												$row_cta_debe=& $conn->Execute($sql_cta_debe);
												if(!$row_cta_debe->EOF)
												{
													$id_cuenta_contable=$row_cta_debe->fields("cuenta_contable_id");
													$requiere_ue=$row_cta_debe->fields("requiere_unidad_ejecutora");
													$requiere_uf=$row_cta_debe->fields("requiere_utilizacion_fondos");
													$requiere_pr=$row_cta_debe->fields("requiere_proyecto");
													$requiere_aux=$row_cta_debe->fields("requiere_auxiliar");
												}
											 $monto_debito=str_replace(",",".",$monto);
											 $monto_credito=0;
											 $debito_credito=1;
											 
											 $descripcion="prueba FACTURAS CXP debe";
											 
				
									}	
								if($cont==2)
								{	
											if($_POST['cuentas_por_pagar_db_op_oculto']==2)
											{
												$nombre_prove=$_POST['cuentas_por_pagar_db_empleado_nombre'];
												$cuenta_contable="2110309";
											}else
											if($_POST['cuentas_por_pagar_db_op_oculto']==1)
											{
												$sql_prove="select codigo_proveedor,nombre as nombre_proveedor  from proveedor where id_proveedor='$id_proveedor'";
												$row_prove=$conn->Execute($sql_prove);
												$nombre_prove=$row_prove->fields("nombre_proveedor");
												$cuenta_contable="2110301";
											}
												$sql_cta="select 
																id,
																cuenta_contable_contabilidad.requiere_auxiliar,
																cuenta_contable_contabilidad.requiere_unidad_ejecutora,
																cuenta_contable_contabilidad.requiere_proyecto,
																cuenta_contable_contabilidad.requiere_utilizacion_fondos
															 from 
																cuenta_contable_contabilidad 
															where cuenta_contable='$cuenta_contable'";
												$row_cta=& $conn->Execute($sql_cta);
												if(!$row_cta->EOF)
												{
													$id_cuenta_contable=$row_cta->fields("id");
													$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
													$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
													$requiere_pr=$row_cta->fields("requiere_proyecto");
													$requiere_aux=$row_cta->fields("requiere_auxiliar");
												}
									$debito_credito=2;
									$monto_debito=0;
									$monto_2=str_replace(",",".",$monto);
									$monto_iva_2=str_replace(",",".",$monto_iva);
									$monto_credito=($monto_2)-($monto_iva_2);
									$descripcion="prueba FACTURAS CXP haber";
								}
								
			
						if($cont==3)
						{
							$sql_cta="select 
											id,
											cuenta_contable_contabilidad.cuenta_contable,
											cuenta_contable_contabilidad.requiere_auxiliar,
											cuenta_contable_contabilidad.requiere_unidad_ejecutora,
											cuenta_contable_contabilidad.requiere_proyecto,
											cuenta_contable_contabilidad.requiere_utilizacion_fondos
										 from 
											cuenta_contable_contabilidad 
										where nombre='RETENCIONES DE IVA'";
										$row_cta=& $conn->Execute($sql_cta);
										if(!$row_cta->EOF)
										{
											$id_cuenta_contable=$row_cta->fields("id");
											$cuenta_contable=$row_cta->fields("cuenta_contable");
											$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
											$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
											$requiere_pr=$row_cta->fields("requiere_proyecto");
											$requiere_aux=$row_cta->fields("requiere_auxiliar");
										}
							$debito_credito=2;
							$monto_debito=0;
							$monto_credito=str_replace(",",".",$monto_iva);
							//$descripcion="prueba FACTURAS CXP retenciones iva";
						}
		/////////////////verificando si requiere auxliar la cuenta contable/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if($requiere_aux==true)
					{
						$sql_auxiliar="select 
											id_auxiliares
										from 
												auxiliares 
										where cuenta_contable='$id_cuenta_contable'";
										
						if (!$conn->Execute($sql_auxiliar)) die ('Error al Registrar: '.$conn->ErrorMsg());
		
						$row_aux=& $conn->Execute($sql_auxiliar);
						if(!$row_aux->EOF)
						{
							$auxiliar=$row_aux->fields("id_auxiliares");
							//die($auxiliar);
						}
						else
						$auxiliar=0;	
					}else
						if($requiere_aux==false)
							$auxiliar=0;
		////////////////////// verificando si requiere unidad ejecutora//////////////////////
					if($requiere_ue==true)
					{
						$unidad_ejecutora2=$unidad_ejecutora;
					}else
					$unidad_ejecutora2=0;
		////////////////////// verificando si requiere utilizacion fondo//////////////////////
					if($requiere_uf==true)
					{
						$partida2=$partida;
					}else
					$partida2=0;
		////////////////////// verificando si requiere proyecto//////////////////////
					if($requiere_pr==true)
					{
						$accion_central2=$accion_central;
					}else
					$accion_central2=0;
					///
					$debe=round($monto_debito * 100) / 100; 
					$haber=round($monto_credito * 100) / 100; 
					$acu_debe=$acu_debe+$debe;
					$acu_haber=$acu_haber+$haber;
						
	}//fin del else
//---------------------------------------//guardando datos necesarios para el desarollo de la integracion contable
						$descripcion=$_POST['cuentas_por_pagar_db_comentarios2'];
						$sql = "INSERT INTO 
											integracion_contable
											(
												id_organismo,
												numero_comprobante,
												secuencia,
												ano_comprobante,
												mes_comprobante,
												id_tipo_comprobante,
												cuenta_contable,
												descripcion,
												referencia,
												debito_credito,
												monto_debito,
												monto_credito,
												id_unidad_ejecutora,
												id_proyecto,
												id_utilizacion_fondos,
												id_auxiliar,
												fecha_comprobante,
												ultimo_usuario,
												fecha_actualizacion,
												id_accion_central
												  
												
											) 
											VALUES
											(
												".$_SESSION["id_organismo"].",
												$numero_comprobante,
												$secuencia,
												'$ano',
												$mes,
												'$id_tipo_comprobante',
												 '$cuenta_contable',
												 '$descripcion',
												 $referencia,
												 $debito_credito,
												 $monto_debito,
												 $monto_credito,
												 $unidad_ejecutora2,
												 $accion_central,
												 $partida2,
												 $auxiliar,
												 '".date("Y-m-d H:i:s")."',
												 ".$_SESSION['id_usuario'].",
												 '".date("Y-m-d H:i:s")."',
												 $accion_central
											);
											UPDATE
													tipo_comprobante	
												set
													numero_comprobante_integracion='$numero_comprobante3'
											where
												id='$tipo_comp'						
											";
											
			if (!$conn->Execute($sql)) 
			{
			    $error=1;
			}
		if($error==1)
			die ('Error al Registrar: '.$sql);
			$secuencia=$secuencia+1;
	
				
		$cont=$cont+1;
	
		}
		//$numero_comprobante=$numero_comprobante+1;
//}
if($error==1)
				//die('Error al Registrar:'.$conn->ErrorMsg());
die("no_documento");
if($acu_debe!=$acu_haber)
			{
				//	echo($acu_debe);echo($acu_haber);
				//	die('Error al Registrar:'.$conn->ErrorMsg());
					die("No coinciden las columnas debe"." ".$acu_debe." "."y haber"." ".$acu_haber);
			}	
//die ('Error al Registrar: '.$sql);
else
{
/////////////////////////////////////////////-/cerrando logicamente el documento/-//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$numero_compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
			$sql_doc="SELECT
								*	
						FROM	
								documentos_cxp 
						WHERE
									
								
								documentos_cxp.id_organismo=".$_SESSION["id_organismo"]."
						AND
								numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'				
																			
			";	
			$row_doc=& $conn->Execute($sql_doc);
			if(!$row_doc->EOF)
			{									
				$doc=$row_doc->fields("id_documentos");				
////////////////////////////////////////////////////////-ACTUALIZANDO LOS DOCUMENTOS SELECCIONADOS-///////////////////////////////////////////////////////////
//y cuentas de orden
if(($tipo_cierre!="anticipo")&&($numero_compromiso!="")&&($co==1))
{

////////////////////////creando el query con las cuentas de orden 
				$cont_cor=1;
				$turnos_cor=3;	
				$error_cor=0;
				$secuencia_cor=1;
									/*///
										$sql_comprobante="select numero_comprobante,codigo_tipo_comprobante
																					 from 
																					tipo_comprobante
																			where
																					id='$tipo_comp'				
																				";
																	$row_comprobante=& $conn->Execute($sql_comprobante);
																	if(!$row_comprobante->EOF)
																	{
																		$numero_comprobante_corden1=$row_comprobante->fields("numero_comprobante");	
																		$cod_tipo=$row_comprobante->fields("codigo_tipo_comprobante");
																		if(($numero_comprobante_corden1!="")&&($numero_comprobante_corden1!="0000"))
																			$numero_comprobante_corden2=$numero_comprobante_corden1+1.00;	
																		else
																			if($numero_comprobante_corden1=="0000")	
																			$numero_comprobante_corden2="0001";	
																		//
																			$valor_medida=strlen($numero_comprobante_corden2);													//echo($numero_comprobantex3);
																			
																			//	echo($valor_medida);
																			if($valor_medida==1)
																			{
																				$numero_comprobante_corden2="000".$numero_comprobante_corden2;
																			}
																			else
																			if($valor_medida==2)
																			{
																				$numero_comprobante_corden2="00".$numero_comprobante_corden2;
																			}
																			else	
																			if($valor_medida==3)
																			{
																						$numero_comprobante_corden2="0".$numero_comprobante_corden2;
																			}
																			
																			$numero_comprobante_corden=$cod_tipo.$numero_comprobante_corden2;
																	}
																	else
																die("no_comp");
									///	*/				
				while(($cont_cor!=$turnos_cor)&&($error_cor==0))	
				{
					if($cont_cor==1)
					{
								$monto_neto_cor=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_neto']);
								$monto_debito_cor=str_replace(",",".",$monto_neto_cor);
								$monto_credito_cor=0;
								$debito_credito=1;
								$secuencia=1;
								
								if($partida_presu=='401')
								{
									$tipo_cor='94';
									//$cuenta_cont_cor=41230100;
									$cuenta_cont_cor=4221301;
								}
								if($partida_presu=='402')
								{
										$tipo_cor='91';
										//$cuenta_cont_cor=412130200;
										$cuenta_cont_cor=4221302;
								}
								if($partida_presu=='403')
								{
										$tipo_cor='92';
										//$cuenta_cont_cor=41230100;
										$cuenta_cont_cor=4221303;
								}
								if($partida_presu=='404')
								{
										$tipo_cor='93';
									//	$cuenta_cont_cor=41230400;
										$cuenta_cont_cor=4221304;
								}
									//consultando caracteristicas de la cuenta contable
									$sql_cta="select 
																	id,
																	cuenta_contable_contabilidad.cuenta_contable,
																	cuenta_contable_contabilidad.nombre as descripcion,
																	cuenta_contable_contabilidad.requiere_auxiliar,
																	cuenta_contable_contabilidad.requiere_unidad_ejecutora,
																	cuenta_contable_contabilidad.requiere_proyecto,
																	cuenta_contable_contabilidad.requiere_utilizacion_fondos
																 from 
																	cuenta_contable_contabilidad 
																where
																			(cuenta_contable_contabilidad.id_organismo =".$_SESSION['id_organismo'].")
																		AND
																			cuenta_contable='$cuenta_cont_cor'";
																$row_cta=& $conn->Execute($sql_cta);												
				
																if(!$row_cta->EOF)
																{
																	
																	
																	///
																		$sql_comprobante="select numero_comprobante
																						 from 
																						tipo_comprobante
																				where
																						codigo_tipo_comprobante='$tipo_cor'				
																					";
																					 
																		$row_comprobante=& $conn->Execute($sql_comprobante);
																		if(!$row_comprobante->EOF)
																		{
																			$numero_comprobante_corden1=$row_comprobante->fields("numero_comprobante");	
																			//$tipo_cor=$row_comprobante->fields("codigo_tipo_comprobante");
																			if(($numero_comprobante_corden1!="")&&($numero_comprobante_corden1!="0000"))
																				$numero_comprobante_corden2=$numero_comprobante_corden1+1.00;	
																			else
																				if($numero_comprobante_corden1=="0000")	
																				$numero_comprobante_corden2="0001";	
																			//
																			//	die($numero_comprobante_corden2);
																				$valor_medida=strlen($numero_comprobante_corden2);													//echo($numero_comprobantex3);
																				
																				//	echo($valor_medida);
																				if($valor_medida==1)
																				{
																					$numero_comprobante_corden2="000".$numero_comprobante_corden2;
																				}
																				else
																				if($valor_medida==2)
																				{
																					$numero_comprobante_corden2="00".$numero_comprobante_corden2;
																				}
																				else	
																				if($valor_medida==3)
																				{
																							$numero_comprobante_corden2="0".$numero_comprobante_corden2;
																				}
																				
																				$numero_comprobante_corden=$tipo_cor.$numero_comprobante_corden2;
																		}
																		else
																		die("no_comp");
																	///	
																	//$id_cuenta_contable=$row_cta->fields("id");
																	$cuenta_contable=$row_cta->fields("cuenta_contable");
																	$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
																	$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
																	$requiere_pr=$row_cta->fields("requiere_proyecto");
																	$requiere_aux=$row_cta->fields("requiere_auxiliar");
																	$descripcion_cord=$row_cta->fields("descripcion");
																																	$id_cc=$row_cta->fields("id");

																}
									
									
									//consultando id tipos
									$sql_id_tipos="
													SELECT	id
															from
																tipo_comprobante
															where
															 codigo_tipo_comprobante='$tipo_cor'	
									";		
									//die($sql_id_tipos);		
									$row_tipos=& $conn->Execute($sql_id_tipos);
									if(!$row_tipos->EOF)
									{
										$id_tipo_comprobante_cor=$row_tipos->fields("id");
									}
										else
										$id_tipo_comprobante_cor=0;
									
									
						}//fin 1
						else
						if($cont_cor==2)
						{
							
								$monto_neto_cor=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_neto']);
								$monto_credito_cor=str_replace(",",".",$monto_neto_cor);
								$monto_debito_cor=0;
								$debito_credito=2;
								$secuencia=2;
								if($partida_presu=='401')
								{
									$tipo_cor='94';
									$cuenta_cont_cor=4121301;
									//$cuenta_cont_cor_haber=422130100;
								}
								if($partida_presu=='402')
								{
										$tipo_cor='91';
										$cuenta_cont_cor=4121302;
										//$cuenta_cont_cor_haber=422130200;
								}
								if($partida_presu=='403')
								{
										$tipo_cor='92';
										$cuenta_cont_cor=4121303;
									//$cuenta_cont_cor_haber=422130300;
								}
								if($partida_presu=='404')
								{
										$tipo_cor='93';
										$cuenta_cont_cor=4121304;
										//$cuenta_cont_cor_haber=422130400;
								}
									//consultando caracteristicas de la cuenta contable
						$sql_cta="select 
																	id,
																	cuenta_contable_contabilidad.cuenta_contable,
																	cuenta_contable_contabilidad.nombre as descripcion,
																	cuenta_contable_contabilidad.requiere_auxiliar,
																	cuenta_contable_contabilidad.requiere_unidad_ejecutora,
																	cuenta_contable_contabilidad.requiere_proyecto,
																	cuenta_contable_contabilidad.requiere_utilizacion_fondos
																 from 
																	cuenta_contable_contabilidad 
																where
																			(cuenta_contable_contabilidad.id_organismo =".$_SESSION['id_organismo'].")
																		AND
																			cuenta_contable='$cuenta_cont_cor'";
																			//die($sql_cta);
																$row_cta=& $conn->Execute($sql_cta);
																if(!$row_cta->EOF)
																{
																	//$id_cuenta_contable=$row_cta->fields("id");
																	$cuenta_contable=$row_cta->fields("cuenta_contable");
																	$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
																	$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
																	$requiere_pr=$row_cta->fields("requiere_proyecto");
																	$requiere_aux=$row_cta->fields("requiere_auxiliar");
																	$descripcion_cord=$row_cta->fields("descripcion");

																	$id_cc=$row_cta->fields("id");

																}
									
									
									//consultando id tipos
									$sql_id_tipos="
													SELECT	id
															from
																tipo_comprobante
															where
															 codigo_tipo_comprobante='$tipo_cor'	
									";				
									$row_tipos=& $conn->Execute($sql_id_tipos);
									if(!$row_tipos->EOF)
									{
										$id_tipo_comprobante_cor=$row_tipos->fields("id");
									}
										
						}
									
										////////////////////// verificando si requiere unidad ejecutora//////////////////////
									if($requiere_ue==true)
									{
										$unidad_ejecutora2=$unidad_ejecutora;
									}else
									$unidad_ejecutora2=0;
						////////////////////// verificando si requiere utilizacion fondo//////////////////////
									if($requiere_uf==true)
									{
										$partida2=$partida;
									}else
									$partida2=0;
						////////////////////// verificando si requiere proyecto//////////////////////
									if($requiere_pr==true)
									{
										$accion_central2=$accion_central;
										$proyecto2=$proyecto;
									}else
									$accion_central2=0;
									$proyecto2=0;
									//die($partida_presu);
/////////////////////////////////////////////// agregando a tablasd de saldo
$turnos=1;
$contadores=0;
$id_sumas=$id_cc;
/************************************/
$sql_tipo="select 
									cuenta_contable_contabilidad.id,
									naturaleza_cuenta.codigo  AS codigo
								
								from
										cuenta_contable_contabilidad 
								inner join
											naturaleza_cuenta
								on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id"
							;
	$rs_tipo_c=& $conn->Execute($sql_tipo);
	if (!$rs_tipo_c->EOF) 
	{
		$codigo=$rs_tipo_c->fields("codigo");
	}						
/************************************/
		while($turnos>$contadores)
		{
			$monto_debito=$monto_debito_cor;
			$monto_credito=$monto_credito_cor;
					$sqlw="select 
									*
								
								from
										cuenta_contable_contabilidad 
								
								where id='$id_sumas'";				//	die($sqlw);

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
									";
									//die($sql_mov_suma);
												$rs_mov_suma=& $conn->Execute($sql_mov_suma);
												if (!$rs_mov->EOF) 
												{
													$monto_debe_suma = $rs_mov_suma->fields("debe")+$monto_debito;
													$monto_haber_suma = $rs_mov_suma->fields("haber")+$monto_credito;

													$sql_mod_suma="update
																	saldo_contable
																SET 
																		debe[".$mes."]= '$monto_debe_suma',
																		haber[".$mes."]= '$monto_haber_suma'
																WHERE
																			cuenta_contable='$suma_cuenta';
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
															";
															//die($sql_mov);
																		$rs_mov=& $conn->Execute($sql_mov);
																		if (!$rs_mov->EOF) 
																		{
																			$monto_debe2 = $rs_mov->fields("debe")+$monto_debito;
																			$monto_haber2 = $rs_mov->fields("haber")+$monto_credito;
																$sql_mod="update
																				saldo_contable
																			SET 
																					debe[".$mes."]= '$monto_debe2',
																					haber[".$mes."]= '$monto_haber2'
																			WHERE
																					cuenta_contable='$id_cc';
																						";
																		}
																		else
																		$sql_mod="";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////	
								
									$sql_cuenta_orden =  "INSERT INTO 
																				movimientos_contables
																				(
																					id_organismo,
																					numero_comprobante,
																					secuencia,
																					ano_comprobante,
																					mes_comprobante,
																					id_tipo_comprobante,
																					comentario,
																					cuenta_contable,
																					descripcion,
																					referencia,
																					debito_credito,
																					monto_debito,
																					monto_credito,
																					id_unidad_ejecutora,
																					id_proyecto,
																					id_accion_central,
																					id_utilizacion_fondos,
																					id_auxiliar,
																					fecha_comprobante,
																					ultimo_usuario,
																					ultima_modificacion,
																					estatus  
																					
																				) 
																				VALUES
																				(
																					".$_SESSION["id_organismo"].",
																					'$numero_comprobante_corden',
																					'$secuencia_cor',
																					'$ano',
																					$mes,
																					'$id_tipo_comprobante_cor',
																					'presupuesto prueba cuentas de orden debe ',
																					'$cuenta_cont_cor',
																					'$descripcion_cord',
																					'$numero_compromiso',
																					'$debito_credito',
																					 $monto_debito_cor,
																					 $monto_credito_cor,
																					 $unidad_ejecutora2,
																					 $proyecto2,
																					 $accion_central,
																					 $partida2,
																					 $auxiliar,
																					'".date("Y-m-d H:i:s")."',
																					 ".$_SESSION['id_usuario'].",
																					 '".date("Y-m-d H:i:s")."',
																					 '1'
																				);
																				UPDATE
																							tipo_comprobante	
																						set
																							numero_comprobante='$numero_comprobante_corden2'
																				where

																							id='$id_tipo_comprobante_cor'	;
																				$sql_mod;
																				$sql_mod_sumas_todas						
																				";
													//$sql_cuenta_orden2=$sql_cuenta_orden2." ".$sql_cuenta_orden;							
							
												if (!$conn->Execute($sql_cuenta_orden)) 
												{
													$error_cor=1;
												}
											if($error_cor==1)
												die ('Error al Registrar: '.$sql_cuenta_orden);
									
											
									$cont_cor=$cont_cor+1;	
									$secuencia_cor=$secuencia_cor+1;	
				}
			//die($sql_cuenta_orden2);
				/*if (!$conn->Execute($sql_cuenta_orden2)) 
							{
								$error_cor=1;
							}
						if($error_cor==1)
							die ('Error al Registrar: '.$sql_cuenta_orden2);*/
				
}// fin de if tipo cierre anticipo;			
			///////////////////////////////////////////////////////////////////////									
										
																		$sql = "UPDATE documentos_cxp 
																		 SET
																			numero_comprobante='$numero_comprobante',
																			n_comprobante_co='$numero_comprobante_corden',
																			estatus='2',
																			ultimo_usuario=".$_SESSION['id_usuario'].", 
																			fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
																		WHERE 
																					id_organismo=$_SESSION[id_organismo]
																			AND
																				id_documentos='$doc'
																		";
																		//die($sql);
																		if (!$conn->Execute($sql)) {
																				die ('Error al Actualizar: '.$conn->ErrorMsg());}
										//--- CERRANDO ELSE//
								$responce="integrado"."*".$numero_comprobante3."*".$numero_comprobante_corden;

										die($responce);

			}
			else
				die("no_documento");
}	
?>