<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="DELETE FROM perfil_programa WHERE id_perfil=$_POST[id_perfil] AND id_modulo=$_POST[modulo]";
if (!$conn->Execute($sql))			die('Error al Eliminar Permisos: '.$conn->ErrorMsg());

$programas = $_POST['programaSelect'];
$partem = explode(",",$programas); 
$n = count($partem);
if ($programas!="")
{
	for($i=0;$i<$n;$i++)
	{ 
	$sql = "
		INSERT INTO perfil_programa 
					(
					id_perfil,
					id_modulo,
					id_programa						
					) 
					VALUES
					(
					$_POST[id_perfil],
					$_POST[modulo],
					".$partem[$i]."
					)
			";
		if (!$conn->Execute($sql))			die('Error al Agregar Permisos: '.$conn->ErrorMsg());
	}
}
echo "Registrado";
?>