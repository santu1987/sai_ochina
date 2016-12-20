<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT nombre FROM programa WHERE id_modulo=$_POST[programa_db_id_modulo] AND id_proceso=$_POST[programa_db_id_proceso] AND upper(nombre)='".strtoupper($_POST[perfil_db_nombre])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
				INSERT INTO 
					programa 
					(
						id_modulo,
						id_proceso,						
						nombre,
						pagina,
						obs,
						icono,
						ultimo_usuario,
						fecha_actualizacion						
					) 
					VALUES
					(
						$_POST[programa_db_id_modulo],
						$_POST[programa_db_id_proceso],						
						'$_POST[programa_db_nombre]',
						'$_POST[programa_db_pagina]',
						'$_POST[programa_db_obs]',
						'$_POST[programa_db_icono]',
						$_SESSION[id_usuario],
						'".date("Y-m-d")."'						
					)
			";
else
	die("NoRegistro");
	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql);
else
	die("Registrado");
?>