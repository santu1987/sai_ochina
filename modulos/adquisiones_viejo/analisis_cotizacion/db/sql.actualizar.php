<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "
SELECT * FROM 
	parametro_analisis_cotizacion 
WHERE 
	((upper(aspecto) = '".strtoupper($_POST[parametro_analisis_db_aspecto])."')
OR
	(peso = ".$_POST[parametro_analisis_db_peso].")	)
AND
	id_parametro_analisis_cotizacion != $_POST[parametro_analisis_db_id]
	
";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					UPDATE  
						parametro_analisis_cotizacion
					SET
						aspecto = '$_POST[parametro_analisis_db_aspecto]',
						peso = $_POST[parametro_analisis_db_peso],
						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion = '".$fecha."'
					WHERE
						id_parametro_analisis_cotizacion = $_POST[parametro_analisis_db_id]
				";
else
	$repetido=true;

	
if (!$conn->Execute($sql)||$repetido) 
	echo (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Ok';

?>