<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id FROM sareta.bandera WHERE id <> $_POST[vista_id_bandera] AND upper(nombre) ='".strtoupper($_POST['sareta_bandera_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	$sqlb = "SELECT id FROM sareta.bandera WHERE id <> $_POST[vista_id_bandera] AND upper(abreviatura) ='".strtoupper($_POST['sareta_bandera_db_vista_abreviatura'])."'";
	if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
	$row=& $conn->Execute($sqlb);
	if($row->EOF){
	$sql = "	
					UPDATE sareta.bandera  
						 SET
							nombre = '".strtoupper($_POST[sareta_bandera_db_vista_nombre])."',
							abreviatura ='".strtoupper($_POST[sareta_bandera_db_vista_abreviatura])."',
							obs = '$_POST[sareta_bandera_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id = $_POST[vista_id_bandera]
							
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