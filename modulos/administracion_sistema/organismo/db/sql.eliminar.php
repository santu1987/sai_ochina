<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

/*$sql = "
			SELECT 
				organismo.id_organismo 
			FROM 
				organismo
			$_POST[organismo_db_vista_id_organismo] IN  (SELECT id_organismo FROM perfil_organismo)
			";
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);*/

//if($row->EOF)
	$sql = "DELETE FROM organismo WHERE id_organismo = $_POST[organismo_db_vista_id_organismo]";
//else
	//die("Existe");
	
if (!$conn->Execute($sql)) 
	die ('Error al Eliminar: '.$conn->ErrorMsg());
else
	die("Eliminado");?>