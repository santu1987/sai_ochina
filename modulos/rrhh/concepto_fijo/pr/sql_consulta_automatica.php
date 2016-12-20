<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				calculo_rrhh.id_calculo_rrhh,
				nombre
			FROM 
				calculo_rrhh
			INNER JOIN
				concep_cal_rrhh
			ON
				calculo_rrhh.id_calculo_rrhh = concep_cal_rrhh.id_calculo_rrhh
			INNER JOIN
				conceptos
			ON	
				concep_cal_rrhh.id_conceptos = conceptos.id_concepto
			WHERE
				conceptos.id_concepto = $_POST[conceptos_fijos_pr_id_concepto]
			AND
				concep_cal_rrhh.estatu = '1'
			AND 
				conceptos.id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
while(!$row->EOF){
	$arreglo.= $row->fields("id_calculo_rrhh")."-".$row->fields("nombre")."*";
	$row->MoveNext();
}
echo $arreglo;
?>