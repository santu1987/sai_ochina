<?php
session_start();
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
/*if(($cerrado!="ano")||($cerrado!="mes"))
{*/
			$monto = str_replace(".","",$_POST[cuentas_por_pagar_db_facturas_total]);
			$opcion=$_POST['cuentas_por_pagar_orden_db_op_oculto'];
			$benef=strtoupper($_POST['cuentas_por_pagar_db_benef']);
						if($opcion=='1')
						{
							$sql = "	
										INSERT INTO 
											orden_pago
											(
												id_organismo,
												ano,
												id_proveedor,
												fecha_orden_pago,
												comentarios,
												ultimo_usuario,
												fecha_ultima_modificacion,
												documentos,
												cheque,
												secuencia,
												estatus,
												saldo,
												beneficiario
												
											) 
											VALUES
											(
												".$_SESSION["id_organismo"].",
												'$_POST[cuentas_por_pagar_db_ayo_orden_pago]',
												'$_POST[cuentas_por_pagar_db_orden_proveedor_id]',
												'$_POST[cuentas_por_pagar_db_orden_fecha_v]',
												 '$_POST[cuentas_por_pagar_db_ordenes_comentarios]',
												".$_SESSION['id_usuario']."	,
												'".date("Y-m-d H:i:s")."',
												'{".$_POST[cuentas_por_pagar_db_facturas_oculto]."}',
												'0',
												'0',
												'1',
												'".str_replace(",",".",$monto)."',
												'$benef'				
											)
									";
								}else
						if($opcion=='2')
						{
							$sql = "	
										INSERT INTO 
											orden_pago
											(
												id_organismo,
												ano,
												cedula_rif_beneficiario,
												beneficiario,
												fecha_orden_pago,
												comentarios,
												ultimo_usuario,
												fecha_ultima_modificacion,
												documentos,
												cheque,
												secuencia,
												estatus,
												id_proveedor
												
											) 
											VALUES
											(
												".$_SESSION["id_organismo"].",
												'$_POST[cuentas_por_pagar_db_ayo_orden_pago]',
												'$_POST[cuentas_por_pagar_orden_db_empleado_codigo]',
												'$_POST[cuentas_por_pagar_orden_db_empleado_nombre]',
												'$_POST[cuentas_por_pagar_db_orden_fecha_v]',
												 '$_POST[cuentas_por_pagar_db_ordenes_comentarios]',
												".$_SESSION['id_usuario']."	,
												'".date("Y-m-d H:i:s")."',
												'{".$_POST[cuentas_por_pagar_db_facturas_oculto]."}',
												'0',
												'0',
												'1',
												'0'
																	
											)
									";
								}
			if (!$conn->Execute($sql)) 
				//die ('Error al Registrar: '.$sql);
			//die ('Error al Registrar: '.$conn->ErrorMsg());
			die($sql);
			else
				{
						//// consultando el numero de la orden de pago a ser guardado
							$sql="SELECT 
									orden_pago 
								FROM 
									orden_pago
								INNER JOIN
									organismo
								ON
									orden_pago.id_organismo=organismo.id_organismo
								
									ORDER BY ORDEN_PAGO DESC
									";
									
									$row_pago=& $conn->Execute($sql);
									if (!$row_pago->EOF)
									{
										$orden = $row_pago->fields("orden_pago");
									}else
									die("error");
						/////////////////////////////////////////////////
						
						$vector=$_POST[cuentas_por_pagar_db_facturas_oculto];
						$facturas=split(",",$vector);
						sort($facturas);
						if($facturas!="")
						{
							$contador=count($facturas);
						
							$is=0;
							while($is<$contador)
							{
										
										$sql = "UPDATE documentos_cxp 
										 SET
											orden_pago='$orden',
											ultimo_usuario=".$_SESSION['id_usuario'].", 
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
										WHERE 
													id_organismo=$_SESSION[id_organismo]
											AND
												id_documentos='$facturas[$is]'
												";
												if (!$conn->Execute($sql)) {
												die ('Error al Actualizar: '.$conn->ErrorMsg());}
									
							$is=$is+1;
							}
							
													$registrado="Registrado"."*".$orden;
													die($registrado);
						
						}
				
				}
				//die("Registrado");
				//die("$sql");
/*}
else
die("cerrados");	*/			
?>