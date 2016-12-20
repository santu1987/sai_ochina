<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_detalle_demanda FROM detalle_demanda WHERE id_detalle_demanda <> $_POST[id_detalle_demanda] AND upper(nombre) ='".strtoupper($_POST['detalle_demanda_db_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF)
	$sql = "	
					UPDATE detalle_demanda  
						 SET
							id_demanda = '$_POST[detalle_demanda_db_id_demanda]',
							codigo_detalle_demanda = '$_POST[detalle_demanda_db_codigo]',
							nombre = '$_POST[detalle_demanda_db_nombre]',
							comentario = '$_POST[detalle_demanda_db_comentario]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_detalle_demanda = $_POST[id_detalle_demanda]
							
				";
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>