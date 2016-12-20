<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	SELECT 
					id_beneficiario_ayudas 
				FROM 
					beneficiario_ayudas 
				WHERE 
					cedula='$_POST[beneficiario_ayudas_db_vista_nacionalidad]$_POST[beneficiario_ayudas_db_vista_cedula]'";
					

$row= $conn->Execute($sql);
if (!$conn->Execute($sql)) die ('Error al actualizar: '.$conn->ErrorMsg().' $sql');

if (!$row->EOF)
{
	echo "Existe";
}
?>