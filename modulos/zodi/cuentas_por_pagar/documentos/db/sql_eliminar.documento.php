<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha2 = date("Y-m-d H:i:s");
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_cxp WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF){
	$fecha_cierre_anual = $row_fecha_cierre->fields('fecha_ultimo_cierre_anual');
	$fecha_cierre_mensual = $row_fecha_cierre->fields('fecha_ultimo_cierre_mensual');
}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre_mensual);
list($dia2,$mes2,$ano2)=split("-",$fecha2);
list($dia3,$mes3,$ano3)=split("-",$fecha_cierre_anual);
//
if(($dia2 >= $dia1) && ($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}else
if(($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}
if(($dia2 >= $dia3) && ($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}
if(($cerrado!="ano")||($cerrado!="mes"))
{

			//////////////////////////////////////////////////////////////////////////////
			$sql_valida1 = "
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
							documentos_cxp.estatus='2'	
						AND
							documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]'		
						
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
						AND
							documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]'		
						
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
						AND
							documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]'		
						";
					
			$row_doc= $conn->Execute($sql_doc);
			
			if(!$row_doc->EOF)
			{	/*$sql= "DELETE FROM documentos_cxp 
				WHERE numero_documento ='$_POST[cuentas_por_pagar_db_numero_documento]' AND documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]'		
						";
			*/
			$idss=$_POST[cuentas_por_pagar_db_id];
			$sql_doc_det="DELETE FROM doc_cxp_detalle
 							WHERE   id_doc='$idss'
					";
				$sql= "UPDATE
						 documentos_cxp
						SET
						estatus='3'  
				WHERE numero_documento ='$_POST[cuentas_por_pagar_db_numero_documento]' AND documentos_cxp.id_documentos='$_POST[cuentas_por_pagar_db_id]';
				$sql_doc_det		
						";
			}		
			else
				$bloqueado=true;
				
			if (!$conn->Execute($sql)||$bloqueado){
			
				echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');}
			else
				echo 'Eliminado';
}else
die("cerrado");

?>