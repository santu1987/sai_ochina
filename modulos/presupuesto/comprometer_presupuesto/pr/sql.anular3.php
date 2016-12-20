<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cotizacion = $_POST['anular_compromiso_numero'];
$anulacion = $_GET['anulacion'];
//$cotizacion = '40350004';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$busca_fecha = "
SELECT 
			fecha_compromiso
		FROM
			\"presupuesto_ejecutadoD\"
		WHERE
			numero_compromiso = '$cotizacion'
";
$row_busca_fecha=& $conn->Execute($busca_fecha);
$fecha_compromiso = split('[-/]',$row_busca_fecha->fields("fecha_compromiso"));
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_monto_anular="
	SELECT 
		SUM(monto*cantidad) AS monto_anular,
		\"orden_compra_servicioE\".ano,
		id_unidad_ejecutora,
		id_accion_especifica,
		partida,
		generica,
		especifica,
		subespecifica
	FROM
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
	WHERE
		numero_compromiso = '$cotizacion'
	GROUP BY
		\"orden_compra_servicioE\".ano, id_unidad_ejecutora, id_accion_especifica, partida,generica,especifica,subespecifica
";
$row_monto_anular=& $conn->Execute($sql_monto_anular);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_requi="
	SELECT 
		numero_requisicion,
		numero_cotizacion
	FROM
		\"orden_compra_servicioE\"
	WHERE
		numero_compromiso = '$cotizacion'
";
$row_requi_anular=& $conn->Execute($sql_requi);
while (!$row_monto_anular->EOF) 
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql_comprometido = "
		SELECT  
			(monto_comprometido[".$fecha_compromiso[1]."]) AS monto_comprometido
		FROM 
			\"presupuesto_ejecutadoR\"
		WHERE
			(id_organismo = ".$_SESSION['id_organismo'].")
		AND
			(ano = '".$row_monto_anular->fields("ano")."')
		AND
			(id_unidad_ejecutora = ".$row_monto_anular->fields("id_unidad_ejecutora").")
		AND
			(id_accion_especifica = ".$row_monto_anular->fields("id_accion_especifica").")
		AND
			(partida = '".$row_monto_anular->fields("partida")."')
		AND
			(generica = '".$row_monto_anular->fields("generica")."')
		AND
			(especifica = '".$row_monto_anular->fields("especifica")."')
		AND
			(sub_especifica = '".$row_monto_anular->fields("subespecifica")."')
	";
	$row_comprometido=& $conn->Execute($sql_comprometido);
	$monto_anulado = $row_comprometido->fields("monto_comprometido") - $row_monto_anular->fields("monto_anular");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($anulacion == 0){
	$actu="UPDATE 
			\"presupuesto_ejecutadoR\"
		SET 
			monto_comprometido[".$fecha_compromiso[1]."]= '".$monto_anulado."'
		WHERE
			(id_organismo = ".$_SESSION['id_organismo'].") 
		AND 
			(id_unidad_ejecutora = ".$row_monto_anular->fields("id_unidad_ejecutora").") 
		AND 
			(id_accion_especifica = ".$row_monto_anular->fields("id_accion_especifica").") 
		AND 
			(ano = '".$row_monto_anular->fields("ano")."')
		AND
			partida = '".$row_monto_anular->fields("partida")."'  
		AND 
			generica = '".$row_monto_anular->fields("generica")."'  
		AND	
			especifica = '".$row_monto_anular->fields("especifica")."'  
		AND	
			sub_especifica = '".$row_monto_anular->fields("subespecifica")."';
			
		UPDATE 
			\"presupuesto_ejecutadoD\"
		SET 
			estatus_compromiso= 1
		WHERE 
			numero_compromiso='$cotizacion';
		
		UPDATE 
			\"orden_compra_servicioE\"
		SET 
			numero_compromiso= '0',
			estatus=3, 
		   ultimo_usuario=".$_SESSION['id_usuario'].",
		   fecha_actualizacion='".$fecha."'
		WHERE 
			numero_compromiso='$cotizacion';	
		
		UPDATE 
			\"solicitud_cotizacionE\"
		SET 
			orden_compra_servicioe= '0'
		WHERE 
			numero_cotizacion='".$row_requi_anular->fields("numero_cotizacion")."';	
			";
	}else{
		$actu="UPDATE 
			\"presupuesto_ejecutadoR\"
		SET 
			monto_comprometido[".$fecha_compromiso[1]."]= '".$monto_anulado."'
		WHERE
			(id_organismo = ".$_SESSION['id_organismo'].") 
		AND 
			(id_unidad_ejecutora = ".$row_monto_anular->fields("id_unidad_ejecutora").") 
		AND 
			(id_accion_especifica = ".$row_monto_anular->fields("id_accion_especifica").") 
		AND 
			(ano = '".$row_monto_anular->fields("ano")."')
		AND
			partida = '".$row_monto_anular->fields("partida")."'  
		AND 
			generica = '".$row_monto_anular->fields("generica")."'  
		AND	
			especifica = '".$row_monto_anular->fields("especifica")."'  
		AND	
			sub_especifica = '".$row_monto_anular->fields("subespecifica")."';
			
		UPDATE 
			\"presupuesto_ejecutadoD\"
		SET 
			estatus_compromiso= 1
		WHERE 
			numero_compromiso='$cotizacion';
		
		UPDATE 
			\"orden_compra_servicioE\"
		SET 
			numero_compromiso= '0',
			estatus=3, 
		   ultimo_usuario=".$_SESSION['id_usuario'].",
		   fecha_actualizacion='".$fecha."'
		WHERE 
			numero_compromiso='$cotizacion';
		
		UPDATE 
			\"solicitud_cotizacionE\"
		SET 
			orden_compra_servicioe= '0'
		WHERE 
			numero_cotizacion='".$row_requi_anular->fields("numero_cotizacion")."';	
		
		UPDATE 
		requisicion_encabezado
	SET 
		usuario_anula= ".$_SESSION['id_usuario'].", 
		fecha_anula='".date("d-m-Y")."'
	WHERE 
		numero_requisicion='".$row_requi_anular->fields("numero_requisicion")."'
		
			";
	
	}
		if (!$conn->Execute($actu)){
					die('Error al Actulizar: ');
			}else{
			$xxx="Registrado";
			}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$row_monto_anular->MoveNext();
}
echo $xxx;
//echo ($busca_fecha.'<br>'.$fecha_compromiso[1]);

?>