<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");


$sql = "SELECT * FROM parametros_tesoreria WHERE id_organismo<> ".$_SESSION['id_organismo']." AND ano='".strtoupper($_POST[parametro_tesoreria_db_anio])."'";
$row=& $conn->Execute($sql);
$islr=str_replace(".","",$_POST[parametro_tesoreria_db_factor_islr]);
$itf=str_replace(".","",$_POST[parametro_tesoreria_db_porcentaje_itf]);

					
				
if($row->EOF)
	$sql = "	
			UPDATE 
				parametros_tesoreria
   			SET 
				fecha_ultimo_cierre_mensual='".$_POST[parametro_tesoreria_db_fecha_cierre_mes]."', 
       			fecha_ultimo_cierre_anual='".$_POST[parametro_tesoreria_db_fecha_cierre_anual]."', 
				comentarios='".$_POST[parametro_tesoreria_db_comentario]."',  
				ultimo_usuario=".$_SESSION['id_usuario'].", 
				fecha_ultima_modificacion='".$fecha."',
				porcentaje_itf='".str_replace(",",".",$itf)."',
				factor_islr='".str_replace(",",".",$islr)."',
				ultimo_mes_cerrado='".$_POST[parametro_tesoreria_db_ultimo_mes]."'
 WHERE id_organismo=".$_SESSION['id_organismo']." AND ano ='".$_POST[parametro_tesoreria_db_anio]."'";

if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	//echo $sql;
}
else
{
	echo 'Actualizado';
}
?>