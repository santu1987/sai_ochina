<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$estatus=$_POST['unidad_ejecutora_db_estatus'];
$regional=$_POST['unidad_ejecutora_db_estatus2'];

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlBus = "SELECT nombre FROM unidad_ejecutora WHERE upper(nombre) = '".strtoupper($_POST[unidad_ejecutora_db_nombre])."' AND id_unidad_ejecutora<>$_POST[unidad_ejecutora_db_id]";
$row=& $conn->Execute($sqlBus);
if($row->EOF)
	$sql = "	
					UPDATE unidad_ejecutora  
						 SET
							nombre = '$_POST[unidad_ejecutora_db_nombre]',
							jefe_unidad = '$_POST[unidad_ejecutora_db_jefe]',
							comentario ='$_POST[unidad_ejecutora_db_comentario]',
							tipo_unidad = $estatus,
							unidad_regional = $regional,
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE id_unidad_ejecutora = $_POST[unidad_ejecutora_db_id]
							
				";
else
	die("Existe");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");

?>