<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$check = $_POST["tesoreria_cheques_db_itf"]; 
$monto = str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
$fecha2 = date("Y-m-d H:i:s");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$monto_pagar1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
$monto_total1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_total]);
$monto_saldo1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_saldo]);
$monto_cheque1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_cheque]);

$monto_pagar=str_replace(",",".",$monto_pagar1);
$monto_total=str_replace(",",".",$monto_total1);
$monto_saldo=str_replace(",",".",$monto_saldo1);
$monto_cheque=str_replace(",",".",$monto_cheque1);
//die($monto_pagar.">".$monto_total);
if($monto_pagar>$monto_total)
die('el cheque supera al monto total de la orden');
else
$saldo_suma=($monto_saldo+$monto_cheque);
//die($saldo_suma);
$exilon=(round($saldo_suma,2)-round($monto_pagar,2));
//die($exilon.">".$monto_total);
if($exilon>$monto_total)
die('el cheque supera al monto total de la orden');
if($exilon<0)
die('el cheque supera al monto total de la orden');
///////////////////////////////////////////////////////////////////////////////////////////////////////777
if(isset($_POST[id_cheques]))
						{
								$id_cheques=$_POST[id_cheques];
								if($id_cheques!='')
								{
									//este proceso es pàra eliminar todas las ordenes de este cheque luego abajo en el ciclo se le seran asiganadas nuevamente las que sean elegidas por el usuario
									$sql_limpiar_orden="DELETE FROM orden_cheque where id_cheque='$id_cheques'";
								//	die($sql_limpiar_orden);
									if (!$conn->Execute($sql_limpiar_orden)){die("error limpiado tabla orden_cheque");}
									$sql_cheque2="SELECT SUM(monto_cheque) as monto_suma,ordenes from cheques where id_cheques='$id_cheques' group by monto_cheque,ordenes";
									//die($sql_cheque2);
									$row_cheque2=& $conn->Execute($sql_cheque2);
									if(!$row_cheque2->EOF) 
									{
										
										
										
											
											$ordenes2=$row_cheque2->fields("ordenes");
											$ordenes=substr($ordenes2,1,strlen($ordenes2)-2);
											//die($ordenes);
											$vectorx = split( ",",$ordenes);
											$contadorx=count($vectorx);  ///$_POST['covertir_req_cot_titulo']
											$i=0;
													while($i < $contadorx)
													{
																			
															
														
															$orden=$vectorx[$i];
																	$Sql2="
																			SELECT 
																				documentos_cxp.numero_documento,
																				documentos_cxp.monto_bruto,
																				documentos_cxp.monto_base_imponible,
																				documentos_cxp.porcentaje_iva,
																				documentos_cxp.porcentaje_retencion_iva,
																				documentos_cxp.porcentaje_retencion_islr,
																				documentos_cxp.tipo_documentocxp,
																				documentos_cxp.amortizacion,
																				documentos_cxp.retencion_ex1,
																				documentos_cxp.retencion_ex2,
																				documentos_cxp.monto_base_imponible2,
																				documentos_cxp.porcentaje_iva2 ,
																				documentos_cxp.retencion_iva2
																			FROM 
																				documentos_cxp
																			WHERE
																				documentos_cxp.orden_pago='$orden'
																";
																$row_orden=& $conn->Execute($Sql2);
															while(!$row_orden->EOF)
															{
																$monto_bruto=$row_orden->fields("monto_bruto");
																$iva=$row_orden->fields("porcentaje_iva");
																$ret1=$row_orden->fields("retencion_ex1");
																$ret2=$row_orden->fields("retencion_ex2");
																if(($retislr2==0)||($retislr2=='0.00'))
																{
																$retislr2=$row_orden->fields("porcentaje_retencion_islr");
																}
																
																$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
																//-/si es factura con anticipo
																if(($row_orden->fields("tipo_documentocxp")==$tipos_fact)&&($row_orden->fields("amortizacion")!='0'))
																{
																	$monto_bruto=$row_orden->fields("monto_bruto");
																	$amort=$row_orden->fields("amortizacion");
																	$base_imponible=$monto_bruto;//+$amort
																	$islr=((($base_imponible)*($retislr2))/100);
																}else
																{
																		$base_imponible=$row_orden->fields("monto_base_imponible");
																		$islr=((($monto_bruto)*($retislr2))/100);
																}
																//----calculos
																
																if($row_orden->fields("monto_base_imponible2")!='0')
																{
																$base2=$row_orden->fields("monto_base_imponible2");
																$iva2=$row_orden->fields("porcentaje_iva2");
																$retiva2=$row_orden->fields("retencion_iva2");
																
																$base_iva=(($base_imponible)*($iva))/100;
																$base_iva2=(($base2)*($iva2))/100;
																
																
																$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
																$monto_restar2=(($base_iva2)*($retiva2))/100;
																
																$bruto_iva=($monto_bruto)+($base_iva+$base_iva2)-($monto_restar+$monto_restar2);//
																//-
																//die($monto_bruto);
																
																if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
																{
																
																$islr=$islr-138;
																
																}
																//die(islr);	
																$retenciones=$ret1+$ret2;
																$monto_def=($bruto_iva)-($islr+$retenciones);
																//	echo($monto_def);
																$total_orden=$total_orden+$monto_def;
																$monto_def=0;
																$islr=0;
																$base_imponible=0;
																$monto_bruto=0;
																//die($monto_det);
																}
																else
																{
																$base_iva=(($base_imponible)*($iva))/100;
																if($porcentaje_iva_ret!='0')
																{
																$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
																$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);//
																}else
																$bruto_iva=($monto_bruto)+($base_iva);
																//-
																//die($monto_bruto);
																
																if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
																{
																
																$islr=$islr-138;
																
																}
																//die(islr);	
																$retenciones=$ret1+$ret2;
																$monto_def=($bruto_iva)-($islr+$retenciones);
																//	echo($monto_def);
																$total_orden=$total_orden+$monto_def;
																$monto_def=0;
																$islr=0;
																$base_imponible=0;
																$monto_bruto=0;
																
																}
																$row_orden->MoveNext();	
																
															}
																$pagar=$pagar+$total_orden;
																$total_orden=0;
																//$retislr2=0;
																$retenciones=0;
																$ret1=0;
																$ret2=0;
																			
																//$retislr2=0;
															   ///////////////////////actualizando ordenees y tabla relacional de orden_cheque
															   
															   $sql_ordenes_pagon="
																					UPDATE
																							orden_pago
																					SET
																							saldo='$pagar',
																							cheque='0'
																					where
																							orden_pago='$orden';
																							INSERT 
																								 INTO
																										orden_cheque
																										(id_cheque,id_orden)
																								values
																										('$id_cheques','$orden')		
																							
																									   ";
													$pagar=0; 
													if($i==0)
													$sql_orden1n=$sql_ordenes_pagon;
													else
													$sql_orden1n=$sql_orden1n.";".$sql_ordenes_pagon;
													$i=$i+1;
													
													}				
											
											
									}
								}
						}
