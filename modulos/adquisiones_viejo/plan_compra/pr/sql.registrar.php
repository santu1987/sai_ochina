<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sqlbus = "
	SELECT 
		COUNT(\"id_plan_comprasD\")
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
	$count = $count + 1 ;
	$sql = "
		INSERT INTO 
			\"plan_comprasE\"
			(
				id_organismo, 
				ano, 
				id_unidad_ejecutora, 
				responsable, 
				estatus,
				comentario, 
				ultimo_usuario, 
				fecha_actualizacion
			)
		VALUES (
				".$_SESSION['id_organismo'].", 
				".$_POST['plan_compra_pr_ano'].", 
				".$_POST['plan_compra_pr_id_unidad'].",
				'".$_POST['plan_compra_pr_responsable']."',
				1, 
				'".$_POST['plan_compra_pr_comentario']."',
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'
			)
	";

$sqld= "
		INSERT INTO 
			\"plan_comprasD\"(
				id_organismo, 
				ano, 
				id_unidad_ejecutora, 
				id_detalle_demanda,
				secuencia, 
				cantidad, 
				valor, 
				fecha_propuesta, 
				tipo_compra, 
				comentario,
				ultimo_usuario, 
				fecha_actualizacion
		)VALUES (
				".$_SESSION['id_organismo'].", 
				".$_POST['plan_compra_pr_ano'].",
				".$_POST['plan_compra_pr_id_unidad'].",
				".$_POST['plan_compra_pr_id_detelle'].",
				".$count.",				
				".str_replace(".","",$_POST['plan_compra_pr_cantidad']).",
				'".$_POST['plan_compra_pr_valor']."',
				'".$_POST['plan_compra_pr_fecha_propuesta']."',
				".$_POST['plan_compra_pr_tipo'].",
				'".$_POST['plan_compra_pr_comentario']."',
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'
		)
"	;

	if ($roww->fields("count") == 0)
	{	
		if (!$conn->Execute($sql)) {
			die ('Error al Registrar: '.$conn->ErrorMsg());
		}else{
			//die("Registrado");
			if (!$conn->Execute($sqld)) {
				die ('Error al Registrar: '.$conn->ErrorMsg());
			}else{
				die("Registrado");
			}
		}
	}else{
		if (!$conn->Execute($sqld)) {
			die ('Error al Registrar: '.$conn->ErrorMsg());
		}else{
			die("Registrado");
		}
	}
}	
?>