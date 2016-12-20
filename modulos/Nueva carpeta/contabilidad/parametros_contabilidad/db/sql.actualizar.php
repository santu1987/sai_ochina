<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$fecha = date("Y-m-d H:i:s");

$id_para=$_POST['parametro_contabilidad_db_id'];
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$numeracion = $_POST[parametro_contabilidad_db_numeracion];
if ($numeracion ==1)
	$auto = 't';
else
	$auto = 'f';
if(($id_para!=0)&&($id_para!="")){
 	$sql = "	
				UPDATE parametros_contabilidad  
					 SET
						fecha_cierre_mensual = '".$_POST[parametro_contabilidad_db_fecha_cierre_mes]."',
						fecha_cierre_anual = '".$_POST[parametro_contabilidad_db_fecha_cierre_ano]."',
						numeracion_automatica_comprobantes = '".$auto."',
						comentarios='".$_POST[parametro_contabilidad_db_comentario]."',
						cuenta_superavit = '".$_POST[parametro_contabilidad_db_cuenta_superavit]."',
						ultimo_usuario = ".$_SESSION['id_usuario'].",
						ultima_modificacion ='".$fecha."',
						ultimo_mes='".$_POST[parametro_contabilidad_db_ultimo_mes]."',
						ano='".$_POST[parametro_contabilidad_db_anio]."'
					WHERE id_parametros_contabilidad = $_POST[parametro_contabilidad_db_id]
						
			";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
//	'$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]'
//$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]
}
else
{	
		die ('Actualizado');
}}
?>