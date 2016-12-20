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
if ($_POST[presupuesto_ley_pr_accion_central_id] == "")
	$accion_central = 0;
else
	$accion_central = $_POST[presupuesto_ley_pr_accion_central_id];
if ($_POST[presupuesto_ley_pr_proyecto_id] == "")
	$proyecto = 0;
else
	$proyecto = $_POST[presupuesto_ley_pr_proyecto_id];
	
$partida_toda = $_POST[presupuesto_ley_pr_partida_numero];
$partida =explode(".",$partida_toda);
$ano = $_POST[presupuesto_ley_pr_anio];
$sqlfecha_cierre = "SELECT  fecha_cierre_anteproyecto FROM parametros_presupuesto WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF){
	$fecha_cierre = $row_fecha_cierre->fields('fecha_cierre_anteproyecto');
}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre);
list($dia2,$mes2,$ano2)=split("-",$fecha2);
if (($dia2 <= $dia1) && ($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	//***********************
	
						$monto_enero = str_replace(".","",$_POST[presupuesto_ley_pr_monto_enero]);
						$monto_febrero = str_replace(".","",$_POST[presupuesto_ley_pr_monto_febrero]);
						$monto_marzo = str_replace(".","",$_POST[presupuesto_ley_pr_monto_marzo]);
						$monto_abril = str_replace(".","",$_POST[presupuesto_ley_pr_monto_abril]);
						$monto_mayo = str_replace(".","",$_POST[presupuesto_ley_pr_monto_mayo]);
						$monto_junio = str_replace(".","",$_POST[presupuesto_ley_pr_monto_junio]);
						$monto_julio = str_replace(".","",$_POST[presupuesto_ley_pr_monto_julio]);
						$monto_agosto = str_replace(".","",$_POST[presupuesto_ley_pr_monto_agosto]);
						$monto_septiembre = str_replace(".","",$_POST[presupuesto_ley_pr_monto_septiembre]);
						$monto_octubre = str_replace(".","",$_POST[presupuesto_ley_pr_monto_octubre]);
						$monto_noviembre = str_replace(".","",$_POST[presupuesto_ley_pr_monto_noviembre]);
						$monto_diciembre = str_replace(".","",$_POST[presupuesto_ley_pr_monto_diciembre]);
	//***************
	$sqlcerrado = "SELECT  count(estatus) as estatus FROM anteproyecto_presupuesto WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (anio = '".$ano."') AND (id_unidad_ejecutora = ".$_POST[presupuesto_ley_pr_unidad_ejecutora_id].") AND (estatus = 2) ";
	$rowww=& $conn->Execute($sqlcerrado);
	//echo ($sqlcerrado);
	if($rowww->fields('estatus') == 0){
	
	
	
		$sqlBus = "SELECT  * FROM anteproyecto_presupuesto WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND ((id_accion_central = ".$accion_central.") OR (id_proyecto = ".$proyecto.")) AND (id_unidad_ejecutora = ".$_POST[presupuesto_ley_pr_unidad_ejecutora].") AND (id_accion_especifica = ".$_POST[presupuesto_ley_pr_accion_especifica].")  AND (anio = '".$_POST[presupuesto_ley_pr_anio]."')  AND (partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND (especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."')";
		$row=& $conn->Execute($sqlBus);
		if(!$row->EOF){
			$sql = "	
					INSERT INTO 
						anteproyecto_presupuesto(
							id_organismo, 
							id_accion_central, 
							id_unidad_ejecutora, 
							id_accion_especifica, 					
							id_proyecto, 
							anio, 					
							partida, 
							generica, 					
							especifica, 
							sub_especifica, 					
							enero, 
							febrero, 					
							marzo, 
							abril,				
							mayo, 					
							junio,            		
							julio, 					
							agosto, 					
							septiembre, 					
							octubre, 					
							noviembre, 
							diciembre,					
							comentario, 
							fecha_modificacion, 
							ultimo_usuario)
					 VALUES (
							".$_SESSION['id_organismo'].", 
							".$accion_central.", 
							".$_POST[presupuesto_ley_pr_unidad_ejecutora_id].", 
							".$_POST[presupuesto_ley_pr_accion_especifica].", 
							".$proyecto.", 
							'".$_POST[presupuesto_ley_pr_anio]."', 
							'".$partida[0]."', 
							'".$partida[1]."', 
							'".$partida[2]."', 
							'".$partida[3]."', 
							'".str_replace(",",".",$monto_enero)."',
							'".str_replace(",",".",$monto_febrero)."',
							'".str_replace(",",".",$monto_marzo)."',
							'".str_replace(",",".",$monto_abril)."',
							'".str_replace(",",".",$monto_mayo)."',
							'".str_replace(",",".",$monto_junio)."',
							'".str_replace(",",".",$monto_julio)."',
							'".str_replace(",",".",$monto_agosto)."',
							'".str_replace(",",".",$monto_septiembre)."',
							'".str_replace(",",".",$monto_octubre)."',
							'".str_replace(",",".",$monto_noviembre)."',
							'".str_replace(",",".",$monto_diciembre)."',
							'".$_POST[presupuesto_ley_pr_comentario]."', 
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
			die("Existe");
	}else
		die("cerrado");
}else{
	die("cerrado2");
}
?>