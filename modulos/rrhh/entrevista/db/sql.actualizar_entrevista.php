<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$cedula="$_POST[entrevista_db_nacionalidad]"."$_POST[entrevista_db_cedula]";
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$exi = 0;				
$Sql="
			SELECT 
				count(id_entrevista) 
			FROM 
				entrevista
			WHERE 
				cedula='$_POST[entrevista_db_cedula]'
			AND 
				id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row!=0){
	$sql = "	
				UPDATE 
					entrevista
				SET
					id_curriculos='$_POST[entrevista_db_id_curriculum]',
					cedula='$cedula',
					nombre=trim('$_POST[entrevista_db_nombre_entrevista]'),
					observaciones='$_POST[entrevista_db_comentario]', 
					ultimo_usuario=".$_SESSION['id_usuario'].", 
					fecha_actualizacion='".date("Y-m-d H:i:s")."',
					fecha_entrevista='$_POST[entrevista_db_fecha]'
				WHERE
					id_entrevista = $_POST[entrevista_db_id_entrevista]
			";	
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
}
if ($row==0){
	echo'Existe';
}
?>