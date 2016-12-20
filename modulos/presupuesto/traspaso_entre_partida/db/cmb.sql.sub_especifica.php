<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT DISTINCT 
					subespecifica 
				FROM  
					clasificador_presupuestario 
				WHERE 
					especifica =$_GET[especifica] 
				ORDER BY 
					subespecifica
			";

$rs_subespecifica =& $conn->Execute($sql);
while (!$rs_subespecifica->EOF) {
	$opt_subespecifica.=(($opt_subespecifica)?",":"").'"'.$rs_subespecifica->fields('subespecifica').'":"'.$rs_subespecifica->fields('subespecifica').'"';
	$rs_subespecifica->MoveNext();
}
?>
{<?=$opt_subespecifica?>}