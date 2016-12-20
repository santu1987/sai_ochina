<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_entrevista) 
			FROM 
				entrevista
			WHERE
				entrevista.id_ramas = $_POST[ramas_db_id_ramas]
			AND 
				entrevista.id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				DELETE FROM 
					ramas
				WHERE	
					id_ramas = $_POST[ramas_db_id_ramas]
";
if ($conn->Execute($sql) == false) {
	echo 'Error al Eliminar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Eliminado");
}
}
if ($row!=0){
	echo'Relacion_Existe';
}
?>