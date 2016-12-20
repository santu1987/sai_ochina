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
					accion_especifica_proyecto
				JOIN 
					accion_especifica 
				ON 
					accion_especifica_proyecto.id_accion_especifica = accion_especifica.id_accion_especifica
				WHERE
					(accion_especifica_proyecto.id_proyecto  =$_GET[id_proyecto])
				ORDER BY
					denominacion
			";

$rs_accion_especifica_proyecto =& $conn->Execute($sql);
while (!$rs_accion_especifica_proyecto->EOF) {
	$opt_accion_especifica_proyecto.=(($opt_accion_especifica_proyecto)?",":"").'"'.$rs_accion_especifica_proyecto->fields('id_accion_especifica').'":"'.$rs_accion_especifica_proyecto->fields('denominacion').'"';
	$rs_accion_especifica_proyecto->MoveNext();
}
?>
{<?=$opt_accion_especifica_proyecto?>}