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
				count(id_mayor) 
			FROM 
				mayor
			WHERE
				upper(mayor.nombre) like '".trim(strtoupper($_POST['mayor_db_nombre']))."'
			AND
				mayor.id_mayor != '$_POST[mayor_db_id_mayor]'
			AND
				mayor.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE
					mayor
				SET
					nombre=trim('$_POST[mayor_db_nombre]'),
					comentarios='$_POST[mayor_db_comentario]',
					ultimo_usuario='$_SESSION[id_usuario]',
					fecha_actualizacion='$_POST[mayor_db_fechact]'
				WHERE
					id_mayor = '$_POST[mayor_db_id_mayor]'
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