<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$nom=$_POST['tesoreria_banco_db_nombre'];
$suc=$_POST['tesoreria_banco_db_sucursal'];
$nombre_banco=$nom."-".$suc;
$nombre_banco=strtoupper($nombre_banco);
$sql = "SELECT nombre FROM banco WHERE banco.id_banco=$_POST[tesoreria_vista_banco] AND banco.id_organismo = ".$_SESSION["id_organismo"]."" ;
$row=& $conn->Execute($sql);
//die($sql);
if(!$row->EOF)
	$sql = "		UPDATE banco 
						 SET
						 	nombre = '$nombre_banco',
							sucursal='$_POST[tesoreria_banco_db_sucursal]',
							direccion='$_POST[tesoreria_banco_db_direccion]',
							codigoarea='$_POST[tesoreria_banco_db_codigoarea]',
							telefono='$_POST[tesoreria_banco_db_telefono]',
							fax='$_POST[tesoreria_banco_db_fax]',
							persona_contacto='$_POST[tesoreria_banco_db_persona_contacto]',
							cargo_contacto='$_POST[tesoreria_banco_db_cargo_contacto]',
							email_contacto='$_POST[tesoreria_banco_db_email_contacto]',
							pagina_banco='$_POST[tesoreria_banco_db_pagina_web]',
							estatus='$_POST[tesoreria_banco_db_estatus]',
							comentarios='$_POST[tesoreria_banco_db_comentarios]',
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_ultima_modificacion='".$fecha."'
						WHERE id_banco = $_POST[tesoreria_vista_banco]
						AND
							banco.id_organismo=$_SESSION[id_organismo]
				";
else
	die ("NoActualizo");
if (!$conn->Execute($sql)) {
	echo($sql);
	die ('Error al Actualizar: '.$conn->ErrorMsg());}
else {
	die ('Actualizado');
	}
?>