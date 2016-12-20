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
				count(id_entrevista) 
			FROM 
				entrevista
			WHERE
				entrevista.id_curriculos = $_POST[curriculum_db_id_curriculum]
			AND 
				entrevista.id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);

if ($row==0){
	$sql = "SELECT imagen FROM curriculos WHERE id_curriculum = $_POST[curriculum_db_id_curriculum]";
	$bus =& $conn->Execute($sql);
	$foto = $bus->fields("imagen");
	$sql = "	
				DELETE FROM 
					curriculos
				WHERE	
					id_curriculum = $_POST[curriculum_db_id_curriculum]
";
if ($conn->Execute($sql) == false) {
	echo 'Error al Eliminar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	
	if($foto!='')
	unlink('../../../../imagenes/curriculos/'.$foto);
	echo ("Eliminado");
}
}
if ($row!=0){
	echo'Relacion_Existe';
}
?>