<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

	$sql = "SELECT 
					distinct(conceptos.id_concepto) as id,
					conceptos.descripcion,
					conceptos.asignacion_deduccion,
					conceptos.num_orden,
					SUM(nomina.monto_concepto) as total
				FROM
					conceptos
				INNER JOIN
					nomina
				ON
					conceptos.id_concepto = nomina.id_concepto
				INNER JOIN
					trabajador
				ON
					nomina.id_trabajador = trabajador.id_trabajador
				WHERE
					nomina.id_tipo_nomina = 5
				AND
					nomina.id_nominas = 241
				AND
					nomina.id_organismo = $_SESSION[id_organismo]	
				GROUP BY
					id, conceptos.descripcion, conceptos.num_orden, conceptos.asignacion_deduccion 
				ORDER BY
					conceptos.asignacion_deduccion, conceptos.num_orden asc";
	$row =& $conn->Execute($sql);
	$arreglo = "";
	if($row->fields("id")!=''){
		$arreglo = $row->fields("id")."*".$row->fields("descripcion")."*".$row->fields("asignacion_deduccion")."*".$row->fields("num_orden")."*".$row->fields("total");
	}
	echo $arreglo;
?>