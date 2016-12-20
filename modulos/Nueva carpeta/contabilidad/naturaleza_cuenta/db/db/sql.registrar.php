<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "
		SELECT 
			id 
		FROM 
			naturaleza_cuenta 
		WHERE 
			id_organismo=$_SESSION[id_organismo] AND 
		 	codigo='$_POST[contabilidad_naturaleza_cuenta_db_codigo]'
		"; 


if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);
if(!$row->EOF) die('Existe');

$sql = "	
		INSERT INTO 
			naturaleza_cuenta
			(
				id_organismo,
				codigo,
				descripcion,
				ultimo_usuario,
				fecha_actualizacion
			) 
			VALUES
			(
				$_SESSION[id_organismo],
				'".strtoupper($_POST[contabilidad_naturaleza_cuenta_db_codigo])."',
				'".strtoupper($_POST[contabilidad_naturaleza_cuenta_db_descripcion])."',
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'						
			)
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>