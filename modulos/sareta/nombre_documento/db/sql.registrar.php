<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id FROM sareta.nombre_documento WHERE codigo=".$_POST['sareta_nombre_documento_db_vista_codigo']." or upper(descripcion) ='".strtoupper($_POST['sareta_nombre_documento_db_vista_nombre'])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){

$sql = "	
				INSERT INTO 
					sareta.nombre_documento 
					(
					 	codigo,
						descripcion,
						ultimo_usuario,
						fecha_creacion,
						fecha_actualizacion
					) 
					VALUES
					(
						$_POST[sareta_nombre_documento_db_vista_codigo],
						upper('$_POST[sareta_nombre_documento_db_vista_nombre]'),
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
						'".date("Y-m-d H:i:s")."'
						
					)";
	

}else{
	die("NoRegistro");
}
	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Registrado");
?>