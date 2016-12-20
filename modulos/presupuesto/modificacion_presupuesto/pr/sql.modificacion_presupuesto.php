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
		
	$monto_cedente = str_replace(".","",$_POST[modificacion_presupuesto_db_monto_cedente]);
	$monto_total = str_replace(".","",$_POST[modificacion_presupuesto_db_monto_total_disponible]);
	
$sqlSecuencia = "SELECT count(secuencia) FROM modificacion_ley 
				WHERE 
					(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
					(id_unidad_ejecutora = ".$_POST[modificacion_presupuesto_db_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[modificacion_presupuesto_db_accion_especifica_id].") AND
					(id_proyecto = ".$proyecto.") AND (ano = '".$anio."') AND
					(partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND
					(especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."') AND (mes_modificado = '".$_POST[modificacion_presupuesto_db_mes_cendente]."')
				";
$rowSecuencia=& $conn->Execute($sqlSecuencia);
if (!$rowSecuencia->EOF)
{
	$Secuencia = $rowSecuencia->fields("count");
	$Secuencia = $Secuencia + 1;
}
$sqlbusca_modi = "
					SELECT monto_modificado[".$posicion."] FROM
						\"presupuesto_ejecutadoR\"
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
						(id_unidad_ejecutora = ".$_POST[modificacion_presupuesto_db_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[modificacion_presupuesto_db_accion_especifica_id].") AND
						(id_proyecto = ".$proyecto.") AND (ano = '".$anio."') AND
						(partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND
						(especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')
					";
					$rowbusca_modi=& $conn->Execute($sqlbusca_modi);
					//die ($sqlbusca_modi);
if (!$rowbusca_modi->EOF)
{
	$monto_modificado = $rowbusca_modi->fields("monto_modificado");
	$monto = $monto_modificado + str_replace(",",".",$monto_cedente);
	
}

		$sql = "	
				INSERT INTO 
					modificacion_ley(
						id_organismo, 
						id_accion_centralizada, 
						id_unidad_ejecutora, 
						id_accion_especifica, 
						id_proyecto, 
						ano, 
						partida, 
						generica, 
						especifica, 
						sub_especifica,
						secuencia, 
						mes_modificado,
						monto, 
						monto_total,
						comentario, 
						fecha_actualizacion, 
						ultimo_usuario)
				 VALUES (
						".$_SESSION['id_organismo'].", 
						".$accion_central.", 
						".$_POST[modificacion_presupuesto_db_unidad_ejecutora].", 
						".$_POST[modificacion_presupuesto_db_accion_especifica_id].", 
						".$proyecto .", 
						".$anio.", 
						'".$partida[0]."', 
						'".$partida[1]."', 
						'".$partida[2]."', 
						'".$partida[3]."',
						".$Secuencia.", 
						'".$_POST[modificacion_presupuesto_db_mes_cendente]."', 
						'".str_replace(",",".",$monto_cedente)."',
						'".str_replace(",",".",$monto_total)."', 
						'".$_POST[modificacion_presupuesto_db_comentario]."', 
						'".$fecha."', 
						".$_SESSION['id_usuario'].")
					";
					$sqlupdate = "
					UPDATE 
						\"presupuesto_ejecutadoR\"
					SET  
						monto_modificado[".$posicion."]='".$monto."', 
						ultimo_usuario=".$_SESSION['id_usuario'].", 
						fecha_actualizacion='".$fecha."'
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
						(id_unidad_ejecutora = ".$_POST[modificacion_presupuesto_db_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[modificacion_presupuesto_db_accion_especifica_id].") AND
						(id_proyecto = ".$proyecto.") AND (ano = '".$anio."') AND
						(partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND
						(especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')
					";
					
					if (!$conn->Execute($sql)) {
					//	echo (($repetido)?$msgExiste:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
						echo $sql;
					}else{
							if (!$conn->Execute($sqlupdate)) {
							echo ('Error al Actualizar: '.$conn->ErrorMsg().'<br />');
							}else{
								echo 'Registrado';
								//echo $sqlupdate;
							}
					}
?>