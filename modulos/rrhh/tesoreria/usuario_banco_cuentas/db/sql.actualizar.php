<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST[tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta];
$sql = "SELECT count(id_usuario) FROM  usuario_banco_cuentas WHERE id_banco= $_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco] AND cuenta_banco ='".$cuenta."'";
$row_prueba= $conn->Execute($sql);
$count=$row_prueba->fields("count");	
if($count==1)
{
			$sql = "SELECT banco_cuentas.cuenta_banco FROM  banco_cuentas WHERE id_banco= $_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco] AND cuenta_banco ='".$cuenta."'";
			if (!$conn->Execute($sql)) die ('Error al actualizar: '.$conn->ErrorMsg());
					$row= $conn->Execute($sql);
			
			if(!$row->EOF)
							{
								$sql = "		UPDATE usuario_banco_cuentas 
													 SET
														id_banco= '$_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco]',
														cuenta_banco ='$_POST[tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta]',
														estatus=	'$_POST[tesoreria_usuario_banco_cuentas_db_estatus]',
														comentarios='$_POST[tesoreria_usuario_banco_cuentas_db_comentarios]',
														ultimo_usuario=".$_SESSION['id_usuario'].", 
														fecha_ultima_modificacion='".$fecha."'
													WHERE id_usuario_banco_cuentas  = $_POST[tesoreria_usuario_banco_cuentas_db_id]
													AND
							                            id_organismo=$_SESSION[id_organismo]

											";
								}								
			else
					die ("NoActualizo");
			if (!$conn->Execute($sql)) {
				
				die ('Error al Actualizar: '.$conn->ErrorMsg());}
			else {
				die ('Actualizado');
				}
}else
		//die($sql);
		die('Registro existe con otro identificador');		
?>