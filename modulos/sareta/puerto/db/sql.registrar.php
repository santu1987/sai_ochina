<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_puerto FROM sareta.puerto WHERE upper(nombre) ='".strtoupper($_POST['sareta_puerto_db_vista_nombre'])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){

$sql = "	
				INSERT INTO 
					sareta.puerto 
					(
						nombre,
						id_bandera,
						obs,
						ultimo_usuario,
						fecha_creacion,
						fecha_actualizacion
					) 
					VALUES
					(
						upper('$_POST[sareta_puerto_db_vista_nombre]'),
						$_POST[puerto_vista_id_bandera],
						'$_POST[sareta_puerto_db_vista_observacion]',
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