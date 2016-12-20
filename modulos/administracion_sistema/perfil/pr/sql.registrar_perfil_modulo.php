<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="DELETE FROM perfil_modulo WHERE id_perfil=$_POST[id_perfil]";
if (!$conn->Execute($sql))			die('Error al Eliminar Permisos: '.$conn->ErrorMsg());

$modulos = $_POST['moduloSelect'];
$partem = explode(",",$modulos); 
$n = count($partem);

for($i=0;$i<$n;$i++)
{ 
	$sql = "	
		INSERT INTO perfil_modulo 
					(
					id_perfil,
					id_modulo				
					) 
					VALUES
					(
					$_POST[id_perfil],
					".$partem[$i]."
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