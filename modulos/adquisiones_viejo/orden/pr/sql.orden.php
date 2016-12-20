<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$fecha = date("Y-m-d H:i:s");
$ano = date("Y");
//*****************************************************************************************************************************
$sqlBus = "SELECT * FROM requisicion_encabezado WHERE (numero_requisicion = '".$_POST[ordenes_pr_id_requisicion]."')";
$row=& $conn->Execute($sqlBus);
//echo ($sqlBus);
$nro_requi =  $row->fields("numero_requisicion");
$accion_especifica = $row->fields("id_accion_especifica");

if ($row->fields("id_accion_centralizada") != 0){
	$id_proyecto_accion =  $row->fields("id_accion_centralizada");
	$tipo =  2;
}else{
	$id_proyecto_accion =  $row->fields("id_proyecto");
	$tipo =  1;
}
//*****************************************************************************************************************************
$sqlNSolicitud = "SELECT count(id_orden_compra_servicioe) FROM \"orden_compra_servicioE\" ";
$roww=& $conn->Execute($sqlNSolicitud);
if (!$roww->EOF)
{
	$count = $roww->fields("count");
	$count = $count + 1;
}
	if ($count < 10)
	{
		$numero_precompromiso = date('y')."000".$count;
	}elseif ($count >= 10 && $count < 100)
	{
		$numero_precompromiso = date('y')."00".$count;
	}elseif ($count >= 100 && $count < 1000)
	{
		$numero_precompromiso = date('y')."0".$count;
	}elseif ($count >= 10000)
	{
		$numero_precompromiso = date('y').$count;
	}
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
//*****************************************************************************************************************************
$encabezado = "
		SELECT 
			\"solicitud_cotizacionE\".id_unidad_ejecutora, 
			requisicion_encabezado.id_accion_centralizada,
			requisicion_encabezado.id_proyecto,
			requisicion_encabezado.id_accion_especifica,
			\"solicitud_cotizacionE\".numero_cotizacion,
			requisicion_encabezado.numero_requisicion,
			\"solicitud_cotizacionE\".tiempo_entrega, 
			\"solicitud_cotizacionE\".lugar_entrega, 
			\"solicitud_cotizacionE\".condiciones_pago, 
			\"solicitud_cotizacionE\".fecha_maxima_entrega, 
			\"solicitud_cotizacionE\".titulo
		FROM 
			\"solicitud_cotizacionE\"
		INNER JOIN
			requisicion_encabezado
		ON
			requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
		WHERE
			\"solicitud_cotizacionE\".numero_cotizacion = '".$_POST[ordenes_pr_nro_cotizacion]."'
		AND
			\"solicitud_cotizacionE\".id_proveedor = ".$_POST[ordenes_pr_id_proveedor]."
		AND
			\"solicitud_cotizacionE\".id_organismo = ".$_SESSION['id_organismo']."
";

//********************************************************************
$rs_encabezado=& $conn->Execute($encabezado);
//********************************************************************


