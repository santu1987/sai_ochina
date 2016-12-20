<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					modulo.id,
					modulo.nombre  
				FROM 
					perfil_modulo 
				INNER JOIN 
					modulo 
				ON
					perfil_modulo.id_modulo=modulo.id  
				WHERE 
					perfil_modulo.id_perfil=$_GET[id_perfil] 
				ORDER BY 
					modulo.nombre 
			";

$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.=(($opt_modulo)?",":"").'"'.$rs_modulo->fields('id').'":"'.$rs_modulo->fields('nombre').'"';
	$rs_modulo->MoveNext();
}
?>
{<?=$opt_modulo?>}