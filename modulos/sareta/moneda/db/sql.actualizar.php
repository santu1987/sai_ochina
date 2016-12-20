<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_moneda FROM sareta.moneda WHERE id_moneda <> $_POST[vista_id_moneda] AND upper(nombre) ='".strtoupper($_POST['sareta_moneda_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	$sqlb = "SELECT id_moneda FROM sareta.moneda WHERE id_moneda <> $_POST[vista_id_moneda] AND upper(abreviatura) ='".strtoupper($_POST['sareta_moneda_db_vista_abreviatura'])."'";
	if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
	$row=& $conn->Execute($sqlb);
	if($row->EOF){
	$sql = "	
					UPDATE sareta.moneda  
						 SET
							nombre = '".strtoupper($_POST[sareta_moneda_db_vista_nombre])."',
							multiplica_divide= '".strtoupper($_POST[sareta_moneda_db_vista_multiplica_divide])."',
							abreviatura ='".strtoupper($_POST[sareta_moneda_db_vista_abreviatura])."',
							obs = '$_POST[sareta_moneda_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_moneda = $_POST[vista_id_moneda]
							
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