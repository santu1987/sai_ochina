<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
if ($_POST[accion_especifica_db_proyecto] != "")
	$proyecto=$_POST[accion_especifica_db_proyecto];
else
	$proyecto= 0;
	
if ($_POST[accion_especifica_db_accion_central] != "")
	$accion_central=$_POST[accion_especifica_db_accion_central];
else
	$accion_central= 0;
	
	
$sqlBus = "SELECT * FROM accion_especifica WHERE ((codigo_accion_especifica) = '".$_POST[accion_especifica_db_codigo]."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					INSERT INTO 
						accion_especifica
						(
							id_organismo,
							denominacion,
							comentario,
							ultimo_usuario,
							fecha_actualizacion,
							id_jefe_proyecto,
							codigo_accion_especifica,
							id_accion_central,
							id_proyecto
						) 
						VALUES
						(
							".$_SESSION['id_organismo'].",
							'$_POST[accion_especifica_db_denominacion]',
							'$_POST[accion_especifica_db_comentario]',
							".$_SESSION['id_usuario'].",
							'".$fecha."',
							'$_POST[accion_especifica_db_jefe_accion_id]',
							'$_POST[accion_especifica_db_codigo]',
							$accion_central,
							$proyecto
						)
				";
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>