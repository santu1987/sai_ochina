<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="DELETE FROM sareta.tipo_tasa WHERE id_tipo_tasa=$_POST[vista_id_tipo_tasa]";
if (!$conn->Execute($sql)) {
	$ErroForanio=strcmp("viola la llave foránea",$conn->ErrorMsg());
if($ErroForanio=true && $_SESSION['perfil']==0 ){
	die("Foranio");
}if($ErroForanio=true && $_SESSION['perfil']==1 ){
	die ('Error al Eliminar: '.$conn->ErrorMsg());
	
	}

}
else
	die("Eliminado");
?>