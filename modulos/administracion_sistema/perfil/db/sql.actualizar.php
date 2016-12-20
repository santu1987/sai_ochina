<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT nombre FROM perfil WHERE id_perfil<>$_POST[perfil_db_id_perfil] AND upper(nombre)='".strtoupper($_POST[perfil_db_nombre])."'";
if (!$conn->Execute($sql)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
					UPDATE 
						perfil  
					 SET
						nombre = '$_POST[perfil_db_vista_nombre]',
						comentario = '$_POST[perfil_db_vista_comentarios]',						
						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion ='".date("Y-m-d")."'
					WHERE 
						id_perfil = $_POST[perfil_db_id_perfil]
							
				";
else
	die("NoActualizo");
	
if (!$conn->Execute($sql)) 
	die (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
else
	die("Actualizado");
?>