<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlBus = "SELECT nombre FROM ramo WHERE id_ramo<>$_POST[ramos_db_id] AND upper(nombre)='".strtoupper($_POST[ramos_db_nombre])."'";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
					UPDATE ramo  
						 SET
							id_ramo = $_POST[ramos_db_id],
							nombre = '$_POST[ramos_db_nombre]',
							comentario ='$_POST[ramos_db_comentario]',
							fecha_actualizacion = '".$fecha."',
							ultimo_usuario = ".$_SESSION['id_usuario']."
						WHERE id_ramo = $_POST[ramos_db_id]
							
				";
else
	die("Existe");
	
if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	//echo $sql;
}
else
{
	echo 'Actualizado';
}
?>