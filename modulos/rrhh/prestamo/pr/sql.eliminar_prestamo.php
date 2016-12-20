<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

/*$Sql="
			SELECT 
				count(id_bienes) 
			FROM 
				bienes
			WHERE
				bienes.id_sitio_fisico = $_POST[sitio_fisico_db_id_sitio_fisico]
			AND
				bienes.id_unidad_ejecutora = $_POST[sitio_fisico_db_id_unidad_ejecutora]
			AND 
				bienes.id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){*/
	$sql = "	
				DELETE FROM 
					prestamo
				WHERE	
					id_prestamo = $_POST[prestamo_pr_id_prestamo]
";
if ($conn->Execute($sql) == false) {
	echo $sql;
	echo 'Error al Eliminar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Eliminado");
}
//}
/*if ($row!=0){
	echo'Relacion_Existe';
}*/
?>