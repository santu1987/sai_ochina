<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$secuencia = $_POST[presupuesto_traspaso_pr_secuencia];
$precopromiso = $_POST[traspaso_entre_partida_pr_precopromiso];

$partida_cedente = $_POST[presupuesto_traspaso_pr_codigo_partida_cedente];
$partida_cede =explode(".",$partida_cedente);

$partida_receptora = $_POST[presupuesto_traspaso_pr_codigo_partida_receptor];
$partida_recive =explode(".",$partida_receptora);
//*********************************
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Enero')
	$posicion = 1;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Febrero')
	$posicion = 2;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Marzo')
	$posicion = 3;	
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Abril')
	$posicion = 4;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Mayo')
	$posicion = 5;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Junio')
	$posicion = 6;	
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Julio')
	$posicion = 7;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Agosto')
	$posicion = 8;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Septiembre')
	$posicion = 9;	
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Octubre')
	$posicion = 10;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Noviembre')
	$posicion = 11;
if ($_POST[presupuesto_traspaso_pr_mes_cendente] == 'Diciembre')
	$posicion = 12;	
//----
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Enero')
	$posicion_re = 1;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Febrero')
	$posicion_re = 2;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Marzo')
	$posicion_re = 3;	
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Abril')
	$posicion_re = 4;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Mayo')
	$posicion_re = 5;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Junio')
	$posicion_re = 6;	
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Julio')
	$posicion_re = 7;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Agosto')
	$posicion_re = 8;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Septiembre')
	$posicion_re = 9;	
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Octubre')
	$posicion_re = 10;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Noviembre')
	$posicion_re = 11;
if ($_POST[presupuesto_traspaso_pr_mes_receptor] == 'Diciembre')
	$posicion_re = 12;			
//*****************************
$monto_cedente = str_replace(".","",$_POST[presupuesto_traspaso_pr_monto_cedente]);
$monto = str_replace(".","",$_POST[presupuesto_traspaso_pr_monto]);
$monto_totaln = str_replace(".","",$_POST[presupuesto_traspaso_pr_monto_total_receptor]);
$monto_total = $monto - $monto_cedente;
//**********
if ($_POST[presupuesto_traspaso_pr_id_proyecto_cedente] != "")
	$proyecto = $_POST[presupuesto_traspaso_pr_id_proyecto_cedente];
else
	$proyecto = 0;
if ($_POST[presupuesto_traspaso_pr_id_central_cedente] != "")
$accion_central = $_POST[presupuesto_traspaso_pr_id_central_cedente];
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_bus1="
SELECT  

	monto_traspasado[".$posicion."] AS monto_traspasado
FROM 
	\"presupuesto_ejecutadoR\"
WHERE
	(id_organismo = ".$_SESSION['id_organismo'].") 
AND 
	(id_accion_centralizada = ".$accion_central.") 
AND
	(id_unidad_ejecutora = ".$_POST[presupuesto_traspaso_pr_id_unidad_cedente].") 
AND 
	(id_accion_especifica = ".$_POST[presupuesto_traspaso_pr_id_especifica_cedente].") 
AND
	(id_proyecto = ".$proyecto.") 
AND 
	(ano = '".date("Y")."') 
AND
	(partida = '".$partida_cede[0]."')
 AND 
	(generica = '".$partida_cede[1]."') 
AND
	(especifica = '".$partida_cede[2]."') 
AND 
	(sub_especifica = '".$partida_cede[3]."')
";
$row_bu = $conn->Execute($sql_bus1);
//die ($sql_bus1);
if(!$row_bu->EOF)
	$monto_pre_cede = $row_bu->fields("monto_traspasado");
else
	$monto_pre_cede = 0;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_bus2="
SELECT  

	monto_traspasado[".$posicion_re."] AS monto_traspasado
FROM 
	\"presupuesto_ejecutadoR\"
WHERE
	(id_organismo = ".$_SESSION['id_organismo'].") 
AND 
	(id_accion_centralizada = ".$accion_central.") 
AND
	(id_unidad_ejecutora = ".$_POST[presupuesto_traspaso_pr_id_unidad_cedente].") 
AND 
	(id_accion_especifica = ".$_POST[presupuesto_traspaso_pr_id_especifica_receptor].") 
AND
	(id_proyecto = ".$proyecto.") 
AND 
	(ano = '".date("Y")."') 
AND
	(partida = '".$partida_recive[0]."')
 AND 
	(generica = '".$partida_recive[1]."') 
