<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM parametros_contabilidad WHERE  (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);
$numeracion = $_POST[parametro_contabilidad_db_numeracion];
if ($numeracion ==1)
	$auto = 't';
else
	$auto = 'f';
//if($row->EOF)
	$sql = "	
			INSERT INTO 
				parametros_contabilidad(
            		id_organismo, 
           			fecha_cierre_mensual, 
					fecha_cierre_anual, 
					numeracion_automatica_comprobantes, 
            		comentarios, 
					ultimo_usuario, 
					ultima_modificacion,
					cuenta_superavit,
					ano,
					ultimo_mes
					)
   			 VALUES (
			 		".$_SESSION['id_organismo'].",
					'".$_POST[parametro_contabilidad_db_fecha_cierre_mes]."', 
					'".$_POST[parametro_contabilidad_db_fecha_cierre_ano]."', 
					'".$auto."', 
					'".$_POST[parametro_contabilidad_db_comentario]."', 
					".$_SESSION['id_usuario'].", 
					'".$fecha."',
					'".$_POST[parametro_contabilidad_db_cuenta_superavit]."',
					'".$_POST[parametro_contabilidad_db_anio]."',
					'".$_POST[parametro_contabilidad_db_ultimo_mes]."'

					)
				";
/*else
	$repetido=true;*/

	
if (!$conn->Execute($sql)||$repetido) 
	echo (($repetido)?$msgExiste:'Error al Insertar: '.$sql/*$conn->ErrorMsg()*/.'<br />');
else
	echo 'Registrado';

?>