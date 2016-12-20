<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id FROM programa WHERE id_modulo = $_POST[vista_id_modulo]";
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
	$sql="DELETE FROM modulo WHERE id=$_POST[vista_id_modulo]";
	if (!$conn->Execute($sql)) 
		die ('Error al Eliminar: '.$conn->ErrorMsg());
	else
		die("Eliminado");
}else{
	die("Existe");
	//die($sql);
}	

?>