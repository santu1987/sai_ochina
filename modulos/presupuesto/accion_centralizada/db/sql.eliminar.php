<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


 $sqlbus = "SELECT id_accion_central FROM accion_especifica WHERE id_accion_central = $_POST[accion_centralizada_db_id]";
$row=& $conn->Execute($sqlbus);
if($row->EOF)
{
	$sql = "DELETE FROM accion_centralizada WHERE id_accion_central = $_POST[accion_centralizada_db_id]";
	 $conn->Execute($sql);

	
if (!$conn->Execute($sql))
	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Ok';
}
else{
	echo 'bloqueado';
}
?>