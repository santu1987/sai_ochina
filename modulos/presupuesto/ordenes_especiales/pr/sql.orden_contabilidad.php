<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$reglon = $_POST['orden_especial_pr_numero_reglon'];
$unidad_id = $_POST['orden_especial_pr_unidad_id'];

$proyecto_id = $_POST['orden_especial_pr_proyecto_id'];
if($proyecto_id == ""){
	$proyecto_id = 0;
	}else{
	$tipo = 1;
	$poryec_central =$proyecto_id;
	}
	
$accion_central_id = $_POST['orden_especial_pr_accion_central_id'];
if($accion_central_id == ""){
	$accion_central_id = 0;
	}else{
	$tipo = 2;
	$poryec_central =$accion_central_id;
	}
	
$ivas = str_replace(",",".",$_POST['orden_especial_pr_iva']);
$montos = str_replace(".","",$_POST['orden_especial_pr_monto']);
$montos = str_replace(",",".",$montos);
$montodds = str_replace(",",".",$montos);	
//----- verificar si esta cerrada la requisicion es decir se convirtio en cotizacion
//-----------------------------------------------------------------------------------------------------------

// ********************  requisicion_encabezado
/*if($_POST['orden_especial_pr_numero']==""){
$sqlNRequisicion = "SELECT count(id_orden_compra_servicioe) FROM \"orden_compra_servicioE\" 
				WHERE 
					(id_organismo = '".$_SESSION['id_organismo']."')

				
				AND
					(cuenta_orden = 5)
				";/*
								AND
					(id_unidad_ejecutora = '".$_SESSION['id_unidad_ejecutora']."')
				*/
				//die($sqlNRequisicion);
/*$roww=& $conn->Execute($sqlNRequisicion);

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
	$count = $roww->fields("count");
	$count = $count + 1;
}
	if ($pre < 10)
	{
		$numero_requision = '9'.date('y')."000".$pre;
		$numero_requision_final = '499'.date('y')."000".$pre;
	}elseif ($pre >= 10 && $pre < 100)
	{
		$numero_requision = '9'.date('y')."00".$pre;
		$numero_requision_final = '499'.date('y')."00".$pre;
	}elseif ($pre >= 100 && $pre < 1000)
	{
		$numero_requision = '9'.date('y')."0".$pre;
		$numero_requision_final = '499'.date('y')."0".$pre;
	}elseif ($pre >= 1000)
	{
		$numero_requision = '9'.date('y').$pre;
		$numero_requision_final = '499'.date('y').$pre;
	}
}else{*/
	$numero_requision = $_POST['orden_especial_pr_numero'];
	$numero_requision_final = $_POST['orden_especial_pr_numero'];
//}
$sqlEncabeza = "SELECT count(id_orden_compra_servicioe) FROM \"orden_compra_servicioE\" 
				WHERE 
					(numero_orden_compra_servicio = '".$_POST['orden_especial_pr_numero']."')
				";
$rowEncabeza=& $conn->Execute($sqlEncabeza);
if (!$rowEncabeza->EOF)
{
	$enca = $rowEncabeza->fields("count");
	//$enca = $enca + 1;
}
// ********************  FIN requisicion_encabezado
// ********************  Jefe Proyecto
$sqlJefe = "SELECT 
				jefe_proyecto.cedula_jefe_proyecto
			FROM 
				proyecto
			INNER JOIN
				jefe_proyecto
			ON
				proyecto.id_jefe_proyecto = jefe_proyecto.id_jefe_proyecto
			WHERE
				(proyecto.id_proyecto=".$proyecto_id.")";
$rowJefe= $conn->Execute($sqlJefe);
if (!$rowJefe->EOF)
	$cedulaJefe = $rowJefe->fields("cedula_jefe_proyecto");
else
	$cedulaJefe = 0;
// ********************  FIN Jefe Proyecto

$sqlSecuencia = "SELECT count(secuencia) FROM \"orden_compra_servicioD\" 
				WHERE 
					(numero_pre_orden = '".$numero_requision."')
				";
$rowSecuencia=& $conn->Execute($sqlSecuencia);
if (!$rowSecuencia->EOF)
{
	$Secuencia = $rowSecuencia->fields("count");
	$Secuencia = $Secuencia + 1;
}
$proyecto_id = $_POST['orden_especial_pr_proyecto_id'];
if($proyecto_id == "")
	$proyecto_id = 0;
	
$accion_central_id = $_POST['orden_especial_pr_accion_central_id'];
if($accion_central_id == "")
	$accion_central_id = 0;

$cuenta = $_POST['orden_especial_pr_partida'];

if(( $_POST['orden_especial_pr_tipo_doc'] =='401') or( $_POST['orden_especial_pr_tipo_doc'] =='402') or ( $_POST['orden_especial_pr_tipo_doc'] =='404') or ( $_POST['orden_especial_pr_tipo_doc'] =='407')  or ( $_POST['orden_especial_pr_tipo_doc'] =='408'))
	$tipos_d = 3;
elseif ( $_POST['orden_especial_pr_tipo_doc'] =='403') 
	$tipos_d = 4;
