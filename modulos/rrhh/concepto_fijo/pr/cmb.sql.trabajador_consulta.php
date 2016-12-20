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
					persona.nombre,
					persona.apellido
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
				INNER JOIN
					confij_tipo_nomina
				ON
					tipo_nomina.id_tipo_nomina = confij_tipo_nomina.id_tipo_nomina
				
				WHERE
					trabajador.id_tipo_nomina = $_GET[id_tipo_nomina] 
				AND
					estatus = 1
				AND
					trabajador.id_trabajador NOT IN 	(
																				SELECT 
																					confij_tipo_nomina.id_trabajador    
																				FROM 
																					conceptos_fijos
																				INNER JOIN 
																				confij_tipo_nomina 
																				ON
																					conceptos_fijos.id_concepto_fijos = confij_tipo_nomina.id_concepto_fijos 
																				WHERE
																					estatus = 1
																				AND
																					conceptos_fijos.id_concepto_fijos = '$_GET[conceptos_fijos_pr_id_concepto_fijo]'
																				AND
																				conceptos_fijos.id_organismo = '$_SESSION[id_organismo]'
																			)
				ORDER BY 
					persona.nombre
			";

$rs_trabajador =& $conn->Execute($sql);
while (!$rs_trabajador->EOF) {
	$opt_trabajador.=(($opt_trabajador)?",":"").'"'.$rs_trabajador->fields('id_trabajador').'":"'.$rs_trabajador->fields('nombre').', '.$rs_trabajador->fields("apellido").'"';
	$rs_trabajador->MoveNext();
}
?>
{<?=$opt_trabajador?>}