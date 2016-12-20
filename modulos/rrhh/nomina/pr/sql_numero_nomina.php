<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

	$sql = "SELECT 
				nominas.id_nominas,
				nominas.desde,
				nominas.hasta,
				nominas.procesada
			FROM
				nominas
			WHERE 
				nominas.procesada != 1
			AND
				nominas.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
			AND
				nominas.id_organismo = $_SESSION[id_organismo]
			ORDER BY 
				nominas.desde ASC
			";
	
	$row =& $conn->Execute($sql);

	$arreglo = "";
	if($row->fields("id_nominas")!=''){
		$arreglo = $row->fields("id_nominas")."*".$row->fields("desde")."*".$row->fields("hasta")."*".$row->fields("procesada");
	}
	echo $arreglo;


?>