<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					perfil.id_perfil, perfil.nombre
				FROM 
					perfil
				INNER JOIN
					perfil_usuario 
				ON
					perfil_usuario.id_perfil = perfil.id_perfil 
				WHERE  
					perfil_usuario.id_usuario = $_GET[id_usuario]
				ORDER BY 
					perfil.nombre  
			";

$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.=(($opt_modulo)?",":"").'"'.$rs_modulo->fields('id_perfil').'":"'.$rs_modulo->fields('nombre').'"';
	$rs_modulo->MoveNext();
}
?>
{<?=$opt_modulo?>}