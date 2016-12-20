<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="DELETE FROM perfil_organismo WHERE id_perfil=$_POST[id_perfil]";
if (!$conn->Execute($sql))			die('Error al Eliminar Permisos: '.$conn->ErrorMsg());

$partem = explode(",",$_POST['organismoSelect']); 

if ($partem)
{
	for($i=0;$i<count($partem);$i++)
	{ 
	$sql = "
					INSERT INTO perfil_organismo 
					(
						id_perfil,
						id_organismo						
					) 
					VALUES
					(
						$_POST[id_perfil],
						".$partem[$i]."
					)
			";
		if (!$conn->Execute($sql))			die('Error al Agregar Permisos: '.$conn->ErrorMsg());
	}
}
echo "Registrado";
?>