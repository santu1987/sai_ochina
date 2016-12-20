<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha=date("Y-m-d");
$sql = "	UPDATE 
				cheques  
			SET
				estado[5]='1',
				nombre_conformador='$_POST[contacto]',
				estado_fecha[5]='$fecha'
			WHERE
				id_cheques='$_POST[id_cheque]'
				";
$conn->Execute($sql);

if (!$conn->Execute($sql)) 
	die($sql);	
else
	die("Registrado");
?>