<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
			DELETE 
			FROM 
				beneficiario_ayudas   
			WHERE 
				id_beneficiario_ayudas = $_POST[vista_id_beneficiario_ayudas]
		";

if ($conn->Execute($sql) === false) {
	echo 'No se pudo elminar el registro: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo 'Eliminado';
}

?>