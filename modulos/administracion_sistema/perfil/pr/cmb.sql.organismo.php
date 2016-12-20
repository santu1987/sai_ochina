<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					organismo.id_organismo,
					organismo.nombre  
				FROM 
					organismo 
				WHERE  
					organismo.id_organismo NOT IN 	(
																				SELECT 
																					perfil_organismo.id_organismo    
																				FROM 
																					organismo
																				INNER JOIN 
																					perfil_organismo 
																				ON
																					perfil_organismo.id_organismo=organismo.id_organismo 
																				WHERE 
																					perfil_organismo.id_perfil=$_GET[id_perfil]
																			)
				ORDER BY 
					organismo.nombre
			";

$rs_organismo =& $conn->Execute($sql);
while (!$rs_organismo->EOF) {
	$opt_organismo.=(($opt_organismo)?",":"").'"'.$rs_organismo->fields('id_organismo').'":"'.$rs_organismo->fields('nombre').'"';
	$rs_organismo->MoveNext();
}
?>
{<?=$opt_organismo?>}