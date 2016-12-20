<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$id = $_GET['id'];
$preorden = $_GET['preorden'];

$sqlBus = "
SELECT 
	id_orden_compra_serviciod,
	secuencia,
	\"orden_compra_servicioD\".numero_pre_orden,
	numero_orden_compra_servicio,
	descripcion,
	\"orden_compra_servicioE\".numero_requisicion
FROM 
	\"orden_compra_servicioD\" 
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
WHERE 
	id_orden_compra_serviciod= ".$id."
AND
	\"orden_compra_servicioD\".numero_pre_orden = '$preorden'


 	
	";
$row=& $conn->Execute($sqlBus);

//die('nada: '.$sqlBus);

if($row->fields("numero_orden_compra_servicio")==0){

		$sql="
		
		DELETE 
		FROM 
			\"orden_compra_servicioD\" 
		WHERE 
			id_orden_compra_serviciod = $id 
		AND
			numero_pre_orden = '$preorden';
		UPDATE 
			requisicion_detalle
		SET 
			numero_cotizacion='0', 
			id_proveedor= 0,
			estatu_compra=1
		 WHERE 
			numero_requision='".$row->fields("numero_requisicion")."'
		AND
			secuencia=".$row->fields("secuencia")." ;	
			
			";
		
			if (!$conn->Execute($sql)) {
				die ('Error al Eliminar: '.$conn->ErrorMsg());
			}else{
				

				echo("Ok");
			}
	

}else{
	die('nada: '.$sqlBus);
}
?>