<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM parametros_cxp WHERE (ano = '".$_POST[parametro_cxp_db_anio]."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
			INSERT INTO 
				parametros_cxp(
            		id_organismo, 
					ano, 
					fecha_ultimo_cierre_mensual, 
					usuario_cierre_mensual, 
					fecha_ultimo_cierre_anual, 
            		comentarios, 
					ultimo_usuario, 
					fecha_ultima_modificacion,
					ultimo_mes_cerrado
					)
   			 VALUES (
			 		".$_SESSION['id_organismo'].",
					".$_POST[parametro_cxp_db_anio].", 
					'".$_POST[parametro_cxp_db_fecha_cierre_mes]."', 
					".$_SESSION['id_usuario'].", 
					'".$_POST[parametro_cxp_db_fecha_cierre_anual]."', 
					'".$_POST[parametro_cxp_db_comentario]."', 
					".$_SESSION['id_usuario'].",
					'".$fecha."',
					'".$_POST['parametro_cxp_db_ultimo_mes']."'
					)
				";
else
	$repetido=true;

	
if (!$conn->Execute($sql)||$repetido) 
	echo (($repetido)?$msgExiste:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Registrado';

?>