<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					unidad_ejecutora.id_unidad_ejecutora,
					unidad_ejecutora.nombre AS ejecurora  
				FROM 
					organismo 
				INNER JOIN 
					unidad_ejecutora 
				ON
					unidad_ejecutora.id_organismo=organismo.id_organismo  
				WHERE 
					unidad_ejecutora.id_organismo=$_GET[id_unidad_ejecutora]
				ORDER BY 
					ejecurora
			";

$rs_unidad_ejecutora =& $conn->Execute($sql);
while (!$rs_unidad_ejecutora->EOF) {
	$opt_unidad_ejecutora.=(($opt_unidad_ejecutora)?",":"").'"'.$rs_unidad_ejecutora->fields('id_unidad_ejecutora').'":"'.$rs_unidad_ejecutora->fields('ejecurora').'"';
	$rs_unidad_ejecutora->MoveNext();
}
?>
{<?=$opt_unidad_ejecutora?>}