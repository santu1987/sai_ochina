<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$anio = date("Y");
$partida_toda = $_POST[modificacion_presupuesto_db_partida_numero];
$partida =explode(".",$partida_toda);
$proyecto = $_POST['modificacion_presupuesto_db_proyecto_id'];
$accion_central = $_POST['modificacion_presupuesto_db_accion_central_id'];

if ($proyecto  =="")
	$proyecto  = 0;
if ($accion_central  =="")
	$accion_central  = 0;
//getObj('modificacion_presupuesto_db_operacion').checked ==true
$operacion = $_POST[modificacion_presupuesto_db_operacion];
if ($operacion==2)
	$signo = '-';

//***********************
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
//*****************************

	$monto_cedente = str_replace(".","",$_POST[modificacion_presupuesto_db_monto_cedente]);
	$monto_total = str_replace(".","",$_POST[modificacion_presupuesto_db_monto_total]);
/*
$sqlBus = "SELECT  count(id_modificacion_ley) AS cantidad FROM modificacion_ley WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND ((id_accion_centralizada = ".$accion_central.") OR (id_proyecto = ".$proyecto.")) AND (id_unidad_ejecutora = ".$_POST[modificacion_presupuesto_db_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[modificacion_presupuesto_db_accion_especifica_id].")  AND (ano = '".$anio."') AND (partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND (especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')";
$row=& $conn->Execute($sqlBus);

if(!$row->EOF)
{
	if($row->fields("cantidad")==0) 
	{*/
		$sql = "	
				UPDATE 
					modificacion_ley SET
						id_accion_centralizada = ".$accion_central.", 
						id_unidad_ejecutora = ".$_POST[modificacion_presupuesto_db_unidad_ejecutora].", 
						id_accion_especifica = ".$_POST[modificacion_presupuesto_db_accion_especifica_id].", 
						id_proyecto = ".$proyecto .", 
						partida = '".$partida[0]."', 
						generica = '".$partida[1]."', 
						especifica ='".$partida[2]."', 
						sub_especifica = '".$partida[3]."', 
						mes_modificado = '".$_POST[modificacion_presupuesto_db_mes_cendente]."', 
						monto = '".str_replace(",",".",$monto_cedente)."', 
						monto_total = '".str_replace(",",".",$monto_total)."',  
						comentario = '".$_POST[modificacion_presupuesto_db_comentario]."',  
						fecha_actualizacion = '".$fecha."',  
						ultimo_usuario = ".$_SESSION['id_usuario']."
					WHERE	
						(id_modificacion_ley = ".$_POST['modificacion_presupuesto_db_id'].")
					";
					
			$sqlupdate = "
					UPDATE 
						\"presupuesto_ejecutadoR\"
					SET  
						monto_presupuesto[".$posicion."]='".str_replace(",",".",$monto_total)."',
						monto_modificado[".$posicion."]='".str_replace(",",".",$monto_cedente)."', 
						ultimo_usuario=".$_SESSION['id_usuario'].", 
						fecha_actualizacion='".$fecha."'
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
						(id_unidad_ejecutora = ".$_POST[modificacion_presupuesto_db_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[modificacion_presupuesto_db_accion_especifica_id].") AND
						(id_proyecto = ".$proyecto.") AND (ano = '".$anio."') AND
						(partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND
						(especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')
					";
					//echo $sqlupdate;
					if (!$conn->Execute($sql)) {
						echo (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
					//	echo $sql;
					}else{
						
						if (!$conn->Execute($sqlupdate)) {
							echo (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
							}else{
							echo 'Actualizado';
							//echo $sqlupdate;
							}
					}

?>