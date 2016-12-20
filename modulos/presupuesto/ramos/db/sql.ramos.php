<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM ramo WHERE (upper(nombre) = '".strtoupper($_POST[ramos_db_nombre])."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
$sql = "	
				INSERT INTO 
					ramo 
					(
						
						id_organismo,
						nombre,
						comentario,
						fecha_actualizacion,
						ultimo_usuario
					) 
					VALUES
					(
					    
						".$_SESSION['id_organismo'].",
						'$_POST[ramos_db_nombre]',
						'$_POST[ramos_db_comentario]',
						'".$fecha."',
						'".$_SESSION['id_usuario']."'
					)
			";
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>