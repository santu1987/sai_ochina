<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM accion_centralizada WHERE (codigo_accion_central = '".$_POST[accion_centralizada_db_codigo]."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);
$ano=date('Y');
$ano = $ano +1 ;
if($row->EOF)
	$sql = "	
					INSERT INTO 
						accion_centralizada
						(
							id_organismo,
							ano,
							codigo_accion_central,
							denominacion,
							comentario,
							ultimo_usuario,
							fecha_actualizacion,
							id_jefe_proyecto
						) 
						VALUES
						(
							".$_SESSION['id_organismo'].",
							".$ano.",
							'$_POST[accion_centralizada_db_codigo]',
							'$_POST[accion_centralizada_db_nombre]',
							'$_POST[accion_centralizada_db_comentario]',
							".$_SESSION['id_usuario'].",
							'".$fecha."',
							$_POST[accion_centralizada_db_jefe_accion_id]
						)
				";
				
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
	//die($sql);
else
	die("Registrado");

?>