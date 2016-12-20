<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM parametros_presupuesto WHERE (ano = '".$_POST[parametro_presupuesto_db_anio]."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
	$sql = "	
			INSERT INTO 
				parametros_presupuesto(
            		id_organismo, 
					ano, 
					numero_precompromiso, 
					numero_compromiso, 
           			ultimo_mes_cerrado, 
					fecha_cierre_mes, 
					usuario_cierre_mes, 
					fecha_cierre_anual, 
            		comentario, 
					ultimo_usuario, 
					fecha_actualizacion)
   			 VALUES (
			 		".$_SESSION['id_organismo'].",
					".$_POST[parametro_presupuesto_db_anio].", 
					".$_POST[parametro_presupuesto_db_num_precompromiso].", 
					".$_POST[parametro_presupuesto_db_num_compromiso].", 
					".$_POST[parametro_presupuesto_db_ultimo_mes].", 
					'".$_POST[parametro_presupuesto_db_fecha_cierre_mes]."', 
					".$_SESSION['id_usuario'].", 
					'".$_POST[parametro_presupuesto_db_fecha_cierre_anual]."', 
					'".$_POST[parametro_presupuesto_db_comentario]."', 
					".$_SESSION['id_usuario'].", 
					'".$fecha."'
					)
				";
else
	$repetido=true;

	
if (!$conn->Execute($sql)||$repetido) 
	echo (($repetido)?$msgExiste:'Error al Insertar: '.$conn->ErrorMsg().'<br />');
else
	echo 'Registrado';

?>