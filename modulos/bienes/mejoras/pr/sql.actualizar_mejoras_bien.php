<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				usuario  
			FROM 
				usuario 
			WHERE 
				id_usuario = $_SESSION[id_usuario]";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
$usuario = $row->fields("usuario");
	$sql = "	
				UPDATE 
					mejoras
				SET
						id_organismo='$_SESSION[id_organismo]',
						id_bienes='$_POST[form_mejoras_bien_pr_id_bienes]',
						nombre_mejora='$_POST[form_mejoras_bien_pr_nombre_mejora]',
						fecha_mejora='$_POST[form_mejoras_bien_pr_fecha_mejora]',
						valor_rescate='$_POST[form_mejoras_bien_pr_valor_mejora]',
						vida_util='$_POST[form_mejoras_bien_pr_vida_util]',
						usuario_carga_mejora='$usuario',
						descripcion_general='$_POST[form_mejoras_bien_pr_descripcion]',
						comentarios='$_POST[form_mejoras_bien_pr_comentario]',
						ultimo_usuario = '$_SESSION[id_usuario]',
						fecha_actualizacion = '$_POST[form_mejoras_bien_pr_fechact]'
				WHERE
					id_mejoras = $_POST[form_mejoras_bien_pr_id_mejoras]
			";	
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$sql = "
			SELECT  
				vida_util
			FROM	
				bienes
			WHERE 
				id_bienes = $_POST[form_mejoras_bien_pr_id_bienes] ";
	$row =& $conn->Execute($sql); 	
	$vida_util = $row->fields("vida_util");
	$vida_util = $vida_util + $_POST['form_mejoras_bien_pr_vida_util'];
	/*$sql = "
			UPDATE 
				bienes
			SET	
				estatus_bienes=4 
			WHERE 
				id_bienes = $_POST[form_mejoras_bien_pr_id_bienes] ";
	$row =& $conn->Execute($sql);*/ 	
	echo ("Actualizado");
}
?>