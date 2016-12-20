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
				count(id_tipo_bienes) 
			FROM 
				tipo_bienes
			INNER JOIN
				mayor
			ON
				tipo_bienes.id_mayor = mayor.id_mayor
			WHERE
				upper(tipo_bienes.nombre) like '".trim(strtoupper($_POST['tipo_bienes_db_nombre_tipo_bienes']))."'
			AND
				tipo_bienes.id_mayor like '$_POST[tipo_bienes_db_id_mayor]'
			AND 
				tipo_bienes.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					tipo_bienes 
					(
						id_organismo,
						nombre,
						comentarios,
						ultimo_usuario,
						fecha_actualizacion,
						id_mayor,
						vida_util_tb,
						vehiculo
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						trim('$_POST[tipo_bienes_db_nombre_tipo_bienes]'),
						'$_POST[tipo_bienes_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[tipo_bienes_db_fechact]',
						'$_POST[tipo_bienes_db_id_mayor]',
						'$_POST[vida_util_tb]',
						'$_POST[vehiculo]'
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