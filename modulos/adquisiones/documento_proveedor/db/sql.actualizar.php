<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT nombre FROM documento WHERE documento.id_documento_proveedor<>$_POST[documento_proveedor_db_id] AND upper(nombre)='".strtoupper($_POST[documento_proveedor_db_nombre])."'";
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "		UPDATE documento  
						 SET
						 	
							nombre = '$_POST[documento_proveedor_db_nombre]',
							estatus='$_POST[documento_proveedor_db_estatus]',
							codigo_documento='$_POST[documento_proveedor_db_codigo]',
							comentario='$_POST[documento_proveedor_db_observacion]', 
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_actualizacion='".$fecha."'
						WHERE id_documento_proveedor = $_POST[documento_proveedor_db_id]
				";
else
	die ("NoActualizo");
if (!$conn->Execute($sql)) {
	die ('Error al Actualizar: '.$conn->ErrorMsg());}
else {
	die ('Actualizado');
	}
?>