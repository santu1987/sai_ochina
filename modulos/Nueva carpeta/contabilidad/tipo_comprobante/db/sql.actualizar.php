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
			id<>$_POST[contabilidad_tipo_comprobante_db_id] AND 
			(
			 	codigo_tipo_comprobante=$_POST[contabilidad_tipo_comprobante_db_codigo_comprobante] OR
				upper(nombre) ='".strtoupper($_POST[contabilidad_tipo_comprobante_db_nombre])."'
			)
		"; 


if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);
if(!$row->EOF) die('Existe');

$sql = "	
		UPDATE  
			tipo_comprobante
		SET		
			codigo_tipo_comprobante=$_POST[contabilidad_tipo_comprobante_db_codigo_comprobante],
			nombre='$_POST[contabilidad_tipo_comprobante_db_nombre]',
			comentario='$_POST[contabilidad_tipo_comprobante_db_comentario]',
			ultimo_usuario=".$_SESSION['id_usuario'].",
			fecha_actualizacion='".date("Y-m-d H:i:s")."' ,
			numero_comprobante='$_POST[contabilidad_tipo_comprobante_db_numero_comp]',
			numero_comprobante_integracion='$_POST[contabilidad_tipo_comprobante_db_numero_comp_int]'
		WHERE 
			id=$_POST[contabilidad_tipo_comprobante_db_id]
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>