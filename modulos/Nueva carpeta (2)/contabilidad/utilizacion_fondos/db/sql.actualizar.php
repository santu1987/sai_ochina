<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlb = "SELECT id_utilizacion_fondos FROM utilizacion_fondos  WHERE id_utilizacion_fondos  <> $_POST[contabilidad_vista_ut_fondos] AND upper(nombre) ='".strtoupper($_POST['contabilidad_ut_fondos_db_nombre'])."'";

if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF)
{
	$sql = "	
					UPDATE utilizacion_fondos  
						 SET
							nombre = '$_POST[contabilidad_ut_fondos_db_nombre]',
							comentarios = '$_POST[contabilidad_ut_fondos_db_comentario]',
							tipo='$_POST[contabilidad_ut_fondos_db_tipo]',							
							id_organismo=	 ".$_SESSION["id_organismo"].",
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							ultima_modificacion ='".date("Y-m-d H:i:s")."'	
							WHERE id_utilizacion_fondos = $_POST[contabilidad_vista_ut_fondos]
							
				";
		
}
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>