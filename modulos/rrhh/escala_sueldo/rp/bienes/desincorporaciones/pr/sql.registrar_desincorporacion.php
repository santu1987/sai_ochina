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
				count(id_desincorporaciones) 
			FROM 
				desincorporaciones
			WHERE
				id_bienes = $_POST[form_desincorporar_bien_pr_id_bienes]
			AND
				id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "SELECT usuario from usuario where id_usuario = $_SESSION[id_usuario] ";
	$bus= $conn->Execute($sql);
	$usuario = $bus->fields("usuario");
	$sql = "	
				INSERT INTO 
					desincorporaciones 
					(
						id_organismo,
						id_bienes,
						id_tipo_desincorporaciones,
						fecha_desincorporacion,
						usuario_carga_desincorporacion,
						descripcion_general,
						comentarios,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[form_desincorporar_bien_pr_id_bienes]',
						'$_POST[form_desincorporar_bien_pr_tipo]',
						'$_POST[form_desincorporar_bien_pr_fechact]',
						'$usuario',
						'$_POST[form_desincorporar_bien_pr_descripcion]',
						'$_POST[form_desincorporar_bien_pr_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[form_desincorporar_bien_pr_fechact]'
					)
			";
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$sql = "UPDATE bienes set estatus_bienes = '3' where id_bienes = $_POST[form_desincorporar_bien_pr_id_bienes]";
	$row=& $conn->Execute($sql);
	die ("Registrado");
}
}
if ($row!=0){
	echo'Existe';
}
?>