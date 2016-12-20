<?php
if (!$_SESSION) session_start();

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
				INNER JOIN 
					perfil_organismo 
				ON
					perfil_organismo.id_organismo=organismo.id_organismo  
				WHERE 
					perfil_organismo.id_perfil=$_GET[id_perfil] 
				ORDER BY 
					organismo.id_organismo 
			";

$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.=(($opt_modulo)?",":"").'"'.$rs_modulo->fields('id_organismo').'":"'.$rs_modulo->fields('nombre').'"';
	$rs_modulo->MoveNext();
}

?>
{<?=$opt_modulo?>}