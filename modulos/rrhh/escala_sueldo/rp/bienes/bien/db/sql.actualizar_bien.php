<?php
session_start();
$verf = 0;
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$Sql = " SELECT 
				count(id_bienes)
			FROM 
				bienes
			WHERE
				bienes.id_bienes!=$_POST[registrar_bien_db_id_bienes]
			AND
				upper(bienes.codigo_bienes)='".strtoupper($_POST['registrar_bienes_db_codigo'])."'"
		  ;
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if($row!=0)
$verf = 1;
$Sql = " SELECT 
				count(id_bienes)
			FROM 
				bienes
			WHERE
				bienes.id_bienes!=$_POST[registrar_bien_db_id_bienes]
			AND
				upper(bienes.serial_bien)='".strtoupper($_POST['registrar_bienes_db_serial'])."'
		  ";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if($row!=0)
$verf = 1;
if($verf==0)
	$sql = "	UPDATE 
					bienes 
				SET
					
					id_organismo='$_SESSION[id_organismo]',
					nombre='$_POST[registrar_bienes_db_nombre]',
					valor_compra='$_POST[registrar_bienes_db_valor_compra]',
					fecha_compra='$_POST[registrar_bienes_db_fecha_compra]',
					valor_rescate='$_POST[registrar_bienes_db_valor_rescate]',
					id_tipo_bienes='$_POST[bien_db_id_tipo]',
					id_sitio_fisico='$_POST[bien_db_id_sitio_fisico]',
					id_unidad_ejecutora='$_POST[bien_db_id_unidad]',
					id_mayor='$_POST[bien_db_id_mayor]',
					vida_util='$_POST[registrar_bienes_db_vida_util]',
					id_custodio='$_POST[bien_db_id_custodio]',
					descripcion_general='$_POST[registrar_bienes_db_comentarios2]',
					marca='$_POST[registrar_bienes_db_marca]',
					modelo='$_POST[registrar_bienes_db_modelo]',
					anobien='$_POST[registrar_bienes_db_ano]',
					serial_motor='$_POST[registrar_bienes_db_serial_motor]',
					serial_carroceria='$_POST[registrar_bienes_db_serial_car]',
					color='$_POST[registrar_bienes_db_color]',
					placa='$_POST[registrar_bienes_db_placa]',
					estatus_bienes='$_POST[estatus]',
					comentarios='$_POST[registrar_bienes_db_comentarios]',
					ultimo_usuario='$_SESSION[id_usuario]',
					codigo_bienes='$_POST[registrar_bienes_db_codigo]',
					serial_bien='$_POST[registrar_bienes_db_serial]',
					calcular_depreciacion='$_POST[val_depreciacion]',
					fecha_actualizacion='$fecha',
					num_seguro='$_POST[bienes_bien_num_seguro]'
				WHERE
					bienes.id_bienes=$_POST[registrar_bien_db_id_bienes]							
				";

else
	die("Existe");	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>