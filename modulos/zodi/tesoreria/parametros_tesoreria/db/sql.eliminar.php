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
$ayos=date("Y");
if($_POST[parametro_tesoreria_db_anio]!=$ayos)
{
	$sql = "DELETE FROM parametros_tesoreria WHERE id_parametros_tesoreria = $_POST[parametro_tesoreria_db_id_parametros_tesoreria]";
	$conn->Execute($sql);
}
else
	die("no_eliminar_xayos");
//else
	//$bloqueado=true;
	
if (!$conn->Execute($sql)/*||$bloqueado*/)
	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Eliminado';
?>