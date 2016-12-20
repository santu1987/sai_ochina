<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_tipo_documento FROM tipo_documento WHERE id_tipo_documento = $_POST[tipo_documento_db_id]";
$row= $conn->Execute($sql);

if($row->EOF)
	$sql = "DELETE FROM tipo_documento WHERE id_tipo_documento = $_POST[tipo_documento_db_id]";
else
	$bloqueado=true;
	
if (!$conn->Execute($sql)||$bloqueado)
	echo (($bloqueado)?$msgBloqueado:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Ok';
?>