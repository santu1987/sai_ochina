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
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					curriculos
					(
						id_organismo,
						cedula_persona,
						nombre_persona,
						id_ramas,
						imagen,
						observaciones,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$cedula',
						trim('$_POST[curriculum_db_nombre_persona]'),
						'$_POST[curriculum_db_id_ramas]',
						'$_POST[curriculum_db_nombre_img]',
						'$_POST[curriculum_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[curriculum_db_fechact]'
					)
			";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$SQL = "SELECT id_curriculum FROM curriculos where imagen like '$_POST[curriculum_db_nombre_img]' ";
	$bus=& $conn->Execute($SQL);
	echo ("Registrado_".$bus->fields("id_curriculum"));
}
}
if ($row!=0){
	echo'Existe';
}
?>