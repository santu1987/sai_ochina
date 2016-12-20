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
				count(id_nivel_academico) 
			FROM 
				nivel_academico
			WHERE 
				nombre='$_POST[nivel_academico_db_nombre_nivel_academico]'
			AND 
				id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					nivel_academico
				SET
					nombre=trim('$_POST[nivel_academico_db_nombre_nivel_academico]'),
					observaciones='$_POST[nivel_academico_db_comentario]', 
					ultimo_usuario=".$_SESSION['id_usuario'].", 
					fecha_actualizacion='".date("Y-m-d H:i:s")."'
				WHERE
					id_nivel_academico = $_POST[nivel_academico_db_id_nivel_academico]
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