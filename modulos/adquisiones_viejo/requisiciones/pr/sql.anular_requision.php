<?
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("d-m-Y");

$numero_requision = $_POST['anular_requisicion_numero'];


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
	$anula_req = "
	UPDATE 
		requisicion_encabezado
	SET 
		usuario_anula= ".$_SESSION['id_usuario'].", 
		fecha_anula='$fecha'
	WHERE 
		numero_requisicion='$numero_requision'
	
	";
	if (!$conn->Execute($anula_req)) {
		die ('Error al Registrar: '.$anula_req);
	}else{
		die ('Registrado');
	}
}else{
	die("cotizacion_existe");
}
?>