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
				count(id_ramas) 
			FROM 
				ramas
			WHERE
				upper(nombre) like '".trim(strtoupper($_POST['ramas_db_nombre_rama']))."'
			AND
				id_ramas <> $_POST[ramas_db_id_ramas]
			AND
				id_nivel_academico = $_POST[ramas_db_id_nivel_academico]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					ramas
				SET
					id_nivel_academico = '$_POST[ramas_db_id_nivel_academico]',
					nombre = trim('$_POST[ramas_db_nombre_rama]'),
					observaciones = '$_POST[ramas_db_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$_POST[ramas_db_fechact]'
				WHERE	
					id_ramas = $_POST[ramas_db_id_ramas]
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