<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$id = $_POST['cargar_cotizacion_pr_id_detalle'];
$sqlBus = "SELECT * FROM \"solicitud_cotizacionD\" WHERE (id_solicitud_cotizacion = ".$id.") AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);
$monto = str_replace(",","",$_POST['cargar_cotizacion_pr_costo']);
			
if(!$row->EOF){
			$sqlsolideta = 	"UPDATE 
								\"solicitud_cotizacionD\"
							SET 
								monto='".$monto."', 
								ultimo_usuario=".$_SESSION['id_usuario'].", 
								fecha_actualizacion='".$fecha."'
							WHERE 
								(id_solicitud_cotizacion = ".$id.") ";


}else{
	die("Existe");
}								
			if (!$conn->Execute($sqlsolideta)) 
				die ('Error al Actulizar: '.$conn->ErrorMsg());
			else
				die("Actualizado");

?>





