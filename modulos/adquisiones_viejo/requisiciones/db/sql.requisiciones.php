<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$proyecto_id = $_POST['requisiones_pr_proyecto_id'];
$reglon = $_POST['requisiones_pr_numero_reglon'];

if($proyecto_id == "")
	$proyecto_id = 0;
	
$accion_central_id = $_POST['requisiones_pr_accion_central_id'];
if($accion_central_id == "")
	$accion_central_id = 0;
	
//----- verificar si esta cerrada la requisicion es decir se convirtio en cotizacion
/*if($_POST['requisiones_pr_numero']!="")
{
			$sqlRequisicionCotizacion = "SELECT count(id_requisicion) 
											FROM 
												 \"solicitud_cotizacionE\"
											INNER JOIN
												 requisicion_encabezado
											ON
												 \"solicitud_cotizacionE\".id_requisicion=requisicion_encabezado.id_requisicion_encabezado
										WHERE 
											( \"solicitud_cotizacionE\".id_organismo = '".$_SESSION['id_organismo']."')
										AND
											(requisicion_encabezado.numero_requisicion=  $_POST['requisiones_pr_numero'])
						
							";
			$rowprueba=& $conn->Execute($sqlRequisicionCotizacion);
					if (!$rowprueba>EOF)
					{
						die("cotizacion_existe");
					}
			}*/
//-----------------------------------------------------------------------------------------------------------

// ********************  requisicion_encabezado
if($_POST['requisiones_pr_numero_requision']==""){
$sqlNRequisicion = "SELECT count(id_requisicion_encabezado) FROM requisicion_encabezado 
				WHERE 
					(id_organismo = '".$_SESSION['id_organismo']."')

				AND
					(ano = '".date('Y')."')
				";/*
								AND
					(id_unidad_ejecutora = '".$_SESSION['id_unidad_ejecutora']."')
				*/
$roww=& $conn->Execute($sqlNRequisicion);
if (!$roww->EOF)
{
	$count = $roww->fields("count");
	$count = $count + 1;
}
	if ($count < 10)
	{
		$numero_requision = date('y')."000".$count;
	}elseif ($count >= 10 && $count < 100)
	{
		$numero_requision = date('y')."00".$count;
	}elseif ($count >= 100 && $count < 1000)
	{
		$numero_requision = date('y')."0".$count;
	}elseif ($count >= 10000)
	{
		$numero_requision = date('y').$count;
	}
}else{
	$numero_requision = $_POST['requisiones_pr_numero_requision'];
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

$sqlSecuencia = "SELECT count(secuencia) FROM requisicion_detalle 
				WHERE 
					(numero_requision = '".$numero_requision."')
				";
$rowSecuencia=& $conn->Execute($sqlSecuencia);
if (!$rowSecuencia->EOF)
{
	$Secuencia = $rowSecuencia->fields("count");
	$Secuencia = $Secuencia + 1;
}

$cuenta = $_POST['requisiones_pr_partida'];

list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['requisiones_pr_partida']);
//$partida = split( ".", $_POST['requisiones_pr_partida'] );

if($_POST['requisiones_pr_numero_requision']==""){
	$sqlEn = "
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
				cedula_jefe_proyecto, 
				usuario_elabora_requisicion, 
				estatus, 
				fecha_requerida, 
				prioridad, 
				ano_csc, 
				id_tipo_documento, 
				observacion,
				ultimo_usuario, 
				fecha_actualizacion 
			)VALUES (
				".$_SESSION['id_organismo'].", 
				".$_SESSION['id_unidad_ejecutora'].",
				".date('Y').",
				'".$numero_requision."', 
				".$proyecto_id .",
				".$accion_central_id.", 
				".$_POST['requisiones_pr_accion_especifica_id'].", 
				'".$fecha."', 
				'".$_POST['requisiones_pr_asunto']."', 
				".$cedulaJefe.", 
				".$_SESSION['id_usuario'].",
				1, 
				'".$fecha."', 
				'1',
				".date('Y').",				
				".$_POST['requisiones_pr_tipo_doc'].", 
				'".$_POST['requisiones_pr_obesrvacion']."', 
				".$_SESSION['id_usuario'].",
				'".$fecha."'
					)
			";
	if (!$conn->Execute($sqlEn)) {
		die ('Error al Registrar: '.$sqlEn);
	}
	/*else{
		die("Registrado");
		}*/
			/* cosas qitadas a peticion d ela gente de presupuesto
			.$_POST['requisiones_pr_priorida'].", ->prioridad***
			comentario 	'".$_POST['requisiones_pr_comentario']."', 
			*/
}

