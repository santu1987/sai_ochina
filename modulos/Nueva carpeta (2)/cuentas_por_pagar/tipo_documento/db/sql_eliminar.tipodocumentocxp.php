<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//////////////////////////////////////////////////////////////////////////////
$sql_valida1 ="SELECT 
					id_tipo_documento
				FROM 
					tipo_documento_cxp
				INNER JOIN
					organismo
				ON
					tipo_documento_cxp.id_organismo=organismo.id_organismo	
	       		 INNER JOIN
					documentos_cxp
				ON
					tipo_documento_cxp.id_tipo_documento=documentos_cxp.tipo_documentocxp 
				 WHERE
					tipo_documento_cxp.id_tipo_documento='$_POST[cuentas_por_pagar_db_id_tipo]' 
				AND	
					documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]."
				AND
					documentos_cxp.tipo_documentocxp!=0
							
			";
$row_valida1= $conn->Execute($sql_valida1);
if(!$row_valida1->EOF)
{
		die("documento_tipo");
}
////////////////////////////////////////////////////////////////////////////////

$sql_doc = "
			SELECT 
					id_tipo_documento
				FROM 
					tipo_documento_cxp
				INNER JOIN
					organismo
				ON
					tipo_documento_cxp.id_organismo=organismo.id_organismo	
	       		 WHERE
					tipo_documento_cxp.id_tipo_documento='$_POST[cuentas_por_pagar_db_id_tipo]' 
				AND	
					tipo_documento_cxp.id_organismo = ".$_SESSION["id_organismo"]."

			";
		
$row_doc= $conn->Execute($sql_doc);
	//die($sql_doc);
if(!$row_doc->EOF)
	$sql= "DELETE FROM tipo_documento_cxp WHERE	tipo_documento_cxp.id_tipo_documento='$_POST[cuentas_por_pagar_db_id_tipo]'";
	
else
	$bloqueado=true;

if (!$conn->Execute($sql)||$bloqueado){

	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');}
else
	echo 'Eliminado';
?>