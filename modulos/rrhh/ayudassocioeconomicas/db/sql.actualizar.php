<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$monto= str_replace(".","",$_POST['rrhh_ayudasocioeconomica_db_monto']);
$monto= str_replace(",",".",$monto);

$sql = "	
				UPDATE ayudasocioeconomica  
					 SET
						id_usuario = $_POST[rrhh_ayudasocioeconomica_usuarios_rp_id_usuario],
						fecha = '$_POST[rrhh_ayudasocioeconomica_db_fecha]',
						concepto = '$_POST[rrhh_ayudasocioeconomica_db_concepto]',
						monto = $monto 
					WHERE id = $_POST[rrhh_ayudasocioeconomica_db_id]
						
			";


if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg()." $sql");
else
	die("Actualizado");
?>