$sqlBus = "SELECT * FROM requisicion_detalle WHERE (upper(descripcion) = '".strtoupper($_POST[requisiones_pr_producto])."') AND numero_requision =  '".$numero_requision."'";
$row=& $conn->Execute($sqlBus);
/*echo ($sqlBus);*/
$sqlBusRe = "
		SELECT 
			id_requisicion_encabezado, 
			numero_requisicion
		FROM 
			requisicion_encabezado
		INNER JOIN
			\"solicitud_cotizacionE\"
		ON
			\"solicitud_cotizacionE\".id_requisicion = requisicion_encabezado.id_requisicion_encabezado
		WHERE
			numero_requisicion =  '".$numero_requision."'";
$roww=& $conn->Execute($sqlBusRe);
if( $roww->EOF ){
	if($row->EOF && $numero_requision !=""){
					$sql = "
					INSERT INTO requisicion_detalle(
						id_organismo, 
						id_unidad_ejecutora, 
						ano, 
						numero_requision, 
						secuencia, 
						cantidad, 
						id_unidad_medida, 
						descripcion, 
						ultimo_usuario, 
						fecha_actualizacion,
						partida,
						generica,
						especifica,
						subespecifica
						) VALUES (
						".$_SESSION['id_organismo'].", 
						".$_SESSION['id_unidad_ejecutora'].",
						".date('Y').",
						'".$numero_requision."',
						".$Secuencia.",
						".str_replace(".","",$_POST['requisiones_pr_cantidad']).",
						".$_POST['requisiones_pr_unidad_medida'].",
						'".$_POST['requisiones_pr_producto']."',
						".$_SESSION['id_usuario'].",
						'".$fecha."',
						'".$partida."',
						'".$generica."',
						'".$especifica."',
						'".$subespecifica."'
						);
						UPDATE 
							requisicion_encabezado
						SET 
						   asunto='".$_POST['requisiones_pr_asunto']."', 
						   observacion='".$_POST['requisiones_pr_obesrvacion']."', 
						   ultimo_usuario=".$_SESSION['id_usuario'].",
						   fecha_actualizacion='".date("Y-m-d H:i:s")."' 
						   
						WHERE
							numero_requisicion='$numero_requision';
					";
	
		if (!$conn->Execute($sql)) {
			die ('Error al Registrar: '.$conn->ErrorMsg());
		}else{
			die("Ok,".$numero_requision);
		}
	}else{
	if($reglon == "")
		$reglon = $row->fields("id_requisicion_detalle");
		$sql_ac="
		UPDATE 
			requisicion_encabezado
		SET 
		   asunto='".$_POST['requisiones_pr_asunto']."', 
		   observacion='".$_POST['requisiones_pr_obesrvacion']."', 
		   ultimo_usuario=".$_SESSION['id_usuario'].",
		   fecha_actualizacion='".date("Y-m-d H:i:s")."' 
		   
		WHERE
			numero_requisicion='$numero_requision';


		UPDATE 
			requisicion_detalle
		SET 
			descripcion= '".$_POST['requisiones_pr_producto']."',
			cantidad = ".str_replace(".","",$_POST['requisiones_pr_cantidad']).",
			id_unidad_medida = ".$_POST['requisiones_pr_unidad_medida'].",
			partida = '".$partida."',
			generica = '".$generica."',
			especifica = '".$especifica."',
			subespecifica = '".$subespecifica."'
		WHERE 
			(id_requisicion_detalle = ".$reglon.")";
		
			if (!$conn->Execute($sql_ac)) {
				die ('Error al actualizar: '.$conn->ErrorMsg());
			}else{
				echo("Okk,".$numero_requision);
			}
		//die("Existe");
	}
}else{
		die("cotizacion_existe");
	}
?>