if(!$row->EOF){


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
			orden_especial,
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
            ".$_POST['ordenes_pr_tipo_orden'].", 
            ".$_POST['ordenes_pr_id_proveedor'].", 
			'".$fecha."', 
            ".$_POST['ordenes_pr_id_unidad_ejecutora'].", 
            '".$_POST['ordenes_pr_nro_cotizacion']."', 
            '".$nro_requi."', 
			".$_SESSION['id_usuario'].", 
			'".$fecha."',  
            ".$id_proyecto_accion.", 
			".$tipo.",
			".$_POST['ordenes_pr_especial'].",
			".$accion_especifica.", 
			'".$numero_precompromiso."', 
			'".$numero_precompromiso."', 
			'".$_POST['ordenes_pr_concepto']."', 
			'".$rs_encabezado->fields("tiempo_entrega")."',
			'".$rs_encabezado->fields("lugar_entrega")."',
			'".$rs_encabezado->fields("condiciones_pago")."',
			'".$_POST['ordenes_pr_comentraios']."',
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
		
		";
		
		if (!$conn->Execute($sql)) {
			echo ('Error al Registrar: '.$conn->ErrorMsg());
		}else{
				echo("Registrado,".$numero_precompromiso);
		}
$id_detalles = split( ",", $_POST['ordenes_pr_cot_select'] );
$contador = count($id_detalles);  ///$_POST['covertir_req_cot_titulo']
$i=0;
		
	while($i < $contador){
	$cantida = 0;
//***********************************
	$detalle = "
	SELECT 
		secuencia, 
		cantidad, 
		monto,
		id_unidad_medida, 
		impuesto, 
		partida, 
		generica, 
		especifica, 
		subespecifica, 
		descripcion, 
		comentario
	FROM 
		\"solicitud_cotizacionD\"
	WHERE
		id_solicitud_cotizacion = ".$id_detalles[$i]."
	
	";
	//echo($detalle);
	//***********************************
	$detalle_suma = "
	SELECT 
		sum(monto) AS monto
	FROM 
		\"solicitud_cotizacionD\"
	WHERE
		id_solicitud_cotizacion = ".$id_detalles[$i]."
	";
//***********************************

$rs_detalle=& $conn->Execute($detalle);
$rs_detalle_suma=& $conn->Execute($detalle_suma);
//***********************************
$sql_van="
SELECT 
	SUM(cantidad) as canti
FROM 
	\"orden_compra_servicioD\" 
INNER JOIN
	\"orden_compra_servicioE\" 
ON
	\"orden_compra_servicioE\".numero_cotizacion = \"orden_compra_servicioD\".numero_cotizacion
WHERE
	\"orden_compra_servicioD\".secuencia = ".$rs_detalle->fields("secuencia")."
AND
	\"orden_compra_servicioE\".numero_requisicion = '".$nro_requi."'
";
$rs_van=& $conn->Execute($sql_van);
//***********************************
//***********************************
$sql_eran="
SELECT 
	cantidad
FROM 
	requisicion_detalle 
WHERE
	secuencia = ".$rs_detalle->fields("secuencia")."
AND
	numero_requisicion = '".$nro_requi."'
";
$rs_eran=& $conn->Execute($sql_eran);
//***********************************
if(!$rs_van->EOF){
	$cantida = $rs_van->fields("canti");
}


	$cantidad = $rs_detalle->fields("cantidad") - $cantida;


		$sqll = "
		UPDATE 
			requisicion_detalle
		SET 
			numero_cotizacion='".$_POST['ordenes_pr_nro_cotizacion']."', 
			id_proveedor= ".$_POST['ordenes_pr_id_proveedor'].",
			estatu_compra = 2
		 WHERE 
			numero_requision='".$nro_requi."'
		AND
			secuencia=".$rs_detalle->fields("secuencia")." ;
			
		INSERT INTO 
			\"orden_compra_servicioD\"(
				id_organismo,
				ano,
				id_tipo_documento, 
				id_unidad_medida, 
				numero_cotizacion, 
				numero_pre_orden,
				secuencia, 
				cantidad, 
				monto,
				impuesto,
				descripcion, 
				comentario, 
				partida, 
				generica, 
				especifica, 
				subespecifica, 
				ultimo_usuario, 
				fecha_actualizacion
			)
		VALUES (
				".$_SESSION['id_organismo'].", 
				".$ano.",
				".$_POST['ordenes_pr_tipo_orden'].", 
				".$rs_detalle->fields("id_unidad_medida").",
				'".$_POST['ordenes_pr_nro_cotizacion']."', 
				'".$numero_precompromiso."', 
				".$rs_detalle->fields("secuencia").",
				".$cantidad.",
				".$rs_detalle->fields("monto").",
				".$rs_detalle->fields("impuesto").",
				'".$rs_detalle->fields("descripcion")."', 
				'".$rs_detalle->fields("comentario")."', 
				'".$rs_detalle->fields("partida")."', 
				'".$rs_detalle->fields("generica")."', 
				'".$rs_detalle->fields("especifica")."', 
				'".$rs_detalle->fields("subespecifica")."', 
				".$_SESSION['id_usuario'].", 
				'".$fecha."'
		)";	
			if (!$conn->Execute($sqll)) 
				die ('Error al Registrar: '.$sqll);
				
	$resumen_suma = "
	SELECT  
		   (monto_precomprometido[".date("n")."]) AS monto
	FROM 
		\"presupuesto_ejecutadoR\"
	WHERE
		id_unidad_ejecutora=".$_POST['ordenes_pr_id_unidad_ejecutora']."
	AND
		id_accion_centralizada = ".$rs_encabezado->fields("id_accion_centralizada")."  AND	id_proyecto = ".$rs_encabezado->fields("id_proyecto")."  AND	id_accion_especifica = ".$rs_encabezado->fields("id_accion_especifica")."
	AND
		partida = '".$rs_detalle->fields("partida")."'  AND	generica = '".$rs_detalle->fields("generica")."'  AND	especifica = '".$rs_detalle->fields("especifica")."'  AND	sub_especifica = '".$rs_detalle->fields("subespecifica")."'
	";
	$rs_resumen_suma=& $conn->Execute($resumen_suma);
	if (!$rs_resumen_suma->EOF) 
		$monto_pre = $rs_resumen_suma->fields("monto");
	else
		$monto_pre = 0;
	$monto_total = $rs_detalle_suma->fields("monto") * $rs_detalle->fields("cantidad"); 
	//$monto_impuesto = ($rs_detalle->fields("impuesto")/100)  * $monto_total ;	
	$monto_total = $monto_pre + $monto_total /*+ $monto_impuesto*/;			
	$actu="UPDATE 
		\"presupuesto_ejecutadoR\"
	SET 
		monto_precomprometido[".date("n")."]= '".str_replace(",",".",$monto_total)."'
	WHERE
		(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$rs_encabezado->fields("id_accion_centralizada").") AND
		(id_unidad_ejecutora = ".$_POST['ordenes_pr_id_unidad_ejecutora'].") AND (id_accion_especifica = ".$rs_encabezado->fields("id_accion_especifica").") AND
		(id_proyecto = ".$rs_encabezado->fields("id_proyecto").") AND (ano = '".date("Y")."') AND
		partida = '".$rs_detalle->fields("partida")."'  AND	generica = '".$rs_detalle->fields("generica")."'  AND	especifica = '".$rs_detalle->fields("especifica")."'  AND	sub_especifica = '".$rs_detalle->fields("subespecifica")."'
	
		";
			if (!$conn->Execute($actu))
				echo ('Error al Actulizar: '.$conn->ErrorMsg());
		$i++;
	}
	
	
}else{
	die("Existe");
}

?>