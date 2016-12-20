<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_armador FROM armador WHERE id_armador = $_POST[vista_id_armador]";
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
$sql="DELETE FROM armador WHERE id_armador =$_POST[vista_id_armador]";
if (!$conn->Execute($sql)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());
else
	die("Eliminado");
?>