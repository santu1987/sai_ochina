<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$ano=substr($fecha,0,4);
$mes=substr($fecha,5,2);
$dia=substr($fecha,8,2);
$ci=0;
///verificando q si no tiene iva el usuario debe cargarlo
$sql_iva="select cuenta_contable as cuenta_contable_iva from impuesto where upper(nombre)='IVA'
												AND	impuesto.id_organismo = $_SESSION[id_organismo]									
													";
$row_iva=& $conn->Execute($sql_iva);
///////////////////////
if($row_iva->EOF)
 {
	//die($sql_iva); 
	die('valor_iva');
 }	
ELSE
if(!$row_iva->EOF)
 {
	 
	if($row_iva->fields("cuenta_contable_iva")==NULL)
	die('valor_iva');
 }


$Sql="  SELECT  distinct
			    cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				banco_cuentas.cuenta_contable_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.monto_cheque,
				cheques.id_proveedor,
				cheques.nombre_beneficiario,
				cheques.cedula_rif_beneficiario,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				cheques.ordenes,
				cheques.tipo_cheque,
				cheques.estatus,
				cuenta_contable_contabilidad.requiere_auxiliar,
				cuenta_contable_contabilidad.requiere_unidad_ejecutora,
				cuenta_contable_contabilidad.requiere_proyecto,
				cuenta_contable_contabilidad.requiere_utilizacion_fondos,
				cuenta_contable_contabilidad.nombre	as descripcion,
				cuenta_contable_contabilidad.id as cuenta_contable_id 
			FROM
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				cheques.id_banco=banco.id_banco
			INNER JOIN 
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			INNER JOIN
				banco_cuentas
			ON
				cheques.cuenta_banco=banco_cuentas.cuenta_banco
			INNER JOIN
				 cuenta_contable_contabilidad
			ON
				banco_cuentas.cuenta_contable_banco=cuenta_contable_contabilidad.cuenta_contable  									
			where
					numero_cheque>0
			AND
					cheques.estatus!='5'
			AND
					usuario_cheque='".$_SESSION['id_usuario']."'
			AND
					cheques.contabilizado=0
			AND
					cheques.id_organismo = $_SESSION[id_organismo]			
			";
			
