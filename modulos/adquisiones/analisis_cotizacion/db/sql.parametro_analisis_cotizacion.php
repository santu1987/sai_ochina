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
	(upper(aspecto) = '".strtoupper($_POST[parametro_analisis_db_aspecto])."')
OR
	(peso = ".$_POST[parametro_analisis_db_peso].")	
";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					INSERT INTO 
						parametro_analisis_cotizacion
						(
							id_organismo,
							aspecto,
							peso,
							ultimo_usuario,
							fecha_actualizacion
						) 
						VALUES
						(
							".$_SESSION['id_organismo'].",
							'$_POST[parametro_analisis_db_aspecto]',
							$_POST[parametro_analisis_db_peso],
							".$_SESSION['id_usuario'].",
							'".$fecha."'
						)
				";
else
	$repetido=true;

	
if (!$conn->Execute($sql)||$repetido) 
	echo (($repetido)?$msgExiste:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Ok';

?>