AND
	(especifica = '".$partida_recive[2]."') 
AND 
	(sub_especifica = '".$partida_recive[3]."')
";
$row_bus = $conn->Execute($sql_bus2);
//die ($sql_bus2);
if(!$row_bus->EOF)
	$monto_pre_resive = $row_bus->fields("monto_traspasado");
else
	$monto_pre_resive = 0;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$total_cede = $monto_pre_cede + ($monto_cedente * -1);
$total_reci = $monto_pre_resive + $monto_cedente;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
				anio,
				pre_compromiso
			)
   			 VALUES (
			 	".$_SESSION['id_organismo'].", 
				".$_POST[presupuesto_traspaso_pr_id_unidad_cedente].", 
				".$proyecto.", 
				".$accion_central.", 
				".$_POST[presupuesto_traspaso_pr_id_especifica_cedente].", 
				'".$partida_cede[0]."', 
				'".$partida_cede[1]."', 
				'".$partida_cede[2]."', 
				'".$partida_cede[3]."',
				".$_POST[presupuesto_traspaso_pr_id_unidad_cedente].", 
				".$proyecto.", 
				".$accion_central.", 
				".$_POST[presupuesto_traspaso_pr_id_especifica_receptor].", 
				'".$partida_recive[0]."', 
				'".$partida_recive[1]."', 
				'".$partida_recive[2]."', 
				'".$partida_recive[3]."',  
				0, 
				'".$_POST[presupuesto_traspaso_pr_mes_cendente]."',
				'".str_replace(",",".",$monto_cedente)."',
				'".$_POST[presupuesto_traspaso_pr_mes_receptor]."',
				'".str_replace(",",".",$monto_cedente)."',
				'".$_SESSION['id_usuario']."',
				'".$fecha."',
				'".$_POST[presupuesto_traspaso_pr_comentario]."',
				'".$fecha."',
				".$_SESSION['id_usuario'].",
				'".date("Y")."',
				'".$precopromiso."'
			);
			UPDATE 
						\"presupuesto_ejecutadoR\"
					SET  
						monto_traspasado[".$posicion."]='".str_replace(",",".",$total_cede)."',
						ultimo_usuario=".$_SESSION['id_usuario'].", 
						fecha_actualizacion='".$fecha."'
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
						(id_unidad_ejecutora = ".$_POST[presupuesto_traspaso_pr_id_unidad_cedente].") AND (id_accion_especifica = ".$_POST[presupuesto_traspaso_pr_id_especifica_cedente].") AND
						(id_proyecto = ".$proyecto.") AND (ano = '".date("Y")."') AND
						(partida = '".$partida_cede[0]."') AND (generica = '".$partida_cede[1]."') AND
						(especifica = '".$partida_cede[2]."') AND (sub_especifica = '".$partida_cede[3]."');
			";
			
			$sqlupdate = "
					UPDATE 
						\"presupuesto_ejecutadoR\"
					SET  
						
						monto_traspasado[".$posicion_re."]='".str_replace(",",".",$total_reci)."',
						ultimo_usuario=".$_SESSION['id_usuario'].", 
						fecha_actualizacion='".$fecha."'
					WHERE
						(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central.") AND
						(id_unidad_ejecutora = ".$_POST[presupuesto_traspaso_pr_id_unidad_cedente].") AND (id_accion_especifica = ".$_POST[presupuesto_traspaso_pr_id_especifica_receptor].") AND
						(id_proyecto = ".$proyecto.") AND (ano = '".date("Y")."') AND
						(partida = '".$partida_recive[0]."') AND (generica = '".$partida_recive[1]."') AND
						(especifica = '".$partida_recive[2]."') AND (sub_especifica = '".$partida_recive[3]."');
						
						UPDATE 
							\"orden_compra_servicioD\"
						SET	 
							disponible=1
						WHERE 
							(partida = '".$partida_recive[0]."') AND (generica = '".$partida_recive[1]."') AND
						(especifica = '".$partida_recive[2]."') AND (subespecifica = '".$partida_recive[3]."')
						AND
							numero_pre_orden= '".$precopromiso."'
					";
					
					
if (!$conn->Execute($sql)) {
	die ('Error al Registrar: '.$conn->ErrorMsg());
	//die ($sql_bus1);
}else{
	if (!$conn->Execute($sqlupdate)) {
		//die ($sqlupdate);
		//die ($sql_bus1);
		die ('Error al Registrar1: '.$conn->ErrorMsg());
	}else{
		die("Registrado");
	}
}
?>
