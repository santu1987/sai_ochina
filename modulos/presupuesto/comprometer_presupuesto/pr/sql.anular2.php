<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
//$cotizacion = $_POST['anular_compromiso_numero'];
$cotizacion = '40350004';
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

///////////////////////////////////////////////////////////////////////////////
$Sql = "	
	SELECT 
		id_orden_compra_serviciod,
		secuencia,
		cantidad,
		monto,
		impuesto,
		partida,
		generica,
		especifica,
		subespecifica,
		id_unidad_ejecutora,
		id_accion_especifica,
		\"orden_compra_servicioE\".ano,
		(
		SELECT  
			(monto_presupuesto[".date("n")."] +
			monto_traspasado[".date("n")."] +
			monto_modificado[".date("n")."] -
			monto_comprometido[".date("n")."]) AS disponible
		FROM 
			\"presupuesto_ejecutadoR\"
		WHERE
			(id_organismo = $_SESSION[id_organismo])
		AND
			(ano = '".date("Y")."')
		AND
			(id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora)
		AND
			(id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica)
		AND
			(partida = \"orden_compra_servicioD\".partida)
		AND
			(generica = \"orden_compra_servicioD\".generica)
		AND
			(especifica = \"orden_compra_servicioD\".especifica)
		AND
			(sub_especifica = \"orden_compra_servicioD\".subespecifica)
		)AS disponible
		
	FROM 
		\"orden_compra_servicioD\" 
	INNER JOIN 
		\"orden_compra_servicioE\" 
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso 
	WHERE 
		(\"orden_compra_servicioD\".id_organismo=$_SESSION[id_organismo] )
	AND
		(\"orden_compra_servicioE\".numero_compromiso = '$cotizacion')	
	ORDER BY 
		\"orden_compra_servicioD\".secuencia  
	";
	$contardor = 0;
	$row=& $conn->Execute($Sql);
	$articulo = 0;
	//echo($Sql);	
		$disponoble = $row->fields("disponible");
		$monto_actual = 0;
		
			
	while (!$row->EOF) 
	{
		$monto = ($row->fields("monto")* $row->fields("cantidad"));
		
			 
	
	///////////////////////////////////////////////////////////////////////////////
		$sql_comprometido ="
		SELECT  
			(monto_comprometido[".$fecha_compromiso[1]."]) AS comprometido
		FROM 
			\"presupuesto_ejecutadoR\"
		WHERE
			(id_organismo = $_SESSION[id_organismo])
		AND
			(ano = '".date("Y")."')
		AND
			(id_unidad_ejecutora =".$row->fields("id_unidad_ejecutora").")
		AND
			(id_accion_especifica = ".$row->fields("id_accion_especifica").")
		AND
			(partida = '".$row->fields("partida")."')
		AND
			(generica = '".$row->fields("generica")."')
		AND
			(especifica ='".$row->fields("especifica")."')
		AND
			(sub_especifica = '".$row->fields("subespecifica")."')
		";
		$row_comprometido=& $conn->Execute($sql_comprometido);
		
		///////////////////////////////////////////////////////////////////////////////
		$sql_disponible ="
		SELECT  
			(monto_presupuesto[".$fecha_compromiso[1]."] +
			monto_traspasado[".$fecha_compromiso[1]."] +
			monto_modificado[".$fecha_compromiso[1]."] -
			monto_comprometido[".$fecha_compromiso[1]."]) AS disponible
		FROM 
			\"presupuesto_ejecutadoR\"
		WHERE
			(id_organismo = $_SESSION[id_organismo])
		AND
			(ano = '".date("Y")."')
		AND
			(id_unidad_ejecutora =".$row->fields("id_unidad_ejecutora").")
		AND
			(id_accion_especifica = ".$row->fields("id_accion_especifica").")
		AND
			(partida = '".$row->fields("partida")."')
		AND
			(generica = '".$row->fields("generica")."')
		AND
			(especifica ='".$row->fields("especifica")."')
		AND
			(sub_especifica = '".$row->fields("subespecifica")."')
		";
		$row_disponible=& $conn->Execute($sql_disponible);
///////////////////////////////////////////////////////////////////////////////
		
		//echo ($sql_comprometido.' '.$monto);
	$compromiso = $row_comprometido->fields("comprometido") - $monto;

	
	
		$actu="UPDATE 
			\"presupuesto_ejecutadoR\"
		SET 
			monto_comprometido[".$fecha_compromiso[1]."]= '".$compromiso."'
		WHERE
			(id_organismo = ".$_SESSION['id_organismo'].") AND 
			(id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").") AND (id_accion_especifica = ".$row->fields("id_accion_especifica").") 
			AND (ano = '".$row->fields("ano")."') AND
			partida = '".$row->fields("partida")."'  AND generica = '".$row->fields("generica")."'  AND	especifica = '".$row->fields("especifica")."'  AND	sub_especifica = '".$row->fields("subespecifica")."';
			";
			
		$sql_acutlizar = "UPDATE 
			\"presupuesto_ejecutadoD\"
		SET 
			estatus_compromiso= 1
		WHERE 
			numero_compromiso='$cotizacion';
		
		UPDATE 
			\"orden_compra_servicioE\"
		SET 
			numero_compromiso= '0'
		WHERE 
			numero_compromiso='$cotizacion';
		
			";
			//$acutlizar=& $conn->Execute($sql_acutlizar);
			$actu=& $conn->Execute($actu);
				/*if (!$conn->Execute($actu))
					echo('Error al Actulizar: ');
				else
					echo("Registrado");*/
				
		echo ($actu);
		$row->MoveNext();
	}
//echo ($Sql.'<br>');

?>