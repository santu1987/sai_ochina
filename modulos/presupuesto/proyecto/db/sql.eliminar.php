<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sqll = "SELECT id_proyecto FROM accion_especifica WHERE id_proyecto = $_POST[proyecto_db_id]";
$row= $conn->Execute($sqll);

if($row->EOF){
	$sql = "DELETE FROM proyecto WHERE id_proyecto = $_POST[proyecto_db_id]";
	$conn->Execute($sql);
	
	if (!$conn->Execute($sql)/*||$bloqueado*/)
		echo (($bloqueado)?$msgBloqueado:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
	else
		echo 'Ok';
}else{
	echo 'bloqueado';
}
?>