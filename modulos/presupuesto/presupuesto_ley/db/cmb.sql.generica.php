<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT DISTINCT 
					generica 
				FROM  
					clasificador_presupuestario 
				WHERE 
					partida =$_GET[partida] 
				ORDER BY 
					generica
			";

$rs_generica =& $conn->Execute($sql);
while (!$rs_generica->EOF) {
	$opt_generica.=(($opt_generica)?",":"").'"'.$rs_generica->fields('generica').'":"'.$rs_generica->fields('generica').'"';
	$rs_generica->MoveNext();
}
?>
{<?=$opt_generica?>}