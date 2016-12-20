<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$nom=$_POST['cuentas_por_cobrar_db_tipo_documento'];
$siglas=$_POST['cuentas_por_cobrar_db_siglas_documento'];
$id=$_POST['cuentas_por_pagar_db_id_tipo'];
$Sql="
						SELECT 
							tipo_documento_cxp.id_tipo_documento
						FROM 
							tipo_documento_cxp
						INNER JOIN 
							organismo 
						ON 
							tipo_documento_cxp.id_organismo =organismo.id_organismo
						WHERE
							tipo_documento_cxp.nombre='$_POST[cuentas_por_cobrar_db_tipo_documento]'
						AND
							tipo_documento_cxp.siglas='$_POST[cuentas_por_cobrar_db_siglas_documento]'			
						AND 
							tipo_documento_cxp.id_tipo_documento='$id';
					";

$row=& $conn->Execute($sql);

if(!$row->EOF)
{
	$sql = "		UPDATE tipo_documento_cxp
						 SET
						 	nombre = '$nom',
							siglas='$siglas',
							comentarios='$_POST[cuentas_por_cobrar_db_tipo_documento_comentarios]',
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_ultima_modificacion='".$fecha."'
						WHERE tipo_documento_cxp.id_tipo_documento='$id'
						AND
							tipo_documento_cxp.id_organismo=$_SESSION[id_organismo]
				";
				}
else
	die ("NoActualizo");
if (!$conn->Execute($sql)) {
	echo($sql);
	die ('Error al Actualizar: '.$conn->ErrorMsg());}
else {
	die ('Actualizado');
	}
?>