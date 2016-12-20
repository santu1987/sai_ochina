<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$check = $_POST["tesoreria_cheques_manual_orden_db_itf"]; 
$monto = str_replace(".","",$_POST[tesoreria_cheques_manual_orden_db_monto_pagar]);
$fecha2 = date("Y-m-d H:i:s");
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
if(($cerrado!="ano")||($cerrado!="mes"))
{


						//------------------- VERIFICANDO SI LAS ORDENES DE PAGO NO FUERON CANCELADAS POR OTROS CHEQUES//----------------------------//
									if($_POST['tesoreria_cheques_manual_orden_db_ordenes_pago']!="")
									{
										//die($_POST['tesoreria_cheques_db_ordenes_pago']);
										$vector = split( ",", $_POST['tesoreria_cheques_manual_orden_db_ordenes_pago'] );
										
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
												if(($row_orden->fields("numero_cheque")!=$_POST['tesoreria_cheques_manual_orden_db_n_precheque']) AND ($row_orden->fields("numero_cheque")!=0))	
															die ('Error-orden');
															
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
														AND
															cheques.tipo_cheque='1'	
														";
																			$row2=& $conn->Execute($sql_prueba);
																			$row_prueba=& $conn->Execute($sql_prueba);
										
																//			$id=$row2->fields("id_cheques");
																			//$cuenta_contable=$row2->fields("cuenta_contable_banco");				
									if(!$row2->EOF)
									{
											$monto=str_replace(".","",$_POST[tesoreria_cheques_manual_orden_db_monto_pagar]);
								
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
													
													$islr=$_POST[tesoreria_cheques_manual_orden_pr_ret_islr];
													$sql="		UPDATE cheques
																SET
																	concepto='$_POST[tesoreria_cheques_manual_orden_db_concepto]',
																	porcentaje_itf='$porcentaje',
																	monto_cheque='".str_replace(",",".",$monto)."',
																	ordenes='{".$_POST[tesoreria_cheques_manual_orden_db_ordenes_pago]."}',																		
																	fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																	ultimo_usuario=".$_SESSION['id_usuario'].",
																	porcentaje_islr='".str_replace(",",".",$islr)."',
																	sustraendo=$_POST[tesoreria_cheques_manual_pr_sustraendo_oculto]
																WHERE
																	 cheques.numero_cheque='$_POST[tesoreria_cheques_manual_orden_db_n_precheque]'
																AND
																	cheques.id_organismo=".$_SESSION["id_organismo"]."
																AND
																	cheques.tipo_cheque='2'	
																";
																		
																		if (!$conn->Execute($sql)) 
																		//die ('Error al Actualizar: '.$conn->ErrorMsg());
																		die ("NoActualizo");
																			
												//--------------------------------------------------------------------------------------------------------------------------------------									
																			//blanquenado los datos que seran modificados
																		if($_POST['tesoreria_cheques_db_ordenes_pago']=="")
																				{		
																					die("Actualizado");	
																				 }
																						/*//----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
																											$vector = split( ",", $_POST['tesoreria_cheques_manual_orden_db_ordenes_pago'] );
																											
																											$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
																											$i=0;
																											$n_cuenta=$_POST['tesoreria_cheques_manual_orden_db_n_cuenta'];
																											
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
																											}	*/
											
										//-------------------------------------------------------------------------------------------------------------
								die("Actualizado");
						}
}else
die("cerrado");
?>