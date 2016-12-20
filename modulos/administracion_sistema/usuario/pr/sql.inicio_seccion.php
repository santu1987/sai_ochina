<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');

$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	$usuario = $_POST['usuario'];
	$clave = $_POST['clave'];
	$SQL = "
			SELECT 
				usuario.id_usuario, 
				usuario.usuario, 
				usuario.nombre, 
				usuario.apellido, 
				usuario.foto,
				perfil.nombre AS  perfil,
				perfil.id_perfil
			FROM 
				usuario 
			INNER JOIN
				perfil_usuario
			ON
				perfil_usuario.id_usuario = usuario.id_usuario
			INNER JOIN
				perfil
			ON
				perfil_usuario.id_perfil = perfil.id_perfil
			WHERE 
				usuario.usuario = '$usuario' 
			AND 
				usuario.clave = md5('$clave')
	";
	
if(!$sidx) $sidx =1;


 $row=& $conn->Execute($SQL);
 
 $SQL2 = "SELECT * FROM sesion WHERE (id_usuario = ".$row->fields("id_usuario").") AND (estatus = 1)";
 $row2=& $conn->Execute($SQL2);
if ($row2)
{
	if ($_SESSION["id_usuario"] != $row->fields("id_usuario")/* || $_SESSION["id_usuario"] == ""*/) 
	{
		if (!$row->EOF) {
			$_SESSION["id_usuario"] = $row->fields("id_usuario");
			$_SESSION["usuario"] = $row->fields("usuario");
			$_SESSION["nombre"] = $row->fields("nombre");
			$_SESSION["apellido"] = $row->fields("apellido");
			$_SESSION["foto"] = $row->fields("foto");
			$_SESSION["id_unidad_ejecutora"] = $row->fields("id_unidad_ejecutora");
			echo "Ok";
			$sql = " INSERT INTO  sesion (id_usuario, fecha, estatus	)	VALUES	(".$_SESSION['id_usuario'].",'".date('c')."',1)";
	
			if ($conn->Execute($sql) === false) {
				echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
			}
		}
		else
		{
			echo 'Error al Iniciar Secci&oacute;n: '.$conn->ErrorMsg().'<BR>';
		}
	}
	else
		echo 'Debe cerrar la secci&oacute;n anterior';
}else
		echo 'sesion ya Iniciada';
		
//	echo $SQL2;
echo "<script>window.location='../../../../index2.php';</script>";
?>