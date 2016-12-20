<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$partida_toda = $_POST[pre_antepresupuesto_ley_pr_partida_numero];
$partida_todas = $_POST[pre_antepresupuesto_ley_pr_partida_numero_old];

$partida =explode(".",$partida_toda);
$partidax =explode(".",$partida_todas);
$sqlBus = "SELECT  * FROM precierre_anteproyecto WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND ((id_accion_central = ".$_POST[pre_antepresupuesto_ley_pr_accion_central_id_old].") OR (id_proyecto = ".$_POST[pre_antepresupuesto_ley_pr_proyecto_id_old].")) AND (id_unidad_ejecutora = ".$_POST[pre_antepresupuesto_ley_pr_unidad_ejecutora_id_old].") AND (id_accion_especifica = ".$_POST[pre_antepresupuesto_ley_pr_accion_especifica_old].")  AND (anio = '".$_POST[pre_antepresupuesto_ley_pr_anio]."')  AND (partida = '".$partidax[0]."') AND (generica = '".$partidax[1]."') AND (especifica = '".$partidax[2]."') AND (sub_especifica = '".$partidax[3]."') AND (estatus <>2)";
$row=& $conn->Execute($sqlBus);
//***********************

					$monto_enero = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_enero]);
					$monto_febrero = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_febrero]);
					$monto_marzo = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_marzo]);
					$monto_abril = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_abril]);
					$monto_mayo = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_mayo]);
					$monto_junio = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_junio]);
					$monto_julio = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_julio]);
					$monto_agosto = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_agosto]);
					$monto_septiembre = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_septiembre]);
					$monto_octubre = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_octubre]);
					$monto_noviembre = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_noviembre]);
					$monto_diciembre = str_replace(".","",$_POST[pre_antepresupuesto_ley_pr_monto_diciembre]);
//***************
if ($_POST[pre_antepresupuesto_ley_pr_proyecto_id] == "" || $_POST[pre_antepresupuesto_ley_pr_proyecto_id] == 0)
	$proyecto = 0;
else
	$proyecto = $_POST[pre_antepresupuesto_ley_pr_proyecto_id];
	
if ($_POST[pre_antepresupuesto_ley_pr_accion_central_id] == "" || $_POST[pre_antepresupuesto_ley_pr_accion_central_id] == 0)
	$accion_central = 0;
else
	$accion_central = $_POST[pre_antepresupuesto_ley_pr_accion_central_id];
	if(!$row->EOF){
	$sql = "	
			UPDATE 
				precierre_anteproyecto
			SET  
				id_organismo=".$_SESSION['id_organismo'].", 
				id_accion_central=".$accion_central.", 
				id_unidad_ejecutora=".$_POST[pre_antepresupuesto_ley_pr_unidad_ejecutora_id].", 
		   		id_accion_especifica=".$_POST[pre_antepresupuesto_ley_pr_accion_especifica].", 
				id_proyecto=".$proyecto.", 
				anio='".$_POST[pre_antepresupuesto_ley_pr_anio]."', 
				partida='".$partida[0]."', 
				generica='".$partida[1]."', 
		   		especifica='".$partida[2]."', 
				sub_especifica='".$partida[3]."', 
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
				comentario='".$_POST[pre_antepresupuesto_ley_pr_comentario]."', 
				fecha_modificacion='".$fecha."', 
		   		ultimo_usuario=".$_SESSION['id_usuario']."
			WHERE 
				(id_precierre_anteproyecto=".$_POST[pre_antepresupuesto_ley_pr_id].")
			";
			if (!$conn->Execute($sql)||$repetido) 
				echo (($repetido)?$msgExiste:'Error al Modificar: '.$sql.'<br />');
			else
				echo 'Actualizado';
}else{
	die ('Cerrado');
	//echo $sqlBus;
	}
	//echo $sqlBus;

?>