<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_accion_especifica FROM accion_especifica_central WHERE id_accion_especifica = $_POST[accion_especifica_db_id]";
//die($sql);
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);

if($row->EOF)
	$sql = "DELETE FROM accion_especifica WHERE id_accion_especifica = $_POST[accion_especifica_db_id]";
else
	die("Existe");
	
if (!$conn->Execute($sql)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());
else
	die("Eliminado");
?>