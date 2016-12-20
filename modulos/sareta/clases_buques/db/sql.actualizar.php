<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_clases_buques FROM sareta.clases_buques WHERE id_clases_buques <> $_POST[vista_id_clases_buques] AND upper(nombre) ='".strtoupper($_POST['sareta_clases_buques_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	$sqlb = "SELECT id_clases_buques FROM sareta.clases_buques WHERE id_clases_buques <> $_POST[vista_id_clases_buques] AND upper(abreviatura) ='".strtoupper($_POST['sareta_clases_buques_db_vista_abreviatura'])."'";
	if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
	$row=& $conn->Execute($sqlb);
	if($row->EOF){
	$sql = "	
					UPDATE sareta.clases_buques  
						 SET
							nombre = '".strtoupper($_POST[sareta_clases_buques_db_vista_nombre])."',
							abreviatura ='".strtoupper($_POST[sareta_clases_buques_db_vista_abreviatura])."',
							obs = '$_POST[sareta_clases_buques_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_clases_buques = $_POST[vista_id_clases_buques]
							
				";
	}else{
		die("Abreviatura_Existe");	}
}else{
	die("NoActualizo");			
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>