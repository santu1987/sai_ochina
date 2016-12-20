<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> EL NOMBRE DEL ORGANISMO YA EXISTE";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT nombre FROM organismo WHERE id_organismo<>$_POST[organismo_db_vista_id_organismo] AND upper(nombre)='".strtoupper($_POST[organismo_db_vista_nombre])."'";
if (!$conn->Execute($sql)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
				UPDATE organismo  
					 SET
						nombre = '$_POST[organismo_db_vista_nombre]',
						direccion1 = '$_POST[organismo_db_vista_direccion_principal]',
						direccion2 = '$_POST[organismo_db_vista_direccion_secundaria]',
						codigo_area = $_POST[organismo_db_vista_cod_area],
						telefono = '$_POST[organismo_db_vista_telefono]',
						fax = '$_POST[organismo_db_vista_fax]',
						rif = '$_POST[organismo_db_vista_hrif]$_POST[organismo_db_vista_rif]-$_POST[organismo_db_vista_rif2]',
						pagina_web = '$_POST[organismo_db_vista_pag_web]',
						email = '$_POST[organismo_db_vista_email]',
						representante = '$_POST[organismo_db_vista_persona_contacto]',
						cedula_repre = '$_POST[organismo_db_vista_nacionalidad]$_POST[organismo_db_vista_cedula_contacto]',
						cargo_repre = '$_POST[organismo_db_vista_cargo_contacto]',
						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion ='".date("Y-m-d H:i:s")."'
					WHERE 
						id_organismo = $_POST[organismo_db_vista_id_organismo]
						
			";
else
	die("Existe");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizo");
?>