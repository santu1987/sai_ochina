<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$fecha = date("Y-m-d H:i:s");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//	$SQLB = "SELECT * FROM programa WHERE id_modulo = $_POST[vista_id_modulo]";
//	$row= $conn->Execute($SQLB);

	$SQLD = "SELECT COUNT(id_usuario) from perfil_usuario where id_usuario=$_POST[vista_id_usuario]";
	$row=$conn->Execute($SQLD);
	$row=substr($row,7,2);
	if ($row==0){
	if ($_POST['usuario2']!="sombra.png")
	{
	$dir="../../../../imagenes/foto/";
	$dir.=$_POST['usuario2'];
	unlink($dir);
	}
	$SQLS = "	
				DELETE FROM sesion  
				WHERE id_usuario = $_POST[vista_id_usuario]
			";
 
	$SQLU = "	
				DELETE FROM usuario  
				WHERE id_usuario = $_POST[vista_id_usuario]
			";
			}
//if (count($row->fields("id_modulo"))>=1) {
//	echo 'Error al Elminar: Este registro tiene campos relacionado con otras tablas'.$conn->ErrorMsg().'<BR>';
//}
//else
//{
	//echo 'Ok';
	if ($row==0){
	if ($conn->Execute($SQLU) === false) {
		echo 'No se pudo elminar el registro: '.$conn->ErrorMsg().'<BR>';
	}
	else
	{
		echo 'Eliminado';
	}
	}else
	echo "Tiene un Perfil";
//}
?>