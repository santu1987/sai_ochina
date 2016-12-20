<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;


	$sql = "	
				DELETE FROM 
					concepto_variable
				WHERE	
					id_concepto_variable = $_POST[concepto_variable_pr_id_concepto_variable]
				AND
					id_organismo = $_SESSION[id_organismo]
";
if ($conn->Execute($sql) == false) {
	echo 'Error al Eliminar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Eliminado");
}

?>