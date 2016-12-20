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
if($_POST['orden_especial_pr_numero']==""){
$sqlNRequisicion = "SELECT count(id_orden_compra_servicioe) FROM \"orden_compra_servicioE\" 
				WHERE 
					(id_organismo = '".$_SESSION['id_organismo']."')

				AND
					(ano = '".date('Y')."')
				AND
					(cuenta_orden = 1)
				";/*
								AND
					(id_unidad_ejecutora = '".$_SESSION['id_unidad_ejecutora']."')
				*/
$roww=& $conn->Execute($sqlNRequisicion);

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
}else{
	$numero_requision = substr($_POST['orden_especial_pr_numero'],2);
	$numero_requision_final = $_POST['orden_especial_pr_numero'];
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
if($_POST['orden_especial_pr_numero']==""){
	$sqlEn = "
			INSERT INTO \"orden_compra_servicioE\"(
				id_organismo, 
				ano, 
				id_tipo_documento, 
				id_proveedor, 
				id_unidad_ejecutora, 
				id_proyecto_accion_centralizada, 
				id_accion_especifica, 
				numero_orden_compra_servicio, 
				numero_precompromiso, 
				usuario_elabora, 
				fecha_orden_compra_servicio, 
				fecha_elabora, 
				tipo, 
				concepto, 
				comentarios, 
				estatus, 
				ultimo_usuario, 
				fecha_actualizacion, 
				numero_pre_orden,
				cuenta_orden,
				compromiso_anterior,
				custodio,
				orden_especial
			)VALUES (
				".$_SESSION['id_organismo'].", 
				".date('Y').",
				".$tipos_d.",
				".$proveedor_id .",
				".$unidad_id.",
				".$poryec_central .",
				".$_POST['orden_especial_pr_accion_especifica_id'].", 
				'".$numero_requision_final."', 
				'".$numero_requision."',
				".$_SESSION['id_usuario'].",
				'".$fecha."',  
				'".$fecha."', 
				".$tipo.",
				'".$_POST['orden_especial_pr_asunto']."', 
				'".$_POST['orden_especial_pr_obesrvacion']."', 
				'1',
				".$_SESSION['id_usuario'].",			
				'".$fecha."',
				'".$numero_requision."',
				1,
				'".$_POST['orden_especial_pr_numero_compromiso']."',
				".$custodio.",
				1
					)
			";
			
			
			
	if (!$conn->Execute($sqlEn)) {
		die ('Error al Registrar: '.$sqlEn);
	
	}else{/*
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
				die ('Error al Registrar: '.$conn->ErrorMsg()." ".$sql);
			}else{
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
			if (!$conn->Execute($actu)){
				echo ('Error al Actulizar: '.$conn->ErrorMsg());
			}else{	
			$montoxx = 0;
				die("Registrado,".$numero_requision_final.",".$numero_requision);
				}
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
				die ('Error al Registrar: '.$conn->ErrorMsg()." ".$sql);
			}else{
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
			if (!$conn->Execute($actu)){
				echo ('Error al Actulizar: '.$conn->ErrorMsg());
			}else	{
			$montoxx = 0;
				die("Registrado,".$numero_requision_final.",".$numero_requision);
				}
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////	
				//die("Registrado,".$numero_requision_final);
			}
			

}
			
?>