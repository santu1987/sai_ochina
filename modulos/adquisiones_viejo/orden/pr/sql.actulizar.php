<?php
session_start();
//*****************************************************************************************************************************

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//*****************************************************************************************************************************
$fecha = date("Y-m-d H:i:s");
$ano = date("Y");
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $_POST['ordenes_pr_partida']);
$ordenes_pr_monto= str_replace(".","",$_POST['ordenes_pr_monto']);
$ordenes_pr_monto= str_replace(",",".",$ordenes_pr_monto);

$ordenes_pr_iva= str_replace(".","",$_POST['ordenes_pr_iva']);
$ordenes_pr_iva= str_replace(",",".",$ordenes_pr_iva);
//*****************************************************************************************************************************
$sql = "UPDATE 
	\"solicitud_cotizacionD\"
SET 
	cantidad=".$_POST['ordenes_pr_cantidad'].", 
	monto=".$ordenes_pr_monto.",
	ultimo_usuario=".$_SESSION['id_usuario'].",
	fecha_actualizacion='".$fecha."'
WHERE 
	(secuencia =".$_POST['ordenes_pr_idd'].")
AND 	
	(numero_cotizacion = '".$_POST['ordenes_pr_nro_cotizacion']."')
	";
//*****************************************************************************************************************************
if (!$conn->Execute($sql)) {
		echo ('Error al Actulizar: '.$sql);
	}else{
		echo("Registrado");
	}
	
	
?>