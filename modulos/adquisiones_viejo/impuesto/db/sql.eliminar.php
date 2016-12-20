<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//$sql = "SELECT id_impuesto FROM impuesto WHERE id_impuesto = $_POST[adquisiciones_vista_impuesto]";
//f (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
//$row= $conn->Execute($sql);
//if($row->EOF)
	$sql="DELETE FROM impuesto WHERE id_impuesto=$_POST[adquisiciones_vista_impuesto]";
//else
	//die("Existe");

if (!$conn->Execute($sql)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());
else
	die("Eliminado");
?>