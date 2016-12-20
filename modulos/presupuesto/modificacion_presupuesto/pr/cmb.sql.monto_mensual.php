<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$fecha = date('Y');

$unidad =	 $_POST['modificacion_presupuesto_db_unidad_ejecutora'];
$unidad_es = $_POST['modificacion_presupuesto_db_accion_especifica_id'];
$mes =		 $_POST['modificacion_presupuesto_db_mes_cendente'];
$partida2 =	 $_POST['modificacion_presupuesto_db_partida_numero'];
$partida =	 explode(".",$partida2);

if( $_POST['modificacion_presupuesto_db_proyecto_id']!=""){
	$proyecto =	 $_POST['modificacion_presupuesto_db_proyecto_id'];
}else{
	$proyecto =	 0;
}

if( $_POST['modificacion_presupuesto_db_accion_central_id']!=""){
	$accion_central =	 $_POST['modificacion_presupuesto_db_accion_central_id'];
}else{
	$accion_central =	 0;
}
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'enero')
	$posicion = 1;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'febrero')
	$posicion = 2;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'marzo')
	$posicion = 3;	
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'abril')
	$posicion = 4;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'mayo')
	$posicion = 5;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'junio')
	$posicion = 6;	
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'julio')
	$posicion = 7;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'agosto')
	$posicion = 8;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'septiembre')
	$posicion = 9;	
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'octubre')
	$posicion = 10;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'noviembre')
	$posicion = 11;
if ($_POST[modificacion_presupuesto_db_mes_cendente] == 'diciembre')
	$posicion = 12;		

$Sql="

SELECT  
	\"presupuesto_ejecutadoR\".monto_presupuesto[".$posicion."],
	(\"presupuesto_ejecutadoR\".monto_modificado[".$posicion."]) AS modificado,
	(\"presupuesto_ejecutadoR\".monto_presupuesto[".$posicion."]+
	\"presupuesto_ejecutadoR\".monto_modificado[".$posicion."]+
	\"presupuesto_ejecutadoR\".monto_traspasado[".$posicion."]-
	\"presupuesto_ejecutadoR\".monto_comprometido[".$posicion."]) AS total
FROM 
	\"presupuesto_ejecutadoR\"

 WHERE
	(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad.")
	AND
	(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es.")
	AND
	(\"presupuesto_ejecutadoR\".id_accion_centralizada = ".$accion_central.")
	AND
	(\"presupuesto_ejecutadoR\".id_proyecto =".$proyecto.")
	AND
	(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
	AND
	(\"presupuesto_ejecutadoR\".ano='".$fecha."') 
	AND
	(\"presupuesto_ejecutadoR\".partida = '".$partida[0]."')
	AND
	(\"presupuesto_ejecutadoR\".generica ='".$partida[1]."')
	AND
	(\"presupuesto_ejecutadoR\".especifica='".$partida[2]."')
	AND
	(\"presupuesto_ejecutadoR\".sub_especifica='".$partida[3]."')


";
$row=& $conn->Execute($Sql);

if (!$row->EOF) 
{
	if(($row->fields("modificado") <> 0)&& ($row->fields("modificado") <> '')){
		$monto = $row->fields("modificado") + $row->fields("monto_presupuesto");
	}else{
		$monto  = 0;
	}
	//$total = $row->fields("total") + $row->fields("monto_presupuesto"); 
		$totales = number_format($row->fields("total"),2,',','.')."*". number_format($row->fields("monto_presupuesto"),2,',','.')."*". number_format($monto,2,',','.');
		echo $totales;
		//echo $Sql;
}
?>