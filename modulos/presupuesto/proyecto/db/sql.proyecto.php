<?php
if (!$_SESSION) session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM proyecto WHERE (codigo_proyecto = '".$_POST[proyecto_db_codigo]."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);
$ano=date('Y');
$ano = $ano +1 ;
if($row->EOF)
$sql = "	
				INSERT INTO 
					proyecto 
					(
						id_organismo,
						ano,
						nombre,
						id_jefe_proyecto,
						codigo_proyecto,
						comentario,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						".$_SESSION['id_organismo'].",
						$ano,
						'$_POST[proyecto_db_nombre]',
						$_POST[proyecto_db_jefe_proyecto_id],
						'$_POST[proyecto_db_codigo]',
						'$_POST[proyecto_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".$fecha."'
					)
			";
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>