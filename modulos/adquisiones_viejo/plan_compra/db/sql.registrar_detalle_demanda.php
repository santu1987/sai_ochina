<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	
$sqlBus = "SELECT * FROM detalle_demanda WHERE (upper(nombre) = '".strtoupper($_POST[detalle_demanda_db_nombre])."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);
			
if($row->EOF){
$sql = "	
		INSERT INTO detalle_demanda(
				id_organismo, 
				id_demanda,
				codigo_detalle_demanda,
				nombre, 
				comentario, 
				ultimo_usuario, 
				fecha_actualizacion
		)VALUES 	(
				".$_SESSION['id_organismo'].",
				".$_POST['detalle_demanda_db_id_demanda'].", 
				'".$_POST['detalle_demanda_db_codigo']."', 
				'".$_POST['detalle_demanda_db_nombre']."', 
				'".$_POST['detalle_demanda_db_comentario']."', 
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'
		)
";
//echo($sql);
}else{
	die("NoRegistro");
	}
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>