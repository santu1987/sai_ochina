<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_tipo_demanda FROM tipo_demanda WHERE (upper(nombre) ='".strtoupper($_POST['tipo_demanda_db_nombre'])."')";

if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
$sql = "	
		INSERT INTO tipo_demanda(
				id_organismo, 
				nombre, 
				comentario, 
				ultimo_usuario, 
				fecha_actualizacion
		)VALUES 	(
				".$_SESSION['id_organismo'].",
				'".$_POST['tipo_demanda_db_nombre']."', 
				'".$_POST['tipo_demanda_db_observacion']."', 
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