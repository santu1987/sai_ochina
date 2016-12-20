<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');

$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	$perfiles = $_POST['perfiles'];
	$_SESSION["perfiles"] = $perfiles;
	$organismos = $_POST['organismos'];
	$_SESSION["id_organismo"] = $organismos;

if ($_POST['organismos']!=0)
{
	$sql ="	SELECT 
				organismo.id_organismo, organismo.nombre 
			FROM 
				organismo
			WHERE  
				organismo.id_organismo = ".$_SESSION["id_organismo"]."
			ORDER BY 
				organismo.nombre ";
			$rs_organismo =& $conn->Execute($sql);
	echo $sql;
	if (!$rs_organismo->EOF)
	{
				$_SESSION["organismo"] = $rs_organismo->fields("nombre");
	}
}
echo "<script>window.location='../../../../index2.php';</script>";
?>