if (!$conn->Execute($Sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($Sql);
if(!$row->EOF)
{
				//*die($Sql);
				/*	$sql_secuencia="select  secuencia from integracion_contable ORDER BY secuencia desc";
					if(!$conn->Execute($sql_secuencia))die('Error al Registrar:'.$conn->ErrorMsg());
						$row_secuencia=$conn->Execute($sql_secuencia);
						if(!$row->EOF)
						$secuencia=$row_secuencia->fields("secuencia");	
						else*/
				$secuencia=0;
				$error=0;
				$secuencia=$secuencia+1;
				//
				//buscando numero_de_comprobante
				//// consultando el tipo de comprobante
				$sql_tipo="select 
									id,codigo_tipo_comprobante,numero_comprobante_integracion
								from 
										tipo_comprobante 
								where upper(nombre)='EMISION DE CHEQUES'
								AND	tipo_comprobante.id_organismo = $_SESSION[id_organismo]									
							";
						//	die($sql_tipo);
								if (!$conn->Execute($sql_tipo)) die ('Error al Registrar: '.$conn->ErrorMsg());
							//	$row_tipo=& $conn->Execute($sql_tipo);
								/*if(!$row_tipo->EOF)
								{
									$tipo=$row_tipo->fields("id");
								}*/
					/*$sql_comprobante="select numero_comprobante_integracion
																 from 
																		tipo_comprobante
															where
															
														numeracion_comprobante.id_organismo = $_SESSION[id_organismo]									
														and
															codigo_tipo_comprobante='21'			
															";*/
															
														/*	INNER JOIN
																		organismo
																	on
																		organismo	
										WHERE
													id_organismo = $_SESSION[id_organismo]	*/
															
												$row_tipo=& $conn->Execute($sql_tipo);
												if(!$row_tipo->EOF)
												{
													$tipo=$row_tipo->fields("id");
													$cod_tipo=$row_tipo->fields("codigo_tipo_comprobante");
														$sql_num="SELECT  
																	  max(integracion_contable.numero_comprobante) as maximo
																  FROM integracion_contable
																  INNER JOIN
																	tipo_comprobante
																 ON
																 tipo_comprobante.id=integracion_contable.id_tipo_comprobante
																				where		
																						(integracion_contable.id_organismo =".$_SESSION['id_organismo'].") 
																				and
																					ayo='$ano'
																				and
																					codigo_tipo_comprobante='$cod_tipo'
																				and	
																					mes_comprobante='$mes'
																	
																	
																	";
													//	die($sql_num);
													$ci++;
														$rs_comprobante =& $conn->Execute($sql_num);
														if(!$rs_comprobante->EOF)
														{
																	$numero_comprobante=substr($rs_comprobante->fields("maximo"),7)+1;
															//		echo($comprobante);die("!");
																	$sig_comp=substr($numero_comprobante,2);
																/*if($ci==2)
																{
																	echo($numero_comprobante);
																	}*/
				/*													$uno=substr($mes,0,1);
																	if($uno==0)
																	$mes=substr($mes,1,1);
///				*/											//$comprobante=$ano.$mes.$dia.$numero_comprobante;	
														}
														if($numero_comprobante=='1')
														{		
														/*$uno=substr($mes,0,1);
														if($uno==0)
														$mes=substr($mes,1,1);
														$comprobante=$cod_tipo.$mes.'000';
														$sig_comp=substr($comprobante,2);*/
														//die($comprobante);	
														
															$uno=substr($mes,0,1);
	if($uno==0)
	$mes2=substr($mes,1,1);
	$numero_comprobante=$cod_tipo.$mes2.'000';
	//die($comprobante);
	$sig_comp=substr($numero_comprobante,2);
//	$comprobante=$ano.$mes.$dia.$comprobante;		

														}
																													$numero_comprobante=$ano.$mes.$dia.$numero_comprobante;	
	
														$sql_act_comp="UPDATE
																			tipo_comprobante	
																		set
																			numero_comprobante='$sig_comp'
																		where
																			
																			id='$tipo'
																			";
																			$numero_comprobante3=$sig_comp;
	//die($sql_act_comp);
													/*if(($row_tipo->fields("numero_comprobante_integracion")!="")&&($row_tipo->fields("numero_comprobante_integracion")!="0000"))
													{
														$numero_comprobantex=$row_tipo->fields("numero_comprobante_integracion");	
														$numero_comprobantex3=$numero_comprobantex+1;
													}

													
													if($row_tipo->fields("numero_comprobante_integracion")=="0000")
													$numero_comprobantex3="0001";	
													$valor_medida=strlen($numero_comprobantex3);													//echo($numero_comprobantex3);

												//	echo($valor_medida);
												if($valor_medida==1)
												{
													$numero_comprobantex3="000".$numero_comprobantex3;
												}
												else
												if($valor_medida==2)
												{
													$numero_comprobantex3="00".$numero_comprobantex3;
												}
												else	
												if($valor_medida==3)
												{
															$numero_comprobantex3="0".$numero_comprobantex3;
												}
												
												$numero_comprobante=$cod_tipo.$numero_comprobantex3;
													//die($numero_comprobantex3);
												*/	
												}
				//
				$cuenta_contable_iva=0;
				
				while((!$row->EOF)&&($error==0))
				{	
					$cont=1;
					while(($cont!=$turnos)&&($error==0))
					{
					////
						$id_proveedor=$row->fields("id_proveedor");
						$requiere_ue=$row->fields("requiere_unidad_ejecutora");
						$requiere_uf=$row->fields("requiere_utilizacion_fondos");
						$requiere_pr=$row->fields("requiere_proyecto");
						$requiere_aux=$row->fields("requiere_auxiliar");
						$id_cuenta_contable=$row->fields("cuenta_contable_id");
						$numero_cheque=$row->fields("numero_cheque");
					////
					
					$turnos=3;	
						$auxiliar=0;
						//////////////////////////////////////////// realizando el recorrido a tablas de presupuesto/////////////
										if($row->fields("ordenes")!="{0}")
										{	
															$ord_1=str_replace("{","",$row->fields("ordenes"));
															$ord_2=str_replace("}","",$ord_1);
															$vector = split(",",$ord_2);
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
																$iva=0;
																while($i_fact < $contador_fact)
																{//////////consultando las facturas	
																	
																						$sql_facturas="
																											SELECT 
																													numero_compromiso,monto_bruto,porcentaje_iva,porcentaje_retencion_iva
																											FROM
																													documentos_cxp
																											where
																													id_documentos='$facturas[$i_fact]'";
																						if(!$conn->Execute($sql_facturas)){echo('Error al Registrar:'.$conn->ErrorMsg());die($sql_facturas);}
																						$row_documentos=& $conn->Execute($sql_facturas);
																						$numero_compromiso=$row_documentos->fields("numero_compromiso");
																						$monto_restar=$row_documentos->fields("monto_bruto");
																						$iva=$row_documentos->fields("porcentaje_iva");
																						$retencion=$row_documentos->fields("porcentaje_retencion_iva");
																						if(($numero_compromiso==0)or($numero_compromiso=="")or($numero_compromiso=="NULL"))
																							{
																								$partida=0;
																								$unidad_ejecutora=0;
																								$accion_central=0;
																								$accion_especifica=0;
																								$pre_orden=0;
																								$proyecto=0;
																								
																							}
																						else{
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
																											\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
																										where
																											\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
																										AND	\"orden_compra_servicioE\".id_organismo = $_SESSION[id_organismo]									
																											";
																									$row_orden_compra=& $conn->Execute($sql);
																									$tipo_servicio=$row_orden_compra->fields("tipo");
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
																										if($tipo_servicio==1)
																										{
																											$proyecto=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																											$accion_central=0;
																										}else
																										if($tipo_servicio==2)
																										{
																											$proyecto=0;
																											$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																										}
																									}
																									else
																										$unidad_ejecutora=0;
																								//	$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
																								//	$pre_orden=$row_orden_compra->fields("id_accion_especifica");
																								//	$tipo=$row_orden_compra->fields("tipo");
																								}
																								
																$i_fact++;
																
																}						
															$i++;							
															}			
												}//fin si tiene ordenes
												else
												{
													$accion_central=0;
													$proyecto=0;
													$unidad_ejecutora=0;
													$partida=0;
													$iva=0;
													$retencion=0;
												}
								/////////////////veriicando si cuenta con iva 
								$sql_iva="select cuenta_contable as cuenta_contable_iva from impuesto where nombre='IVA'
												AND	impuesto.id_organismo = $_SESSION[id_organismo]									
													";
								$row_iva=& $conn->Execute($sql_iva);
								if((!$row_iva->EOF)&&($iva!=0))
								 {
									$cuenta_contable_iva=$row_iva->fields("cuenta_contable_iva");
									$turnos=4;
								 }	
											
							//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $sql_ut="select utilizacion_fondos from utilizacion_fondos where cuenta_utilizacion_fondos='$partida' AND	utilizacion_fondos.id_organismo = $_SESSION[id_organismo]									
													";
							 $row_ut=& $conn->Execute($sql_ut);
							 if($row->EOF)
							 {
								$partida=0;
							 }
							/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
						//forma provisional de cargar debe y haber 
							if($cont==1)
								{
								
											if($id_proveedor==NULL)
											{
												$codigo_prove=$row->fields("cedula_rif_beneficiario");
												$nombre_prove=$row->fields("nombre_beneficiario");
												$cuenta_contable="2110309";
											}else
											{
												$sql_prove="select codigo_proveedor,nombre as nombre_proveedor  from proveedor where id_proveedor='$id_proveedor' AND	proveedor.id_organismo = $_SESSION[id_organismo]";
												$row_prove=$conn->Execute($sql_prove);
												$codigo_prove=$row_prove->fields("codigo_proveedor");
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
															where cuenta_contable='$cuenta_contable'
															AND	cuenta_contable_contabilidad.id_organismo = $_SESSION[id_organismo]									
													";
												$row_cta=& $conn->Execute($sql_cta);
												if(!$row_cta->EOF)
												{
													$id_cuenta_contable=$row_cta->fields("id");
													$requiere_ue=$row_cta->fields("requiere_unidad_ejecutora");
													$requiere_uf=$row_cta->fields("requiere_utilizacion_fondos");
													$requiere_pr=$row_cta->fields("requiere_proyecto");
													$requiere_aux=$row_cta->fields("requiere_auxiliar");
												}
												/*$sql_desc="select nombre  from cuenta_contable_contabilidad where cuenta_contable='$cuenta_contable'";
												$row_desc=$conn->Execute($sql_desc);
												$descripcion=$row_desc->fields("nombre");		*/
									$debito_credito=1;
									$monto_debito=$row->fields("monto_cheque");
									if($iva!=0)
									{
										$monto_iva=$row->fields("monto_cheque")*$iva/100;
										$monto_ret=($monto_iva*$retencion)/100;
										$monto_ret2=$monto_iva-$monto_ret;
										$monto_debito=$monto_debito-$monto_ret2;
				
									}
									$monto_credito=0;
									$acu_debe=$acu_debe+$monto_debito;
									$acu_haber=$acu_haber+$monto_haber;
									$referencia=substr($row->fields("numero_cheque"),0,14);
									$descripcion="ch-".$referencia."    ".$nombre_prove;
								}
								if($cont==2)
								{	
									if($turnos==3)
									{
										$debito_credito=2;
										$monto_credito=$row->fields("monto_cheque");
										$monto_debito=0;
										$acu_debe=$acu_debe+$monto_debito;
										$acu_haber=$acu_haber+$monto_haber;
										$cuenta_contable=$row->fields("cuenta_contable_banco");
										$referencia=substr($row->fields("numero_cheque"),0,14);
									}else
									if($turnos==4)
									{
										$debito_credito=1;
										$monto_iva=$row->fields("monto_cheque")*$iva/100;
										$monto_ret=($monto_iva*$retencion)/100;
										$monto_debito=$monto_iva-$monto_ret;
										$monto_credito=0;
										$acu_debe=$acu_debe+$monto_debito;
										$acu_haber=$acu_haber+$monto_haber;
										$cuenta_contable=$cuenta_contable_iva;
										$referencia=substr($row->fields("numero_cheque"),0,14);
									}
									//$descripcion=$row->fields("descripcion");
								}
								else
								if((($cont==2)&&($turnos==3))||(($cont==3)&&($turnos==4)))
								{
										$debito_credito=2;
										$monto_credito=$row->fields("monto_cheque");
										$monto_debito=0;
										$acu_debe=$acu_debe+$monto_debito;
										$acu_haber=$acu_haber+$monto_haber;
										$cuenta_contable=$row->fields("cuenta_contable_banco");
										$referencia=substr($row->fields("numero_cheque"),0,14);
								}
/////////////////verificando si requiere auxliar la cuentacontable//////////////////////////////////////////////////////
							if($requiere_aux==true)
							{
								$sql_auxiliar="select 
													id_auxiliares
												from 
														auxiliares 
												where cuenta_contable='$id_cuenta_contable'
												AND	auxiliares.id_organismo = $_SESSION[id_organismo]									
											";
												
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
							$auxiliar=0;
								///////////////////////
								if ($unidad_ejecutora=="")
								{
									$unidad_ejecutora='0';
								}
								if($proyecto=="")
								{
									$proyecto='0';
								}
								if($accion_central=="")
								{
									$accion_central='0';
								}									 		
							//-------id_accion_central--------------------------------//guardando datos necesarios para el desarollo de la integracion contable}								
							//$numero_comprobante_guardar=substr($numero_comprobantex3,2,4);
							$numero_comprobante_guardar=$numero_comprobantex3;
										$sql = "
													$sql_act_comp;
													INSERT INTO 
															integracion_contable
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
																'$mes',																
																'$tipo',
																'$_POST[tesoreria_integracion_contable_comentarios]',
																 $cuenta_contable,
																 '$descripcion',
																 $referencia,
																 '$debito_credito',
																 '$monto_debito',
																 '$monto_credito',
																 '$unidad_ejecutora',
																 '$proyecto',
																 '$partida',
																 '$auxiliar',
																 '".date("Y-m-d H:i:s")."',
																 ".$_SESSION['id_usuario'].",
																 '".date("Y-m-d H:i:s")."',
																 '$accion_central'
															);
															UPDATE
															cheques
															set
																contabilizado='1',
																fecha_contab='".date("Y-m-d H:i:s")."',
																usuario_contab='".$_SESSION['id_usuario']."',
																numero_comprobante_integracion='$numero_comprobante',
																cuenta_contable_banco='$cuenta_contable'	
															where
																numero_cheque='$numero_cheque';
															
													";
											//	die($sql);	
							if (!$conn->Execute($sql)) 
							{
								$error=1;
							}
							
							$cont=$cont+1;
						if($error==1)
							die ('Error al Registrar: '.$sql);
							$secuencia=$secuencia+1;
					
							/*if($acu_debe!=$acu_haber)
							{
				
							//	die('Error al Registrar:'.$conn->ErrorMsg());
								die('Error al registrar'.$sql);
							}	*/	
						}
						$numero_comprobante=$numero_comprobante+1.00;
				$row->MoveNext();			
				}
				if($error==1)
								//die('Error al Registrar:'.$conn->ErrorMsg());
											
				die ('Error al Registrar: '.$sql);
				else
				
					die("Registrado");
				//die("$sql");
}
else
					die ('registros_integrados'.$sql);
			
?>