<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$fecha = date('Y');

$unidad =	$_POST['traspaso_entre_partida_db_unidad_ejecutora'];
$unidad_es = $_POST['traspaso_entre_partida_db_accion_especifica_id'];
$mes =		$_POST['traspaso_entre_partida_db_mes_cendente'];
$partida_toda = $_POST['traspaso_entre_partida_db_partida_numero'];
$partida =explode(".",$partida_toda);

if ($_POST[traspaso_entre_partida_db_proyecto_id] != "")
	$proyecto = $_POST[traspaso_entre_partida_db_proyecto_id];
else
	$proyecto = 0;
if ($_POST[traspaso_entre_partida_db_accion_central_id] != "")
$accion_central = $_POST[traspaso_entre_partida_db_accion_central_id];
else
	$accion_central = 0;
	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'enero')
	$posicion = 1;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'febrero')
	$posicion = 2;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'marzo')
	$posicion = 3;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'abril')
	$posicion = 4;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'mayo')
	$posicion = 5;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'junio')
	$posicion = 6;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'julio')
	$posicion = 7;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'agosto')
	$posicion = 8;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'septiembre')
	$posicion = 9;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'octubre')
	$posicion = 10;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'noviembre')
	$posicion = 11;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'diciembre')
	$posicion = 12;		

$Sql="
			SELECT  
				(\"presupuesto_ejecutadoR\".monto_presupuesto[".$posicion."]+
				\"presupuesto_ejecutadoR\".monto_modificado[".$posicion."]+
				\"presupuesto_ejecutadoR\".monto_traspasado[".$posicion."]-
				\"presupuesto_ejecutadoR\".monto_comprometido[".$posicion."]) AS monto_presupuesto
			FROM 
				\"presupuesto_ejecutadoR\"
			INNER JOIN
				presupuesto_ley
			ON
				(
				\"presupuesto_ejecutadoR\".id_unidad_ejecutora =presupuesto_ley.id_unidad_ejecutora
				AND
				\"presupuesto_ejecutadoR\".id_accion_centralizada =presupuesto_ley.id_accion_central
				AND
				\"presupuesto_ejecutadoR\".id_proyecto =presupuesto_ley.id_proyecto
				AND
				\"presupuesto_ejecutadoR\".id_accion_especifica =presupuesto_ley.id_accion_especifica
				AND
				\"presupuesto_ejecutadoR\".partida =presupuesto_ley.partida
				AND
				\"presupuesto_ejecutadoR\".generica =presupuesto_ley.generica
				AND
				\"presupuesto_ejecutadoR\".especifica =presupuesto_ley.especifica
				AND
				\"presupuesto_ejecutadoR\".sub_especifica =presupuesto_ley.sub_especifica
				AND
				\"presupuesto_ejecutadoR\".id_organismo =presupuesto_ley.id_organismo
				AND
				\"presupuesto_ejecutadoR\".ano =presupuesto_ley.anio
				)
			WHERE
				(presupuesto_ley.id_unidad_ejecutora = ".$unidad .")
				AND
				(presupuesto_ley.id_accion_especifica =".$unidad_es .")
				AND
				(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
				AND
				(presupuesto_ley.anio='".$fecha."') 
				AND
				(presupuesto_ley.id_accion_central=".$accion_central.")
				AND
				(presupuesto_ley.id_proyecto='".$proyecto."') 
				AND
				(presupuesto_ley.partida='".$partida[0]."')
				AND
				(presupuesto_ley.generica='".$partida[1]."') 
				AND
				(presupuesto_ley.especifica='".$partida[2]."')
				AND
				(presupuesto_ley.sub_especifica='".$partida[3]."') 
";
$row=& $conn->Execute($Sql);
if (!$row->EOF) 
{
		echo number_format($row->fields("monto_presupuesto"),2,',','.');
}//echo $Sql;
?>