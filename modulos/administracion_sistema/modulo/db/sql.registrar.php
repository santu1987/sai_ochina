<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id FROM modulo WHERE upper(nombre) ='".strtoupper($_POST['modulo_db_vista_nombre'])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF)
$sql = "	
				INSERT INTO 
					modulo 
					(
						nombre,
						obs
					) 
					VALUES
					(
						'$_POST[modulo_db_vista_nombre]',
						'$_POST[modulo_db_vista_observacion]'
					)
			";

else
	die("NoRegistro");
	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>