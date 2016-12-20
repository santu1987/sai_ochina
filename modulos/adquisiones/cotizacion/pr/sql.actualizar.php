<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$id = $_POST['cotizacones_pr_id_solicitud'];
$monto = $_POST['cotizacones_pr_monto'];
$monto =	str_replace(".","",$monto);
$monto =	str_replace(",",".",$monto);
$iva = $_POST['cotizacones_pr_iva'];
$iva =	str_replace(".","",$iva);
$iva =	str_replace(",",".",$iva);
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['cotizacones_pr_partida']);


$sqlBus = "SELECT * FROM \"solicitud_cotizacionD\" WHERE (id_solicitud_cotizacion = ".$id.")";
$row=& $conn->Execute($sqlBus);
$sql="
UPDATE 
	\"solicitud_cotizacionD\"
SET 
	cantidad = ".$_POST['cotizacones_pr_cantidad'].",
	id_unidad_medida = ".$_POST['cotizacones_pr_unidad_medida'].",
	monto= '$monto' ,
	impuesto= '$iva',
	partida= '".$partida."', 
	generica='".$generica."', 
	especifica='".$especifica."', 
	subespecifica='".$subespecifica."'

WHERE 
	(id_solicitud_cotizacion = ".$id.")";


$sqle="
UPDATE 
	\"solicitud_cotizacionE\"
SET 
	tiempo_entrega = '".$_POST['cotizaciones_pr_tiempo_entrega']."',
	lugar_entrega = '".$_POST['cotizaciones_pr_lugar_entrega']."',
	condiciones_pago = '".$_POST['cotizaciones_pr_condiciones_pago']."',
	validez_oferta = '".$_POST['cotizaciones_pr_validez_oferta']."',
	garantia = ".$_POST['cotizaciones_pr_garantia'].",
	estatus = 1
WHERE 
	(numero_cotizacion = '".$_POST['cotizacones_pr_numero_cotizacion']."')";
	
	if (!$conn->Execute($sql) && !$conn->Execute($sqle)) {
		die ('Error al actualizar: '.$conn->ErrorMsg());
	}else{
		if (!$conn->Execute($sqle)) {
			die ('Error al actualizar2: '.$conn->ErrorMsg());
		}else{
			echo("Ok");
		}
	}
?>