<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

	$sql = "	
					UPDATE jefe_proyecto  
						 SET
							cedula_jefe_proyecto = '$_POST[jefe_proyecto_db_cedula]',
							nombre_jefe_proyecto  = '$_POST[jefe_proyecto_db_nombre]',
							cargo_jefe_proyecto  = '$_POST[jefe_proyecto_db_cargo]',
							estatus = '$_POST[jefe_proyecto_db_estatus]',
							grado_jefe_proyecto  = '$_POST[jefe_proyecto_db_grado]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE
							id_jefe_proyecto = ".$_POST[jefe_proyecto_db_id];

	
if (!$conn->Execute($sql)||$repetido) {
	echo (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
	//echo $sqlbus;
}
else
{
	echo 'Actualizado';
}
?>