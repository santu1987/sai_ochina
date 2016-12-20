<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$fecha2 = date("Y-m-d H:i:s");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
						
						$monto = str_replace(".","",$_POST[cuentas_por_pagar_db_facturas_total]);
						//------------------- VERIFICANDO SI LAS facturas no fueron agregadas a otra orden de pago//----------------------------//
									if($_POST['cuentas_por_pagar_db_facturas_oculto']!="")
									{
										$vector = split( ",", $_POST['cuentas_por_pagar_db_facturas_oculto'] );
										
										$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
										$i=0;
										while($i < $contador)
										{
												$sql_fact="SELECT orden_pago 
															FROM
																documentos_cxp
															WHERE documentos_cxp.id_documentos='$vector[$i]'
															AND
															id_organismo=".$_SESSION["id_organismo"]."
															";
										
										
												$i=$i+1;	
												$row_fact=$conn->Execute($sql_fact); 
												if(($row_fact->fields("orden_pago")!=$_POST['cuentas_por_pagar_db_orden_numero_control']) AND ($row_fact->fields("orden_pago")!=0))	
															die ('Error-orden');
															
										}		
									}
						//-----------------------------------------verificando si existe la orden de pago------------------------------------------------------------------------------------------------------
									$n_control=$_POST['cuentas_por_pagar_db_orden_numero_control'];
									$sql="SELECT 
												orden_pago 
											FROM 
												orden_pago
											INNER JOIN
												organismo
											ON
												orden_pago.id_organismo=organismo.id_organismo
											
											WHERE
												orden_pago.orden_pago='$n_control'	
												";
												
												$row_pago=& $conn->Execute($sql);
												if ($row_pago->EOF)
												{
														die ("NoActualizo");
												}else
												{
																		
													
												
														
						//////////////////////////////////////////////////////////////////////////////////////////////////
														$sql_pago="UPDATE orden_pago
																	SET
																		fecha_orden_pago='$_POST[cuentas_por_pagar_db_orden_fecha_v]',
																		comentarios= '$_POST[cuentas_por_pagar_db_ordenes_comentarios]',
																		ultimo_usuario=".$_SESSION['id_usuario']."	,
																		fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																		documentos='{".$_POST[cuentas_por_pagar_db_facturas_oculto]."}'	
																	WHERE 
																		orden_pago='$_POST[cuentas_por_pagar_db_orden_numero_control]'
																	AND
																		orden_pago.id_organismo=".$_SESSION["id_organismo"]."
																";				
																		
																	if (!$conn->Execute($sql_pago)) 
																		die ('Error al registrar: '.$sql_pago);
											
						////////////////////////////////////////////////////////-ACTUALIZANDO LOS DOCUMENTOS SELECCIONADOS-///////////////////////////////////////////////////////////
																	$orden=$_POST[cuentas_por_pagar_db_orden_numero_control];
																	$vector=$_POST[cuentas_por_pagar_db_facturas_oculto];
																	$facturas=split(",",$vector);
																	sort($facturas);
																	if($facturas!="")
																	{
																		$contador=count($facturas);
																	
																		$is=0;
																		//////////////////////////blanqeando campos a modificar
																		$sql = "UPDATE documentos_cxp 
																				 SET
																					orden_pago='0',
																					ultimo_usuario=".$_SESSION['id_usuario'].", 
																					fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
																				WHERE 
																					 id_organismo=$_SESSION[id_organismo]
																				AND
																					orden_pago='$n_control'";
																						if (!$conn->Execute($sql)) {
																						die ('Error al Actualizar: '.$conn->ErrorMsg());}
																		/////////////////////////modificando
																		while($is<$contador)
																		{
																					
																					$sql = "UPDATE documentos_cxp 
																					 SET
																						orden_pago='$orden',
																						ultimo_usuario=".$_SESSION['id_usuario'].", 
																						fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
																					WHERE 
																								id_organismo=$_SESSION[id_organismo]
																						AND
																							id_documentos='$facturas[$is]'
																							";
																							if (!$conn->Execute($sql)) {
																							die ('Error al Actualizar: '.$conn->ErrorMsg());}
																				
																		$is=$is+1;
																		}
																	}	
													}//--- CERRANDO ELSE//
													die("Actualizado");
}else
die("cerrados");	
	?>