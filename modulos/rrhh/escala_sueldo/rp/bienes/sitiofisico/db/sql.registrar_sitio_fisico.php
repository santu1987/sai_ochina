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
				id_unidad_ejecutora = $_POST[sitio_fisico_db_id_unidad_ejecutora]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					sitio_fisico 
					(
						id_organismo,
						id_unidad_ejecutora,
						nombre,
						comentarios,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[sitio_fisico_db_id_unidad_ejecutora]',
						trim('$_POST[sitio_fisico_db_nombre_sitio]'),
						'$_POST[sitio_fisico_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[sitio_fisico_db_fechact]'
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
if ($row!=0){
	echo'Existe';
}
?>