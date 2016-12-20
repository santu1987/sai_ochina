<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sqlb = "SELECT id_forma_de_pago FROM sareta.formas_de_pago WHERE id_forma_de_pago <> $_POST[vista_id_forma_de_pago] AND upper(nombre) ='".strtoupper($_POST['sareta_forma_de_pago_db_vista_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF){
	
	$sql = "	
					UPDATE sareta.formas_de_pago  
						 SET
							nombre = '".strtoupper($_POST[sareta_forma_de_pago_db_vista_nombre])."',
							comentario = '$_POST[sareta_forma_de_pago_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_forma_de_pago = $_POST[vista_id_forma_de_pago]
							
				";
}else{
	die("NoActualizo");			
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>