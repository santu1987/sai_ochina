<?php
session_start();

$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM proveedor WHERE  id_proveedor= '$_POST[cuentas_por_pagar_db_proveedor_ret_id]'";
$row=& $conn->Execute($sqlBus);
$islr=str_replace(".","",$_POST[cuentas_por_pagar_db_proveedor_ret_islr]);
$iva=str_replace(".","",$_POST[cuentas_por_pagar_db_proveedor_ret_iva]);
if(!$row->EOF){
	$sql = "	
					UPDATE
					
							proveedor
					SET
							fecha_actualizacion='".$fecha."',
							usuario_ingreso=".$_SESSION['id_usuario'].",
							ret_iva='".str_replace(",",".",$iva)."', 
							ret_islr='".str_replace(",",".",$islr)."'
					WHERE  
							id_proveedor= '$_POST[cuentas_por_pagar_db_proveedor_ret_id]'";
	}
	
	else
	die("Existe");
			if (!$conn->Execute($sql)) 
				die ('Error al Registrar: '.$sql);
				//$conn->ErrorMsg());
				else{
					die('Registrado');										
		    		   }												
								
?>