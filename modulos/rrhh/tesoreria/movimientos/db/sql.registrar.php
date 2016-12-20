<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST['tesoreria_banco_cuenta_db_n_cuenta'];
$sql = "SELECT banco_cuentas.id_cuenta_banco FROM banco_cuentas WHERE banco_cuentas.cuenta_banco ='".$cuenta."' AND banco_cuentas.id_organismo=$_SESSION[id_organismo]";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
$saldo= str_replace(".","",$_POST[tesoreria_movimientos_saldo]);
$saldo_cuenta= str_replace(".","",$_POST[tesoreria_movimientos_total]);
	
if($row->EOF)
{
				$sql = "	
							INSERT INTO 
								movimientos_cuentas
								(
									id_organismo,
									referencia,
									cuenta_banco,
									id_banco,
									fecha_proceso,
									monto,
									ultimo_usuario,
									fecha_ultima_modificacion
								) 
								VALUES
								(
									".$_SESSION["id_organismo"].",
									'$_POST[tesoreria_movimientos_db_nombre_ref]',
									'$_POST[tesoreria_movimientos_db_n_cuenta]',
									'$_POST[tesoreria_movimientos_id_banco]',
									'$_POST[tesoreria_movimientos_db_fecha_v]',
									'".str_replace(",",".",$saldo)."',
									".$_SESSION['id_usuario']."	,
									'".date("Y-m-d H:i:s")."'
								);
					UPDATE banco_cuentas 
						 SET
						 	saldo_actual='".str_replace(",",".",$saldo_cuenta)."',
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_ultima_modificacion='".$fecha."'
						WHERE cuenta_banco ='$_POST[tesoreria_movimientos_db_n_cuenta]'
						AND
							id_organismo=$_SESSION[id_organismo]
					";
							
					
}						
else
die("NoRegistro");
if (!$conn->Execute($sql)) 
die ('Error al Registrar: '.$sql);
else
	die("Registrado");
?>