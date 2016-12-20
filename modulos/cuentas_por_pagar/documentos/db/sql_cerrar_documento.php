<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql_cuenta_orden2="";
$numero_comprobante_corden=0;
/////////////////////////////////////////////-/creando comprobante contable debe y haber/-//////////////////////////////////////////////////////////			
//die($fecha);
//$ano=date("Y");
/*$mes=substr($fecha,3,2);
$ano=substr($fecha,6,4);
*/
// el deber ser es que la fecha de cmprobante sea la del dia e q se crea la factura, per no es asi para esta prueba..asi que cambio la fecha del comprobante con la fecha de la factura...NOTA IMPORTANTE TAMBIEN SE LE CAMBIARON LOS VALORES DE LAS FECHAS A:MOVIMIENTOS CONTABLES
/*$fecha = date("Y-m-d H:i:s");
$dia=substr($fecha,8,2);
$mes=substr($fecha,5,2);
$ano=substr($fecha,0,4);*/
$fecha=$_POST['cuentas_por_pagar_db_fecha_f'];
$dia=substr($fecha,0,2);
$mes=substr($fecha,3,2);
$ano=substr($fecha,6,4);
//die($dia."/".$mes."/".$ano);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
*** SE TUVO QUE MODIFICAR NUEVAMENTE ESTE SCRIPT FECHA 29/02/2012 CAMBIANDO LAS RELACIONES CON LAS TABLAS REQUISICION ENCABEZADO  A ORDEN ENCABEZADO, PARA AGOSTO DE 2011 ESTABA ASI PERO DEBIDO A INDICACIONES DADAS PARA CREA UN PROCESO QUE PERMITIERA CREAR ORDENES A CONTABILIDAD (PROGRAMA PRESUPUESTARIO) SE REALIZÓ EL CAMBIO Y AHORA DEBE REVERTIRSE EN TODOS LOS PROGRAMAS DE LOS MODULOS DE CXP Y TESORERIA...
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$numero_documento=$_POST['cuentas_por_pagar_db_numero_documento'];
$id_tipo_comprobante=$_POST['cuentas_por_pagar_integracion_tipo_id'];
$id_proveedor=$_POST['cuentas_por_pagar_db_proveedor_id'];
$referencia=$_POST['cuentas_por_pagar_db_numero_documento'];
$monto= str_replace(".","",$_POST['cuentas_por_pagar_db_sub_total']);
$monto_iva=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ret_iva']);
$monto_ret_iva2=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ret_iva2']);
$monto_iva_causar2=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_iva']);
$sub=str_replace(".","",$_POST['cuentas_por_pagar_db_sub_total']);
//$comp=$_POST['cuentas_por_pagar_db_compromiso_n'];
$monto_iva_causar=str_replace(",",".",$monto_iva_causar2);
$iva_sec=$_POST['cuentas_por_pagar_db_iva'];
$iva_sec2=$_POST['cuentas_por_pagar_db_iva2'];
$unidad_ejecutora=0;
$accion_central=0;
$partida=0;
$auxiliar=0;
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
		}	
    	$is++;
	}
	
