<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE id_dia_feriado = $_POST[dias_feriados_db_id]";
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
$sql="DELETE FROM sareta.dias_feriados WHERE id_dia_feriado=$_POST[dias_feriados_db_id]";
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