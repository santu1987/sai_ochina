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
				count(id_tipo_desincorporaciones) 
			FROM 
				tipo_desincorporaciones
			WHERE
				upper(tipo_desincorporaciones.nombre) like '".trim(strtoupper($_POST['tipo_desinc_db_nombre_tipo_desinc']))."'
			AND 
				tipo_desincorporaciones.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					tipo_desincorporaciones
					(
						id_organismo,
						nombre,
						comentarios
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						trim('$_POST[tipo_desinc_db_nombre_tipo_desinc]'),
						'$_POST[tipo_desinc_db_comentario]'
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