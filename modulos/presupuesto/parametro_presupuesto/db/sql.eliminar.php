<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//$sql = "SELECT id_ramo FROM ramos WHERE id_ramos = $_POST[ramos_db_id]";
//$row= $conn->Execute($sql);

//if($row->EOF)
	$sql = "DELETE FROM parametros_presupuesto WHERE id_parametros_presupuesto = $_POST[parametro_presupuesto_db_id_parametros_presupuesto]";
	$conn->Execute($sql);
//else
	//$bloqueado=true;
	
if (!$conn->Execute($sql)/*||$bloqueado*/)
	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Eliminado';
?>