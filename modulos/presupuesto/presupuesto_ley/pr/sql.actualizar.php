<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
/*$partida_toda = $_POST[presupuesto_aprobado_pr_partida_numero];
$partida =explode(".",$partida_toda);
$sqlBus = "SELECT  * FROM anteproyecto_presupuesto WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND ((id_accion_central = ".$_POST[presupuesto_aprobado_pr_accion_central_id].") OR (id_proyecto = ".$_POST[presupuesto_aprobado_pr_proyecto_id].")) AND (id_unidad_ejecutora = ".$_POST[presupuesto_aprobado_pr_unidad_ejecutora_id].") AND (id_accion_especifica = ".$_POST[presupuesto_aprobado_pr_accion_especifica].")  AND (anio = '".$_POST[presupuesto_aprobado_pr_anio]."')  AND (partida = '".$partida[0]."') AND (generica = '".$partida[1]."') AND (especifica = '".$partida[2]."') AND (sub_especifica = '".$partida[3]."') AND (estatus <>2)";
$row=& $conn->Execute($sqlBus);*/
$sql_busca="
SELECT 
	count(id_presupuesto_ejecutador)
FROM 
	\"presupuesto_ejecutadoR\"
WHERE
	id_unidad_ejecutora = ".$_POST['presupuesto_aprobado_pr_id_unidad']."
AND
	ano = '".$_POST['presupuesto_aprobado_pr_ano']."'

";
//echo $sql_busca;
$rs_presu =& $conn->Execute($sql_busca);
if($rs_presu->fields("count") == 0){
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

	$sql = "	
			UPDATE 
				presupuesto_ley
			SET  
				enero='".str_replace(",",".",$monto_enero)."', 
				febrero='".str_replace(",",".",$monto_febrero)."', 
				marzo='".str_replace(",",".",$monto_marzo)."', 
		   		abril='".str_replace(",",".",$monto_abril)."', 
				mayo='".str_replace(",",".",$monto_mayo)."', 
				junio='".str_replace(",",".",$monto_junio)."', 
				julio='".str_replace(",",".",$monto_julio)."', 
				agosto='".str_replace(",",".",$monto_agosto)."', 
				septiembre='".str_replace(",",".",$monto_septiembre)."', 
				octubre='".str_replace(",",".",$monto_octubre)."', 
		   		noviembre='".str_replace(",",".",$monto_noviembre)."', 
				diciembre='".str_replace(",",".",$monto_diciembre)."', 
				fecha_modificacion='".$fecha."', 
		   		ultimo_usuario=".$_SESSION['id_usuario']."
			WHERE 
				(id_presupuesto_ley=".$_POST[presupuesto_aprobado_pr_cot_select].")
			";
			if (!$conn->Execute($sql)||$repetido) 
				echo (($repetido)?$msgExiste:'Error al Modificar: '.$sql.'<br />');
			else
				echo 'Actualizado';
	//die ('Cerrado');
	//echo $sqlBus;
	//echo $sqlBus;
}else{

	echo ('cerrado');
}
?>