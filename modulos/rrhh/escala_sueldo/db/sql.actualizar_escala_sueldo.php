<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$exi = 0;
for($i=1;$i<57;$i++){
	$monto=$_POST['sueldo'.$i];
	$id=$_POST['id_sueldo'.$i];
	$monto=str_replace(".","",$monto);
	$monto=str_replace(",",".",$monto);
	$sql = "	
				UPDATE 
					escala_sueldos
				SET
					monto=$monto, 
					ultimo_usuario=".$_SESSION['id_usuario'].", 
					fecha_actualizacion='".date("Y-m-d H:i:s")."'
				WHERE
					id_escala_sueldo = $id
			";
$eje= $conn->Execute($sql);
}
if ($eje == false) {
	//echo $sql;
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
?>