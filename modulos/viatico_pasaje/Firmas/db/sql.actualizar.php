<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_armador FROM armador WHERE id_armador <> $_POST[vista_id_armador] AND upper(nombre) ='".strtoupper($_POST['sareta_armador_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	
	$sql = "	
					UPDATE armador  
						 SET
							nombre = '".strtoupper($_POST[sareta_armador_db_vista_nombre])."',
							obs = '$_POST[sareta_armador_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_armador = $_POST[vista_id_armador]
							
				";
}else{
	die("NoActualizo");			
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>