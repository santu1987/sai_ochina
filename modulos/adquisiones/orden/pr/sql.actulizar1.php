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
//*********************************************************************************************************
$cantida_requi = 0;
$sql_busrequi = "
SELECT 
	distinct requisicion_detalle.numero_requision
FROM 
	\"solicitud_cotizacionD\" 
INNER JOIN 
	requisicion_detalle 
ON
	requisicion_detalle.numero_requision = \"solicitud_cotizacionD\".numero_requisicion 
WHERE 
	(\"solicitud_cotizacionD\".id_organismo=1)
AND
	(\"solicitud_cotizacionD\".numero_cotizacion = '".$_POST['ordenes_pr_nro_cotizacion']."')
ORDER BY 
	requisicion_detalle.numero_requision
";
$busrequi=& $conn->Execute($sql_busrequi);
//echo ($sql_busrequi);	
$nro_requision = $busrequi->fields("numero_requision");
$sql_eran="
SELECT 
	cantidad
FROM 
	requisicion_detalle 
WHERE
	secuencia = ".$_POST['ordenes_pr_idd']."
AND
	numero_requision = '".$nro_requision."'
";
$rs_eran=& $conn->Execute($sql_eran);
//die($sql_eran);
if(!$rs_eran->EOF){
	$cantida_requi = $rs_eran->fields("cantidad");
}
if ($cantida_requi > $_POST['ordenes_pr_cantidad']){
	$estatus = 1;
}else{
	$estatus = 2;
}	

//*********************************************************************************************************
$sql = "
UPDATE 
	requisicion_detalle
SET 
	 
	estatu_compra=".$estatus."
WHERE
	numero_requision='$nro_requision'
AND
	secuencia=".$_POST['ordenes_pr_idd'].";

UPDATE 
	\"orden_compra_servicioD\"
SET 
	cantidad=".$_POST['ordenes_pr_cantidad'].", 
	monto=".$ordenes_pr_monto."
WHERE 
	(secuencia =".$_POST['ordenes_pr_idd'].")
AND 	
	(numero_pre_orden = '".$_POST['ordenes_pr_nro_pre_orden']."')
	";
//*****************************************************************************************************************************
if (!$conn->Execute($sql)) {
		echo ('Error al Actulizar: '.$sql);
	}else{
		echo("Registrado");
	}
	
	
?>