<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
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
				$sql = "
							SELECT 
								id_cheques
							FROM 
								cheques
							INNER JOIN
								organismo
							ON
							cheques.id_organismo=organismo.id_organismo	
							INNER JOIN
								 orden_pago
							ON
							cheques.numero_cheque=orden_pago.cheque
							WHERE
								cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]' 
							AND
								id_organismo=".$_SESSION["id_organismo"]."	
							";
				$row= $conn->Execute($sql);
				
				if(!$row->EOF)
				{
					$sql = "DELETE 
								FROM 
										cheques
								 WHERE 
										cheques.numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]' 
								 AND
										 id_organismo=".$_SESSION["id_organismo"].";
								UPDATE orden_pago
								SET
										cheque='0',
										id_banco='0',
										cuenta_banco='0'
								WHERE 
									cheque='$_POST[tesoreria_cheques_db_n_precheque]'
								AND
									orden_pago.id_organismo=".$_SESSION["id_organismo"]."	
					
					";
					
					}																	
					else
						$bloqueado=true;
				//echo($sql_pago);
				if (!$conn->Execute($sql)||$bloqueado){
				//die($sql);
					echo (($bloqueado)?$msgBloqueado:'Error al Eliminar: '.$conn->ErrorMsg().'<br />');
					}
					else
						{
							die ('Eliminado');
						}
}
else
die("cerrados");							
?>