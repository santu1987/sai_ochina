<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
include_once('../../../../controladores/numero_to_letras.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$check = $_POST["tesoreria_cheques_db_itf"]; 
//
$fecha2 = date("Y-m-d H:i:s");

$monto_pagar1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
$monto_total1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_total]);
$monto_saldo1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_saldo]);
$monto_cheque1 = str_replace(".","",$_POST[tesoreria_cheques_db_monto_cheque]);

$monto_pagar=str_replace(",",".",$monto_pagar1);
$monto_total=str_replace(",",".",$monto_total1);
$monto_saldo=str_replace(",",".",$monto_saldo1);
$monto_cheque=str_replace(",",".",$monto_cheque1);


$exilon=$monto_saldo-$monto_pagar;
if($exilon>$monto_total)
die('el cheque supera al monto total de la orden');
if($exilon<0)
die('el cheque supera al monto total de la orden');
//die($exilon);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}
/*if(($cerrado!="ano")||($cerrado!="mes"))
{*/
				//------------------- VERIFICANDO SI LAS ORDENES DE PAGO NO FUERON CANCELADAS POR OTROS CHEQUES//----------------------------//
								/*$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
								
								$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
								$i=0;
								while($i < $contador)
								{
										$sql_orden="SELECT * 
													FROM
														orden_pago
													WHERE(orden_pago.id_orden_pago='$vector[$i]')";
								
										//echo($sql_pago);
										$i=$i+1;	
										$row_orden=$conn->Execute($sql_orden); 
									
										if(($row_orden->fields("cheque")!=$_POST['tesoreria_cheques_db_n_precheque']) AND ($row_orden->fields("cheque")!=0))	
													die ('Error-orden');
													
													
								}	*/	
									
				//-----------------------------------------------------------
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
											$sql_ord="SELECT saldo from orden_pago where orden_pago='$orden'";
										 $row_ord=& $conn->Execute($sql_ord);
										 if (!$row_ord->EOF)
										{
										    $salt=$row_ord->fields("saldo");
										    $pagar=round($salt,2)-round($pagar,2);
										}
										if($contador==1)
										   {
												$pagar=$exilon;  
											}
										
										   $sql_ordenes_pago="
										   						UPDATE
																		orden_pago
																SET
																		saldo='$pagar'
																where
																		orden_pago='$orden'
										   ";
										  // die($sql_ordenes_pago)
										   ;
								$pagar="";
								if($i==0)
								$sql_orden1=$sql_ordenes_pago;
								else
								$sql_orden1=$sql_orden1.";".$sql_ordenes_pago;
								$i=$i+1;
										
										
								}//fin del while
								//die($sql_orden1);
							/////////////////////////////////////////////////////////////////////////////
				/*$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
								
								$contador=count($vector);  
								$i=0;
								while($i < $contador)
								{
										$sql_orden="SELECT documentos 
													FROM
														orden_pago
													WHERE(orden_pago.id_orden_pago='$vector[$i]')";
								
										 $row_orden=$conn->Execute($sql_orden); 
										 $vector2 = split( ",",$row_orden->fields("documentos"));
										 $contador2=count($vector2);  ///$_POST['covertir_req_cot_titulo']
										$i2=0;
										while($i < $contador2)
										{
												$sql_doc="SELECT 
														documentos_cxp .monto_bruto,
														documentos_cxp .monto_base_imponible,
														documentos_cxp .porcentaje_iva,
														documentos_cxp .porcentaje_retencion_iva,
														documentos_cxp .porcentaje_retencion_islr
													FROM 
														documentos_cxp 
													WHERE
														(documentos_cxp .orden_pago='$vector2[$i2]')";
										
												 $row_doc=$conn->Execute($sql_doc); 
												 $base=$row_doc->fields("monto_base_imponible");
												 $bruto=$row_doc->fields("monto_bruto");
												 $iva=$row_doc->fields("porcentaje_iva");
												 $retislr2=$row_doc->fields("porcentaje_retencion_islr");
												 $porcentaje_iva_ret=$row_doc->fields("porcentaje_retencion_iva");
												 $base_imponible=$base_imponible+$base;
												 $monto_bruto=$monto_bruto+$bruto;
												 $row_doc->MoveNext();	
												 $i2=$i2+1;	
										}
										$base_iva=(($base_imponible)*($iva))/100;
										$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
										$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);//
										$islr=((($monto_bruto)*($retislr2))/100);
										if(($base>=4138)&&($rif=='V')&&($islr!='0'))$islr=$islr-138;
										$monto_def=($bruto_iva)-$islr;
										$pagar=$pagar+$monto_def;
										$monto_total=$monto_total+$pagar;
										$i=$i+1;			
								}*/	
				//----------------------------------------------------------------------------------------------------------------------	
				$fecha = date("Y-m-d H:i:s");
				//------ verificando si la cuenta y el banco tienen chequera creada
				$sql_cheque = "SELECT id_chequeras FROM chequeras WHERE cuenta='$_POST[tesoreria_cheques_db_n_cuenta]' and id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]' AND chequeras.id_organismo=".$_SESSION["id_organismo"]."
				";
				if (!$conn->Execute($sql_cheque))die ('Error al Registrar(RELACION:CUENTA-CHEQUERA): '.$conn->ErrorMsg());
					$row= $conn->Execute($sql_cheque);
				//-------------------------------------------------------------------------------------
				//---------------busqueda del ultimo negativo para hacer el n_precheque----------------
				$sql_ultimo_negativo = "SELECT 
											numero_cheque
										FROM 
											cheques
										WHERE 
											numero_cheque<0 
										AND	
											cheques.id_organismo=".$_SESSION["id_organismo"]."
								
										order by 
											numero_cheque asc limit 1 ";
				if (!$conn->Execute($sql_ultimo_negativo))die ('Error al Registrar(RELACION:ULTIMO NCHEQUE-): '.$conn->ErrorMsg());
					$row_negativo= $conn->Execute($sql_ultimo_negativo);
					if(!$row_negativo->EOF)	
					{
						$n_cheque=$row_negativo->fields("numero_cheque");
						$n_precheque=$n_cheque-1;
					}
					else
						{
							$n_precheque=-1;
					 }	
				//---------------------------------------------------------------------------------------------------------------------------------------------------------------------	 
				$monto = str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
				if($_POST[tesoreria_cheques_pr_ret_islr]!="")
				{
					$islr=str_replace(".","",$_POST[tesoreria_cheques_pr_ret_islr]);
					
				}else
				$islr=0;
				//$monto_escrito=NumerosALetras($monto);
				//--------------------busqueda del porcentaje itf en la tabla parametro_tesoreria------------------------------------------------------------------------------------
				if($check=="true")
				{
						
						$sql_porcentaje = "SELECT * from parametros_tesoreria WHERE id_organismo='".$_SESSION["id_organismo"]."'";
						$row_p= $conn->Execute($sql_porcentaje);
						//die($sql_porcentaje);
						if(!$row_p->EOF)
						{
							//die($row->fields("id_organismo"));
							$porcentaje_itf=$row_p->fields("porcentaje_itf");
							
							$porcentaje=($monto*$porcentaje_itf)/100;
						}
				}else
					{
						$porcentaje=0;
						
						}
				//----------------------------------------------------------------------------------------------------//----------------------------------------------------------------------------------------------------
				$Sql_firmas="
							SELECT 
								firmas_voucher.id_firmas_voucher,
								firmas_voucher.codigo_director_ochina,
								firmas_voucher.codigo_director_administracion,
								firmas_voucher.codigo_jefe_finanzas,
								firmas_voucher.codigo_preparado_por,
								firmas_voucher.comentarios,
								firmas_voucher.fecha_firma
							FROM 
								firmas_voucher
							INNER JOIN 
								organismo 
							ON 
								firmas_voucher.id_organismo = organismo.id_organismo
							WHERE
									firmas_voucher.estatus='1'
									";	 
				$row_firmas=& $conn->Execute($Sql_firmas);
				if($row_firmas->EOF)
				{
					die("No hay firmas activas");
				}else
				$firmas=$row_firmas->fields("fecha_firma");
				//die($row_firmas->fields("fecha_firma"));
				//----------------------------------------------------------------------------------------------------
				if(!$row->EOF)
					{
						
					$comentario=utf8_encode($_POST[tesoreria_cheques_db_comentario]);
					$monto_pagar=str_replace(",",".",$monto);
					$monto_saldo = str_replace(".","",$_POST[tesoreria_cheques_db_monto_saldo]);
					$porcentaje=$monto_pagar*($islr/100);
					$base=$monto_pagar-$porcentaje;
					$concepto=$_POST[tesoreria_cheques_db_concepto];
					$concepto=strtoupper($concepto);
					$benef=$_POST[tesoreria_cheque_db_nombre_benef];
					if($benef!="")
					{
						$tipos=2;
						}else
						$tipos=1;
						$sustraendo_oculto=$_POST[tesoreria_cheques_pr_sustraendo_oculto];
						if($sustraendo_oculto=="")
						{
							$sustraendo_oculto="0";	
							}
									$sql = "	
													INSERT INTO 
														cheques
														(
															id_banco,
															cuenta_banco,
															numero_cheque,
															tipo_cheque,
															id_proveedor,	
															monto_cheque,
															concepto,
															estatus,
															comentarios,
															porcentaje_itf,
															id_organismo,
															fecha_cheque,
															ordenes,
															usuario_cheque,
															fecha_ultima_modificacion,
															ultimo_usuario,
															porcentaje_islr,
															fecha_firma,
															base_imponible,
															sustraendo,
															contabilizado,
															estado,
															estado_fecha,
															benef_nom
												
														) 
														VALUES
														(
															'$_POST[tesoreria_cheques_db_banco_id_banco]',
															'$_POST[tesoreria_cheques_db_n_cuenta]',
															'$n_precheque',
															'$tipos',
															'$_POST[tesoreria_cheques_pr_proveedor_id]',
															'".str_replace(",",".",$monto)."',
															'$concepto',
															'1',
															'$comentario',
															'$porcentaje',
															".$_SESSION["id_organismo"].",
															'".$_POST[tesoreria_cheque_pr_fecha]."',
															'{".$_POST[tesoreria_cheques_db_ordenes_pago]."}',
															".$_SESSION['id_usuario'].",
															'".date("Y-m-d H:i:s")."',
															".$_SESSION['id_usuario'].",
															'".str_replace(",",".",$islr)."',
															'".$firmas."',
															'$base',
															'$sustraendo_oculto',
															'0',
															'{0,0,0,0,0,0,0,0,0}',
															'{10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010}',
															'$benef'
															);
														$sql_orden1
														"; 

				
						
					
					}
						else
							echo("NoRegistro");
							//die($sql);
						if (!$conn->Execute($sql)) 
							//die ('Error al Actualizar: '.$conn->ErrorMsg());
							die ('Error al RegistrarHHH: '.$sql);
						else
						{//----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
								$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
								
								$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
								$i=0;
								//consultando el id del cheque registrado
								$sql_chequeid="
												SELECT 
														id_cheques
													FROM
														cheques
													where
														numero_cheque='$n_precheque'		
												";
											//
											//echo($sql_chequeid);
								$row_chequeid=& $conn->Execute($sql_chequeid);
										if(!$row_chequeid->EOF)
										{
											
											$id_cheque=$row_chequeid->fields("id_cheques");
										}else
										die("error");
								//
								while($i < $contador)
								{//tam,bien se guaradra e la tabla relacional orden_cheque
										$sql_orden="UPDATE orden_pago
													SET
															cheque='$n_precheque',	
															id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]',
															cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]',
															estatus='2'
															
													WHERE
															(orden_pago.id_orden_pago='$vector[$i]');
																
													;
													INSERT 
														 INTO
														 		orden_cheque
																(id_cheque,id_orden)
														values
																('$id_cheque','$vector[$i]')		
													
													";	
								
										//echo($sql_pago);
										$i=$i+1;	
										if (!$conn->Execute($sql_orden)) 
													die ('Error al registrar: '.$sql_orden);
				//									die ('Error al Actualizar: '.$conn->ErrorMsg());
														//die ("NoActualizo");
								}		
									
						//-----------------------------------------------------------
						}	
							
					//die ('Error al Registrar: '.$conn->ErrorMsg());
					/*else
						{	
								//------------------------------------------------------------------------------------------------------------------------------------------------
								//--------------------------- Contabilizados------------------------------------------------------------------------------------------------------
								$sql_prueba="	SELECT 
													id_cheques,banco_cuentas.cuenta_contable_banco 
												FROM 
													cheques 
												INNER JOIN
													banco_cuentas
												ON
													cheques.cuenta_banco=banco_cuentas.cuenta_banco
												WHERE cheques.numero_cheque='$_POST[tesoreria_cheques_db_ncheque_codigo]' AND cheques.cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]' AND cheques.id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]' ";
																	$row2=& $conn->Execute($sql_prueba);
																	$id=$row2->fields("id_cheques");
																	$cuenta_contable=$row2->fields("cuenta_contable_banco");				
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
								
								}
								//-------------------------------------------------------------------------------------------------------------
				*/
						
						
							die("Registrado");
//}
/*}else
die("cerrado");
*/?>