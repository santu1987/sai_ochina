<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST[tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta];
		
$sql = "SELECT banco_cuentas.cuenta_banco FROM  banco_cuentas WHERE id_banco= $_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco] AND cuenta_banco ='".$cuenta."'";
$sql2 = "SELECT usuario_banco_cuentas.cuenta_banco FROM  usuario_banco_cuentas WHERE id_usuario= $_POST[tesoreria_usuario_banco_cuentas_db_id_usuario] AND  id_banco='$_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco]' AND cuenta_banco ='".$cuenta."'";

if (!$conn->Execute($sql))  die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);

if (!$conn->Execute($sql2))  die ('Error al Registrar: '.$conn->ErrorMsg());
		$row2= $conn->Execute($sql2);

		if((!$row->EOF)&&($row2->EOF)){
		
						$sql = "	
									INSERT INTO 
										usuario_banco_cuentas
										(
											id_organismo,
											id_banco,
											id_usuario,
											cuenta_banco,
											estatus,
											comentarios,
											ultimo_usuario,
											fecha_ultima_modificacion
										) 
										VALUES
										(
											".$_SESSION["id_organismo"].",
											'$_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco]',
											'$_POST[tesoreria_usuario_banco_cuentas_db_id_usuario]',
											'$_POST[tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta]',
											'$_POST[tesoreria_usuario_banco_cuentas_db_estatus]',
											'$_POST[tesoreria_usuario_banco_cuentas_db_comentarios]',
											 ".$_SESSION['id_usuario'].",		
											'".date("Y-m-d H:i:s")."'
												)
								";
					
					
					}
				
else
die("NoRegistro");
if (!$conn->Execute($sql)) 
//die ('Error al Registrar: '.$conn->ErrorMsg());
die ('Error al Registrar: '.$sql);

else
	die("Registrado");

?>