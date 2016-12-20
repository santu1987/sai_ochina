<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
	for($i=1;$i<57;$i++){
		$monto=$_POST['sueldo'.$i];
		$monto= str_replace(",",".",$monto);
		$sql = "	
				INSERT INTO 
					escala_sueldos
					(
						id_organismo,
						monto,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						".$_SESSION["id_organismo"].",
						$monto,
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'
					)
			";
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
	}
?>