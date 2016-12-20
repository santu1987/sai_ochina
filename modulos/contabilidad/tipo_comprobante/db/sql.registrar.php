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
			tipo_comprobante 
		WHERE 
			id_organismo=$_SESSION[id_organismo] AND 
			(
			 	codigo_tipo_comprobante=$_POST[contabilidad_tipo_comprobante_db_codigo_comprobante] OR
				upper(nombre) ='".strtoupper($_POST[contabilidad_tipo_comprobante_db_nombre])."'
			)
		"; 
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);
if(!$row->EOF) die('Existe');

$sql = "	
		INSERT INTO 
			tipo_comprobante
			(
				id_organismo,
				codigo_tipo_comprobante,
				nombre,
				comentario,
				ultimo_usuario,
				fecha_actualizacion,
				numero_comprobante,
				numero_comprobante_integracion
			) 
			VALUES
			(
				$_SESSION[id_organismo],
				$_POST[contabilidad_tipo_comprobante_db_codigo_comprobante],
				'$_POST[contabilidad_tipo_comprobante_db_nombre]',
				'$_POST[contabilidad_tipo_comprobante_db_comentario]',
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."',
				'$_POST[contabilidad_tipo_comprobante_db_numero_comp]',
				'$_POST[contabilidad_tipo_comprobante_db_numero_comp_int]'						
			)
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>