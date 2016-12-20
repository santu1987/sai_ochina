<?
if (!$_SESSION) session_start();
require_once('controladores/db.inc.php');
require_once('utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "
		SELECT 
			usuario.id_usuario, 
			usuario.usuario, 
			usuario.nombre, 
			usuario.apellido, 
			usuario.foto ,
			usuario.id_unidad_ejecutora 
		FROM
			usuario
		WHERE 
			usuario.usuario = '$_POST[usuario]' 
		AND 
			usuario.clave = md5('$_POST[clave]')
";
	
$row=& $conn->Execute($sql);

if(!$row->EOF) 
{
	$_SESSION[id_usuario] = $row->fields("id_usuario");
	$_SESSION[usuario] = $row->fields("usuario");
	$_SESSION[nombre] = $row->fields("nombre");
	$_SESSION[apellido] = $row->fields("apellido");
	$_SESSION[foto] = $row->fields("foto");	
	$_SESSION["id_unidad_ejecutora"] = $row->fields("id_unidad_ejecutora");
	die("$_SESSION[id_usuario],$_SESSION[usuario],$_SESSION[nombre],$_SESSION[apellido],$_SESSION[foto]");
}
die("Fail");
?>