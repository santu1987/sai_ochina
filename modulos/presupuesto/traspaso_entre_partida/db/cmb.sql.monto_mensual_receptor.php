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
$unidad_es = $_POST['traspaso_entre_partida_db_accion_especifica_id_receptor'];
$mes =		$_POST['traspaso_entre_partida_db_mes_receptor'];
$partida_toda = $_POST['traspaso_entre_partida_db_partida_numero_receptor'];
$partida =explode(".",$partida_toda);

if ($_POST[traspaso_entre_partida_db_proyecto_id] != "")
	$proyecto = $_POST[traspaso_entre_partida_db_proyecto_id];
else
	$proyecto = 0;
if ($_POST[traspaso_entre_partida_db_accion_central_id] != "")
$accion_central = $_POST[traspaso_entre_partida_db_accion_central_id];
else
	$accion_central = 0;

if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'enero')
	$posicion = 1;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'febrero')
	$posicion = 2;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'marzo')
	$posicion = 3;	
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'abril')
	$posicion = 4;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'mayo')
	$posicion = 5;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'junio')
	$posicion = 6;	
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'julio')
	$posicion = 7;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'agosto')
	$posicion = 8;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'septiembre')
	$posicion = 9;	
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'octubre')
	$posicion = 10;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'noviembre')
	$posicion = 11;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'diciembre')
	$posicion = 12;		

$Sql="
			SELECT  
				(\"presupuesto_ejecutadoR\".monto_presupuesto[".$posicion."]+
				\"presupuesto_ejecutadoR\".monto_modificado[".$posicion."]+
				\"presupuesto_ejecutadoR\".monto_traspasado[".$posicion."]-
				\"presupuesto_ejecutadoR\".monto_precomprometido[".$posicion."]) AS monto_presupuesto
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
				(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad .")
				AND
				(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es .")
				AND
				(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
				AND
				(\"presupuesto_ejecutadoR\".ano='".$fecha."') 
				AND
				(\"presupuesto_ejecutadoR\".id_accion_centralizada=".$accion_central.")
				AND
				(\"presupuesto_ejecutadoR\".id_proyecto='".$proyecto."') 
				AND
				(\"presupuesto_ejecutadoR\".partida='".$partida[0]."')
				AND
				(\"presupuesto_ejecutadoR\".generica='".$partida[1]."') 
				AND
				(\"presupuesto_ejecutadoR\".especifica='".$partida[2]."')
				AND
				(\"presupuesto_ejecutadoR\".sub_especifica='".$partida[3]."') 
";
$row=& $conn->Execute($Sql);

		
		echo number_format($row->fields("monto_presupuesto"),2,',','.');

?>