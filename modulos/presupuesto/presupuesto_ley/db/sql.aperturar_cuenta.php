<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Preupuesto ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$fecha2 = date("Y-m-d");
if ($_POST[aperturar_cuenta_pr_accion_central_id] == "")
	$accion_central = 0;
else
	$accion_central = $_POST[aperturar_cuenta_pr_accion_central_id];
if ($_POST[aperturar_cuenta_pr_proyecto_id] == "")
	$proyecto = 0;
else
	$proyecto = $_POST[aperturar_cuenta_pr_proyecto_id];
	
$partida_toda = $_POST[aperturar_cuenta_pr_partida_numero];
$partida =explode(".",$partida_toda);
$ano = $_POST[aperturar_cuenta_pr_anio];
//echo $sqlfecha_cierre;
	//***********************
	
	
	
	
		$sqlBus = "SELECT  * FROM \"presupuesto_ejecutadoR\" WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND ((id_accion_centralizada = ".$accion_central.") OR (id_proyecto = ".$proyecto.")) AND (id_unidad_ejecutora = ".$_POST[aperturar_cuenta_pr_unidad_ejecutora_id].") AND (id_accion_especifica = ".$_POST[aperturar_cuenta_pr_accion_especifica].")  AND (ano = '".$_POST[aperturar_cuenta_pr_anio]."')  AND (partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND (especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')";
	//die ($sqlBus);	
		$row=& $conn->Execute($sqlBus);
		if($row->EOF){
			$sql2="	
					INSERT INTO
						\"presupuesto_ejecutadoR\" (
							id_organismo,
							id_unidad_ejecutora,
							id_proyecto,
							id_accion_centralizada,
							id_accion_especifica,
							ano,
							partida,
							generica,
							especifica,
							sub_especifica,
							monto_presupuesto,
							monto_precomprometido,
							monto_comprometido,
							monto_causado,
							monto_traspasado,
							monto_modificado,
							monto_pagado,
							ultimo_usuario,
							fecha_actualizacion
						)
						VALUES(
							".$_SESSION['id_organismo']." ,
							".$_POST[aperturar_cuenta_pr_unidad_ejecutora_id]." ,
							".$proyecto." ,
							".$accion_central." ,
							".$_POST[aperturar_cuenta_pr_accion_especifica].",
							".$_POST[aperturar_cuenta_pr_anio].",
							'".$partida[0]."',
							'".$partida[1]."',
							'".$partida[2]."',
							'".$partida[3]."',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
							$_SESSION[id_usuario],
							'".date("Y-m-d H:i:s")."'
						)			
					";
		
		
			if (!$conn->Execute($sql2)) 
				die ($sql2); 
				//die ('Error al Insertar: '.$conn->ErrorMsg().'<br />');
			else
				die("Registrado");
			}
		else
			die("Existe");
			
			
			//die ($sql); 	
?>