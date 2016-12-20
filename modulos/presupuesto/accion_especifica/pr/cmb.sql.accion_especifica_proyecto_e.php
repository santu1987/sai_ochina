<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					accion_especifica.id_accion_especifica  , 
					accion_especifica.denominacion 
				FROM 
					accion_especifica_proyecto 
				INNER JOIN 
					accion_especifica 
				ON
					accion_especifica_proyecto.id_accion_especifica = accion_especifica.id_accion_especifica  
				WHERE 
					accion_especifica_proyecto.id_proyecto=$_GET[id_acciones]  
				ORDER BY 
					accion_especifica.denominacion 
			";


$rs_accion_especifica =& $conn->Execute($sql);
while (!$rs_accion_especifica->EOF) {
	$opt_acciones_especifica.=(($opt_acciones_especifica)?",":"").'"'.$rs_accion_especifica->fields('id_accion_especifica').'":"'.$rs_accion_especifica->fields('denominacion').'"';
	$rs_accion_especifica->MoveNext();
}
?>
{<?=$opt_acciones_especifica?>}
