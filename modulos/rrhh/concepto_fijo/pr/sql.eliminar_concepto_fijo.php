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
					conceptos_fijos
				WHERE	
					id_concepto_fijos = $_POST[conceptos_fijos_pr_id_concepto_fijo]
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