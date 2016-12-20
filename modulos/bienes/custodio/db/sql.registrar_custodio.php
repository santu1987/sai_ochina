<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//
//upper(nombre) like '".trim(strtoupper($_POST['custodio_db_nombre_custodio']))."'
//			OR 
//
if(!$sidx) $sidx =1;
$cedula = $_POST['custodio_db_tipo_custodio']."".$_POST['custodio_db_cedula_custodio'];
$Sql="
			SELECT 
				count(id_custodio) 
			FROM 
				custodio
			WHERE
				custodio.cedula = '$cedula'
			AND
				id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					custodio 
					(
						id_organismo,
						cedula,
						nombre,
						comentarios,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$cedula',
						trim('$_POST[custodio_db_nombre_custodio]'),
						'$_POST[custodio_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[custodio_db_fechact]'
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