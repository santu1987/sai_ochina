<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
include_once('../../../../controladores/numero_to_letras.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$check = $_POST["tesoreria_cheque_manual_db_itf"]; 

$fecha = date("Y-m-d H:i:s");
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
/*if(($cerrado!="ano")||($cerrado!="mes"))
{
*/				//------ verificando si la cuenta y el banco tienen chequera creada
				$sql_cheque = "SELECT id_chequeras FROM chequeras WHERE cuenta='$_POST[tesoreria_cheque_manual_db_n_cuenta]' and id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]' AND chequeras.id_organismo=".$_SESSION["id_organismo"]."
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
				$monto = str_replace(".","",$_POST[tesoreria_cheque_manual_db_monto_pagar]);
				$islr_oculto = str_replace(".","",$_POST[oculto_islr]);
				$por_islr = str_replace(".","",$_POST[tesoreria_cheque_manual_db_islr]);
				$base_imp = str_replace(".","",$_POST[tesoreria_cheque_manual_db_baseimp]);
				
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
				//---------------------------------------------------------------------------------------------------------	 
				//----------------------------------------------------------------------------------------------------
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
				//----------------------------------------------------------------------------------------------------
				if(!$row->EOF)
					{ 
								if($_POST['tesoreria_cheque_manual_pr_op_oculto']=='1')
								{				$sql = "	
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
																		porcentaje_islr,
																		monto_islr,
																		base_imponible,
																		porcentaje_itf,
																		id_organismo,
																		fecha_cheque,
																		usuario_cheque,
																		fecha_ultima_modificacion,
																		ultimo_usuario,
																		fecha_firma,
																		contabilizado,
																		estado,
																		estado_fecha,
																		ordenes,
																		sustraendo
															
																	) 
																	VALUES
																	(
																		'$_POST[tesoreria_cheque_manual_db_banco_id_banco]',
																		'$_POST[tesoreria_cheque_manual_db_n_cuenta]',
																		'$n_precheque',
																		'2',
																		'$_POST[tesoreria_cheque_manual_pr_proveedor_id]',
																		'".str_replace(",",".",$monto)."',
																		'$_POST[tesoreria_cheque_manual_db_concepto]',
																		'1',
																		'".str_replace(",",".",$por_islr)."',
																		'".str_replace(",",".",$islr_oculto)."',
																		'".str_replace(",",".",$base_imp)."',
																		'$porcentaje',
																		".$_SESSION["id_organismo"].",
																		'".date("Y-m-d H:i:s")."',
																		".$_SESSION['id_usuario'].",
																		'".date("Y-m-d H:i:s")."',
																		".$_SESSION['id_usuario'].",
																		'".$firmas."',
																		'0',
																		'{0,0,0,0,0,0,0}',
																		'{10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010}',
																		'{0}',
																		1
																		)
															";
								}
								else
								if($_POST['tesoreria_cheque_manual_pr_op_oculto']=='2')
								{				$sql = "	
																		INSERT INTO 
																			cheques
																			(
																				id_banco,
																				cuenta_banco,
																				numero_cheque,
																				tipo_cheque,
																				cedula_rif_beneficiario,
																				nombre_beneficiario,
																				monto_cheque,
																				concepto,
																				estatus,
																				porcentaje_islr,
																				monto_islr,
																				base_imponible,
																				porcentaje_itf,
																				id_organismo,
																				fecha_cheque,
																				usuario_cheque,
																				fecha_ultima_modificacion,
																				ultimo_usuario,
																				fecha_firma,
																				contabilizado,
																				estado,
																				estado_fecha,
																				ordenes,
																				sustraendo
																	
																			) 
																			VALUES
																			(
																				'$_POST[tesoreria_cheque_manual_db_banco_id_banco]',
																		'$_POST[tesoreria_cheque_manual_db_n_cuenta]',
																		'$n_precheque',
																		'2',
																	'$_POST[tesoreria_cheque_manual_pr_empleado_codigo]',
																	'$_POST[tesoreria_cheque_manual_pr_empleado_nombre]',
																		'".str_replace(",",".",$monto)."',
																		'$_POST[tesoreria_cheque_manual_db_concepto]',
																		'1',
																		'".str_replace(",",".",$por_islr)."',
																		'".str_replace(",",".",$islr_oculto)."',
																		'".str_replace(",",".",$base_imp)."',
																		'$porcentaje',
																		".$_SESSION["id_organismo"].",
																		'".date("Y-m-d H:i:s")."',
																		".$_SESSION['id_usuario'].",
																		'".date("Y-m-d H:i:s")."',
																		".$_SESSION['id_usuario'].",
																		'".$firmas."',
																		'0',
																		'{0,0,0,0,0,0,0,0,0}',
																		'{10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010,10/10/2010}',
																		'{0}',
																		'1'
																		)
															";
										}
						}
						else
							echo("NoRegistro");
							//die($sql);
						if (!$conn->Execute($sql)) 
							//die ('Error al Actualizar: '.$conn->ErrorMsg());
							die ('Error al RegistrarHHH: '.$sql);
						else
							die("Registrado");
/*}
else
die("cerrado");
*/
?>