<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlBus = "SELECT nombre FROM proyecto WHERE id_proyecto!=$_POST[proyecto_db_id] AND upper(nombre)='".strtoupper($_POST[proyecto_db_nombre])."'";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					UPDATE proyecto  
						 SET
							nombre = '".$_POST[proyecto_db_nombre]."',
							id_jefe_proyecto = $_POST[proyecto_db_jefe_proyecto_id],
							codigo_proyecto = '$_POST[proyecto_db_codigo]',
							comentario ='$_POST[proyecto_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE id_proyecto = $_POST[proyecto_db_id]
							
				";
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>