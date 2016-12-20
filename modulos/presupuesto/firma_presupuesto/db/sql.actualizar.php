<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlbus = "
SELECT 
	nombre_autoriza 
FROM 
	firma_presupuesto 
WHERE 
	(id_organismo<> ".$_SESSION['id_organismo'].") 
";
$row=& $conn->Execute($sqlbus);

if($row->EOF)
	$sql = "	
					UPDATE firma_presupuesto  
						 SET
							nombre_autoriza = '$_POST[firma_presupuesto_db_nombre_auto]',
							cargo_autoriza  = '$_POST[firma_presupuesto_db_cargo_auto]',
							grado_autoriza  = '$_POST[firma_presupuesto_db_grado_auto]',
							nombre_auto_traspaso = '$_POST[firma_presupuesto_db_nombre_auto_tras]',
							cargo_auto_traspaso  = '$_POST[firma_presupuesto_db_cargo_auto_tras]',
							grado_auto_traspaso  = '$_POST[firma_presupuesto_db_gardo_auto_tras]',
							comentario = '$_POST[firma_presupuesto_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE
							id_organismo = ".$_SESSION['id_organismo'];

else
	$repetido=true;
	
if (!$conn->Execute($sql)||$repetido) {
	echo (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
	//echo $sqlbus;
}
else
{
	echo 'Actualizado';
}
?>