<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$monto= str_replace(".","",$_POST['rrhh_ayudasocioeconomica_db_monto']);
$monto= str_replace(",",".",$monto);

$sql = "	
				INSERT INTO 
					ayudasocioeconomica
					(
						id_usuario,
						fecha,
						concepto,
						monto,
						fecha_actualizacion,
						ultimo_usuario
			
					) 
					VALUES
					(
						$_POST[rrhh_ayudasocioeconomica_usuarios_rp_id_usuario],
						'$_POST[rrhh_ayudasocioeconomica_db_fecha]',
						'$_POST[rrhh_ayudasocioeconomica_db_concepto]',
						$monto,
						'".date("Y-m-d H:i:s")."',
						".$_SESSION['id_usuario']."						
					)
			";
	
if (!$conn->Execute($sql)) 
	//die ('Error al Registrar: '.$sql);
	die ('Error al Registrar: '.$sql.'<br />'.$conn->ErrorMsg());
else
	die("Registrado");
?>