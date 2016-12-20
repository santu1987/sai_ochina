<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$monto=$_POST['prestamo_pr_monto'];
$monto=str_replace(".","",$monto);
$monto=str_replace(",",".",$monto);
$cuota=$_POST['prestamo_pr_cuota'];
$cuota=str_replace(".","",$cuota);
$cuota=str_replace(",",".",$cuota);
$saldo=$_POST['prestamo_pr_saldo'];
$saldo=str_replace(".","",$saldo);
$saldo=str_replace(",",".",$saldo);
$db=dbconn("pgsql");
$fecha=date("Y-m-d H:m:s");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
//
$sql = "	
				INSERT INTO 
					prestamo
					(
						id_organismo,
						id_trabajador,
						id_concepto,
						id_frecuencia,
						monto,
						cuota,
						saldo,
						fecha_prestamo,
						observacion,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[prestamo_pr_id_trabajador]',
						'$_POST[prestamo_pr_id_concepto]',
						'$_POST[prestamo_pr_frecuencia]',
						$monto,
						$cuota,
						$saldo,
						'$_POST[prestamo_pr_fecha]',
						'$_POST[prestamo_pr_comentario]',
						'$_SESSION[id_usuario]',
						'$fecha'
					)
			";
 		
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
?>