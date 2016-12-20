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
if($_POST['vida_util_dep']=="")
	$val=$_POST['registrar_depreciacion_pr_vida_util'];
else
	$val=$_POST['vida_util_dep'];
$Sql = " SELECT 
				count(id_bienes)
			FROM 
				depreciacion_mensual
			WHERE
				id_bienes=$_POST[registrar_depreciacion_pr_id_bien]
			"
		  ;
$row=& $conn->Execute($Sql);
if($row!=0)
{
	$sql = "UPDATE 
					depreciacion_mensual 
			SET
			  valor_depreciacion_mensual='$_POST[registrar_depreciacion_pr_mensual]',
					valor_depreciacion_acumula='$_POST[registrar_depreciacion_pr_acumulada]',
					valor_libros='$_POST[registrar_depreciacion_pr_valor_libro]',
					fecha_depreciacion='$_POST[registrar_depreciacion_pr_fecha_depreciar]',
					ultimo_usuario='$_SESSION[id_usuario]',
					fecha_actualizacion='$fecha',
					vida_util_dep=$val	
				WHERE
					id_bienes=$_POST[registrar_depreciacion_pr_id_bien]							
				";
}
else
	die("Existe");	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>