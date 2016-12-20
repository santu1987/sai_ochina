<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_clases_buques FROM sareta.clases_buques WHERE id_clases_buques = $_POST[vista_id_clases_buques]";
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
$sql="DELETE FROM sareta.clases_buques WHERE id_clases_buques=$_POST[vista_id_clases_buques]";
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