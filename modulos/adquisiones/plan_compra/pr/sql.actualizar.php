<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sqlbus = "
	SELECT 
		\"id_plan_comprasD\"
	FROM 
		\"plan_comprasD\"
	WHERE
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		ano =".$_POST['plan_compra_pr_ano']."
	 AND
		id_unidad_ejecutora = ".$_POST['plan_compra_pr_id_unidad']."
";
$row=& $conn->Execute($sqlbus);

$sqlbusd = "
	SELECT 
		COUNT(\"id_plan_comprasE\")
	FROM 
		\"plan_comprasE\"
	WHERE
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		ano =".$_POST['plan_compra_pr_ano']."
	 AND
		id_unidad_ejecutora = ".$_POST['plan_compra_pr_id_unidad']."
";
$roww=& $conn->Execute($sqlbusd);

if (!$row->EOF)
{
	$count = $row->fields("count");
	$sql = "
		UPDATE
			\"plan_comprasE\"
		SET
				ano = ".$_POST['plan_compra_pr_ano'].", 
				id_unidad_ejecutora=".$_POST['plan_compra_pr_id_unidad'].", 
				responsable='".$_POST['plan_compra_pr_responsable']."', 
				comentario='".$_POST['plan_compra_pr_comentario']."', 
				ultimo_usuario=".$_SESSION['id_usuario'].", 
				fecha_actualizacion='".date("Y-m-d H:i:s")."'
		WHERE
			\"id_plan_comprasE\" = ".$_POST['plan_compra_pr_idd']."		
	";

$sqld= "
		UPDATE
			\"plan_comprasD\"
		SET
				ano = ".$_POST['plan_compra_pr_ano'].", 
				id_unidad_ejecutora = ".$_POST['plan_compra_pr_id_unidad'].", 
				id_detalle_demanda = ".$_POST['plan_compra_pr_id_detelle'].",
				cantidad = ".str_replace(".","",$_POST['plan_compra_pr_cantidad']).", 
				valor = '".$_POST['plan_compra_pr_valor']."', 
				fecha_propuesta = '".$_POST['plan_compra_pr_fecha_propuesta']."', 
				tipo_compra = ".$_POST['plan_compra_pr_tipo'].", 
				comentario = '".$_POST['plan_compra_pr_comentario']."',
				ultimo_usuario = ".$_SESSION['id_usuario'].", 
				fecha_actualizacion = '".date("Y-m-d H:i:s")."'
		WHERE
			\"id_plan_comprasD\" = ".$_POST['plan_compra_pr_id']."		
"	;

	if ($roww->fields("count") != 0)
	{	
		if (!$conn->Execute($sql)) {
			die ('Error al Registrar1: '.$conn->ErrorMsg());
		}else{
			//die("Registrado");
			if (!$conn->Execute($sqld)) {
				die ('Error al Registrar:2 '.$sqld.'  '.$conn->ErrorMsg());
			}else{
				die("Registrado");
			}
		}
	}else{
		if (!$conn->Execute($sqlbus)) {
			die ('Error al Registrar:3 '.$conn->ErrorMsg());
		}else{
			die("Registrado");
		}
	}
}	
?>