<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$sql = "SELECT
			id_concepto,
			descripcion
		FROM
			conceptos
		WHERE
			id_concepto = $_POST[concepto_variable_pr_cod]
		AND
			id_organismo = $_SESSION[id_organismo]
			";
	$row=& $conn->Execute($sql);
	$arreglo = "";
	if($row->fields("id_concepto")!=''){
		$arreglo = $row->fields("id_concepto")."*".$row->fields("descripcion");
	}
	
	echo $arreglo;
?>