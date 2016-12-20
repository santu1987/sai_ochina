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
			codigo='$_POST[contabilidad_naturaleza_cuenta_db_codigo]' AND 
			id<>$_POST[contabilidad_naturaleza_cuenta_db_id] 
		"; 


if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);
if(!$row->EOF) die('Existe');

$sql = "	
		UPDATE  
			naturaleza_cuenta
		SET		
			codigo='".strtoupper($_POST[contabilidad_naturaleza_cuenta_db_codigo])."',
			descripcion='".strtoupper($_POST[contabilidad_naturaleza_cuenta_db_descripcion])."',
			ultimo_usuario=".$_SESSION['id_usuario'].",
			fecha_actualizacion='".date("Y-m-d H:i:s")."' 
		WHERE 
			id=$_POST[contabilidad_naturaleza_cuenta_db_id]
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>