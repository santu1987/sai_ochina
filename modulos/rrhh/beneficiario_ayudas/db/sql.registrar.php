<?php
if (!$_SESSION) session_start();
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
					cedula='$_POST[beneficiario_ayudas_db_vista_nacionalidad]$_POST[beneficiario_ayudas_db_vista_cedula]'";
					

$row= $conn->Execute($sql);
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());

if ($row->EOF)
{
*/	
	$monto= str_replace(".","",$_POST['rrhh_ayudasocioeconomica_db_monto']);
	$monto= str_replace(",",".",$monto);
	$sql = "	
				INSERT INTO 
					beneficiario_ayudas 
					(
						nombre,
						apellido,
						unidad,
						cedula,
						fecha,
						concepto,
						monto,						
						estatus,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_POST[beneficiario_ayudas_db_vista_nombre]',
						'$_POST[beneficiario_ayudas_db_vista_apellido]',
						'$_POST[beneficiario_ayudas_db_vista_unidad]',
						'$_POST[beneficiario_ayudas_db_vista_nacionalidad]$_POST[beneficiario_ayudas_db_vista_cedula]',
						'$_POST[rrhh_ayudasocioeconomica_db_fecha]',
						'$_POST[rrhh_ayudasocioeconomica_db_concepto]',
						$monto,						
						1,
						".$_SESSION['id_usuario']."	,
						'".date("Y-m-d H:i:s")."'
					)
			";

		
	if (!$conn->Execute($sql)) 
		echo 'Error al Insertar: '.$conn->ErrorMsg()." $sql";
	else
		echo "Registrado";
/*	
}
else
{
	echo "Existe";
}
*/
?>