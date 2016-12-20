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
$sqlNOR = "SELECT partida FROM \"orden_compra_servicioD\" WHERE (numero_pre_orden = '".$_POST['ordenes_pr_nro_pre_orden']."')";
$rowwI=& $conn->Execute($sqlNOR);
if (!$rowwI->EOF)
	$partida = $rowwI->fields("partida");
///***********************
$sql_tipo = "SELECT id_tipo_documento FROM \"orden_compra_servicioE\" WHERE (numero_pre_orden = '".$_POST['ordenes_pr_nro_pre_orden']."')";
$row_tipo=& $conn->Execute($sql_tipo);
if (!$row_tipo->EOF)
	$id_tipo_documento = $row_tipo->fields("id_tipo_documento");
//**********************
//$sqlNSolicitud = "SELECT count(numero_orden_compra_servicio) FROM \"orden_compra_servicioE\" WHERE (id_tipo_documento = ".$id_tipo_documento.") ";
$sqlprecompromiso = 
	"SELECT 
		numero_precompromiso
	FROM 
		parametros_presupuesto
	WHERE
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		ano = '".date("Y")."'";
$sqladquision = 
	"SELECT 
		ultimo_numero_ocompra, 
		ultimo_numero_oservicio
	FROM 
		parametros_adquisiciones
	WHERE
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		ano = '".date("Y")."'";

$roww=& $conn->Execute($sqladquision);

if (!$roww->EOF)
{
	if ($id_tipo_documento == 3){
		$ultimo_numero_ocompra = $roww->fields("ultimo_numero_ocompra")+1;
		$ultimo_numero_oservicio = $roww->fields("ultimo_numero_oservicio");
		$numero_orden_serv = $partida.$ultimo_numero_ocompra;
	}elseif ($id_tipo_documento == 4){
		$ultimo_numero_oservicio = $roww->fields("ultimo_numero_oservicio")+1;
		$ultimo_numero_ocompra = $roww->fields("ultimo_numero_ocompra");
		$numero_orden_serv = $partida.$ultimo_numero_oservicio;
	}
}
	
//*****************************************************************************************************************************

//********************************************************************


if(!$row->EOF){

//numero_compromiso='".$numero_orden_serv."', 
	$sql = "	
	UPDATE 
		\"orden_compra_servicioE\"
	SET 
		numero_orden_compra_servicio='".$numero_orden_serv."',  
		
		ultimo_usuario=".$_SESSION['id_usuario'].",  
		fecha_actualizacion='".$fecha."'
	WHERE 
		numero_pre_orden = '".$_POST['ordenes_pr_nro_pre_orden']."';
		
	UPDATE 
			parametros_adquisiciones
		SET 
			ultimo_numero_ocompra='$ultimo_numero_ocompra',
			ultimo_numero_oservicio='$ultimo_numero_oservicio'
		WHERE 
			id_organismo = ".$_SESSION['id_organismo']."
		AND
			ano = '".date("Y")."';	
	UPDATE 
			\"solicitud_cotizacionE\"
		SET  
			orden_compra_servicioe='$numero_orden_serv'
		 WHERE  
			numero_cotizacion='".$_POST['ordenes_pr_nro_cotizacion']."';
			
	UPDATE 
			requisicion_detalle
		SET 
			numero_orden_compra_servicio='".$numero_orden_serv."'
		 WHERE 
			numero_cotizacion='".$_POST['ordenes_pr_nro_cotizacion']."'
		 ;
				
				";
		
		if (!$conn->Execute($sql)) {
			//echo ('Error al Registrar: '.$conn->ErrorMsg());
			echo ($sql );
		}else{
				echo("Registrado,".$numero_orden_serv);
		}
		//die ($sql);

}else{
	die("Existe");
}

?>