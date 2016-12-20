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
				id_nivel_academico = $_POST[ramas_db_id_nivel_academico]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					ramas
					(
						id_organismo,
						id_nivel_academico,
						nombre,
						observaciones,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[ramas_db_id_nivel_academico]',
						trim('$_POST[ramas_db_nombre_rama]'),
						'$_POST[ramas_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[ramas_db_fechact]'
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