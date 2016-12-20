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
				count(id_tipo_nomina) 
			FROM 
				tipo_nomina
			WHERE
				upper(tipo_nomina.nombre) like '".trim(strtoupper($_POST['tipo_nomina_db_nombre_tipo_nomina']))."'
			AND
				tipo_nomina.id_frecuencia = $_POST[tipo_nomina_db_frecuencia]
			AND
				tipo_nomina.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					tipo_nomina
					(
						id_organismo,
						nombre,
						observacion,
						ultimo_usuario,
						fecha_actualizacion,
						id_frecuencia
					) 
					VALUES
					(
						".$_SESSION["id_organismo"].",
						trim('$_POST[tipo_nomina_db_nombre_tipo_nomina]'),
						'$_POST[tipo_nomina_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
						'$_POST[tipo_nomina_db_frecuencia]'
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