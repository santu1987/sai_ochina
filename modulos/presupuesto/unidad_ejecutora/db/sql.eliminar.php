<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sqlbus = "SELECT COUNT(id_presupuesto_ley) FROM presupuesto_ley where id_unidad_ejecutora = $_POST[unidad_ejecutora_db_id]";
$row= $conn->Execute($sqlbus);
$text = $row;
if (substr($row,7,2)==0){
	$sql = "DELETE FROM unidad_ejecutora WHERE id_unidad_ejecutora = $_POST[unidad_ejecutora_db_id]";
	$conn->Execute($sql);
}
/*if($row->EOF){
	$sql = "DELETE FROM unidad_ejecutora WHERE id_unidad_ejecutora = $_POST[unidad_ejecutora_db_id]";
	$conn->Execute($sql);*/
else{
	$bloqueado=true;
}	
if (substr($row,7,2)!=0||$bloqueado)
	//echo (($bloqueado)?$msgBloqueado:'Error al Eleminar: '.$conn->ErrorMsg().'<br />');
	echo "Registro Relacionado";
else
	echo 'Eliminado';
?>