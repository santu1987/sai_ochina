<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST['tesoreria_banco_cuenta_db_n_cuenta'];
//$sql = "SELECT id_organismo FROM firmas_voucher WHERE id_organismo=".$_SESSION["id_organismo"]."";

$ayo_mes=$ayo."/".$mes;
$user=$_SESSION['id_usuario'];
//----
$sql_activo = "SELECT count(estatus) FROM  firmas_voucher WHERE estatus='1' AND  $_POST[tesoreria_firmas_voucher_db_estatus]='1' AND
				firmas_voucher.id_organismo=$_SESSION[id_organismo]";
if (!$conn->Execute($sql_activo)) die ('Error al consultar: '.$conn->ErrorMsg());
$row_activo= $conn->Execute($sql_activo);
			if(!$row_activo->EOF)
			{
				$count = $row_activo->fields("count");
				if($count>0)
				{
					$opcion="activa";
					$estatus=2;
				}else 
				{	
					$estatus=$_POST[tesoreria_firmas_voucher_db_estatus];
				}
			}	
				//	die("chequera_activa");
	
//----
$sql="SELECT * FROM firmas_voucher where fecha_firma='$_POST[form_tesoreria_db_firmas_voucher_rp_fecha]'";
//die($sql);
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row_fecha= $conn->Execute($sql);
	if(!$row_fecha->EOF)
	{
		die("fechas_iguales");
	}
						
					//
				$sql="SELECT * 
						FROM 
							firmas_voucher where codigo_preparado_por='$user'	
						 and 
							codigo_director_ochina='$_POST[tesoreria_firmas_voucher_db_id_director]'
						 and 
							codigo_director_administracion='$_POST[tesoreria_firmas_voucher_db_id_director_administracion]' 
						 and
							 codigo_jefe_finanzas='$_POST[tesoreria_firmas_voucher_db_id_jefe_finanzas]'
						 and
									fecha_firma='$_POST[form_tesoreria_db_firmas_voucher_rp_fecha]'";
							//die($sql);
							if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
							$row= $conn->Execute($sql);
										
							if($row->EOF){
							
										$sql = "	
														INSERT INTO 
															firmas_voucher
															(
																id_organismo,
																codigo_director_ochina,
																codigo_director_administracion,
																codigo_jefe_finanzas,
																codigo_preparado_por,
																comentarios,
																ultimo_usuario,
																fecha_ultima_modificacion,
																fecha_firma,
																estatus
															) 
															VALUES
															(
																".$_SESSION["id_organismo"].",
																'$_POST[tesoreria_firmas_voucher_db_id_director]',
																'$_POST[tesoreria_firmas_voucher_db_id_director_administracion]',
																'$_POST[tesoreria_firmas_voucher_db_id_jefe_finanzas]',
																".$_SESSION['id_usuario'].",		
																'$_POST[tesoreria_banco_db_comentarios]',
																".$_SESSION['id_usuario']."	,
																'".date("Y-m-d H:i:s")."',
																'$_POST[form_tesoreria_db_firmas_voucher_rp_fecha]',
																'$estatus'
															)
													";
										
							}		
													
							else
							//die($sql);
							die("NoRegistro");
								
							if (!$conn->Execute($sql)) 
							die ('Error al Registrar: '.$sql);
							//die ('Error al Registrar: '.$conn->ErrorMsg());
							
							else
								//die('Registrado'.$sql);
								if($opcion=="activa"){die("firma_activa");}
							
								die("Registrado");
		?>