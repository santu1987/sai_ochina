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
				descripcion,
				asignacion_deduccion,
				limite_inf,
				limite_sup,
				observacion,
				estatus,
				num_orden
			FROM
				conceptos
			WHERE 
				id_concepto = $_POST[conceptos_db_cod]
			AND
				id_organismo = $_SESSION[id_organismo]";	
	$row=& $conn->Execute($sql);
	$arreglo = "";
	if($row->fields("id_concepto")!=''){
	//
	$limite_inf = $row->fields("limite_inf");
	if(strpos($limite_inf,'.')==0)
		$limite_inf = $limite_inf.",00";
	else
		$limite_inf = str_replace('.',',',$limite_inf);
	$limite_sup = $row->fields("limite_sup");
	if(strpos($limite_sup,'.')==0)
		$limite_sup = $limite_sup.",00";
	else
		$limite_sup = str_replace('.',',',$limite_sup);
	//
	$arreglo = $row->fields("id_concepto")."*".$row->fields("descripcion")."*".$row->fields("asignacion_deduccion")."*".$limite_inf."*".$limite_sup."*".$row->fields("observacion")."*".$row->fields("estatus")."*".$row->fields("num_orden");
	}
	
	echo $arreglo;
?>