<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cotizacion = $_POST['compromiso_pr_pre_compromiso'];
//$cotizacion = '090001';
$mes= date('n');
$i = 0;
$desde =1;
while($desde<=$mes){
	if ($i == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
		$monto_comprometido = " monto_comprometido [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
		$monto_comprometido = $monto_comprometido." + monto_comprometido [".$desde."]";
	}
	$desde++;
	$i++;
}
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
		\"orden_compra_servicioE\".id_tipo_documento,
		\"orden_compra_servicioE\".id_proyecto_accion_centralizada,
		\"orden_compra_servicioE\".tipo,
		\"orden_compra_servicioD\".ano,
		\"orden_compra_servicioD\".numero_pre_orden,
		numero_orden_compra_servicio,
		(
		SELECT  
			(($monoto) +
			($traspasado) +
			($modificado) -
			($monto_comprometido)) AS disponible
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
		)AS disponible,
		(
		SELECT  
			monto_comprometido[".date("n")."]
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
		)AS monto_comprometido,
		numero_requisicion
	FROM 
		\"orden_compra_servicioD\" 
	INNER JOIN 
		\"orden_compra_servicioE\" 
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso 
	WHERE 
		(\"orden_compra_servicioD\".id_organismo=$_SESSION[id_organismo] )
	AND
		(\"orden_compra_servicioD\".numero_pre_orden = '$cotizacion')	
	ORDER BY 
		\"orden_compra_servicioD\".partida, \"orden_compra_servicioD\".generica,\"orden_compra_servicioD\".especifica,\"orden_compra_servicioD\".subespecifica  
	";
	
	$contardor = 0;
	$row=& $conn->Execute($Sql);
	$tipodd = $row->fields("tipo");
	$articulo = 0;
		$disponoble = $row->fields("disponible");
		$partida = $row->fields("partida");
		$id_tipo_documento = $row->fields("id_tipo_documento");
		$orden = $row->fields("numero_orden_compra_servicio");
		$monto_actual = 0;
	$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");	
			$partidad =0;	
			$generficas = 0;
			$erspecificas =0;	
			$subespecicas = 0;
	while (!$row->EOF) 
	{
	$disponoble = $row->fields("disponible")+1;
		$monto = ($row->fields("monto")* $row->fields("cantidad"));
		
		if ($disponoble >=  $monto ){
			if (($row->fields("partida") == $partidad) and ($row->fields("generica") == $generficas) and ($row->fields("especifica") == $erspecificas) and ($row->fields("subespecifica") == $subespecicas))
			{
				$monto_actual = $monto_actual + $monto;
				$partidad  = $row->fields("partida");
				$generficas = $row->fields("generica");
				$erspecificas =  $row->fields("especifica");
				$subespecicas=  $row->fields("subespecifica") ;
				
			}else{
				$monto_actual =  $monto;
				$entro = 1;
			}	
		}else{
			$entro = 0;
			$contardor++;
			$secuencia = $row->fields("secuencia");
			if ($articulo == 0)
				$articulo = $secuencia;
			else
				$articulo = $articulo.", ".$secuencia;
		}
			
		if ($disponoble <  $monto_actual )
		{
			if(entro ==1)
				$monto_actual = $monto_actual - $monto;
			
			$contardor++;
			$secuencia = $row->fields("secuencia");
			if ($articulo == 0)
				$articulo = $secuencia;
			else
				$articulo = $articulo.", ".$secuencia;
		}
		//echo "Monto -->".$monto." disponible -->".$disponoble." monto actual -->".$monto_actual."<br>";
		
		//echo ($disponible."<br>");
		/*if ($disponible < 0){
			echo "Monto -->".($row->fields("monto")* $row->fields("cantidad"))." aqui -->".$disponible."<br>";
			
			$contardor++;
			if($contardor = 1)
				$secuencia = $row->fields("secuencia");
			elseif($contardor > 1)
				$secuencia = $secuencia.",".$row->fields("secuencia");
		}else{
			$compromiso = $row->fields("monto")* $row->fields("cantidad");
		}*/
		//echo($monto_actual."<br>");
		$row->MoveNext();
	}
	//die($monto);
	
	$row->MoveFirst();
	//echo ($Sql.'<br>');
	$compromiso = $row->fields("monto_comprometido") + $monto_actual;
	if($contardor == 0 ){
	//$sqlcompromiso = "SELECT count(numero_compromiso) FROM \"orden_compra_servicioE\" WHERE (numero_pre_orden = '$cotizacion') and numero_compromiso <> '0'";
	$sqlcompromiso = 
	"SELECT 
		numero_compromiso
	FROM 
		parametros_presupuesto
	WHERE
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		ano = '".date("Y")."'";
	$rota=& $conn->Execute($sqlcompromiso);
	if (!$rota->EOF)
	{
		$numero_compromiso = $rota->fields("numero_compromiso")+1;
		$numero_actual = $rota->fields("numero_compromiso")+1;
		//$count = $count + 1;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$sql_mon ="
	SELECT 
			SUM(cantidad*monto)AS monto,
			partida,
			generica,
			especifica,
			subespecifica,
			\"orden_compra_servicioD\".impuesto,
			\"orden_compra_servicioD\".ano,
			id_unidad_ejecutora,
			id_accion_especifica,
			(
			SELECT  
				monto_comprometido[".date("n")."]
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
			)AS monto_comprometido
		FROM 
			\"orden_compra_servicioD\" 
		INNER JOIN 
			\"orden_compra_servicioE\" 
		ON
			\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso 
		WHERE 
			(\"orden_compra_servicioD\".id_organismo=$_SESSION[id_organismo])
		AND
			(\"orden_compra_servicioD\".numero_pre_orden = '$cotizacion')	
		GROUP  BY 
			partida, generica, especifica, subespecifica, \"orden_compra_servicioD\".ano, id_unidad_ejecutora, id_accion_especifica,impuesto
		";
			$row_mon=& $conn->Execute($sql_mon);
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		//echo ($sql_mon);	
		while (!$row_mon->EOF) 
		{
		if($row_mon->fields("impuesto") =="")
			$ivaa=0;
		else
			$ivaa=$row_mon->fields("impuesto");
		if ($ivaa ==0)
			$ivas =0;
		else
			$ivas = (($ivaa *$row_mon->fields("monto"))/100);
		$monto_con_iva = $ivas + $row_mon->fields("monto");
		$compro = $row_mon->fields("monto_comprometido") + $monto_con_iva;
			$actu="UPDATE 
				\"presupuesto_ejecutadoR\"
			SET 
				monto_comprometido[".date("n")."]= '".$compro."'
			WHERE
				(id_organismo = ".$_SESSION['id_organismo'].") AND 
				(id_unidad_ejecutora = ".$row_mon->fields("id_unidad_ejecutora").") AND (id_accion_especifica = ".$row_mon->fields("id_accion_especifica").") 
				AND (ano = '".$row_mon->fields("ano")."') AND
				partida = '".$row_mon->fields("partida")."'  AND generica = '".$row_mon->fields("generica")."'  AND	especifica = '".$row_mon->fields("especifica")."'  AND	sub_especifica = '".$row_mon->fields("subespecifica")."';
			
			";
					if (!$conn->Execute($actu))
						die('Error al Actulizar: '.$actu);
					else
						$xxx = "Registrado.".$numero_compromiso;
					//echo ($actu);	
				$row_mon->MoveNext();
			}	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
			$actuza="UPDATE 
				\"orden_compra_servicioE\"
			SET 
				numero_compromiso='$numero_compromiso'
			WHERE 
				numero_precompromiso='$cotizacion';
			
			UPDATE 
				parametros_presupuesto
			SET 
				numero_compromiso='$numero_actual'
			WHERE 
				id_organismo = ".$_SESSION['id_organismo']."
			AND
				ano = '".date("Y")."';	
			
			INSERT INTO \"presupuesto_ejecutadoD\"(
				id_tipo_documento, 
				id_organismo, 
				ano, 
				numero_documento, 
				numero_compromiso, 
				fecha_compromiso, 
				ultimo_usuario, 
				fecha_modificacion
			)VALUES (
				".$id_tipo_documento .", 
				".$_SESSION['id_organismo'].",
				'".date("Y")."', 
				'".$orden."', 
				'".$numero_compromiso."', 
				'".date("Y-m-d H:i:s")."', 
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'
			);
			
				";
	$datos_orden =	"SELECT 
	id_proyecto,
	id_accion_centralizada
		
	FROM 
		requisicion_encabezado
	
	WHERE 
		(numero_requisicion='".$row->fields("numero_requisicion")."' )";		
	$row_datos_orden=& $conn->Execute($datos_orden);
	if (!$row_datos_orden->EOF)
	{
		$id_proyecto = $row_datos_orden->fields("id_proyecto");
		$id_accion_centralizada = $row_datos_orden->fields("id_accion_centralizada");
		//$count = $count + 1;
	}	
	$monto_debito_credito = "
	SELECT 
					sum(cantidad * monto) AS monto
				FROM 
					organismo 
				INNER JOIN 
					\"orden_compra_servicioD\" 
				ON
					\"orden_compra_servicioD\".id_organismo=organismo.id_organismo 
				INNER JOIN 
					unidad_medida 
				ON
					\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
				INNER JOIN 
					\"orden_compra_servicioE\" 
				ON
					\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso 
				WHERE 
					(\"orden_compra_servicioD\".id_organismo=1)
				AND
					(\"orden_compra_servicioD\".numero_pre_orden = '".$row->fields("numero_pre_orden")."')
	";
	$row_monto_debito_credito=& $conn->Execute($monto_debito_credito);
	
	$sql_proveedor = "SELECT 
		proveedor.nombre
	FROM 
		\"solicitud_cotizacionE\" 
	INNER JOIN 
		proveedor 
	ON
		\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor 
	WHERE 
		(\"solicitud_cotizacionE\".orden_compra_servicioe = '".$row->fields("numero_orden_compra_servicio")."')	
	AND
		(\"solicitud_cotizacionE\".ano = '".date("Y")."')";
	$row_proveedor=& $conn->Execute($sql_proveedor);
		
	$numero_doc = substr($row->fields("numero_orden_compra_servicio"),3);
	$descripcion = $numero_doc." ".$row_proveedor->fields("nombre");
	
	
	if($partida = '401'){
		$tipocomprobante = 94;
		$cuenta_contable_debe = '4121301';
		$cuenta_contable_haber = '4221301';
	}elseif($partida = '402'){
		$tipocomprobante = 91;
		$cuenta_contable_debe = '4121302';
		$cuenta_contable_haber = '4221302';
	}elseif($partida = '403'){
		$tipocomprobante = 92;
		$cuenta_contable_debe = '4121303';
		$cuenta_contable_haber = '4221303';
	}elseif($partida = '404'){
		$tipocomprobante = 93;
		$cuenta_contable_debe = '4121304';
		$cuenta_contable_haber = '4221304';
	}
	
	$numero_comprobante =	"SELECT 
			id,
			numero_comprobante_integracion,
			codigo_tipo_comprobante
		
			FROM 
				tipo_comprobante
			
			WHERE 
				(ayo='".date("Y")."' )
			and
			codigo_tipo_comprobante =".$tipocomprobante ."
				";		
	$row_numero_comprobante=& $conn->Execute($numero_comprobante);
	
	$id_comprobantex=$row_numero_comprobante->fields("id");	
	$numero_comprobantex=$row_numero_comprobante->fields("numero_comprobante_integracion");	
		$cod_tipo1=$row_numero_comprobante->fields("codigo_tipo_comprobante");
		if(($numero_comprobantex!="")&&($numero_comprobantex!="0000"))
			$numero_comprobante3=$numero_comprobantex+1;			
		else
		if($numero_comprobantex=="0000")
			$numero_comprobante3="0001";
			//echo($numero_comprobantex);
		$valor_medida1=strlen($numero_comprobante3);													//echo($numero_comprobantex3);
												
												//	echo($valor_medida);
												if($valor_medida1==1)
												{
													$numero_comprobante3="000".$numero_comprobante3;
												}
												else
												if($valor_medida1==2)
												{
													$numero_comprobante3="00".$numero_comprobante3;
												}
												else	
												if($valor_medida1==3)
												{
															$numero_comprobante3="0".$numero_comprobante3;
												}
												
												$numero_comprobante=$cod_tipo1.$numero_comprobante3;
												

	
	if ($tipodd == 1){
		$id_proyecto =  $row->fields("id_proyecto_accion_centralizada");
		$id_accion_centralizada = 0;
	}elseif ($tipodd == 2){
		$id_accion_centralizada =  $row->fields("id_proyecto_accion_centralizada");
		$id_proyecto = 0;
	}
	$movimiento_contable1 =	"INSERT INTO integracion_contable
			(
			  id_organismo ,
			  ano_comprobante ,
			  mes_comprobante ,
			  id_tipo_comprobante ,
			  numero_comprobante ,
			  secuencia ,
			  comentario ,
			  cuenta_contable,
			  descripcion,
			  referencia ,
			  debito_credito ,
			  monto_debito ,
			  monto_credito ,
			  fecha_comprobante ,
			  id_unidad_ejecutora ,
			  id_proyecto ,
			  id_accion_central ,
			  ultimo_usuario ,
			  estatus ,
			  fecha_actualizacion 
			)
			VALUES
			(
			  ".$_SESSION['id_organismo'].",
			  '".date("Y")."',
			  '".date("m")."',
			  $id_comprobantex ,
			  $numero_comprobante ,
			  1 ,
			  'Compromiso Presupuesto' ,
			  $cuenta_contable_debe,
			 '$descripcion',
			  '$numero_compromiso' ,
			  1 ,
			  '".$row_monto_debito_credito->fields("monto")."' ,
			  0 ,
			  '".date("Y-m-d H:i:s")."' ,
			  ".$id_unidad_ejecutora." ,
			  $id_proyecto ,
			  $id_accion_centralizada,
			 ".$_SESSION['id_usuario'].",
			  1 ,
			 '".date("Y-m-d H:i:s")."');
			
			
			UPDATE
				tipo_comprobante
			SET
				numero_comprobante_integracion = '$numero_comprobante3'
			WHERE 
				(ayo='".date("Y")."' )
				and
			(codigo_tipo_comprobante =".$tipocomprobante .");
			";
			
			$movimiento_contable2 =	"INSERT INTO integracion_contable
			(
			  id_organismo ,
			  ano_comprobante ,
			  mes_comprobante ,
			  id_tipo_comprobante ,
			  numero_comprobante ,
			  secuencia ,
			  comentario ,
			  cuenta_contable,
			  descripcion,
			  referencia ,
			  debito_credito ,
			  monto_debito ,
			  monto_credito ,
			  fecha_comprobante ,
			  id_unidad_ejecutora ,
			  id_proyecto ,
			  id_accion_central ,
			  ultimo_usuario ,
			  estatus ,
			  fecha_actualizacion 
			)
			VALUES
			(
			  ".$_SESSION['id_organismo'].",
			  '".date("Y")."',
			  '".date("m")."',
			  $id_comprobantex ,
			  $numero_comprobante ,
			  2 ,
			  'Compromiso Presupuesto' ,
			  $cuenta_contable_haber,
			  '$descripcion',
			  '$numero_compromiso' ,
			  2 ,
			  0 ,
			  '".$row_monto_debito_credito->fields("monto")."' ,
			  '".date("Y-m-d H:i:s")."' ,
			  ".$id_unidad_ejecutora." ,
			  $id_proyecto ,
			  $id_accion_centralizada,
			 ".$_SESSION['id_usuario'].",
			  1 ,
			 '".date("Y-m-d H:i:s")."')";
			
			/*if (!$conn->Execute($movimiento_contable1))
						die('Error al movimiento_contable1: '.$movimiento_contable1);
			if (!$conn->Execute($movimiento_contable2))
						die('Error al movimiento_contable2: '.$movimiento_contable2);*/
				//$row_movimiento_contable1=& $conn->Execute($movimiento_contable1);
				//$row_movimiento_contable2=& $conn->Execute($movimiento_contable2);

			
					if (!$conn->Execute($actuza))
						die('Error al Actulizar2: '.$actuza);
					
	}else{
		die("error.".$articulo);
		//echo ($Sql);
	}
	echo $xxx;
?>