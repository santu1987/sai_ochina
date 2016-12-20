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
				count(id_nivel_academico) 
			FROM 
				nivel_academico
			WHERE
				upper(nivel_academico.nombre) like '".trim(strtoupper($_POST['nivel_academico_db_nombre_nivel_academico']))."'
			AND 
				nivel_academico.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					nivel_academico
					(
						id_organismo,
						nombre,
						observaciones,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						".$_SESSION["id_organismo"].",
						trim('$_POST[nivel_academico_db_nombre_nivel_academico]'),
						'$_POST[nivel_academico_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'
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