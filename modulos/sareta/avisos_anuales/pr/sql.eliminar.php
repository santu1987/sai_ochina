<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sql = "SELECT id FROM sareta.planilla WHERE id= $_POST[vista_id_avisos_anuales]";
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);

/*se el registro exite se eliminar pero si exite una relación a otra tabla se valida*/

$sql="DELETE FROM sareta.planilla WHERE planilla.id=$_POST[vista_id_avisos_anuales]";
if (!$conn->Execute($sql)) {
	
	/*el codigo siguiente valida el texto de error para verificar 
	si se debe a una llave foranea*/
	
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