<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					accion_especifica.id_accion_especifica,
					accion_especifica.denominacion  
				FROM 
					accion_especifica_central 
				INNER JOIN 
					accion_especifica 
				ON
					accion_especifica_central.id_accion_especifica = accion_especifica.id_accion_especifica  
				WHERE 
					accion_especifica_central.id_accion_central=$_GET[id_modulo]  
				ORDER BY 
					accion_especifica.denominacion 
			";

$sql2 = "
	SELECT 
	accion_especifica.id_accion_especifica  , 
	accion_especifica.denominacion
FROM 
	accion_especifica
where 	id_accion_especifica !=(SELECT id_accion_especifica
  FROM accion_especifica_central)
	";

$rs_accion_especifica =& $conn->Execute($sql);
while (!$rs_accion_especifica->EOF) {
	$opt_accion_especifica.=(($opt_accion_especifica)?",":"").'"'.$rs_accion_especifica->fields('id_accion_especifica').'":"'.$rs_accion_especifica->fields('denominacion').'"';
	$rs_accion_especifica->MoveNext();
}
?>
{<?=$opt_accion_especifica?>}
