<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql = "	
				INSERT INTO 
					banco 
					(
						nombre,
						sucursal,
						direccion,
						codigo_area,
						telefono,
						fax,
						persona_contacto,
						cargo_contacto,
						email_contacto,
						pagina_banco,
						ultimo_usuario
					) 
					VALUES
					(
						'$_POST[nombre]',
						'$_POST[sucursal]',
						'$_POST[direccion]',
						'$_POST[code_area]',
						'$_POST[telefono]',
						'$_POST[fax]',
						'$_POST[contacto]',
						'$_POST[cargo]',
						'$_POST[email]',
						'$_POST[pagBan]',
						".$_SESSION['id_usuario']."
					)
			";

if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo 'Ok';
}

  
?>