<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
foreach($_POST as $variable => $valor){
	$_POST[$variable] = htmlentities($_POST[$variable]);
	//echo $_POST[$variable]." ";
}
$Sql="
			SELECT 
				count(id_cargos) 
			FROM 
				cargos
			WHERE
				upper(descripcion) like '".trim(strtoupper($_POST['cargos_db_descripcion_cargo']))."'
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					cargos
					(
						id_organismo,
						descripcion,
						observacion,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						trim('$_POST[cargos_db_descripcion_cargo]'),
						'$_POST[cargos_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[cargos_db_fechact]'
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