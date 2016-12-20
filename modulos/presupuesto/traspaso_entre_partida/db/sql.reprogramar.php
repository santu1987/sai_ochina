<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$partida_cedente = $_POST[traspaso_entre_partida_db_partida_numero];
$partida_cede =explode(".",$partida_cedente);

$partida_receptora = $_POST[traspaso_entre_partida_db_partida_numero_receptor];
$partida_recive =explode(".",$partida_receptora);
//*********************************
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Enero')
	$posicion = 1;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Febrero')
	$posicion = 2;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Marzo')
	$posicion = 3;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Abril')
	$posicion = 4;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Mayo')
	$posicion = 5;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Junio')
	$posicion = 6;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Julio')
	$posicion = 7;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Agosto')
	$posicion = 8;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Septiembre')
	$posicion = 9;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Octubre')
	$posicion = 10;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Noviembre')
	$posicion = 11;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'Diciembre')
	$posicion = 12;	
//----
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'enero')
	$posicion_re = 1;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'febrero')
	$posicion_re = 2;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'marzo')
	$posicion_re = 3;	
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'abril')
	$posicion_re = 4;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'mayo')
	$posicion_re = 5;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'junio')
	$posicion_re = 6;	
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'julio')
	$posicion_re = 7;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'agosto')
	$posicion_re = 8;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'septiembre')
	$posicion_re = 9;	
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'octubre')
	$posicion_re = 10;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'noviembre')
	$posicion_re = 11;
if ($_POST[traspaso_entre_partida_db_mes_receptor] == 'diciembre')
	$posicion_re = 12;			
//*****************************
$monto_cedente = str_replace(".","",$_POST[traspaso_entre_partida_db_monto_cedente]);
$monto = str_replace(".","",$_POST[traspaso_entre_partida_db_monto]);
$monto_totaln = str_replace(".","",$_POST[traspaso_entre_partida_db_monto_total_receptor]);
$monto_total = $monto - $monto_cedente;
//**********
if ($_POST[traspaso_entre_partida_db_proyecto_id] != "")
	$proyecto = $_POST[traspaso_entre_partida_db_proyecto_id];
else
	$proyecto = 0;
if ($_POST[traspaso_entre_partida_db_accion_central_id] != "")
$accion_central = $_POST[traspaso_entre_partida_db_accion_central_id];
else
	$accion_central = 0;

if ($_POST[traspaso_entre_partida_db_proyecto_id_receptor] != "")
	$proyecto_receptor = $_POST[traspaso_entre_partida_db_proyecto_id_receptor];
else
	$proyecto_receptor = 0;
	
if ($_POST[traspaso_entre_partida_db_accion_central_id_receptor] != "")
$accion_central_receptor = $_POST[traspaso_entre_partida_db_accion_central_id_receptor];
else
	$accion_central_receptor = 0;
			$monto_total = str_replace(".","",$monto_total);
			$monto_cedente = str_replace(".","",$monto_cedente);

	$sql = "	
			INSERT INTO 
			traspaso_entre_partidas
			(
				id_organismo, 
				id_unidad_cedente, 
				id_proyecto_cedente, 
				id_accion_centralizada_cedente, 
				id_accion_especifica_cedente,
				partida_cedente, 
				generica_cedente, 
				especifica_cedente, 
				subespecifica_cedente, 
				id_unidad_receptora, 
				id_proyecto_receptora, 
				id_accion_centralizada_receptora, 
				id_accion_especifica_receptora, 
				partida_receptora, 
				generica_receptora, 
				especifica_receptora, 
				subespecifica_receptora, 
				secuencia, 
				mes_cedente, 
				monto_cedente, 
				mes_receptora, 
				monto_receptora, 
				usuario_traspaso, 
				fecha_traspaso, 
				comentario, 
				fecha_actualizacion, 
				ultimo_usuario, 
				anio
			)
   			 VALUES (
			 	".$_SESSION['id_organismo'].", 
				".$_POST[traspaso_entre_partida_db_unidad_ejecutora].", 
				".$proyecto.", 
				".$accion_central.", 
				".$_POST[traspaso_entre_partida_db_accion_especifica_id].", 
				'".$partida_cede[0]."', 
				'".$partida_cede[1]."', 
				'".$partida_cede[2]."', 
				'".$partida_cede[3]."',
				".$_POST[traspaso_entre_partida_db_unidad_ejecutora_receptor].", 
				".$proyecto_receptor.", 
				".$accion_central_receptor.", 
				".$_POST[traspaso_entre_partida_db_accion_especifica_id_receptor].", 
				'".$partida_recive[0]."', 
				'".$partida_recive[1]."', 
				'".$partida_recive[2]."', 
				'".$partida_recive[3]."',  
				0, 
				'".$_POST[traspaso_entre_partida_db_mes_cendente]."',
				'".str_replace(",",".",$monto_cedente)."',
				'".$_POST[traspaso_entre_partida_db_mes_receptor]."',
				'".str_replace(",",".",$monto_cedente)."',
				'".$_SESSION['id_usuario']."',
				'".$fecha."',
				'".$_POST[traspaso_entre_partida_db_comentario]."',
				'".$fecha."',
				".$_SESSION['id_usuario'].",
				'".$_POST[traspaso_entre_partida_db_ano]."'
			)";
			$sgino= -1;
			$monto_cedente1 = $monto_cedente * $sgino;
			$sqlupdate = "
					UPDATE 
						\"presupuesto_ejecutadoR\"
					SET  
						
						monto_traspasado[".$posicion."]='".str_replace(",",".",$monto_cedente1)."', 
						ultimo_usuario=".$_SESSION['id_usuario'].", 
						fecha_actualizacion='".$fecha."'
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
						(id_unidad_ejecutora = ".$_POST[traspaso_entre_partida_db_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[traspaso_entre_partida_db_accion_especifica_id].") AND
						(id_proyecto = ".$proyecto.") AND (ano = '".date("Y")."') AND
						(partida = '".$partida_cede[0]."') AND (generica = '".$partida_cede[1]."') AND
						(especifica = '".$partida_cede[2]."') AND (sub_especifica = '".$partida_cede[3]."')
					";
					
			$sqlupdatead = "
					UPDATE 
						\"presupuesto_ejecutadoR\"
					SET  
						
						monto_traspasado[".$posicion_re."]='".str_replace(",",".",$monto_cedente)."', 
						ultimo_usuario=".$_SESSION['id_usuario'].", 
						fecha_actualizacion='".$fecha."'
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_receptor.") AND
						(id_unidad_ejecutora = ".$_POST[traspaso_entre_partida_db_unidad_ejecutora_receptor].") AND (id_accion_especifica = ".$_POST[traspaso_entre_partida_db_accion_especifica_id_receptor].") AND
						(id_proyecto = ".$proyecto_receptor.") AND (ano = '".date("Y")."') AND
						(partida = '".$partida_recive[0]."') AND (generica = '".$partida_recive[1]."') AND
						(especifica = '".$partida_recive[2]."') AND (sub_especifica = '".$partida_recive[3]."')
					";
	//die ($sqlupdate ."     ".$sqlupdatead);				
if (!$conn->Execute($sql)) {
	die ('Error al Registrar: '.$conn->ErrorMsg());
}else{
	if (!$conn->Execute($sqlupdate)) {
		die ($sqlupdate);
		die ('Error al Registrar1: '.$conn->ErrorMsg());
	}
	if (!$conn->Execute($sqlupdatead)) {
			//die ($sqlupdatead);
			die ('Error al Registrar2: '.$conn->ErrorMsg());
		}else{
		die("Registrado");
	}
}
?>
