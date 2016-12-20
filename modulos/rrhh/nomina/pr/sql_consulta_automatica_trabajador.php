<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT
			trabajador.id_trabajador,
			persona.cedula,
			persona.nombre,
			persona.apellido
		FROM
			trabajador
		INNER JOIN
			persona
		ON
			trabajador.id_persona = persona.id_persona
		WHERE
			persona.cedula LIKE '%$_POST[nomina_pr_cedula_trabajador]%'
		AND
			trabajador.id_organismo  =$_SESSION[id_organismo] ";
$row =& $conn->Execute($sql);
$arreglo = "";
 if($row->fields("id_trabajador")!=''){
	 $arreglo = $row->fields("id_trabajador")."*".$row->fields("cedula")."*".$row->fields("nombre")."*".$row->fields("apellido");
 }
 echo $arreglo;
?>