<?php session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$activo1=0;
$id=$_POST['contabilidad_vista_ut_fondos'];
$sql_prueba="SELECT * from integracion_contable where id_utilizacion_fondos='$id'";	
$row_prueba=& $conn->Execute($sql_prueba);
if (!$row_prueba->EOF)
{
	$activo1=1;	
}
$sql_prueba2="SELECT * from movimientos_contables where id_utilizacion_fondos='$id'";
//and id_organismo =$_SESSION['id_organismo']"	
$row_prueba2=& $conn->Execute($sql_prueba2);


if (!$row_prueba2->EOF)
{
	$activo1=1;	
}

if($activo1==0)
{
	$sql = "	
			DELETE FROM utilizacion_fondos WHERE id_utilizacion_fondos='$id' 
		";

		if (!$conn->Execute($sql)) 
			die ('Error al Registrar: '.$conn->ErrorMsg());
		else
			die("Eliminado");

}
else
{
	die("ExisteRelacion");
}
?>