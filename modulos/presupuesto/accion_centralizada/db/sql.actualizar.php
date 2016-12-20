<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$id = str_replace("\\","",$_POST[accion_centralizada_db_id]);
$id = str_replace("\"","",$id );

$fecha = date("Y-m-d H:i:s");
$sqlBus = "SELECT denominacion FROM accion_centralizada WHERE id_accion_central != $id  AND upper(denominacion)='".strtoupper($_POST[accion_centralizada_db_nombre])."'";
if (!$conn->Execute($sqlBus)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					UPDATE accion_centralizada  
						 SET
							denominacion = '$_POST[accion_centralizada_db_nombre]',
							id_jefe_proyecto = $_POST[accion_centralizada_db_jefe_accion_id],
							codigo_accion_central = '$_POST[accion_centralizada_db_codigo]',
							comentario ='$_POST[accion_centralizada_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE id_accion_central = $id 
							
				";
else
	die ("NoActualizo");
	
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die ('Actualizado');
?>