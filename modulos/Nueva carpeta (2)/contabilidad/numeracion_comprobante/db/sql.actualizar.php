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
			ano=$_POST[contabilidad_numeracion_comprobante_db_ano] AND 
			id<>$_POST[contabilidad_numeracion_comprobante_db_id] 
		"; 


if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);
if(!$row->EOF) die('Existe');

$sql = "	
		UPDATE  
			numeracion_comprobante
		SET		
			ano=$_POST[contabilidad_numeracion_comprobante_db_ano],
			numero_comprobante=$_POST[contabilidad_numeracion_comprobante_db_numero_comprobante],
			numero_comprobante_integracion=$_POST[contabilidad_numeracion_comprobante_db_numero_comprobante_integracion],
			ultimo_usuario=".$_SESSION['id_usuario'].",
			fecha_actualizacion='".date("Y-m-d H:i:s")."' 
		WHERE 
			id=$_POST[contabilidad_numeracion_comprobante_db_id]
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>