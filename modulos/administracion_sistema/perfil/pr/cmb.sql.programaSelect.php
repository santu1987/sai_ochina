<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					programa.id,
					programa.nombre  
				FROM 
					perfil_programa 
				INNER JOIN 
					programa 
				ON
					perfil_programa.id_programa=programa.id  
				WHERE 
					perfil_programa.id_perfil=$_GET[id_perfil] AND 
					programa.id_modulo=$_GET[id_modulo] 
				ORDER BY 
					programa.nombre 
			";

$rs_programa =& $conn->Execute($sql);
while (!$rs_programa->EOF) {
	$opt_programa.=(($opt_programa)?",":"").'"'.$rs_programa->fields('id').'":"'.$rs_programa->fields('nombre').'"';
	$rs_programa->MoveNext();
}
?>
{<?=$opt_programa?>}