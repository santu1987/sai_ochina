<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> EL NOMBRE DEL ORGANISMO YA EXISTE...";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT nombre FROM organismo WHERE upper(nombre)='".strtoupper($_POST[organismo_db_vista_nombre])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
					INSERT INTO 
						organismo 
						(
							nombre, 
							direccion1, 
							direccion2, 
							codigo_area, 
							telefono, 
							fax, 
							rif,  
							pagina_web, 
							email, 
							representante, 
							cedula_repre, 
							cargo_repre, 
							comentario
						) 
						VALUES
						(
							'$_POST[organismo_db_vista_nombre]',
							'$_POST[organismo_db_vista_direccion_principal]',						
							'$_POST[organismo_db_vista_direccion_secundaria]',
							'$_POST[organismo_db_vista_cod_area]',
							'$_POST[organismo_db_vista_telefono]',
							'$_POST[organismo_db_vista_fax]',
							'$_POST[organismo_db_vista_hrif]$_POST[organismo_db_vista_rif]-$_POST[organismo_db_vista_rif2]',
							'$_POST[organismo_db_vista_pag_web]',
							'$_POST[organismo_db_vista_email]',
							'$_POST[organismo_db_vista_persona_contacto]',
							'$_POST[organismo_db_vista_nacionalidad]$_POST[organismo_db_vista_cedula_contacto]',
							'$_POST[organismo_db_vista_cargo_contacto]',
							'$_POST[organismo_db_vista_fax]'
						)
				";
else
	die("Existe");
	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>