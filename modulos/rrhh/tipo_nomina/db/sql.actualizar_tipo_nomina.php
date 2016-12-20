<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$exi = 0;				
$Sql="
			SELECT 
				count(id_tipo_nomina) 
			FROM 
				tipo_nomina
			WHERE 
				nombre='$_POST[tipo_nomina_db_nombre_tipo_nomina]'
			AND
				tipo_nomina.id_frecuencia = $_POST[tipo_nomina_db_frecuencia]
			AND
				id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row!=0){
	$sql = "	
				UPDATE 
					tipo_nomina
				SET
					nombre=trim('$_POST[tipo_nomina_db_nombre_tipo_nomina]'),
					observacion='$_POST[tipo_nomina_db_comentario]', 
					ultimo_usuario=".$_SESSION['id_usuario'].", 
					fecha_actualizacion='".date("Y-m-d H:i:s")."',
					id_frecuencia='$_POST[tipo_nomina_db_frecuencia]'
				WHERE
					id_tipo_nomina = $_POST[tipo_nomina_db_id_tipo_nomina]
			";	
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
}
if ($row=0){
	echo'Existe';
}
?>