<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$estatus=$_POST['unidad_ejecutora_db_estatus'];
$regional=$_POST['unidad_ejecutora_db_estatus2'];
if ($estatus == "")
	$estatus= 0;
$sqlBus = "SELECT * FROM unidad_ejecutora WHERE (upper(nombre) = '".strtoupper($_POST[unidad_ejecutora_db_nombre])."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
$sql = "	
				INSERT INTO 
					unidad_ejecutora 
					(
						id_organismo,
						nombre,
						comentario,
						ultimo_usuario,
						fecha_actualizacion,
						jefe_unidad,
						codigo_unidad_ejecutora,
						tipo_unidad,
						unidad_regional
					) 
					VALUES
					(
						".$_SESSION['id_organismo'].",
						'$_POST[unidad_ejecutora_db_nombre]',
						'$_POST[unidad_ejecutora_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".$fecha."',
						'$_POST[unidad_ejecutora_db_jefe]',
						'$_POST[unidad_ejecutora_db_codigo]',
						 $estatus ,
						 $regional
					)
			";
else
	die("Existe");
	
if ($conn->Execute($sql) === false) {
	//echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	echo $sql;
}
else
{
	echo 'Registrado';
}
?>