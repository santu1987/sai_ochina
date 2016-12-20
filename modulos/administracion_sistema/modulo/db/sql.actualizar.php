<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id FROM modulo WHERE id <> $_POST[vista_id_modulo] AND upper(nombre) ='".strtoupper($_POST['modulo_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF)
	$sql = "	
					UPDATE modulo  
						 SET
							nombre = '$_POST[modulo_db_vista_nombre]',
							obs = '$_POST[modulo_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id = $_POST[vista_id_modulo]
							
				";
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>