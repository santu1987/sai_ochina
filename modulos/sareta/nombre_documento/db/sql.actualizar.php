<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id FROM sareta.nombre_documento WHERE id != $_POST[vista_id_nombre_documento] AND( codigo=".$_POST['sareta_nombre_documento_db_vista_codigo']."  or upper(descripcion) ='".strtoupper($_POST['sareta_nombre_documento_db_vista_nombre'])."')";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	
	$sql = "	
					UPDATE sareta.nombre_documento 
						 SET
						 	codigo= ".strtoupper($_POST[sareta_nombre_documento_db_vista_codigo]).",
							descripcion = '".strtoupper($_POST[sareta_nombre_documento_db_vista_nombre])."',				
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id= $_POST[vista_id_nombre_documento]
							
				";

}else{
	die("NoActualizo");			
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>