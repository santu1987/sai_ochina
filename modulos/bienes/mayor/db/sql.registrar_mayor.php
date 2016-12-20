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
				mayor.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					mayor 
					(
						id_organismo,
						nombre,
						comentarios,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						trim('$_POST[mayor_db_nombre]'),
						'$_POST[mayor_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[mayor_db_fechact]'
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