<?php session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$activo1=0;
$cuenta_contable=$_POST['contabilidad_cuentas_contables_db_cuenta_contable'];
$sql_prueba="SELECT * from integracion_contable where cuenta_contable='$cuenta_contable'";	
$row_prueba=& $conn->Execute($sql_prueba);

if (!$row_prueba->EOF)
{
	$activo1=1;	
}
$sql_prueba2="SELECT * from movimientos_contables where cuenta_contable='$cuenta_contable'";
//and id_organismo =$_SESSION['id_organismo']"	
$row_prueba2=& $conn->Execute($sql_prueba2);

if (!$row_prueba2->EOF)
{
	$activo1=1;	
}
if($activo1==0)
{
	$sql = "	
			DELETE FROM cuenta_contable_contabilidad WHERE id=$_POST[contabilidad_cuentas_contables_db_id] 
		";
	$sql_saldos="DELETE from saldo_contable where cuenta_contable='$_POST[contabilidad_cuentas_contables_db_id]'
	;
	$sql
	";
	
		if (!$conn->Execute($sql_saldos)) 
			die ('Error al Registrar: '.$conn->ErrorMsg());
		else
			die("Eliminado");

}
else
{
	die("ExisteRelacion");
}
?>