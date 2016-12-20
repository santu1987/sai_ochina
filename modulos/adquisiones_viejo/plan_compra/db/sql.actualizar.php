<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_tipo_demanda FROM tipo_demanda WHERE id_tipo_demanda <> $_POST[vista_id_tipo_demanda] AND upper(nombre) ='".strtoupper($_POST['tipo_demanda_db_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF)
	$sql = "	
					UPDATE tipo_demanda  
						 SET
							nombre = '$_POST[tipo_demanda_db_nombre]',
							comentario = '$_POST[tipo_demanda_db_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_tipo_demanda = $_POST[vista_id_tipo_demanda]
							
				";
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>