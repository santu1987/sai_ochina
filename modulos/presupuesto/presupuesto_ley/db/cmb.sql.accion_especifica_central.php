<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					accion_especifica.id_accion_especifica, accion_especifica.denominacion
				FROM 
					accion_especifica_central
				JOIN 
					accion_especifica 
				ON 
					accion_especifica_central.id_accion_especifica = accion_especifica.id_accion_especifica
				WHERE
					(accion_especifica_central.id_accion_central  =$_GET[id_accion])
				ORDER BY
					denominacion
			";

$rs_accion_especifica_entral =& $conn->Execute($sql);
while (!$rs_accion_especifica_entral->EOF) {
	$opt_accion_especifica_central.=(($opt_accion_especifica_central)?",":"").'"'.$rs_accion_especifica_entral->fields('id_accion_especifica').'":"'.$rs_accion_especifica_entral->fields('denominacion').'"';
	$rs_accion_especifica_entral->MoveNext();
}
?>
{<?=$opt_accion_especifica_central?>}