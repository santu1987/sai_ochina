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
$descripcion = $_POST['orden_especial_pr_producto'];
$cantidad = $_POST['requisiones_pr_cantidad'];
$unidad_medida = $_POST['orden_especial_pr_unidad_medida'];
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['orden_especial_pr_partida']);
$cantidad =str_replace(".","",$_POST['orden_especial_pr_cantidad']);
$monto =str_replace(".","",$_POST['orden_especial_pr_monto']);
$monto =str_replace(",",".",$monto);
$ivas =str_replace(".","",$_POST['orden_especial_pr_iva']);
$ivas =str_replace(",",".",$ivas);
//die($_POST['orden_especial_pr_accion_central_id']);

$proyecto_id = $_POST['orden_especial_pr_proyecto_id'];
$accion_central_id = $_POST['orden_especial_pr_accion_central_id'];


if(($_POST['orden_especial_pr_accion_central_id'] != "") && ($_POST['orden_especial_pr_accion_central_id'] != 0) )
	$pro_acc =$_POST['orden_especial_pr_accion_central_id'];
else
	$pro_acc =$_POST['orden_especial_pr_proyecto_id'];
//die($pro_acc);



	//die($row->fields("cantidad"));
//$cantidadxxx =$row->fields("cantidad");
//$montoxxx =$row->fields("monto");
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['orden_especial_pr_partida']);
/*
$partidax2 =$row->fields("partida");
$genericax2 =$row->fields("generica");
$especificax2 =$row->fields("especifica");
$subespecificax2 =$row->fields("subespecifica");*/
if(( $_POST['orden_especial_pr_tipo_doc'] =='401') or( $_POST['orden_especial_pr_tipo_doc'] =='402') or ( $_POST['orden_especial_pr_tipo_doc'] =='404') or ( $_POST['orden_especial_pr_tipo_doc'] =='407')  or ( $_POST['orden_especial_pr_tipo_doc'] =='408'))
	$tipos_d = 3;
elseif ( $_POST['orden_especial_pr_tipo_doc'] =='403') 
	$tipos_d = 4;
else	
	$tipos_d = 0;

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
				id_tipo_documento=$tipos_d, 
				ultimo_usuario=".$_SESSION['id_usuario'].", 
				fecha_actualizacion='".$fecha."'
			 WHERE
				numero_orden_compra_servicio='".$_POST['orden_especial_pr_numero']."';
				
			UPDATE 
				\"solicitud_cotizacionE\"
			SET 			 
				id_proveedor=".$_POST['orden_especial_pr_proveedor_id']."
			 WHERE
				numero_cotizacion='".$_POST['orden_especial_pr_numero']."';
		
		UPDATE 
				requisicion_encabezado
			SET 
			 
				asunto='".$_POST['orden_especial_pr_asunto']."', 
				ultimo_usuario=".$_SESSION['id_usuario'].", 
				fecha_actualizacion='".$fecha."'
			 WHERE
				numero_requisicion='".$_POST['orden_especial_pr_numero']."'

			";
		//die($sql);
			if (!$conn->Execute($sql)) {
				die ('Error al actualizar: '.$conn->ErrorMsg());
			}else{
				echo("Ok*".$numero_requision);
			}


?>