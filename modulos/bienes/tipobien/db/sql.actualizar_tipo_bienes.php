<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_tipo_bienes) 
			FROM 
				tipo_bienes
			INNER JOIN
				mayor
			ON
				tipo_bienes.id_mayor = mayor.id_mayor
			WHERE
				upper(tipo_bienes.nombre) like '".trim(strtoupper($_POST['tipo_bienes_db_nombre_tipo_bienes']))."'
			AND
				id_tipo_bienes <> $_POST[tipo_bienes_db_id_tipo_bienes]
			AND
				tipo_bienes.id_mayor = '$_POST[tipo_bienes_db_id_mayor]'
			AND
				tipo_bienes.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE
					tipo_bienes
				SET
					nombre=trim('$_POST[tipo_bienes_db_nombre_tipo_bienes]'),
					comentarios='$_POST[tipo_bienes_db_comentario]',
					ultimo_usuario='$_SESSION[id_usuario]',
					fecha_actualizacion='$_POST[tipo_bienes_db_fechact]',
					id_mayor = '$_POST[tipo_bienes_db_id_mayor]',
					vida_util_tb='$_POST[vida_util_tb]',
					vehiculo='$_POST[val_vehiculo]'
				WHERE
					id_tipo_bienes = $_POST[tipo_bienes_db_id_tipo_bienes]
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