<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$cedula = strtolower($_POST["conceptos_fijos_pr_cedula_trabajador"]);
$id_tipo_nomina = $_POST["id_tipo_nomina"];
$Sql="
			SELECT 
				id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido
			FROM 
				persona
			INNER JOIN
				trabajador
			ON
				persona.id_persona = trabajador.id_persona
			WHERE
				lower(persona.cedula) like '%$cedula%'
			AND
				trabajador.id_tipo_nomina = $id_tipo_nomina
			AND
				persona.id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$arreglo = "";
if($row->fields("id_trabajador")!=''){
	$arreglo.= $row->fields("id_trabajador")."*".$row->fields("cedula")."*".$row->fields("nombre")."*".$row->fields("apellido");
}
echo $arreglo;

?>