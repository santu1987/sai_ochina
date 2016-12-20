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
		die("documento_cerrado");
}
////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
$sql_valida2 = "
			SELECT 
				documentos_cxp.id_documentos
			FROM 
				documentos_cxp
			INNER JOIN
				organismo
			ON
			documentos_cxp.id_organismo=organismo.id_organismo	
				
	        WHERE
				documentos_cxp.numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]' 
			AND	
				documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]."	
			AND	
				documentos_cxp.orden_pago!='0'
			
			";
$row_valida2= $conn->Execute($sql_valida2);
if(!$row_valida2->EOF)
{
		die("documento_orden");
}
////////////////////////////////////////////////////////////////////////////////

$sql_doc = "
			SELECT 
				documentos_cxp.id_documentos
			FROM 
				documentos_cxp
			INNER JOIN
				organismo
			ON
			documentos_cxp.id_organismo=organismo.id_organismo	
			  WHERE
				documentos_cxp.numero_documento='$_POST[cuentas_por_pagar_db_numero_documento]' 
			AND	
				documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]."	
			AND
				documentos_cxp.estatus='1'	
			AND	
				documentos_cxp.orden_pago='0'
			";
		
$row_doc= $conn->Execute($sql_doc);

if(!$row_doc->EOF)
	$sql= "DELETE FROM documentos_cxp WHERE numero_documento ='$_POST[cuentas_por_pagar_db_numero_documento]'";

else
	$bloqueado=true;
	//die($sql);
if (!$conn->Execute($sql)||$bloqueado){

	echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');}
else
	echo 'Eliminado';
?>