return($cont_cadena);

}
$co=$_POST['check_invisible'];
//DEFINIENDO CON QUE TIPO/ID FUERON CARGADOS EN LA BASE DE DATOS LAS FACTURAS Y ANTICIPOS
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
														//SE DEFINE LA CANTIDAD DE TURNOS O ASIENTOS UE TENDRA EL REGISTRO CNTABLE SEGÚN EL TIPO DE DOCUMENTO
														if(!$row_factura2->EOF)
														{
															if($_POST[cuentas_por_pagar_db_anticipos]==$row_factura2->fields("tipo_documentocxp"))//si es anticipo
															{	
																$tipo_cierre="anticipo";
																//die($tipo_cierre);
																		$turnos=3;	
															}		
															else
																if($_POST[cuentas_por_pagar_db_anticipos]!=$row_factura2->fields("tipo_documentocxp"))//si es anticipo
															{	
																$tipo_cierre="otro";
																$turnos=5;
															}	
															if((($row_factura2->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura2->fields("amortizacion")!=0))
															{
																$tipo_cierre="anticipo_factura";
																$turnos=5;	
															}
														}	
									if(($turnos=="")||($turnos=="0")||($turnos==null))					
									{
										die("error en numero factura");
									}
								//	die($sql_facturas2);				
									//	echo($tipo_cierre);die($turnos);				
/////////////////////////////
$id_tipo_d=$_POST['cuentas_por_pagar_db_tipo_documento'];
//OBTENIENDO EL MONTO QUE DEBE LLEVAR LOS REGISTROS DEL ASIENTO CNTABLE
$sql_facturas_v="select 
				id_documentos,
   (monto_base_imponible*porcentaje_iva/100) as iva,
       ((monto_base_imponible*porcentaje_iva/100)* (porcentaje_retencion_iva))/100 as retencion_iva1,
	(monto_base_imponible2*porcentaje_iva2/100) as iva2,
       ((monto_base_imponible2*porcentaje_iva2/100)* (retencion_iva2))/100 as retencion_iva2,
       (((monto_base_imponible*porcentaje_iva/100)* (porcentaje_retencion_iva))/100)+(((monto_base_imponible2*porcentaje_iva2/100)* (retencion_iva2))/100) as suma_ret
				from 
							documentos_cxp 
				where
						(documentos_cxp.id_organismo=$_SESSION[id_organismo])
				  AND   
				  		(documentos_cxp.id_proveedor='$id_proveedor')
				  AND 
				  		(documentos_cxp.tipo_documentocxp='$id_tipo_d')
				  AND
				  		(documentos_cxp.numero_compromiso='$comp')
				  AND
				  		(documentos_cxp.estatus!=3)
				  AND
				  		(documentos_cxp.estatus!=2)		
										
						";
$row_doc2=& $conn->Execute($sql_facturas_v); //die($sql_facturas_v);
			//die($sql);
$conta_toro=0;
while(!$row_doc2->EOF)
{
	$ids_documentos[$conta_toro]=$row_doc2->fields("id_documentos");
	$conta_toro++;
	$retenciones_suma_total=$retenciones_suma_total+$row_doc2->fields("suma_ret");
	
	$row_doc2->MoveNext();
}

//////////////////////////////	
$secuencia=0;
$error=0;
$secuencia=$secuencia+1;
$cuenta_contable_iva=0;
/////buscando datos para causar, y elaborar comprobantes unidad ejecutora, partida etc
if($_POST['cuentas_por_pagar_db_compromiso_n']!="")
	{
			
	//die($_POST['cuentas_por_pagar_db_compromiso_n']);
	$numero_compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
	$fecha_comp=$_POST['fecha_oculta'];
	//$mes=substr($fecha_comp,3,2);
//	$ano=substr($fecha_comp,6,4);
	//die($mes);
	///se quito la relacion con requisicion 29/02/2010
	$sql="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						\"orden_compra_servicioE\".id_unidad_ejecutora, 
						\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
						\"orden_compra_servicioE\".id_accion_especifica,
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
						\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
					GROUP BY
						tipo,

						id_orden_compra_servicioe ,

						\"orden_compra_servicioE\".id_unidad_ejecutora, 

						\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 

						\"orden_compra_servicioE\".id_accion_especifica,

						\"orden_compra_servicioE\".tipo,
					    partida, 

					    generica, 

					    especifica, 

					    subespecifica		
						";
				$row_orden_compra=& $conn->Execute($sql);
			//die($sql);
$conta_tor=0;
while(!$row_orden_compra->EOF)
{				
	$monto_restar0= str_replace(".","",$_POST[cuentas_por_pagar_db_monto_bruto]);		
	$monto_restar=str_replace(",",".",$monto_restar0);	
	//////////////////////////////comprobando que el monto de la factura no sea mayor al compromiso ...comprobandolo nuevamente
	/////////////////////////////ya que al relacionarse con un numero de compromiso se comprueba esto
	//////////////////////////A/////////////////////BUSCANDO TOTALES DE LAS ORDENES  DE COMPRA/SERVICIO COMPROMETIDAS
								$compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
								$sql_cmp="SELECT 
									\"orden_compra_servicioE\".numero_compromiso, 
									\"orden_compra_servicioE\".numero_precompromiso,
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
									\"orden_compra_servicioE\".numero_compromiso='$compromiso'
								AND 
													(\"orden_compra_servicioD\".ano = '".date("Y")."')
												AND
													\"orden_compra_servicioD\".partida = '".$row_orden_compra->fields("partida")."'  
												AND	
													\"orden_compra_servicioD\".generica = '".$row_orden_compra->fields("generica")."'
												AND	
													\"orden_compra_servicioD\".especifica = '".$row_orden_compra->fields("especifica")."'  
												AND
													\"orden_compra_servicioD\".subespecifica = '".$row_orden_compra->fields("subespecifica")."'	
									";
			//	die($sql_cmp);
							$row_orden_compra_datos_monto=& $conn->Execute($sql_cmp);
							$total_renglon=0;
							while(!$row_orden_compra_datos_monto->EOF)
							{
								$total=$row_orden_compra_datos_monto->fields("monto")*$row_orden_compra_datos_monto->fields("cantidad");
								$iva=$total*($row_orden_compra_datos_monto->fields("impuesto")/100);
								//$iva=0;
								$ivas=$ivas+$iva;
								$total_total=$total+$iva;
								$total_renglon=$total_renglon+$total_total;
								$total_renglon2=$total_renglon2+$total_renglon;
								$row_orden_compra_datos_monto->MoveNext();
							}
				
		//elaboro ahora el causado directamente consultando en presupuesto ejecutador y realizando el update funciona in god we trust
		if(($tipo_cierre!="anticipo")&&('$numero_compromiso'!=""))
		{
		//ciclo para causar las facturas q esten asociadas
		$counr2=0;
		while($conta_toro>$counr2)
		{$ids=$ids_documentos[$counr2];
		//die($tipo_cierre);
			$partida=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
			//$ids=$_POST[cuentas_por_pagar_db_id];
			$sql_doc_det="select monto,impuesto from doc_cxp_detalle where id_doc='$ids'
					and partida='$partida'
					";
					//die($sql_doc_det);
					$row_doc_det=& $conn->Execute($sql_doc_det);
					/*if($partida=='402100800')
					die($sql_doc_det);*/
					if(!$row_doc_det->EOF)
					{
						$causado=$row_doc_det->fields("monto");
						$imp=$row_doc_det->fields("impuesto");
					//	die($imp2);
                        $causado_iva=(($causado*$imp)/100);
						$causado=$causado+$causado_iva;
						
						//die($causado);
					
					//die("error");
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
///////////////////////////////////////----- realizando el causado en las tablas de presupuesto
///$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
									$pre_orden=$row_orden_compra->fields("id_accion_especifica");
									//$tipo=$row_orden_compra->fields("id_proyecto");
									//29/02/2012: se cambio nuevamente este proceso relacionandose con la orden_compra asi estaba inicialmente y fue mandando a cambiar en agosto de 2011:otro proceso revertido:S lo dejo en comentario por si acaso NO BORRAR
									/*if($row_orden_compra->fields("id_proyecto")!='')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto").""; 
									}else
									$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_accion_centralizada").""; */
									//
									if($tipo=='1')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									}else
										if($tipo=='2')
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
											//$monto_total = $monto_causado + $monto_restar;	
/*$monto_iva=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ret_iva']);
$monto_iva_causar2=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_iva']);
$monto_iva_causar=str_replace(",",".",$monto_iva_causar2);*/

										
											$monto_total=round($monto_causado,2)+round($causado,2);
											//if($partida='402100200')
										//	die($monto_total."=".$monto_causado."+".$causado);
											//die($causado);
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
												";	
				
				//echo($actu1);
				/*
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
															partida = '".$row_orden_compra->fields("partida")."'  
														AND	
															generica = '".$row_orden_compra->fields("generica")."'
														AND	
															especifica = '".$row_orden_compra->fields("especifica")."'  
														$where
														";
									//	die($resumen_suma);		
										$rs_resumen_suma=& $conn->Execute($resumen_suma);
									
										if (!$rs_resumen_suma->EOF) 
											$monto_causado_iva = $rs_resumen_suma->fields("monto");
										
										else
											$monto_causado_iva = 0;
											//$monto_total = $monto_causado + $monto_restar;
											$causados=$causado+$monto_iva_causar;	
											$monto_total=$monto_causado_iva+$causados;
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
													partida = '".$row_orden_compra->fields("partida")."'  
												AND	
													generica = '".$row_orden_compra->fields("generica")."'
												AND	
													especifica = '".$row_orden_compra->fields("especifica")."'  
												AND
													sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
												$where;";*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//echo($actu1);														
						if (!$conn->Execute($actu1))
							die ('Error al CAUSAR: '.$conn->ErrorMsg().$actu1);
					//	$actu="";
								
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
												/*$asss=strpost($concad,$cadena1);
												//if es uno entonces $actu1=""
												if($asss!=0)
												{
													$actu1="";
													//echo($asss);
													$asss=0;
												}*/
												
												//por ultimo se una la cadena1  con cad
											$concad=$concad.";".$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
											}
											//$actu=$actu.";".$actu1;
											$monto_total=0;
											$monto_causado=0;
											$causado=0;

												
		
		
				}//fin de consulta en doc_detalle
		
		$counr2++;
		  }//end while
		}//end if											

	$row_orden_compra->MoveNext();

	}//fin del while
	//echo($asss);
	//die($actu);
///////////////BUSCANDO LOS TOTALES DE ESAS FACTURAS CERRADAS CON ESE MISMO NUMERO DE COMPROMISO///////////////////////////////////////////////////////////////			
	if(($tipo_cierre!="anticipo")&&('$numero_compromiso'!=""))
		{																		
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
																					//	die($sql_facturas);					
																						$row_factura=& $conn->Execute($sql_facturas);
																						//die($sql_facturas);
																						//$total_renglon=0;
																						$total_facturas_comprometidas=0;
																						while(!$row_factura->EOF)
																						{
																							$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
																							//die($row_factura->fields("monto_bruto")."+".$p_iva_factura);
																							$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;
																							$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
																							//die($total_facturas_comprometidas);
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
////////////////////////////////////c/////////////////////// ultimo paso verificar si el compromiso no es menor que lo que se causara 
					//$total_documento=$monto_restar;
					//$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento;
					$fact_ord=($total_renglon2);
						//die($fact_ord."<".$monto_restar);

							if(round($fact_ord,2)<round($monto_restar,2))
							{//die($total_facturas_comprometidas);	
								die("excede");
							}		
					///
					if($fact_ord<$total_documento)
					{
					die("El monto a pagar no puede superar al monto comprometido");
					}
						else
					{
						
						/*if (!$conn->Execute($actu))
							die ('Error al CAUSAR: '.$conn->ErrorMsg());
						$actu="";
	*/
					}	
		}//fin de if en caso de q sea anticipo no cause			
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
///fin de la elaboracion del cuasado
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
	$codigo_tipo=$_POST[cuentas_por_pagar_integracion_tipo];
	//die($codigo_tipo);
	//NUEVO CALCULCO DE NUMERO DE COMPROBANTE  FEBRERO-2011//
	    $secuencia=1;
	//	$tipo=$_POST[contabilidad_comp_pr_tipo];
		$sql_num="SELECT  
				  max(movimientos_contables.numero_comprobante) as maximo
			  FROM movimientos_contables
			  INNER JOIN
			  	tipo_comprobante
			 ON
			 tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
							where		
									(movimientos_contables.id_organismo =".$_SESSION['id_organismo'].") 
							and
								ano_comprobante='$ano'
							and
								codigo_tipo_comprobante='$codigo_tipo'
							and	
								mes_comprobante='$mes'
				
				
				";
	//die($sql_num);
    $rs_comprobante =& $conn->Execute($sql_num);
	if(!$rs_comprobante->EOF)
	{
				$numero_comprobante=substr($rs_comprobante->fields("maximo"),8)+1;
				//echo($numero_comprobante);die("!");
				$sig_comp=substr($numero_comprobante,2);
/*	$uno=substr($mes,0,1);
	if($uno==0)
	$mes=substr($mes,1,1);
*/
								
	}
	if($numero_comprobante=='1')
	{		
	$uno=substr($mes,0,1);
	if($uno==0)
	$mes2=substr($mes,1,1);
	else
	$mes2=$mes;
	$numero_comprobante=$codigo_tipo.$mes2.'000';
	$sig_comp=substr($numero_comprobante,2);
//die($numero_comprobante);
	
	}
	$numero_comprobante=$ano.$mes.$dia.$numero_comprobante;		
	
	//die($numero_comprobante);
	$sql_act_comp="UPDATE
						tipo_comprobante	
					set
						numero_comprobante='$sig_comp'
					where
						
						id='$_POST[cuentas_por_pagar_integracion_tipo_id]'
						";
						$numero_comprobante3=$sig_comp;
						
///	die($sql_act_comp);
////CONSULTANDO LA INFO SEGUN CTAS CONTABLES NEECSARIAS PARA REALZIAR LOS ASIENTOS, VARIA SEGUN EL TIPO DE DOC QUE SE ESTE CERRANDO					
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
											//die($sql_cta_debe);
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
			//die($monto_amortizacion);
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
											$sql_doc="SELECT *	FROM
																	documentos_cxp
																inner join 
																	organismo	
																ON
																	documentos_cxp.id_organismo=organismo.id_organismo
																where
																	(organismo.id_organismo =".$_SESSION['id_organismo'].")
																and 	
																	documentos_cxp.numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]'
																and
																	documentos_cxp.numero_compromiso='$numero_compromiso'
																";	
											//die($sql_doc);//	and  tipo_documentocxp='$tipos_ant'

																	
											$row_doc=& $conn->Execute($sql_doc);

											if(!$row_doc->EOF)
											{
													//	die($row_doc->fields("numero_comprobante"));
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
																	movimientos_contables
																ON
																	movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
																where
																	(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
																and 
																	movimientos_contables.numero_comprobante=".$row_doc->fields("numero_comprobante")."
																
																	";
													//die($sql_cta);
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
														//die($monto_credito);
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
									 ///die($monto_credito);

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
					$debe=((round($monto_debito,2) * 100) / 100); 
					$haber=((round($monto_credito,2) * 100) / 100); 
					$ad=round($ad,2)+round($debe,2);
					$ah=round($ah,2)+round($haber,2);


		
		}
		else
		{
		
				
///////////////////////////////////////////////SI ES FACT CON VARIAS PARTIDAS NORMALMENTE DEBE SER ESTE EL USADO
//en caso q vayan varios cargos por el debe	
		if($_POST[cuentas_por_pagar_activo_varios]=='1')
			{
			//
			//$cont=1;
				if($cont==1)
				{//die("entro");
	$counr=0;
	//echo($conta_toro.">".$counr);
	while($conta_toro>$counr)
	{$ids_documentos2=$ids_documentos[$counr];
	
	///////////////////////////////////////////////////////////////////////////////////////////////////
					$id_doc=$_POST['cuentas_por_pagar_db_id'];
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
													//die($sql_cta_debe);
													$row_cta_debe=& $conn->Execute($sql_cta_debe);
													if(!$row_cta_debe->EOF)
													{
														$id_cuenta_contable=$row_cta_debe->fields("cuenta_contable_id");
														$requiere_ue=$row_cta_debe->fields("requiere_unidad_ejecutora");
														$requiere_uf=$row_cta_debe->fields("requiere_utilizacion_fondos");
														$requiere_pr=$row_cta_debe->fields("requiere_proyecto");
														$requiere_aux=$row_cta_debe->fields("requiere_auxiliar");
													}
					
					$sql_varias="SELECT id, id_doc, partida, monto, id_organismo
  								FROM doc_cxp_detalle
								where
									id_doc='$ids_documentos2'
								";
							//	die($sql_varias);
					$row_varias=& $conn->Execute($sql_varias);
													while(!$row_varias->EOF)
													{
														$debito_credito=1;
														$monto_debito=$row_varias->fields("monto");
														
														$iva_porcent=str_replace(",",".",$iva_sec);
														$m_iva=(($iva_porcent)*($row_varias->fields("monto")))/100;
														$monto_debito=$row_varias->fields("monto")+$m_iva;
														$acum=$acum+$monto_debito;
														//die($iva_sec2);
														if(($iva_sec2!="")&&($iva_sec2!="0")&&($iva_sec2!="0,00"))
														{
															$iva_porcent2=str_replace(",",".",$iva_sec2);
															$m_iva=(($iva_porcent2)*($row_varias->fields("monto")))/100;
															$monto_debito=str_replace(",",".",$sub);
															
															//die($iva_sec2);
														}
										
														
														$monto_credito="0";
															$descripcion=$_POST['cuentas_por_pagar_db_comentarios2'];
															
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
						$debe=((round($monto_debito,2) * 100) / 100); 
						$haber=((round($monto_credito,2) * 100) / 100); 
						$ad=round($ad,2)+round($debe,2);
						$ah=round($ah,2)+round(haber,2);
															$sql = "
															$sql_act_comp;
															INSERT INTO 
															movimientos_contables
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
															ultima_modificacion,
															id_accion_central,
															estatus
															
															
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
															'$_POST[cuentas_por_pagar_db_compromiso_n]',
															$debito_credito,
															$monto_debito,
															$monto_credito,
															$unidad_ejecutora2,
															$accion_central,
															$partida2,
															$auxiliar,
															'".$_POST['cuentas_por_pagar_db_fecha_f']."',
															".$_SESSION['id_usuario'].",
															'".date("Y-m-d H:i:s")."',
															$accion_central,
															'1'
															);
															UPDATE
															documentos_cxp
															set
															numero_comprobante='$numero_comprobante'
															where
															numero_documento='$numero_documento'									
															";
															//die($sql);			
															if (!$conn->Execute($sql)) 
															{
															$error=1;
															}
															if($error==1)
															die ('Error al Registrar: '.$sql);
															$secuencia=$secuencia+1;
														
												
												$row_varias->MoveNext();
													}
													//echo($cont);
													//die("hola");
												$cont=2;
							
		$counr++;
	}//fin while
						}
						if($cont==2)
									{	
							//		die("entro");
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
										$monto_credito=($acum);
										//-($monto_iva_2);
										$descripcion="prueba FACTURAS CXP haber";
									}
					if($cont==3)
							{
							
									if($_POST['cuentas_por_pagar_db_ret_iva']!='0,00')
									{
											///die("entro");
											
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
										$debito_credito=1;
										$monto_credito=0;
									//$monto_debito=str_replace(",",".",$monto_iva);
									$monto_debito=$retenciones_suma_total;
									if(($iva_sec2!="")&&($iva_sec2!="0")&&($iva_sec2!="0,00"))
									{
										$retiva1=str_replace(",",".",$monto_iva);
										$retiva2=str_replace(",",".",$monto_ret_iva2);
										$retiva_total=$retiva1+$retiva2;
										$monto_debito=$retiva_total;						
									}
									$descripcion="prueba FACTURAS CXP haber";
									
									}else $variable_excepcion="no_entro";
							}
							if($cont==4)
							{
								if($_POST['cuentas_por_pagar_db_ret_iva']!='0,00')
								{
										///die("entro");
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
								//$monto_credito=str_replace(",",".",$monto_iva);
								$monto_credito=$retenciones_suma_total;
								if(($iva_sec2!="")&&($iva_sec2!="0")&&($iva_sec2!="0,00"))
									{
										$retiva1=str_replace(",",".",$monto_iva);
										$retiva2=str_replace(",",".",$monto_ret_iva2);
										$retiva_total=$retiva1+$retiva2;
										$monto_credito=$retiva_total;						
									}
								//$descripcion="prueba FACTURAS CXP retenciones iva";
							
								}else $variable_excepcion="no_entro";		

							}
///////////////////////////////////////////verificando si requiere auxliar la cuentacontable////////////////////////////////////////////////////////////////////
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
						$debe=(($monto_debito * 100) / 100); 
						$haber=(($monto_credito * 100) / 100); 
						$ad=round($ad,2)+round($debe,2);
						$ah=round($ah,2)+round($haber,2);
						
		}
////////////////////////////////////////////////////////////////////////////////////////////			
				else//si es factura simple	
				{
				if($cont==1)
						{	
						//die("entro");
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
										$monto_credito=($monto_2);
										//-($monto_iva_2);
										$descripcion="prueba FACTURAS CXP haber";
									}
									
				
					if($cont==3)
							{		///die("entro");
											
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
										$debito_credito=1;
										$monto_credito=0;
									
									$monto_debito=str_replace(",",".",$monto_iva);
									$descripcion="prueba FACTURAS CXP haber";
									}
							if($cont==4)
							{		///die("entro");
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
						$debe=((round($monto_debito,2) * 100) / 100); 
						$haber=((round($monto_credito,2) * 100) / 100); 
						$ad=round($ad,2)+round($debe,2);
						$ah=round($ah,2)+round($haber,2);
						
		}//fin el caso de q sesa facturasimple
	}//fin del else
	
	
	$numero_compromiso=$_POST['cuentas_por_pagar_db_compromiso_n'];
										
////////////////////////////////////////////////////////-ACTUALIZANDO LOS DOCUMENTOS SELECCIONADOS-///////////////////////////////////////////////////////////
//y cuentas de orden
//////////////////////////////////////////////////////////////////////									
$conta_up=0;
while($conta_toro>$conta_up)
{
	$doc=$ids_documentos[$conta_up];
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
	id_documentos='$doc'";
	$conta_up++;
	if($conta_up==1)
		$sql_up=$sql;
	else
		$sql_up=$sql_up.";".$sql;	
}
////////////////////////////guardando datos necesarios para el desarollo de la integracion contable
			if($variable_excepcion!="no_entro")	
			{	
						$descripcion=$_POST['cuentas_por_pagar_db_comentarios2'];
						$sql = "$sql_act_comp2;
								INSERT INTO 
											movimientos_contables
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
												ultima_modificacion,
												id_accion_central,
												estatus
												  
												
											) 
											VALUES
											(
												".$_SESSION["id_organismo"].",
												$numero_comprobante,
												$secuencia,
												'$ano',
												'$mes',
												'$id_tipo_comprobante',
												 '$cuenta_contable',
												 '$descripcion',
												 '$_POST[cuentas_por_pagar_db_compromiso_n]',
												 $debito_credito,
												 $monto_debito,
												 $monto_credito,
												 $unidad_ejecutora2,
												 $accion_central,
												 $partida2,
												 $auxiliar,
												 '".$_POST['cuentas_por_pagar_db_fecha_f']."',
												 ".$_SESSION['id_usuario'].",
												 '".date("Y-m-d H:i:s")."',
												 $accion_central,
												 '1'
											);
											$sql_up;									
											";
							//die($sql);
			}else
			$variable_excepcion="";
			if (!$conn->Execute($sql)) 
			{
			    $error=1;
			}
		if($error==1)
			die ('Error al Registrar: '.$sql);
			$secuencia=$secuencia+1;
			$cont=$cont+1;
			//echo($cont);
	
		}
		//$numero_comprobante=$numero_comprobante+1;
