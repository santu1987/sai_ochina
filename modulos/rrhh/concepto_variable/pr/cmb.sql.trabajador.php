<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "	
				SELECT 
					trabajador.id_trabajador,
					persona.nombre  
				FROM 
					persona
				INNER JOIN
					trabajador
				ON
					persona.id_persona = trabajador.id_persona
				INNER JOIN
					tipo_nomina
				ON
					trabajador.id_tipo_nomina = tipo_nomina.id_tipo_nomina
				WHERE
					trabajador.id_tipo_nomina = $_GET[id_tipo_nomina] 
					AND
						persona.id_organismo = '$_SESSION[id_organismo]'
				ORDER BY 
					persona.nombre
			";

$rs_trabajador =& $conn->Execute($sql);
while (!$rs_trabajador->EOF) {
	$opt_trabajador.=(($opt_trabajador)?",":"").'"'.$rs_trabajador->fields('id_trabajador').'":"'.$rs_trabajador->fields('nombre').'"';
	$rs_trabajador->MoveNext();
}
?>
{<?=$opt_trabajador?>}