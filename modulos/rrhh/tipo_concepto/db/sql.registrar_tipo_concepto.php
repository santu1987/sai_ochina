<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$fecha=date("Y-m-d H:m:s");
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_tipo_concepto) 
			FROM 
				tipo_concepto
			WHERE
				upper(descripcion) like '".trim(strtoupper($_POST['tipo_concepto_db_descripcion_tipo_concepto']))."'
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					tipo_concepto
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
						trim('$_POST[tipo_concepto_db_descripcion_tipo_concepto]'),
						'$_POST[tipo_concepto_db_comentario]',
						'$_SESSION[id_usuario]',
						'$fecha'
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