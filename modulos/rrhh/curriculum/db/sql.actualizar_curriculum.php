<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$cedula = $_POST['curriculum_db_nac']."".$_POST['curriculum_db_cedula'];
$Sql="
			SELECT 
				count(id_curriculum) 
			FROM 
				curriculos
			WHERE
				upper(cedula_persona) like '$cedula'
			AND
				id_curriculum <>  $_POST[curriculum_db_id_curriculum]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
//
//

if($_POST['curriculum_db_nombre_img']!='')
	$imagen = $_POST['curriculum_db_nombre_img'];
else
	$imagen = $_POST['foto_vie'];
//
//
	$sql = "	
				UPDATE 
					curriculos
				SET
					id_ramas = '$_POST[curriculum_db_id_ramas]',
					cedula_persona = '$cedula',
					nombre_persona = '$_POST[curriculum_db_nombre_persona]',
					imagen = '$imagen',
					observaciones = '$_POST[curriculum_db_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$_POST[curriculum_db_fechact]'
				WHERE	
					id_curriculum = $_POST[curriculum_db_id_curriculum]
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