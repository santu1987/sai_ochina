<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT DISTINCT 
					especifica 
				FROM  
					clasificador_presupuestario 
				WHERE 
					generica =$_GET[generica] 
				ORDER BY 
					especifica
			";

$rs_especifica =& $conn->Execute($sql);
while (!$rs_especifica->EOF) {
	$opt_especifica.=(($opt_especifica)?",":"").'"'.$rs_especifica->fields('especifica').'":"'.$rs_especifica->fields('especifica').'"';
	$rs_especifica->MoveNext();
}
?>
{<?=$opt_especifica?>}