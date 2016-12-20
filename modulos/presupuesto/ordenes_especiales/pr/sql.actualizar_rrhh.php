<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$reglon = $_POST['asignacion_rrhh_pr_numero_reglon'];
$descripcion = $_POST['asignacion_rrhh_pr_producto'];
$cantidad = $_POST['requisiones_pr_cantidad'];
$unidad_medida = $_POST['asignacion_rrhh_pr_unidad_medida'];
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['asignacion_rrhh_pr_partida']);
$cantidad =str_replace(".","",$_POST['asignacion_rrhh_pr_cantidad']);
$monto =str_replace(".","",$_POST['asignacion_rrhh_pr_monto']);
$monto =str_replace(",",".",$monto);
$ivas =str_replace(".","",$_POST['asignacion_rrhh_pr_iva']);
$ivas =str_replace(",",".",$ivas);
$ivas='0.00';
//die($_POST['asignacion_rrhh_pr_accion_central_id']);

$proyecto_id = $_POST['asignacion_rrhh_pr_proyecto_id'];
$accion_central_id = $_POST['asignacion_rrhh_pr_accion_central_id'];


if(($_POST['asignacion_rrhh_pr_accion_central_id'] != "")|| ($_POST['asignacion_rrhh_pr_accion_central_id'] != 0) || ($_POST['asignacion_rrhh_pr_accion_central_id'] == undefined))
	$pro_acc =$_POST['asignacion_rrhh_pr_accion_central_id'];
else
	$pro_acc =$_POST['asignacion_rrhh_pr_proyecto_id'];
//die($pro_acc);
$sqlBus = "
SELECT 
		\"orden_compra_servicioD\".numero_pre_orden,
		id_orden_compra_servicioe,
		numero_compromiso,
		descripcion,
		cantidad,
		monto,
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
	(id_orden_compra_serviciod = ".$reglon.")
 	
	";
$row=& $conn->Execute($sqlBus);

//die($sqlBus);
if($row->fields("numero_compromiso")=='0'){
if($partida == '403')
	$tipo_doc= 4;
else
	$tipo_doc= 3;
if($_POST['asignacion_rrhh_pr_unidad_custodio_id'] == "")
	$id_custo =0 ;
else
	$id_custo =$_POST['asignacion_rrhh_pr_unidad_custodio_id'] ;	
	//die($row->fields("cantidad"));
$cantidadxxx =$row->fields("cantidad");
$montoxxx =$row->fields("monto");
$partidax2 =$row->fields("partida");
$genericax2 =$row->fields("generica");
$especificax2 =$row->fields("especifica");
$subespecificax2 =$row->fields("subespecifica");

///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
$bus_ll = "SELECT monto_precomprometido[".date("n")."] as monto FROM \"presupuesto_ejecutadoR\"
WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$_POST['asignacion_rrhh_pr_unidad_id'].") AND (id_accion_especifica = ".$_POST['asignacion_rrhh_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partidax2."'  AND	generica = '".$genericax2."'  
		AND	especifica = '".$especificax2."'  AND	sub_especifica = '".$subespecificax2."'";
	//die($bus_ll);	
		$row_lla=& $conn->Execute($bus_ll);
if (!$row_lla->EOF)
{

	$montodds = $montoxxx * $cantidadxxx ;
	$montoxx = $row_lla->fields("monto");
	$montoxx = $montoxx - $montodds;
	
}else{
				die($conn->ErrorMsg());
			}

///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////			
			$actu="UPDATE 
		\"presupuesto_ejecutadoR\"
	SET 
		monto_precomprometido[".date("n")."]= '".$montoxx."'
	WHERE
		(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$_POST['asignacion_rrhh_pr_unidad_id'].") AND (id_accion_especifica = ".$_POST['asignacion_rrhh_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partidax2."'  AND	generica = '".$genericax2."'  
		AND	especifica = '".$especificax2."'  AND	sub_especifica = '".$subespecificax2."';
		
	
		";
		//die($actu);
			if (!$conn->Execute($actu)){
				echo ('Error al Actulizar:1 '.$conn->ErrorMsg());
			}
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////	
//////////////////////////////////////////nuevo
				///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
$bus_l2 = "SELECT monto_precomprometido[".date("n")."] as monto FROM \"presupuesto_ejecutadoR\"
WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$_POST['asignacion_rrhh_pr_unidad_id'].") AND (id_accion_especifica = ".$_POST['asignacion_rrhh_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."'";
	//die($bus_l2);	
		$row_lla2=& $conn->Execute($bus_l2);
if (!$row_lla2->EOF)
{
$cant = $_POST['asignacion_rrhh_pr_cantidad'];
$cantidad =str_replace(".","",$cant);
$cantidad =str_replace(",",".",$cant);
$monto =str_replace(".","",$_POST['asignacion_rrhh_pr_monto']);
$monto =str_replace(",",".",$monto);

	$montodds = $monto * $cant ;
	$montoxx2 = $row_lla2->fields("monto");
	$montoxx2 = $montoxx2 + $montodds;
	
}else{
				die($conn->ErrorMsg());
			}
//die($bus_l2);
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////			
			$actu2="UPDATE 
		\"presupuesto_ejecutadoR\"
	SET 
		monto_precomprometido[".date("n")."]= '".$montoxx2."'
	WHERE
		(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accion_central_id.") AND
		(id_unidad_ejecutora = ".$_POST['asignacion_rrhh_pr_unidad_id'].") AND (id_accion_especifica = ".$_POST['asignacion_rrhh_pr_accion_especifica_id'].") AND
		(id_proyecto = ".$proyecto_id.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."';
		
	
	
		";
		//die($actu2);
			if (!$conn->Execute($actu2)){
				echo ('Error al Actulizar2: '.$conn->ErrorMsg());
			}
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////	

		$sql="
		UPDATE 
			\"orden_compra_servicioD\" 
		SET 
			descripcion= '$descripcion',
			cantidad = '".$cantidad."',
			id_unidad_medida = $unidad_medida,
			monto = '".$monto."',
			impuesto = '".$ivas."',
			partida = '$partida',
			generica = '$generica',
			especifica = '$especifica',
			subespecifica = '$subespecifica'
		WHERE 
			(id_orden_compra_serviciod = ".$reglon.");
			
			UPDATE 
				\"orden_compra_servicioE\"
			SET 
			 
				id_tipo_documento=$tipo_doc, 
				id_proveedor=".$_POST['asignacion_rrhh_pr_proveedor_id'].", 
				id_unidad_ejecutora=".$_POST['asignacion_rrhh_pr_unidad_id'].", 
				id_proyecto_accion_centralizada=".$pro_acc.", 
				id_accion_especifica=".$_POST['asignacion_rrhh_pr_accion_especifica_id'].", 
				concepto='".$_POST['asignacion_rrhh_pr_asunto']."', 
				comentarios='".$_POST['asignacion_rrhh_pr_obesrvacion']."', 
				ultimo_usuario=".$_SESSION['id_usuario'].", 
				fecha_actualizacion='".$fecha."'
			 WHERE
				id_orden_compra_servicioe=".$row->fields("id_orden_compra_servicioe")."

			";
		//die($sql);
			if (!$conn->Execute($sql)) {
				die ('Error al actualizar: '.$conn->ErrorMsg());
			}else{
				echo("Ok*".$numero_requision);
			}
}elseif($row->fields("numero_compromiso")!='0'){
	die('cotizacion_existe');
}else{
	die('nada: '.$sqlBus);
}
?>