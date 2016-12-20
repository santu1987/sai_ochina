<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//
/*if ($_POST['conceptos_db_sso']=='true')
	$cal_sso = 1;
else
	$cal_sso = 0;
if($_POST['conceptos_db_lph']=='true')
	$cal_lph = 1;
else
	$cal_lph = 0;
if($_POST['conceptos_db_salario']=='true')
	$cal_salario = 1;
else
	$cal_salario = 0;	
if($_POST['conceptos_db_utilidades']=='true')
	$cal_utilidades = 1;
else
	$cal_utilidades = 0;
if($_POST['conceptos_db_prestaciones']=='true')
	$cal_prestaciones = 1;
else
	$cal_prestaciones = 0;
if($_POST['conceptos_db_forzozo']=='true')
	$cal_forzozo = 1;
else
	$cal_forzozo = 0;
if($_POST['conceptos_db_isrl']=='true')
	$cal_isrl = 1;
else
	$cal_isrl = 0;*/
//
$Sql="
			SELECT 
				calculo_rrhh.id_calculo_rrhh,
				calculo_rrhh.nombre
			FROM 
				calculo_rrhh
			INNER JOIN
				concep_cal_rrhh
			ON
				calculo_rrhh.id_calculo_rrhh = concep_cal_rrhh.id_calculo_rrhh
			WHERE
				concep_cal_rrhh.id_conceptos = '$_POST[conceptos_db_id_concepto]'
			AND
				estatu = '1'
			AND
				calculo_rrhh.id_organismo = $_SESSION[id_organismo]	
";
$row =& $conn->Execute($Sql);
while(!$row->EOF){
	$arreglo.= $row->fields("id_calculo_rrhh")."*";
	$row->MoveNext();
}
echo $arreglo;
?>