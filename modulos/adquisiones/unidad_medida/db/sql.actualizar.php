<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT nombre FROM unidad_medida WHERE id_unidad_medida<>$_POST[unidad_medida_db_id] AND upper(nombre)='".strtoupper($_POST[unidad_medida_db_unidad])."'";
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
					UPDATE unidad_medida  
						 SET
							nombre = '$_POST[unidad_medida_db_unidad]',
							comentario ='$_POST[unidad_medida_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE id_unidad_medida = $_POST[unidad_medida_db_id]
							
				";

if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	//echo $sql;
}
else
{
	echo 'Ok';
}
?>