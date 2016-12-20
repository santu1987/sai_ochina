<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "
			SELECT 
				usuario_banco_cuentas.id_usuario_banco_cuentas
			FROM 
				usuario_banco_cuentas
			INNER JOIN
				organismo
			ON
			usuario_banco_cuentas.id_organismo=organismo.id_organismo	
			INNER JOIN
				cheques
			ON
			usuario_banco_cuentas.id_usuario=cheques.usuario_cheque	
	        WHERE
				usuario_banco_cuentas.id_usuario_banco_cuentas='$_POST[tesoreria_usuario_banco_cuentas_db_id]' 
			AND
				cheques.id_banco='$_POST[tesoreria_usuario_banco_cuentas_cuenta_id_banco]'
			AND	
				cheques.cuenta_banco='$_POST[tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta]'		
			";
$row= $conn->Execute($sql);

if($row->EOF)
	$sql = "DELETE FROM usuario_banco_cuentas WHERE usuario_banco_cuentas.id_usuario_banco_cuentas ='$_POST[tesoreria_usuario_banco_cuentas_db_id]'";
else
	$bloqueado=true;

if (!$conn->Execute($sql)||$bloqueado){
//die($sql);
	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');
	}
else
	
	echo 'Eliminado';
?>