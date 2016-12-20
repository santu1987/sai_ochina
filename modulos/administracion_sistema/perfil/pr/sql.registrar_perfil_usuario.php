<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="DELETE FROM perfil_usuario WHERE id_perfil=$_POST[id_perfil]";
if (!$conn->Execute($sql))			die('Error al Eliminar Permisos: '.$conn->ErrorMsg());

$usuario = $_POST['usuarioSelect'];
$partes = explode(",",$usuario); 
$n = count($partes);

for($i=0;$i<$n;$i++)
{
	$sql = "	
		INSERT INTO perfil_usuario 
					(
					id_perfil,
					id_usuario						
					) 
					VALUES
					(
					$_POST[id_perfil],
					$partes[$i]	
					)
			";
	if ($conn->Execute($sql) === true) {
		echo 'Error al Registrar Permisos: '.$conn->ErrorMsg().'<BR>';
		$e=true;
	}	
}

if (!$e)
	echo "Registrado";
?>