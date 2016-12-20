<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_tipo_actividad FROM sareta.tipo_actividad WHERE id_tipo_actividad <> $_POST[vista_id_tipo_actividad] AND upper(nombre) ='".strtoupper($_POST['sareta_tipo_actividad_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	
	$sql = "	
					UPDATE sareta.tipo_actividad  
						 SET
							nombre = '".strtoupper($_POST[sareta_tipo_actividad_db_vista_nombre])."',
							obs = '$_POST[sareta_tipo_actividad_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_tipo_actividad = $_POST[vista_id_tipo_actividad]
							
				";
}else{
	die("NoActualizo");			
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>