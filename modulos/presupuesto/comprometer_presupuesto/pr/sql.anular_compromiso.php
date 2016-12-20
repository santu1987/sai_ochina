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
		numero_orden_compra_servicio = '0'
	";
$row=& $conn->Execute($sqlBus);
if(!$row->EOF){

$sqlprecompromiso = 
	"SELECT 
		numero_precompromiso
	FROM 
		parametros_presupuesto
	WHERE
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		ano = '".date("Y")."'";
$precompromiso=& $conn->Execute($sqlprecompromiso);

if (!$precompromiso->EOF)
{
	$pre =  $precompromiso->fields("numero_precompromiso") + 1 ;
	if ($pre < 10)
	{
		$numero_precompromiso = date('y')."000".$pre;
	}elseif ($pre >= 10 && $pre < 100)
	{
		$numero_precompromiso = date('y')."00".$pre;
	}elseif ($pre >= 100 && $pre < 1000)
	{
		$numero_precompromiso = date('y')."0".$pre;
	}elseif ($pre >= 10000)
	{
		$numero_precompromiso = date('y').$pre;
	}
	//$num_precompromiso =  date('y').$pre ;
}
	$sql = "	
	INSERT INTO \"orden_compra_servicioE\"(
            id_organismo, 
			ano, 
			id_tipo_documento, 
            id_proveedor, 
			fecha_orden_compra_servicio, 
			id_unidad_ejecutora, 
            numero_cotizacion, 
			numero_requisicion, 
			usuario_elabora, 
			fecha_elabora, 
            id_proyecto_accion_centralizada,
			tipo, 
			id_accion_especifica,
			numero_pre_orden, 
			numero_precompromiso,
			concepto, 
			tiempo_entrega,
			lugar_entrega,
			condiciones_pago,
			comentarios, 
            estatus,
			ultimo_usuario,
			fecha_actualizacion)
    VALUES (
	    	".$_SESSION['id_organismo'].", 
			".$ano.",
            ".$row->fields("id_tipo_documento").", 
            ".$row->fields("id_proveedor").", 
			'".$fecha."', 
            ".$row->fields("id_unidad_ejecutora").", 
            '".$row->fields("numero_cotizacion")."', 
            '".$row->fields("numero_requisicion")."', 
			".$_SESSION['id_usuario'].", 
			'".$fecha."',  
            ".$row->fields("id_proyecto_accion_centralizada").", 
			".$row->fields("tipo").",
			".$row->fields("id_accion_especifica").", 
			'".$numero_precompromiso."', 
			'".$numero_precompromiso."', 
			'".$row->fields("concepto")."', 
			'".$row->fields("tiempo_entrega")."',
			'".$row->fields("lugar_entrega")."',
			'".$row->fields("condiciones_pago")."',
			'".$row->fields("comentarios")."',
            1, 
			".$_SESSION['id_usuario'].", 
			'".$fecha."'
			);
		UPDATE 
			parametros_presupuesto
		SET 
			numero_precompromiso=$pre
		WHERE 
			id_organismo = ".$_SESSION['id_organismo']."
		AND
			ano = '".date("Y")."';
			
		UPDATE \"orden_compra_servicioE\"
		   SET 
			   estatus=3, 
			   ultimo_usuario=".$_SESSION['id_usuario'].", 
			   fecha_actualizacion='".$fecha."'
		 WHERE 
		 	numero_orden_compra_servicio = '$orden';
			
		UPDATE \"orden_compra_servicioD\"
		   SET 
			numero_pre_orden='$numero_precompromiso', 
			ultimo_usuario=".$_SESSION['id_usuario'].", 
			fecha_actualizacion='".$fecha."'
		 WHERE numero_pre_orden = '".$row->fields("numero_precompromiso")."';
		";
		
		if (!$conn->Execute($sql)) {
			echo ('Error al Registrar: '.$conn->ErrorMsg());
		}else{
				echo("Registrado,".$numero_precompromiso);
		}
}else{
	echo("noRegistrado,".$orden);
}
?>