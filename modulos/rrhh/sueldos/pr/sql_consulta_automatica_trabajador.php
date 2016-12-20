<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

	$sql = "SELECT
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				unidad_ejecutora.nombre as unidad_ejecutora,
				cargos.descripcion as cargo
			FROM
				persona
			INNER JOIN
				trabajador
			ON
				persona.id_persona = trabajador.id_persona
			INNER JOIN
				unidad_ejecutora
			ON
				trabajador.id_unidad = unidad_ejecutora.id_unidad_ejecutora
			INNER JOIN
				cargos
			ON
				trabajador.id_cargo = cargos.id_cargos
			WHERE 
				lower(persona.cedula) like '%$_POST[aumento_sueldos_pr_cedula_trabajador]%'
			AND
				persona.id_organismo = $_SESSION[id_organismo] ";
	$row =& $conn->Execute($sql);
	$arreglo = "";
	if($row->fields("id_trabajador")!=''){
		$arreglo = $row->fields("id_trabajador")."*".$row->fields("cedula")."*".$row->fields("nombre")."*".$row->fields("apellido")."*".$row->fields("unidad_ejecutora")."*".$row->fields("cargo");
	}
	echo $arreglo;
?>