////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_tesoreria WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF){
	$fecha_cierre_anual = $row_fecha_cierre->fields('fecha_ultimo_cierre_anual');
	$fecha_cierre_mensual = $row_fecha_cierre->fields('fecha_ultimo_cierre_mensual');

}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre_mensual);
list($dia2,$mes2,$ano2)=split("-",$fecha2);
list($dia3,$mes3,$ano3)=split("-",$fecha_cierre_anual);
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
		$cerrado="aº";
	}else
	if(($mes2 >= $mes3) && ($ano2 >= $ano3))
	{
		$cerrado="ano";
	}
if(($cerrado!="ano")||($cerrado!="mes"))
{			
				//------------------- VERIFICANDO SI LAS ORDENES DE PAGO NO FUERON CANCELADAS POR OTROS CHEQUES//----------------------------//
							if($_POST['tesoreria_cheques_db_ordenes_pago']!="")
							{
								//die($_POST['tesoreria_cheques_db_ordenes_pago']);
						/*		$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
								
								$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
								$i=0;
								while($i < $contador)
								{
										$sql_orden="SELECT * 
													FROM
														\"orden_pagoE\"
													WHERE(\"orden_pagoE\".\"id_orden_pagoE\"='$vector[$i]')
													AND
													id_organismo=".$_SESSION["id_organismo"]."
													";
								
										//echo($sql_orden);
										$i=$i+1;	
										$row_orden=$conn->Execute($sql_orden); 
										if(($row_orden->fields("numero_cheque")!=$_POST['tesoreria_cheques_db_n_precheque']) AND ($row_orden->fields("numero_cheque")!=0))	
													die ('Error-orden');
													
								}		 */
								$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
								sort($vector);
								$contador=count($vector);  
								$i=0;
								while($i < $contador)
								{
										
									
										$orden=$vector[$i];
												$Sql2="
														SELECT 
															documentos_cxp.numero_documento,
															documentos_cxp.monto_bruto,
															documentos_cxp.monto_base_imponible,
															documentos_cxp.porcentaje_iva,
															documentos_cxp.porcentaje_retencion_iva,
															documentos_cxp.porcentaje_retencion_islr,
															documentos_cxp.tipo_documentocxp,
															documentos_cxp.amortizacion,
															documentos_cxp.retencion_ex1,
															documentos_cxp.retencion_ex2,
															documentos_cxp.monto_base_imponible2,
															documentos_cxp.porcentaje_iva2 ,
															documentos_cxp.retencion_iva2
														FROM 
															documentos_cxp
														WHERE
															documentos_cxp.orden_pago='$orden'
											";
											$row_orden=& $conn->Execute($Sql2);
										while(!$row_orden->EOF)
										{
											$monto_bruto=$row_orden->fields("monto_bruto");
											$iva=$row_orden->fields("porcentaje_iva");
											$ret1=$row_orden->fields("retencion_ex1");
											$ret2=$row_orden->fields("retencion_ex2");
											if(($retislr2==0)||($retislr2=='0.00'))
											{
											$retislr2=$row_orden->fields("porcentaje_retencion_islr");
											}
											
											$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
											//-/si es factura con anticipo
											if(($row_orden->fields("tipo_documentocxp")==$tipos_fact)&&($row_orden->fields("amortizacion")!='0'))
											{
												$monto_bruto=$row_orden->fields("monto_bruto");
												$amort=$row_orden->fields("amortizacion");
												$base_imponible=$monto_bruto;//+$amort
												$islr=((($base_imponible)*($retislr2))/100);
											}else
											{
													$base_imponible=$row_orden->fields("monto_base_imponible");
													$islr=((($monto_bruto)*($retislr2))/100);
											}
											//----calculos
											
											if($row_orden->fields("monto_base_imponible2")!='0')
											{
											$base2=$row_orden->fields("monto_base_imponible2");
											$iva2=$row_orden->fields("porcentaje_iva2");
											$retiva2=$row_orden->fields("retencion_iva2");
											
											$base_iva=(($base_imponible)*($iva))/100;
											$base_iva2=(($base2)*($iva2))/100;
											
											
											$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
											$monto_restar2=(($base_iva2)*($retiva2))/100;
											
											$bruto_iva=($monto_bruto)+($base_iva+$base_iva2)-($monto_restar+$monto_restar2);//
											//-
											//die($monto_bruto);
											
											if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
											{
											
											$islr=$islr-138;
											
											}
											//die(islr);	
											$retenciones=$ret1+$ret2;
											$monto_def=($bruto_iva)-($islr+$retenciones);
											//	echo($monto_def);
											$total_orden=$total_orden+$monto_def;
											$monto_def=0;
											$islr=0;
											$base_imponible=0;
											$monto_bruto=0;
											//die($monto_det);
											}
											else
											{
											$base_iva=(($base_imponible)*($iva))/100;
											if($porcentaje_iva_ret!='0')
											{
											$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
											$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);//
											}else
											$bruto_iva=($monto_bruto)+($base_iva);
											//-
											//die($monto_bruto);
											
											if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
											{
											
											$islr=$islr-138;
											
											}
											//die(islr);	
											$retenciones=$ret1+$ret2;
											$monto_def=($bruto_iva)-($islr+$retenciones);
											//	echo($monto_def);
											$total_orden=$total_orden+$monto_def;
											$monto_def=0;
											$islr=0;
											$base_imponible=0;
											$monto_bruto=0;
											
											}
											$row_orden->MoveNext();	
											
										}
											$pagar=$pagar+$total_orden;
											$total_orden=0;
											//$retislr2=0;
											$retenciones=0;
											$ret1=0;
											$ret2=0;
														
											//$retislr2=0;
								           ///////////////////////actualizando ordenees
										   if($contador==1)
										   {
												$pagar=$exilon;  
											}else
											$pagar=0;
										   $sql_ordenes_pago="
										   						UPDATE
																		orden_pago
																SET
																		saldo='$pagar',
																		cheque='$_POST[tesoreria_cheques_db_n_precheque]'
																where
																		orden_pago='$orden'
										   ";
								$pagar=0;
								if($i==0)
								$sql_orden1=$sql_ordenes_pago;
								else
								$sql_orden1=$sql_orden1.";".$sql_ordenes_pago;
								$i=$i+1;
								}
							}
				//-----------------------------------------------------------------------------------------------------------------------------------------------
							
								$sql_prueba="	SELECT 
													id_cheques,banco_cuentas.cuenta_contable_banco 
												FROM 
													cheques 
												INNER JOIN
													banco_cuentas
												ON
													cheques.cuenta_banco=banco_cuentas.cuenta_banco
												WHERE cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
												AND
													cheques.id_organismo=".$_SESSION["id_organismo"]."
												
												";
												//die($sql_prueba);
																	$row2=& $conn->Execute($sql_prueba);
																	$row_prueba=& $conn->Execute($sql_prueba);
								
														//			$id=$row2->fields("id_cheques");
																	//$cuenta_contable=$row2->fields("cuenta_contable_banco");				
							if(!$row2->EOF)
							{
								
								
								
								
									$monto=str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
						
													//--------------------busqueda del porcentaje itf en la tabla parametro_tesoreria------------------------------------------------------------------------------------
													if($check=="true")
				
													{
															
															$sql_porcentaje = "SELECT * from parametros_tesoreria WHERE id_organismo='".$_SESSION["id_organismo"]."'";
															$row= $conn->Execute($sql_porcentaje);
															//die($sql_porcentaje);
															if(!$row->EOF)
															{
																//die($row->fields("id_organismo"));
																$porcentaje_itf=$row->fields("porcentaje_itf");
																$porcentaje=($monto*$porcentaje_itf)/100;
															}
															
													}else
														$porcentaje=0;
														
												
										//---------------------------------------------------------------------------------------------------------	 
											$concepto=$_POST[tesoreria_cheques_db_concepto];
											$concepto=strtoupper($concepto);
											$islr=$_POST[tesoreria_cheques_pr_ret_islr];
											if($_POST[tesoreria_cheque_db_nombre_benef]!="")
											{
													$tipo_cheque=2;
											}else
											$tipo_cheque=1;
											
											$sql="$sql_orden1n;
												UPDATE cheques
														SET
															concepto='$concepto',
															porcentaje_itf='$porcentaje',
															monto_cheque='".str_replace(",",".",$monto)."',
															ordenes='{".$_POST[tesoreria_cheques_db_ordenes_pago]."}',																		
															fecha_ultima_modificacion='".$_POST[tesoreria_cheque_pr_fecha]."',
															fecha_cheque='".$_POST[tesoreria_cheque_pr_fecha]."',
															ultimo_usuario=".$_SESSION['id_usuario'].",
															porcentaje_islr='".str_replace(",",".",$islr)."',
															sustraendo='$_POST[tesoreria_cheques_pr_sustraendo_oculto]',
															benef_nom='$_POST[tesoreria_cheque_db_nombre_benef]',
															tipo_cheque='$tipo_cheque'
														WHERE
															 cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
														AND
															cheques.id_organismo=".$_SESSION["id_organismo"]."
													
														;
														$sql_orden1;
														";
															//	die($sql);
																if (!$conn->Execute($sql)) 
																//die ('Error al Actualizar: '.$conn->ErrorMsg());
																die ("NoActualizo");
																	
										//--------------------------------------------------------------------------------------------------------------------------------------									
																	//blanquenado los datos que seran modificados
																	while(!$row_prueba->EOF)
																	{
																			
																			
																						$sql_pago="UPDATE \"orden_pagoE\"
																							SET
																									numero_cheque='0',
																									id_banco='0',
																									cuenta_banco=''	
																							WHERE 
																								numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
																							AND
																								\"orden_pagoE\".id_organismo=".$_SESSION["id_organismo"]."
																							   ";				
																							$i=$i+1;	
																							if (!$conn->Execute($sql_pago)) 
																									die ('Error al registrar: '.$sql_pago);
																			/*}
																			else
																				die('Error-orden');*/
																	 $row_prueba->MoveNext();
																	}if($_POST['tesoreria_cheques_db_ordenes_pago']=="")
																		{		
																			die("Actualizado");	
																		 }
																				//----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
																									$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
																									
																									$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
																									$i=0;
																									$n_cuenta=$_POST['tesoreria_cheques_db_n_cuenta'];
																									
																									while($i < $contador)
																									{
																											$sql_orden="UPDATE \"orden_pagoE\"
																														SET
																																numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]',
																																id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]',
																																cuenta_banco='$n_cuenta'	
																														WHERE(\"orden_pagoE\".\"id_orden_pagoE\"='$vector[$i]')
																														AND
																														\"orden_pagoE\".id_organismo=".$_SESSION["id_organismo"]."
																													   ";	
																											$i=$i+1;	
																											if (!$conn->Execute($sql_orden)) 
																														die ('Error al registrar: '.$sql_orden);
																									}	
																									/*$sql="		UPDATE cheques
																												SET
																													monto_cheque='".str_replace(",",".",$monto)."',
																													ordenes='{".$_POST[tesoreria_cheques_db_ordenes_pago]."}'																		
																												WHERE cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
																												AND
																												cheques.id_organismo=".$_SESSION["id_organismo"]."
																							   ";	
																											
																										//die($sql);
																										if (!$conn->Execute($sql)) 
																										//die ("NoActualizo");
																										die ('Error al Actualizar: '.$conn->ErrorMsg());
																										die("Actualizado");		*/	
																										
																			
												//-----------------------------------------------------------------------------------------------------
												
												
							}	//fin de row2->eof		
				/*			//----------------------------------- en caja---------------------------------------------------------------------------------------------
							 echo($_POST['tesoreria_cheques_pr_estatus']);
							 if($_POST['tesoreria_cheques_pr_estatus']=="3")
							 {
									$sql_contab="		UPDATE cheques
																SET
																	fecha_caja='".date("Y-m-d H:i:s")."',
																	usuario_recibe_caja=".$_SESSION['id_usuario']."	
															WHERE (id_cheques='$id')
													";		
										if (!$conn->Execute($sql_contab)) 
											
											die ('Error al Actualizar: '.$conn->ErrorMsg());
											//die ("NoActualizo");
							 }	
							//----------------------------------- pagados---------------------------------------------------------------------------------------------
							 if($_POST['tesoreria_cheques_pr_estatus']=="4")
							 {
									$sql_contab="		UPDATE cheques
																SET
																	fecha_pago'".date("Y-m-d H:i:s")."',
																	usuario_pago=".$_SESSION['id_usuario']."
															WHERE (id_cheques='$id')
													";		
										if (!$conn->Execute($sql_contab)) 
											die ('Error al Actualizar: '.$conn->ErrorMsg());
											//die ("NoActualizo");
							 }	
							//-----------------------------------Anulado---------------------------------------------------------------------------------------------
							 if($_POST['tesoreria_cheques_pr_estatus']=="5")
							 {
									$sql_contab="		UPDATE cheques
																SET
																	fecha_anula'".date("Y-m-d H:i:s")."',
																	usuario_anula=".$_SESSION['id_usuario']."	
															WHERE (id_cheques='$id')
													";		
										if (!$conn->Execute($sql_contab)) 
											die ('Error al Actualizar: '.$conn->ErrorMsg());
											//die ("NoActualizo");
							 }	
							//--------------------------- Contabilizados------------------------------------------------------------------------------------------------------
								if($_POST['tesoreria_cheques_db_estatus_procesado']=="1")
								{
																
										$sql_contab="		UPDATE cheques
																SET
																	contabilizado='1',
																	reimpreso='2',
																	fecha_contab='".date("Y-m-d H:i:s")."',
																	usuario_contab=".$_SESSION['id_usuario'].",
																	cuenta_contable_banco='$cuenta_contable'
															WHERE (id_cheques='$id')
													";		
										if (!$conn->Execute($sql_contab)) 
											die ('Error al Actualizar: '.$conn->ErrorMsg());
											//die ("NoActualizo");
								}
								//-------------------------- reimpresos------------------------------------------------------------------------
								if($_POST['tesoreria_cheques_db_estatus_procesado']=="2")
								{
										$sql_reimpresos="		UPDATE cheques
																	SET
																		id_banco_reimpreso='$_POST[tesoreria_cheques_db_banco_id_banco]',
																		cuenta_banco_reimpreso='$_POST[tesoreria_cheques_db_n_cuenta]',
																		numero_cheque_reimpreso='$_POST[tesoreria_cheques_db_ncheque_codigo]',
																		fecha_reimpresion='".date("Y-m-d H:i:s")."',
																		usuario_reimpresion=".$_SESSION['id_usuario']."
															WHERE (id_cheques='$id_cheques')
														";		
											if (!$conn->Execute($sql_reimpresos)) 
												die($sql_reimpresos);
												//die ('Error al Actualizar: '.$conn->ErrorMsg());
												//die ("NoActualizo");
								
								}*/
								//-------------------------------------------------------------------------------------------------------------
						die("Actualizado");
}else
die("cerrado");
?>