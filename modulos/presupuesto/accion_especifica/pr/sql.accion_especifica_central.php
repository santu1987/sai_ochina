<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlb="SELECT * FROM accion_especifica_central WHERE id_accion_central=$_POST[accion_especifica_centra_pr_accion_central] AND id_organismo=".$_SESSION["id_organismo"];
$rs_accion =& $conn->Execute($sqlb);
if ($rs_accion)
{
	$sqld="DELETE FROM accion_especifica_central WHERE id_accion_central=$_POST[accion_especifica_centra_pr_accion_central] AND id_organismo=".$_SESSION["id_organismo"];
if (!$conn->Execute($sqld))			die('Error al Eliminar Permisos: '.$conn->ErrorMsg());
}

$programas = $_POST['accion_especifica_pr_accion_especificaSelect'];
$partem = explode(",",$programas); 
$n = count($partem);
if ($programas!="")
{
	for($i=0;$i<$n;$i++)
	{ 
	$sql = "	
		INSERT INTO accion_especifica_central 
					(
					id_accion_central,
					ano,
					id_organismo,
					id_accion_especifica,
					fecha_actualizacion,
					ultimo_usuario						
					) 
					VALUES
						(
				$_POST[accion_especifica_centra_pr_accion_central],
				".date('Y').",
				".$_SESSION["id_organismo"].",
					".$partem[$i].",
					'".$fecha."',
					".$_SESSION['id_usuario']."
					)
			";
		//if ($conn->Execute($sql) === true)
		if ($conn->Execute($sql) === true) {
			die ('Error al Insertar: '.$conn->ErrorMsg());
		}else {
			die ('Ok');
		}//echo $sql ;
	}
	
	
}else
echo "Debe asignar una acci&oacute;n";
?>