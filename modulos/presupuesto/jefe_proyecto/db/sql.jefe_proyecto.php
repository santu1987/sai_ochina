<?php
session_start();

$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Clasificacion Presupuestaria ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM jefe_proyecto WHERE (upper(nombre_jefe_proyecto) = '".strtoupper($_POST[firma_presupuesto_db_nombre_auto])."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

$cedula = $_POST[jefe_proyecto_db_nacionalidad]+$_POST[jefe_proyecto_db_cedula];
if ($_POST["jefe_proyecto_db_estatus"]==true)
	$estatus = 1;
else
	$estatus = 2;
if($row->EOF)
$sql = "	
				INSERT INTO 
					jefe_proyecto 
					(
						id_organismo,
						cedula_jefe_proyecto ,
						nombre_jefe_proyecto ,
						cargo_jefe_proyecto, 
						estatus ,
						grado_jefe_proyecto ,
						ultimo_usuario ,
						fecha_actualizacion

					) 
					VALUES
					(
						".$_SESSION['id_organismo'].",
						".$cedula.",
						'$_POST[jefe_proyecto_db_nombre]',
						'$_POST[jefe_proyecto_db_cargo]',
						".$_POST["jefe_proyecto_db_estatus"].",
						'$_POST[jefe_proyecto_db_grado]',
						".$_SESSION['id_usuario'].",
						'".$fecha."'
					)
			";
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");

?>