else	
	$tipos_d = 0;
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['orden_especial_pr_partida']);
//$partida = split( ".", $_POST['orden_especial_pr_partida'] );

if ( $_POST['orden_especial_pr_unidad_custodio_id'] =="")
	$custodio=0;
else
	$custodio = $_POST['orden_especial_pr_unidad_custodio_id'];
$proveedor_id = $_POST['orden_especial_pr_proveedor_id'];
if($enca == 0){
$id_requisicion=0;

	$sqlEn = "
			INSERT INTO \"orden_compra_servicioE\"(
				id_organismo, 
				id_tipo_documento, 
				numero_requisicion,
				numero_cotizacion,
				numero_orden_compra_servicio, 
				numero_precompromiso, 
				usuario_elabora, 
				fecha_orden_compra_servicio, 
				fecha_elabora, 
				tipo, 
				estatus, 
				ultimo_usuario, 
				fecha_actualizacion, 
				
				cuenta_orden,
				numero_compromiso,
				orden_especial
			)VALUES (
				".$_SESSION['id_organismo'].", 
				".$tipos_d.",
				'".$numero_requision_final."', 
				'".$numero_requision."',
				'".$numero_requision_final."', 
				'".$numero_requision."',
				".$_SESSION['id_usuario'].",
				'".$fecha."',  
				'".$fecha."', 
				".$tipo.",
				'1',
				".$_SESSION['id_usuario'].",			
				'".$fecha."',
				
				1,
				'".$_POST['orden_especial_pr_numero_compromiso']."',
				3
					);
					
			INSERT INTO requisicion_encabezado(
				id_organismo, 
				id_unidad_ejecutora, 
				ano, 
				numero_requisicion, 
				id_proyecto, 
				id_accion_centralizada, 
				id_accion_especifica, 
				fecha_requisicion, 
				asunto, 
				usuario_elabora_requisicion, 
				estatus, 
				fecha_requerida, 
				prioridad, 
				ano_csc, 
				observacion,
				ultimo_usuario, 
				fecha_actualizacion 
			)VALUES (
				".$_SESSION['id_organismo'].", 
				".$_POST['orden_especial_pr_unidad_id'].",
				".date('Y').",
				'".$numero_requision_final."', 
				".$proyecto_id .",
				".$accion_central_id.", 
				".$_POST['orden_especial_pr_accion_especifica_id'].", 
				'".$fecha."', 
				'".($_POST['orden_especial_pr_asunto'])."', 
				".$_SESSION['id_usuario'].",
				1, 
				'".$fecha."', 
				'1',
				".date('Y').",				
				'".$_POST['orden_especial_pr_obesrvacion']."', 
				".$_SESSION['id_usuario'].",
				'".$fecha."'
					);
				
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
				".$tipos_d.",
				".$_SESSION['id_organismo'].",
				'".date("Y")."', 
				'".$numero_requision_final."',
				'".$_POST['orden_especial_pr_numero_compromiso']."',
				'".date("Y-m-d H:i:s")."', 
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'
			);
			";
			
	
	if (!$conn->Execute($sqlEn)) {
		die ('Error al Registrar 1: '.$sqlEn);
	
	}else{
		$sqlrequicion = "SELECT 
					*
 				 FROM 
				 	requisicion_encabezado
				 WHERE
				 	(numero_requisicion ='".$numero_requision_final."')
				";

$rowrrr=& $conn->Execute($sqlrequicion);

$id_requisicion = $rowrrr->fields("id_requisicion_encabezado");		
			$sqlCotizac ="
			INSERT INTO \"solicitud_cotizacionE\"(
					id_organismo, 
					id_proveedor, 
					 
					fecha_cotizacion, 
					id_usuario_elabora, 
					fecha_elabora_solicitud, 
					anocsc, 
					id_requisicion, 
					ultimo_usuario, 
					fecha_actualizacion, 
					numero_cotizacion
					)
			VALUES (  
					".$_SESSION['id_organismo'].",
					".$_POST['orden_especial_pr_proveedor_id'].", 
					 
					'".$fecha."', 
					".$_SESSION['id_usuario'].", 
					'".$fecha."', 
					'".date('Y')."', 
					".$id_requisicion.",
					".$_SESSION['id_usuario'].", 
					'".$fecha."', 
					'$numero_requision_final'
					)
					";
					
		if (!$conn->Execute($sqlCotizac)) 
			die ('Error al Registrar 4: '.$sqlCotizac);			
					
	//echo ('f '.$sqlEn.'<br>');
	/*
		die("Registrado,".$numero_requision_final);
		}
			 cosas qitadas a peticion d ela gente de presupuesto
			.$_POST['requisiones_pr_priorida'].", ->prioridad***
			comentario 	'".$_POST['requisiones_pr_comentario']."', 
			*/
			$sql = "
					INSERT INTO \"orden_compra_servicioD\"(
						id_organismo, 
						ano, 
						id_tipo_documento, 
						id_unidad_medida, 
						impuesto,  
						numero_pre_orden, 
						secuencia, 
						cantidad, 
						monto, 
						descripcion, 
						partida, 
						generica, 
						especifica, 
						subespecifica, 
						ultimo_usuario, 
						fecha_actualizacion
						) VALUES (
						".$_SESSION['id_organismo'].", 
						".date('Y').",
						".$_POST['orden_especial_pr_tipo_doc'].",
						".$_POST['orden_especial_pr_unidad_medida'].",
						'".$ivas."',
						'".$numero_requision."',
						".$Secuencia.",
						".$_POST['orden_especial_pr_cantidad'].",
						'".$montos."',
						'".$_POST['orden_especial_pr_producto']."',
						'".$partida."',
						'".$generica."',
						'".$especifica."',
						'".$subespecifica."',
						".$_SESSION['id_usuario'].",
						'".$fecha."'
						);
					";
			if (!$conn->Execute($sql)) {
				die ('Error al Registrar:2 '.$conn->ErrorMsg()." ".$sql);
			}else{
			die("Registrado,".$numero_requision_final.",".$numero_requision);
			///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
$bus_ll = "SELECT monto_precomprometido[".date("n")."] as monto FROM \"presupuesto_ejecutadoR\"
WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$unidad_id.") AND (id_accion_especifica = ".$_POST['orden_especial_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."'";
		
		$row_lla=& $conn->Execute($bus_ll);
if (!$row_lla->EOF)
{
$cant = $_POST['orden_especial_pr_cantidad'];
	$montodds = $montodds * $cant ;
	$montoxx = $row_lla->fields("monto");
	$montoxx = $montoxx + $montodds;
	
}else{
				die($conn->ErrorMsg());
			}
//die($bus_ll);
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////			
			$actu="UPDATE 
		\"presupuesto_ejecutadoR\"
	SET 
		monto_precomprometido[".date("n")."]= '".$montoxx."'
	WHERE
		(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$unidad_id.") AND (id_accion_especifica = ".$_POST['orden_especial_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."';
		
		UPDATE 
			parametros_presupuesto
		SET 
			numero_precompromiso=$pre
		WHERE 
			id_organismo = ".$_SESSION['id_organismo']."
		AND
			ano = '".date("Y")."';
	
		";
			/*if (!$conn->Execute($actu)){
				echo ('Error al Actulizar: '.$conn->ErrorMsg());
			}else{	
			$montoxx = 0;
				die("Registrado,".$numero_requision_final.",".$numero_requision);
			}*/
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////	
				//die("Registrado,".$numero_requision_final);
			}
	}
}else{
$sql = "
					INSERT INTO \"orden_compra_servicioD\"(
						id_organismo, 
						ano, 
						id_tipo_documento, 
						id_unidad_medida, 
						impuesto,  
						numero_pre_orden, 
						secuencia, 
						cantidad, 
						monto, 
						descripcion, 
						partida, 
						generica, 
						especifica, 
						subespecifica, 
						ultimo_usuario, 
						fecha_actualizacion
						) VALUES (
						".$_SESSION['id_organismo'].", 
						".date('Y').",
						".$_POST['orden_especial_pr_tipo_doc'].",
						".$_POST['orden_especial_pr_unidad_medida'].",
						'".$ivas."',
						'".$numero_requision."',
						".$Secuencia.",
						".$_POST['orden_especial_pr_cantidad'].",
						'".$montos."',
						'".$_POST['orden_especial_pr_producto']."',
						'".$partida."',
						'".$generica."',
						'".$especifica."',
						'".$subespecifica."',
						".$_SESSION['id_usuario'].",
						'".$fecha."'
						);
					";
			if (!$conn->Execute($sql)) {
				die ('Error al Registrar 3: '.$conn->ErrorMsg()." ".$sql);
			}else{
			die("Registrado,".$numero_requision_final.",".$numero_requision);
						///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
$bus_ll = "SELECT monto_precomprometido[".date("n")."] as monto FROM \"presupuesto_ejecutadoR\"
WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$unidad_id.") AND (id_accion_especifica = ".$_POST['orden_especial_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."';
		
		
		";
		
		$row_lla=& $conn->Execute($bus_ll);
if (!$row_lla->EOF)
{
	$cant = $_POST['orden_especial_pr_cantidad'];
	$montodds = $montodds * $cant ;
	$montoxx = $row_lla->fields("monto");
	$montoxx = $montoxx + $montodds;
}else{
				die($conn->ErrorMsg());
			}
//die($bus_ll);
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////			
			$actu="UPDATE 
		\"presupuesto_ejecutadoR\"
	SET 
		monto_precomprometido[".date("n")."]= '".$montoxx."'
	WHERE
		(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$unidad_id.") AND (id_accion_especifica = ".$_POST['orden_especial_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."';
		
		
	
		";
			/*if (!$conn->Execute($actu)){
				echo ('Error al Actulizar: '.$conn->ErrorMsg());
			}else	{
			$montoxx = 0;
				die("Registrado,".$numero_requision_final.",".$numero_requision);
			}*/
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////	
				//die("Registrado,".$numero_requision_final);
				$montoxx = 0;
				die("Registrado,".$numero_requision_final.",".$numero_requision);
			}
			

}
			
?>