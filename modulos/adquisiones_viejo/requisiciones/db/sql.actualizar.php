<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$reglon = $_POST['requisiones_pr_numero_reglon'];
$descripcion = $_POST['requisiones_pr_producto'];
$cantidad = $_POST['requisiones_pr_cantidad'];
$unidad_medida = $_POST['requisiones_pr_unidad_medida'];
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['requisiones_pr_partida']);

$sqlBus = "
SELECT 
		numero_requision,
		estatus,
		descripcion
	FROM 
		requisicion_detalle 
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_detalle.numero_requision = requisicion_encabezado.numero_requisicion
WHERE 
	(id_requisicion_detalle = ".$reglon.")
 	
	";
$row=& $conn->Execute($sqlBus);

$sqlBuss = "
SELECT 
		COUNT(descripcion) AS contar
	FROM 
		requisicion_detalle 
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_detalle.numero_requision = requisicion_encabezado.numero_requisicion
WHERE 
	(id_requisicion_detalle <> ".$reglon.")
AND
	(descripcion = '$descripcion')
AND
	(numero_requision = '".$_POST['requisiones_pr_numero']."') 	
	";
$roww=& $conn->Execute($sqlBuss);
$numero_requision = $row->fields("numero_requision");
$contar = $roww->fields("contar");

if($row->fields("estatus")==1){
	if($contar ==0){

		$sql="
		UPDATE 
			requisicion_encabezado
		SET 
		   asunto='".$_POST['requisiones_pr_asunto']."', 
		   observacion='".$_POST['requisiones_pr_obesrvacion']."', 
		   ultimo_usuario=".$_SESSION['id_usuario'].",
		   fecha_actualizacion='".date("Y-m-d H:i:s")."' 
		   
		WHERE
			numero_requisicion='$numero_requision';


		UPDATE 
			requisicion_detalle
		SET 
			descripcion= '$descripcion',
			cantidad = ".str_replace(".","",$_POST['requisiones_pr_cantidad']).",
			id_unidad_medida = $unidad_medida,
			partida = '$partida',
			generica = '$generica',
			especifica = '$especifica',
			subespecifica = '$subespecifica'
		WHERE 
			(id_requisicion_detalle = ".$reglon.")";
		
			if (!$conn->Execute($sql)) {
				die ('Error al actualizar: '.$conn->ErrorMsg());
			}else{
				echo("Ok*".$numero_requision);
			}
	}else{
		die('Existe');
	}
}elseif($row->fields("estatus")==2){
	die('cotizacion_existe');
}else{
	die('nada: '.$sqlBus);
}
?>