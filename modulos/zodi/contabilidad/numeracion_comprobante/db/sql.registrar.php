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
			numeracion_comprobante 
		WHERE 
			id_organismo=$_SESSION[id_organismo] AND 
		 	ano=$_POST[contabilidad_numeracion_comprobante_db_ano]
		"; 


if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);
if(!$row->EOF) die('Existe');

$sql = "	
		INSERT INTO 
			numeracion_comprobante
			(
				id_organismo,
				ano,
				numero_comprobante,
				numero_comprobante_integracion,
				ultimo_usuario,
				fecha_actualizacion
			) 
			VALUES
			(
				$_SESSION[id_organismo],
				$_POST[contabilidad_numeracion_comprobante_db_ano],
				$_POST[contabilidad_numeracion_comprobante_db_numero_comprobante],
				$_POST[contabilidad_numeracion_comprobante_db_numero_comprobante_integracion],
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'						
			)
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>