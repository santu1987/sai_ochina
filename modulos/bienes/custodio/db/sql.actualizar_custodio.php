<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$cedula = $_POST['custodio_db_tipo_custodio']."".$_POST['custodio_db_cedula_custodio'];
/*SELECT 
				count(id_custodio) 
			FROM 
				custodio
			WHERE
				upper(nombre) like '".trim(strtoupper($_POST['custodio_db_nombre_custodio']))."'
			AND
				custodio.cedula = '$cedula'	
			AND 
				id_custodio != $_POST[custodio_db_id_custodio] 
			AND 
				id_organismo = $_SESSION[id_organismo]";*/
$exi = 0;				
$Sql="
			SELECT 
				count(id_custodio) 
			FROM 
				custodio
			WHERE
				custodio.cedula = '$cedula'	
			AND 
				id_custodio != $_POST[custodio_db_id_custodio] 
			AND 
				id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					custodio
				SET
					cedula='$cedula',
					nombre=trim('$_POST[custodio_db_nombre_custodio]'), 		comentarios='$_POST[custodio_db_comentario]', ultimo_usuario='$_SESSION[id_usuario]', fecha_actualizacion='$_POST[custodio_db_fechact]'
				WHERE
					id_custodio = $_POST[custodio_db_id_custodio]
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