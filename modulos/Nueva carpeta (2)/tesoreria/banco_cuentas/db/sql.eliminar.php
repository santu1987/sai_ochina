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
				banco_cuentas.cuenta_banco
			FROM 
				banco_cuentas
			INNER JOIN
				organismo
			ON
			banco_cuentas.id_organismo=organismo.id_organismo	
			INNER JOIN
				chequeras
			ON
			banco_cuentas.cuenta_banco=chequeras.cuenta	
	        WHERE
				banco_cuentas.id_cuenta_banco='$_POST[tesoreria_vista_banco_cuenta]' 
			AND
				banco_cuentas.id_organismo=$_SESSION[id_organismo]	
			";
			
$row= $conn->Execute($sql);

if($row->EOF){
	$sql = "DELETE FROM banco_cuentas WHERE banco_cuentas.id_cuenta_banco='$_POST[tesoreria_vista_banco_cuenta]' AND banco_cuentas.id_organismo=$_SESSION[id_organismo]";
}
else
	$bloqueado=true;

if (!$conn->Execute($sql)||$bloqueado){
//die($sql);
	//echo("error_eliminar");
echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');}
else
	echo 'Eliminado';
?>