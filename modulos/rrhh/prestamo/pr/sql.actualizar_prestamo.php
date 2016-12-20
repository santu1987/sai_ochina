<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$fecha=date("Y-m-d H:m:s");
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//
//
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_prestamo) 
			FROM 
				prestamo
			WHERE
				id_prestamo <> $_POST[prestamo_pr_id_prestamo]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					prestamo
				SET
					id_trabajador='$_POST[prestamo_pr_id_trabajador]',
					id_concepto='$_POST[prestamo_pr_id_concepto]',
					id_frecuencia='$_POST[prestamo_pr_frecuencia]',
					monto='$_POST[prestamo_pr_monto]',
					cuota='$_POST[prestamo_pr_cuota]',
					saldo='$_POST[prestamo_pr_saldo]',
					observacion='$_POST[prestamo_pr_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$fecha'
				WHERE	
					id_prestamo = $_POST[prestamo_pr_id_prestamo]
";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
}
if ($row!=0){
	echo'Existe';
}
?>