<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha2 = date("Y-m-d H:i:s");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
/*if((($dia2 <= $dia1) && ($mes2 <= $mes1) && ($ano2 <= $ano1))&&(($dia2 <= $dia3) && ($mes2 <= $mes3) && ($ano2 <= $ano2)))
{
*/
			//////////////////////////////////////////////////////////////////////////////
			$sql_valida1 = "
						SELECT 
							orden_pago.id_orden_pago
						FROM 
							orden_pago
						INNER JOIN
							organismo
						ON
						orden_pago.id_organismo=organismo.id_organismo	
						
						WHERE
							orden_pago.orden_pago='$_POST[cuentas_por_pagar_db_orden_numero_control]' 
						AND	
							orden_pago.id_organismo = ".$_SESSION["id_organismo"]."	
						AND
							orden_pago.estatus='2'	
						
						";
			$row_valida1= $conn->Execute($sql_valida1);
			if(!$row_valida1->EOF)
			{
					die("orden_cerrado");
			}
			////////////////////////////////////////////////////////////////////////////////
			//////////////////////////////////////////////////////////////////////////////
			$sql_valida2 = "
						SELECT 
							orden_pago.id_orden_pago
						FROM 
							orden_pago
						INNER JOIN
							organismo
						ON
						orden_pago.id_organismo=organismo.id_organismo	
							
						WHERE
							orden_pago.orden_pago='$_POST[cuentas_por_pagar_db_orden_numero_control]'
						AND	
							orden_pago.id_organismo = ".$_SESSION["id_organismo"]."	
						AND	
							orden_pago.cheque!='0'
						
						";
						
			$row_valida2= $conn->Execute($sql_valida2);
			if(!$row_valida2->EOF)
			{
					die("orden_cheque");
			}
			////////////////////////////////////////////////////////////////////////////////
			
			$sql_doc = "
						SELECT 
							orden_pago.id_orden_pago
						FROM 
							orden_pago
						INNER JOIN
							organismo
						ON
						orden_pago.id_organismo=organismo.id_organismo	
						  WHERE
							orden_pago.orden_pago='$_POST[cuentas_por_pagar_db_orden_numero_control]' 
						AND	
							orden_pago.id_organismo = ".$_SESSION["id_organismo"]."	
						AND
							orden_pago.estatus='1'	
						AND	
							orden_pago.cheque='0'
						";
					
			$row_doc= $conn->Execute($sql_doc);
			
			if(!$row_doc->EOF)
			/*	$sql= "DELETE FROM orden_pago WHERE orden_pago ='$_POST[cuentas_por_pagar_db_orden_numero_control]';
						UPDATE 	documentos_cxp SET orden_pago='0' WHERE orden_pago ='$_POST[cuentas_por_pagar_db_orden_numero_control]'
							
				";
			*/
			$sql= "UPDATE
						 orden_pago 
						SET
							estatus='3',
							documentos='{}'
						WHERE orden_pago ='$_POST[cuentas_por_pagar_db_orden_numero_control]';
						UPDATE 	documentos_cxp SET orden_pago='0' WHERE orden_pago ='$_POST[cuentas_por_pagar_db_orden_numero_control]'
			";
			else
				$bloqueado=true;
				//die($sql);
			if (!$conn->Execute($sql)||$bloqueado){
			
				echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');}
			else
				echo 'Eliminado';
/*}else
die("cerrado");*/
?>