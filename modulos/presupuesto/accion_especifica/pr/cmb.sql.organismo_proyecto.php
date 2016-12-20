<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					proyecto.id_proyecto,
					proyecto.nombre AS proyecto  
				FROM 
					organismo 
				INNER JOIN 
					proyecto 
				ON
					proyecto.id_organismo=organismo.id_organismo  
				WHERE 
					proyecto.id_organismo=$_GET[id_organismo]
				ORDER BY 
					proyecto 
			";

$rs_proyecto =& $conn->Execute($sql);
while (!$rs_proyecto->EOF) {
	$opt_proyecto.=(($opt_proyecto)?",":"").'"'.$rs_proyecto->fields('id_proyecto').'":"'.$rs_proyecto->fields('proyecto').'"';
	$rs_proyecto->MoveNext();
}
?>
{<?=$opt_proyecto?>}