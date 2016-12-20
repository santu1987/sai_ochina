<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "								
				SELECT 
					usuario.id_usuario,
					usuario.usuario  
				FROM 
					usuario 
				WHERE  
					usuario.id_usuario NOT IN 				(
																				SELECT 
																					perfil_usuario.id_usuario     
																				FROM 
																					usuario 
																				INNER JOIN 
																					perfil_usuario  
																				ON
																					perfil_usuario.id_usuario=usuario.id_usuario 
																				WHERE 
																					perfil_usuario.id_perfil=$_GET[id_perfil]
																			)
				ORDER BY 
					usuario.nombre					
			";

$rs_usuario =& $conn->Execute($sql);
while (!$rs_usuario->EOF) {
	$opt_usuario.=(($opt_usuario)?",":"").'"'.$rs_usuario->fields('id_usuario').'":"'.$rs_usuario->fields('usuario').'"';
	$rs_usuario->MoveNext();
}
?>
{<?=$opt_usuario?>}