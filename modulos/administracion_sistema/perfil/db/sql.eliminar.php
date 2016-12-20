<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "
			SELECT 
				perfil.id_perfil 
			FROM 
				perfil 
			WHERE 
				$_POST[perfil_db_id_perfil] IN  (SELECT id_perfil FROM perfil_organismo) OR 
				$_POST[perfil_db_id_perfil] IN  (SELECT id_perfil FROM perfil_programa) OR 
				$_POST[perfil_db_id_perfil] IN  (SELECT id_perfil FROM perfil_usuario) OR 
				$_POST[perfil_db_id_perfil] IN  (SELECT id_perfil FROM perfil_modulo)
			";
$row= $conn->Execute($sql);

if($row->EOF)
	$sql = "DELETE FROM perfil WHERE id_perfil = $_POST[perfil_db_id_perfil]";
else
	$bloqueado=true;

if (!$conn->Execute($sql)||$bloqueado)
	die($sql);
	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Eliminado';
?>