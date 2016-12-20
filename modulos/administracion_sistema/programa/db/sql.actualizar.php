<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT nombre FROM programa WHERE id<>$_POST[programa_db_id] AND (id_modulo=$_POST[programa_db_id_modulo] AND id_proceso=$_POST[programa_db_id_proceso] AND upper(nombre)='".strtoupper($_POST[perfil_db_nombre])."')";
if (!$conn->Execute($sql)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
					UPDATE programa  
						 SET
							id_modulo = $_POST[programa_db_id_modulo],
							id_proceso = $_POST[programa_db_id_proceso],						
							nombre = '$_POST[programa_db_nombre]',
							pagina ='$_POST[programa_db_pagina]',
							obs = '$_POST[programa_db_obs]',
							icono = '$_POST[programa_db_icono]'
						WHERE id = $_POST[programa_db_id]
							
				";

else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>