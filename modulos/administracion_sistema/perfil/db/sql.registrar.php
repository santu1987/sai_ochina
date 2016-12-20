<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT nombre FROM perfil WHERE upper(nombre)='".strtoupper($_POST[perfil_db_nombre])."'";
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
					INSERT INTO 
						perfil 
						(
							nombre,
							comentario,
							ultimo_usuario,
							fecha_actualizacion
						) 
						VALUES
						(
							'$_POST[perfil_db_vista_nombre]',
							'$_POST[perfil_db_vista_comentarios]',
							".$_SESSION['id_usuario'].",
							'".date("Y-m-d")."'
						)
				";
else
	$repetido=true;

if (!$conn->Execute($sql)||$repetido) 
	echo (($repetido)?$msgExiste:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Registrado';
?>