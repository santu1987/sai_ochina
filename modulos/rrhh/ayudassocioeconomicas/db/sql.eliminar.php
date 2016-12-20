<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "DELETE FROM ayudasocioeconomica WHERE id = $_POST[rrhh_ayudasocioeconomica_db_id]";
	
if (!$conn->Execute($sql)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());
else
	die("Eliminado");
?>