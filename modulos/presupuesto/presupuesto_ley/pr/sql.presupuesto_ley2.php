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
if ($_POST[presupuesto_aprobado_pr_id_accion_c] == "")
	$accion_central = 0;
else
	$accion_central = $_POST[presupuesto_aprobado_pr_id_accion_c];
if ($_POST[presupuesto_aprobado_pr_id_proyecto] == "")
	$proyecto = 0;
else
	$proyecto = $_POST[presupuesto_aprobado_pr_id_proyecto];
	
$partida_toda = $_POST[presupuesto_aprobado_pr_partida];
$partida =explode(".",$partida_toda);
$ano = $_POST[presupuesto_aprobado_pr_ano];
$sql_cierre = " select * from presupuesto_ley where (id_organismo = ".$_SESSION['id_organismo'].") and (anio =".'$ano'.") and (estatus = 1)";
$row_cierre=& $conn->Execute($sql_cierre);
//echo $sqlfecha_cierre; presupuesto_aprobado_pr_id_accion_e
//if(!$row_cierre->EOF){

	//***********************
	
						$monto_enero = str_replace(".","",$_POST[presupuesto_aprobado_pr_enero]);
						$monto_febrero = str_replace(".","",$_POST[presupuesto_aprobado_pr_febrero]);
						$monto_marzo = str_replace(".","",$_POST[presupuesto_aprobado_pr_marzo]);
						$monto_abril = str_replace(".","",$_POST[presupuesto_aprobado_pr_abril]);
						$monto_mayo = str_replace(".","",$_POST[presupuesto_aprobado_pr_mayo]);
						$monto_junio = str_replace(".","",$_POST[presupuesto_aprobado_pr_junio]);
						$monto_julio = str_replace(".","",$_POST[presupuesto_aprobado_pr_julio]);
						$monto_agosto = str_replace(".","",$_POST[presupuesto_aprobado_pr_agosto]);
						$monto_septiembre = str_replace(".","",$_POST[presupuesto_aprobado_pr_septiembre]);
						$monto_octubre = str_replace(".","",$_POST[presupuesto_aprobado_pr_octubre]);
						$monto_noviembre = str_replace(".","",$_POST[presupuesto_aprobado_pr_noviembre]);
						$monto_diciembre = str_replace(".","",$_POST[presupuesto_aprobado_pr_diciembre]);
	//***************
	$sqlcerrado = "SELECT  count(estatus) as estatus FROM presupuesto_ley WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (anio = '".$ano."') AND (id_unidad_ejecutora = ".$_POST[presupuesto_aprobado_pr_id_unidad].") AND (estatus = 1) ";
	//$rowww=& $conn->Execute($sqlcerrado);
	//echo ($sqlcerrado);
	//if($rowww->fields('estatus') == 0){
	
	
	
		$sqlBus = "SELECT  * FROM \"presupuesto_ejecutadoR\" WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND ((id_accion_centralizada = ".$accion_central.") OR (id_proyecto = ".$proyecto.")) AND (id_unidad_ejecutora = ".$_POST[presupuesto_aprobado_pr_id_unidad].") AND (id_accion_especifica = ".$_POST[presupuesto_aprobado_pr_id_accion_e].")   AND (partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND (especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')";
		$row=& $conn->Execute($sqlBus);
		if($row->EOF){
			$sql = "	
					INSERT INTO 
						\"presupuesto_ejecutadoR\"(
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
							monto_presupuesto,			
							comentario, 
							fecha_actualizacion, 
							ultimo_usuario)
					 VALUES (
							".$_SESSION['id_organismo'].", 
							".$accion_central.", 
							".$_POST[presupuesto_aprobado_pr_id_unidad].", 
							".$_POST[presupuesto_aprobado_pr_id_accion_e].", 
							".$proyecto.", 
							'".$_POST[presupuesto_aprobado_pr_ano]."', 
							'".$partida[0]."', 
							'".$partida[1]."', 
							'".$partida[2]."', 
							'".$partida[3]."', 
							'{".str_replace(",",".",$monto_enero).",
								".str_replace(",",".",$monto_febrero).",
								".str_replace(",",".",$monto_marzo).",
								".str_replace(",",".",$monto_abril).",
								".str_replace(",",".",$monto_mayo).",
								".str_replace(",",".",$monto_junio).",
								".str_replace(",",".",$monto_julio).",
								".str_replace(",",".",$monto_agosto).",
								".str_replace(",",".",$monto_septiembre).",
								".str_replace(",",".",$monto_octubre).",
								".str_replace(",",".",$monto_noviembre).",
								".str_replace(",",".",$monto_diciembre)."}',
							'".$_POST[presupuesto_aprobado_pr_comentario]."', 
							'".$fecha."', 
							".$_SESSION['id_usuario']."
						)";
		
			
			if (!$conn->Execute($sql)) 
				die ($sql); 
				//die ('Error al Insertar: '.$conn->ErrorMsg().'<br />');
			else
				die("Registrado");
			}
		else
			die('Existe');
	//}else
		//die("cerrado");
/*}else{
	die ($sql_cierre);
}*/
?>