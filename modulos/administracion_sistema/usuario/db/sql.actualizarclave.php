<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
			UPDATE usuario  
				 SET
					clave = md5('$_POST[usuario_db_vista_clave]') 
				WHERE id_usuario = $_POST[vista_id_usuario]
					
		";
		
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{	
		die ('Actualizado');
}
?>