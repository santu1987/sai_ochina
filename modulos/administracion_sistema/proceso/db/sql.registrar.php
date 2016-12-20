<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql = "	
				INSERT INTO 
					modulo 
					(
						nombre,
						pagina,
						id_grupo,
						variables,
						target,
						menu,
						publico,
						obs,
						icono
					) 
					VALUES
					(
						'$_POST[proceso_db_vista_nombre]',
						'$_POST[pagina]',
						$_POST[id_grupo],
						'$_POST[variables]',
						'$_POST[target]',
						$_POST[menu],
						$_POST[publico],
						'$_POST[proceso_db_vista_observacion]',
						'$_POST[icono]'
					)
			";

if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo 'Ok';
}
?>