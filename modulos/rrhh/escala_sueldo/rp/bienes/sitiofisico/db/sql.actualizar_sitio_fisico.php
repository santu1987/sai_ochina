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
				count(id_sitio_fisico) 
			FROM 
				sitio_fisico
			WHERE
				upper(nombre) like '".trim(strtoupper($_POST['sitio_fisico_db_nombre_sitio']))."'
			AND
				id_sitio_fisico <> $_POST[sitio_fisico_db_id_sitio_fisico]
			AND
				id_unidad_ejecutora = $_POST[sitio_fisico_db_id_unidad_ejecutora]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					sitio_fisico
				SET
					id_unidad_ejecutora = '$_POST[sitio_fisico_db_id_unidad_ejecutora]',
					nombre = trim('$_POST[sitio_fisico_db_nombre_sitio]'),
					comentarios = '$_POST[sitio_fisico_db_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$_POST[sitio_fisico_db_fechact]'
				WHERE	
					id_sitio_fisico = $_POST[sitio_fisico_db_id_sitio_fisico]
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