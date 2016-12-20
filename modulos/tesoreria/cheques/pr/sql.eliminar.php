<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
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
$n_saldo=$monto_saldo+$monto_cheque;
//////////////////////////////////////////77777

///////////////////////////////////////////////////
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
												$pagar=$n_saldo;  
											}
										
								           ///////////////////////actualizando ordenees
										   
											
										   $sql_ordenes_pago="
										   						UPDATE
																		orden_pago
																SET
																		saldo='$pagar',
																		cheque='0'
																where
																		orden_pago='$orden'
										   ";
								$pagar=0;
								if($i==0)
								$sql_orden1=$sql_ordenes_pago;
								else
								$sql_orden1=$sql_orden1.";".$sql_ordenes_pago;
								$i=$i+1;
								}//fin del while

////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_cxp WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
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
if(($cerrado!="ano")||($cerrado!="mes"))
{
				$sql = "
							SELECT 
								id_cheques
							FROM 
								cheques
							INNER JOIN
								organismo
							ON
							cheques.id_organismo=organismo.id_organismo	
							INNER JOIN
								 orden_pago
							ON
							cheques.numero_cheque=orden_pago.cheque
							WHERE
								cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]' 
							AND
								cheques.id_organismo=".$_SESSION["id_organismo"].";
								
							";
				$row= $conn->Execute($sql);//die($sql);
				
				if(!$row->EOF)
				{
					
					
					$id_cheques=$row->fields('id_cheques');
								if($id_cheques!='')
								{
									//este proceso es pàra eliminar todas las ordenes de este cheque luego abajo en el ciclo se le seran asiganadas nuevamente las que sean elegidas por el usuario
									$sql_limpiar_orden="DELETE FROM orden_cheque where id_cheque='$id_cheques'";
								//	die($sql_limpiar_orden);
									if (!$conn->Execute($sql_limpiar_orden)){die("error limpiado tabla orden_cheque");}
								}else
								die("error en id_cheque");	
					$sql = "DELETE 
								FROM 
										cheques
								 WHERE 
										cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]' 
								 AND
										 id_organismo=".$_SESSION["id_organismo"].";
								UPDATE orden_pago
								SET
										cheque='0',
										id_banco='0',
										cuenta_banco='0'
								WHERE 
									cheque='$_POST[tesoreria_cheques_db_n_precheque]'
								AND
									orden_pago.id_organismo=".$_SESSION["id_organismo"]."	
					;$sql_ordenes_pago
					";
					//die($sql);
					}																	
					else
						$bloqueado=true;
					$sql=$sql.";".$sql_orden1;
					//echo($sql_pago);
				if (!$conn->Execute($sql)||$bloqueado){
				//die($sql);
					echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().$sql.'<br />');
					}
					else
						{
							die ('Eliminado');
						}
}
else
die("cerrados");							
?>