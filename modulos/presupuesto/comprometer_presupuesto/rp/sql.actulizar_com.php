<?php
session_start();
//*****************************************************************************************************************************

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//*****************************************************************************************************************************

$sql_bus ="select numero_compromiso, numero_documento from \"presupuesto_ejecutadoD\"";
$row_bus=& $conn->Execute($sql_bus);
while (!$row_bus->EOF)
	{
//*****************************************************************************************************************************
$sql = "UPDATE 
	\"presupuesto_ejecutadoD\"
SET 	
	numero_compromisos = numero_compromiso,
	numero_documentos = numero_documento
WHERE 
	numero_compromiso = '".$row_bus->fields('numero_compromiso')."'
AND
	numero_documento = '".$row_bus->fields('numero_documento')."'
	";
//*****************************************************************************************************************************
if (!$conn->Execute($sql)) {
		echo ('Error al Actulizar: '.$sql);
	}else{
		echo("Registrado");
	}
	$row_bus->MoveNext();
	}
	
?>