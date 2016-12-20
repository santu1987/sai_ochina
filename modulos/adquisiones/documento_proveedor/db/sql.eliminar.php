<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_documento_proveedor FROM documento WHERE id_documento_proveedor = $_POST[documento_proveedor_db_id]";
$row= $conn->Execute($sql);
if(!$row->EOF)
	$sql = "DELETE FROM documento WHERE id_documento_proveedor = $_POST[documento_proveedor_db_id]";
else
{
	die ("No_Existe");
	$bloqueado=true;
}
	
if (!$conn->Execute($sql)||$bloqueado)
	echo (($bloqueado)?$msgBloqueado:'Error al eliminar: '.$conn->ErrorMsg().'<br />');
else
	die ("Eliminado");
?>