//}
//////////////////////PASANDO SALDOS A LAS TABLAS DE CONTABILIDAD:DEBIDO AL CAMBIO SOLITADO PARA LA FECHA 08/11/2011, A PESAR DE QUE EL PROGRAMA Y SU DESARROLLO HABIA ESTADO PAULATINAMENTE TERMINADO EN NOV/2009 , SE DEFINIÓN QUE EL ANALISIS REFERIDOS A QUE EL COMPROBANTE GENERADO EN EL MÓDLO DE CXP DEBÍA CARGARSE DIRECTAMENTE EN LAS TABLAS DE CONTABILIDAD(MOVIMIENTOS_CONTABLES/SALDOS) POR LO QUE DEBIÓ AGREGARSELE EL SIGUIENTE PROCESO. GIANNI SANTUCCI
$comprobante_x=$numero_comprobante;

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
									movimientos_contables.id_organismo = $_SESSION[id_organismo]
									and
									movimientos_contables.numero_comprobante=$comprobante_x
								and
									movimientos_contables.estatus!='3'								
											";
										//	die($sql_comprobante);
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
																					$monto_debe_suma = $rs_mov_suma->fields("debe")+$saldo_viejo;
																					$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																					/////////////////////////////////////////////////////////////////
																							if($tipo_comp==10)
																							{																																	                                                                                               if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																								{
																									$saldo_inicia=$saldo_inicio_suma+$saldo_viejo;																													}
																								else
																								if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																								{
																									$saldo_inicia=$saldo_inicio_suma-$saldo_viejo;																																		}																					
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
																					$monto_haber_suma = $rs_mov_suma->fields("haber")+$saldo_viejo;
																					$saldo_inicio_suma=$rs_mov_suma->fields("saldo_inicio");
																					////////////////////////////////////////////////////////////////////////	
																							if($tipo_comp==10)
																							{
																								if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																								{
																									$saldo_inicia=$saldo_inicio_suma-$saldo_viejo;																													}
																								else
																								if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																								{
																																																																																					                                                                                                    $saldo_inicia=$saldo_inicio_suma+$saldo_viejo;																													}
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
																$total_mov_debe=$rs_mov_original->fields("debe")+$saldo_viejo;
																$saldo_inicio_original=$rs_mov_original->fields("saldo_inicio");
																////////////////////////////////////////////////////////////////////////	
																if($tipo_comp==10)//si es saldo inicial
																{
																		if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																		{
																			$saldo_inicia=$saldo_inicio_original+$saldo_viejo;																													}
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
																$total_mov_haber=$rs_mov_original->fields("haber")+$saldo_viejo;
																$saldo_inicio_original=$rs_mov_original->fields("saldo_inicio");
																////////////////////////////////////////////////////////////////////////	
																if($tipo_comp==10)//si es saldo inicial
																{
																		if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																		{
																			$saldo_inicia=$saldo_inicio_original-$saldo_viejo;																													
																		}
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
															$saldo_aux=$row_saldo_aux->fields("debe")+$saldo_viejo;
															$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
															//////////////////////////////si es saldo inicial//////////////////////////////////////////////////
															if($tipo_comp==10)
															{
																if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																{
																	$saldo1_aux=$saldo_inicio_aux+$saldo_viejo;	
																}
																else
																if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																{
																	$saldo1_aux=$saldo_inicio_aux-$saldo_viejo;																																	}																	$sql_saldo_inicial_aux="update
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
															$saldo_aux=$row_saldo_aux->fields("haber")+$saldo_viejo;
															$saldo_inicio_aux=$row_saldo_aux->fields("saldo_inicio");
															//////////////////////////////si es saldo inicial//////////////////////////////////////////////////
															if($tipo_comp==10)
															{
																	if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
																	{
                                                                          $saldo1_aux=$saldo_inicio_aux-$saldo_viejo;
																	}else
																	if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
																	{
																		$saldo1_aux=$saldo_inicio_aux+$saldo_viejo;																																	}																		$sql_saldo_inicial_aux="update
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
					  // echo($sql);
											if (!$conn->Execute($sql)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
												die($responce);
											}
											
								$row_comprobante->MoveNext();
								$sql_mod_cuentas="";
								$sql_mod_sumas_todas="";
								$sql_act_aux="";	
							}	
						//	die("hola");					
/*********************************************************************************************************************/
////////////////////////////////////////////////////////////////////////////////
if($error==1)
				//die('Error al Registrar:'.$conn->ErrorMsg());
die("no_documento");
										$fecha_cierre=date("Y-m-d H:i:s");
										$fecha_cierre=substr($fecha_cierre,0,10);

$ano=substr($fecha_cierre,0,4);
$mes=substr($fecha_cierre,5,2);
$dia=substr($fecha_cierre,8,2);
$fecha_cierre = $dia."-".$mes."-".$ano;
$responce="integrado"."*".$numero_comprobante3."*".$numero_comprobante_corden."*".$fecha_cierre."*".$numero_comprobante;
die($responce);
?>	