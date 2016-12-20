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
				banco.id_banco
			FROM 
				banco
			INNER JOIN
				organismo
			ON
			banco.id_organismo=organismo.id_organismo	
			INNER JOIN
				banco_cuentas
			ON
			banco.id_banco=banco_cuentas.id_banco	
			
	        WHERE
				banco.id_banco=$_POST[tesoreria_vista_banco] 
			AND	
				banco.id_organismo = ".$_SESSION["id_organismo"]."	
			";
$row= $conn->Execute($sql);

if($row->EOF)
	$sql = "DELETE FROM banco WHERE id_banco = $_POST[tesoreria_vista_banco]";
else
	$bloqueado=true;

if (!$conn->Execute($sql)||$bloqueado){

	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');}
else
	echo 'Eliminado';
?>