<?
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$fecha = date("Y-m-d H:i:s");
$ano = date("Y");
$orden=$_GET['orden'];
//*****************************************************************************************************************************

$sqlBus = "
	SELECT 
		ano, 
		id_tipo_documento, 
		id_proveedor, 
		id_unidad_ejecutora, 
		id_proyecto_accion_centralizada, 
		id_accion_especifica,
		numero_cotizacion, 
		numero_requisicion, 
		numero_orden_compra_servicio, 
		numero_precompromiso, 
		numero_compromiso, 
		numero_pre_orden,
		usuario_elabora, 
		fecha_orden_compra_servicio, 
		fecha_elabora, 
		tiempo_entrega, 
		lugar_entrega, entregara, condiciones_pago, 
		tipo, 
		orden_especial,
		concepto, 
		clase_orden_compra_servicio, 
		comentarios, 
		estatus 	
	FROM 
		\"orden_compra_servicioE\"
	WHERE
		numero_orden_compra_servicio = '$orden'
	AND
		numero_compromiso = '0'
	";
$row=& $conn->Execute($sqlBus);
if(!$row->EOF){


	$sql = "	
	
					
		UPDATE \"orden_compra_servicioE\"
		   SET 
			   estatus=3, 
			   ultimo_usuario=".$_SESSION['id_usuario'].", 
			   fecha_actualizacion='".$fecha."'
		 WHERE 
		 	numero_orden_compra_servicio = '$orden';
			
		
		";
		
		if (!$conn->Execute($sql)) {
			echo ('Error al Registrar: '.$conn->ErrorMsg());
		}else{
				echo("Registrado");
		}
}else{
	echo("noRegistrado");
}
?>