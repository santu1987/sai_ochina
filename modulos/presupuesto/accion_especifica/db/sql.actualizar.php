<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$fecha = date("Y-m-d H:i:s");
$sql = "SELECT id_accion_especifica FROM accion_especifica WHERE id_accion_especifica!=$_POST[accion_especifica_db_id] AND upper(denominacion)='".strtoupper($_POST[accion_especifica_db_denominacion])."'";
$row=& $conn->Execute($sql);
$proyecto = $_POST[accion_especifica_db_proyecto];
$accion_central = $_POST[accion_especifica_db_accion_central];
if ($proyecto =="")
	$proyecto =0;
if ($accion_central =="")
	$accion_central =0;
if($row->EOF)
	$sql = "	
					UPDATE accion_especifica  
						 SET
							codigo_accion_especifica = '$_POST[accion_especifica_db_codigo]',
							denominacion = '$_POST[accion_especifica_db_denominacion]',
							comentario ='$_POST[accion_especifica_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."',
							id_jefe_proyecto = '$_POST[accion_especifica_db_jefe_accion_id]',
							id_proyecto = $proyecto,
							id_accion_central = $accion_central
						WHERE id_accion_especifica = $_POST[accion_especifica_db_id]
							
				";

else
	//die("NoActualizo");			
	die($sql);
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>