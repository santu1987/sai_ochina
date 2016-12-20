<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

/*$sql = "	SELECT 
					id_beneficiario_ayudas 
				FROM 
					beneficiario_ayudas 
				WHERE 
					cedula='$_POST[beneficiario_ayudas_db_vista_nacionalidad]$_POST[beneficiario_ayudas_db_vista_cedula]' AND id_beneficiario_ayudas <> $_POST[vista_id_beneficiario_ayudas]";
					

$row= $conn->Execute($sql);
if (!$conn->Execute($sql)) die ('Error al actualizar: '.$conn->ErrorMsg().' $sql');

if ($row->EOF)
{
*/
	$monto= str_replace(".","",$_POST['rrhh_ayudasocioeconomica_db_monto']);
	$monto= str_replace(",",".",$monto);
	$sql = "	
				UPDATE beneficiario_ayudas  
					 SET
						nombre = '$_POST[beneficiario_ayudas_db_vista_nombre]',
						apellido = '$_POST[beneficiario_ayudas_db_vista_apellido]',
						unidad = '$_POST[beneficiario_ayudas_db_vista_unidad]',
						cedula ='$_POST[beneficiario_ayudas_db_vista_nacionalidad]$_POST[beneficiario_ayudas_db_vista_cedula]',
						fecha = '$_POST[rrhh_ayudasocioeconomica_db_fecha]',
						concepto = '$_POST[rrhh_ayudasocioeconomica_db_concepto]',
						monto = $monto,						
						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion ='".date("Y-m-d H:i:s")."' 
					WHERE id_beneficiario_ayudas = $_POST[vista_id_beneficiario_ayudas]
						
			";
	
	if ($conn->Execute($sql) == false) {
		echo 'Error al Insertar: '.$conn->ErrorMsg()." $sql";
	}
	else
	{	
		die ('Actualizado');
	}
/*
}
else
{
	echo "Existe";
}
*/
?>