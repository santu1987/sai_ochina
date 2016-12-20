<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					accion_centralizada.id_accion_central,
					accion_centralizada.denominacion  
				FROM 
					organismo 
				INNER JOIN 
					accion_centralizada 
				ON
					accion_centralizada.id_organismo=organismo.id_organismo  
				WHERE 
					accion_centralizada.id_organismo=$_GET[id_perfil] 
				ORDER BY 
					organismo.nombre 
			";

$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.=(($opt_modulo)?",":"").'"'.$rs_modulo->fields('id_accion_central').'":"'.$rs_modulo->fields('denominacion').'"';
	$rs_modulo->MoveNext();
}
?>
{<?=$opt_modulo?>}