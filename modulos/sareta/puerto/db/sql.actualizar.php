<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_puerto FROM sareta.puerto WHERE id_puerto <> $_POST[vista_id_puerto] AND upper(nombre) ='".strtoupper($_POST['sareta_puerto_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	
	$sql = "	
					UPDATE sareta.puerto  
						 SET
							nombre = '".strtoupper($_POST[sareta_puerto_db_vista_nombre])."',
							id_bandera ='".strtoupper($_POST[puerto_vista_id_bandera])."',
							obs = '$_POST[sareta_puerto_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_puerto = $_POST[vista_id_puerto]
							
				";

}else{
	die("NoActualizo");			
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>