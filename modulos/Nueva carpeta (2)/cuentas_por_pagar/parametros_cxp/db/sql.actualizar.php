<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");


$sql = "SELECT * FROM parametros_cxp WHERE id_organismo<> ".$_SESSION['id_organismo']." AND ano='".strtoupper($_POST[parametro_cxp_db_anio])."'";
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
			UPDATE 
				parametros_cxp
   			SET 
				fecha_ultimo_cierre_mensual='".$_POST[parametro_cxp_db_fecha_cierre_mes]."', 
       			fecha_ultimo_cierre_anual='".$_POST[parametro_cxp_db_fecha_cierre_anual]."', 
				comentarios='".$_POST[parametro_cxp_db_comentario]."',  
				ultimo_usuario=".$_SESSION['id_usuario'].", 
				fecha_ultima_modificacion='".$fecha."',
				ultimo_mes_cerrado='".$_POST['parametro_cxp_db_ultimo_mes']."'
 WHERE id_organismo=".$_SESSION['id_organismo']." AND ano ='".$_POST[parametro_cxp_db_anio]."'";

if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	//echo $sql;
}
else
{
	echo 'Actualizado';
}
?>