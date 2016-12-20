<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM tipo_documento WHERE (upper(nombre) = '".strtoupper($_POST[tipo_documento_db_nombre])."')";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					INSERT INTO 
						tipo_documento
						(
							id_organismo,
							nombre,
							comentario,
							ultimo_usuario,
							fecha_actualizacion
						) 
						VALUES
						(
							".$_SESSION['id_organismo'].",
							'$_POST[tipo_documento_db_nombre]',
							'$_POST[tipo_documento_db_comentario]',
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