<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
if($_POST['tesoreria_firmas_voucher_db_estatus']=='1')
{
						$sql_activo = "SELECT count(estatus) FROM  firmas_voucher WHERE estatus='1'  AND  $_POST[tesoreria_firmas_voucher_db_estatus]='1' AND  firmas_voucher.id_organismo=$_SESSION[id_organismo] and id_firmas_voucher !=$_POST[tesoreria_vista_banco_firmas_voucher]
";
							if (!$conn->Execute($sql_activo)) die ('Error al consultar: '.$conn->ErrorMsg());
							$row_activo= $conn->Execute($sql_activo);
										if(!$row_activo->EOF)
										{
											$count = $row_activo->fields("count");
											if($count>0)
											{
												$opcion="activa";
												$estatus='2';
											}else 
											{	
												$estatus='1';
											}
										}	
							}
							else
								$estatus='2';
$sql = "SELECT id_organismo FROM firmas_voucher WHERE  id_firmas_voucher=$_POST[tesoreria_vista_banco_firmas_voucher] and  id_organismo!=".$_SESSION["id_organismo"];
$row=& $conn->Execute($sql);

if($row->EOF)
{	
	$sql = "		UPDATE firmas_voucher
						 SET
						 	codigo_director_ochina='$_POST[tesoreria_firmas_voucher_db_id_director]',
						 	codigo_director_administracion='$_POST[tesoreria_firmas_voucher_db_id_director_administracion]',
							codigo_jefe_finanzas='$_POST[tesoreria_firmas_voucher_db_id_jefe_finanzas]',
							codigo_preparado_por=	".$_SESSION['id_usuario'].",
							comentarios='$_POST[tesoreria_firmas_voucher_db_comentarios]',	
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_ultima_modificacion='".$fecha."',
							fecha_firma='$_POST[form_tesoreria_db_firmas_voucher_rp_fecha]',
							estatus='$estatus'
						WHERE id_firmas_voucher =$_POST[tesoreria_vista_banco_firmas_voucher]";
//die($sql_activo);
}						
else
	//die($sql);
	die ("NoActualizo");
if (!$conn->Execute($sql)) {
	
	die ('Error al Actualizar: '.$conn->ErrorMsg());}
else {
	if($opcion=="activa"){die("firma_activa");}

	die ('Actualizado');
	}
?>