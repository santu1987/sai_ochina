<?php
session_start();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[cuentas_por_pagar_db_fecha_f];
$id_facturas=$_POST[cuentas_por_pagar_db_facturas_lista2];
//$fecha = date("Y-m-d H:i:s");
//datos de la factura
$tipo_documento=$_POST['cuentas_por_pagar_db_tipo_documento'];
$sql_tipo_doc="SELECT 
				cuenta_contable_contabilidad.cuenta_contable
							
			FROM 
				rel_doc_cta  
			INNER JOIN
					cuenta_contable_contabilidad
				ON
					cuenta_contable_contabilidad.id=rel_doc_cta.id_cta_contable
			INNER JOIN
					tipo_documento_cxp
				ON
					tipo_documento_cxp.id_tipo_documento=rel_doc_cta.id_tipo		
	 		WHERE
				rel_doc_cta.id_tipo='$tipo_documento'
					";
//die($sql_tipo_doc);
$row_tipo=& $conn->Execute($sql_tipo_doc);
if(!$row_tipo->EOF)
{
	$cuenta_contable=$row_tipo->fields("cuenta_contable");
}
else
{
	die("NoRelacion");//signifca que no se encuentran relacionados los tipo de documentos con las cuentas contables
}	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//paso2: en calcular total de las facturas 
$vector=$_POST['cuentas_por_pagar_db_facturas_lista2'];
						$facturas=split(",",$vector);
						sort($facturas);
						if($facturas!="")
						{
							$contador=count($facturas);
							$is=0;
							while($is<$contador)
							{
								
								$sql_facturas="
										SELECT id_documentos,

												((((monto_base_imponible)*(porcentaje_iva))/100)+(monto_base_imponible)) AS monto1,

												((((monto_base_imponible2)*(porcentaje_iva2))/100)+(monto_base_imponible2))AS monto2,

												(((monto_base_imponible)*(porcentaje_iva))/100)+(((monto_base_imponible2)*(porcentaje_iva2))/100) as total_iva,
											((((((monto_base_imponible)*(porcentaje_iva))/100)+(((monto_base_imponible2)*(porcentaje_iva2))/100)))+(monto_bruto)) as total,


												((((monto_base_imponible)*(porcentaje_iva))/100)*(porcentaje_retencion_iva)) as retencion1,

												((((monto_base_imponible2)*(porcentaje_iva2))/100)*(retencion_iva2)) as retencion2,

												(((((monto_base_imponible)*(porcentaje_iva))/100)*(porcentaje_retencion_iva/100))+((((monto_base_imponible2)*(porcentaje_iva2))/100)*(retencion_iva2/100))) as total_retencion

										FROM

												documentos_cxp
										WHERE
												documentos_cxp.id_documentos='$facturas[$is]'				
												
";
//die($sql_facturas);
							$row_fact=& $conn->Execute($sql_facturas);
							if(!$row_fact->EOF)
							{
								$total_factura=$total_factura+$row_fact->fields("total");
								$total_retenciones=$total_retenciones+$row_fact->fields("total_retencion");
							}
							else
							{
								die('error_sumando_facturas');
							}
							$is++;//contador del ciclo
						}//while($is<$contador) 
				}//fin de 	if($facturas!="")
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//datos del asiento
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$fecha = $_POST[cuentas_por_pagar_db_fecha_f];
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
$dia=substr($fecha,0,2);
$comprobante=$_POST[cuentas_por_pagar_numero_pr_numero_comprobante2];
$tipo=$_POST[cuentas_por_pagar_integracion_tipo];
//calculo de la secuencia
//////////////////////////////////////////////////////////////////////////////
$sql_sec="SELECT secuencia FROM movimientos_contables
							inner join
								organismo
							on
								movimientos_contables.id_organismo=organismo.id_organismo
							where		
									(organismo.id_organismo =".$_SESSION['id_organismo'].")
							and
								numero_comprobante='$comprobante'
							and	
								ano_comprobante='$ano'
							order by
								id_movimientos_contables desc
						
			";
			//die($sql_sec);
			$rs_sec =& $conn->Execute($sql_sec);
			$secuencia=$rs_sec->fields("secuencia");	
			$secuencia=$secuencia+1;
////////////////////////////////////////////////////////////////////////////////////////
$debe_haber='2';
$monto_credito=$total_factura;
$monto_debito=0;	
/*$contabilidad_comp_pr_ubicacion=$_POST[cxp_pr_ejec_id];
$contabilidad_comp_pr_centro_costo=$_POST[cxp_centro_costo_id];
$contabilidad_comp_pr_auxiliar=$_POST[cuentas_por_pagar_integracion_id_aux];
$contabilidad_comp_pr_utf=$_POST[cxp_comp_pr_utf_id];
$contabilidad_comp_pr_acc=$_POST[cxp_pr_acc_id];*/
//if($contabilidad_comp_pr_ubicacion=="")
	$contabilidad_comp_pr_ubicacion=0;
//if($contabilidad_comp_pr_centro_costo=="")
	$contabilidad_comp_pr_centro_costo=0;
//if($contabilidad_comp_pr_auxiliar=="")
	$contabilidad_comp_pr_auxiliar=0;
//if($contabilidad_comp_pr_utf=="")
	$contabilidad_comp_pr_utf=0;
//if($contabilidad_comp_pr_acc=="")
	$contabilidad_comp_pr_acc=0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//creo los querys de retenciones iva
if($total_retenciones!="0")
{
//query 1: la misma cuenta de arriba 
$monto_credito2=0;
$monto_debito2=$total_retenciones;
$debe_haber2='1';
$secuencia2=$secuencia+1;
$query_ret_iva1="
												INSERT INTO 
																		movimientos_contables
																		(
																			id_organismo,
																			numero_comprobante,
																			secuencia,
																			ano_comprobante,
																			mes_comprobante,
																			id_tipo_comprobante,
																			comentario,
																			cuenta_contable,
																			descripcion,
																			referencia,
																			debito_credito,
																			monto_debito,
																			monto_credito,
																			id_unidad_ejecutora,
																			id_proyecto,
																			id_accion_central,
																			id_utilizacion_fondos,
																			id_auxiliar,
																			fecha_comprobante,
																			ultimo_usuario,
																			ultima_modificacion,
																			estatus,
																			comprobante_ant,
																			fecha_ant  
																			
																		) 
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$comprobante',
																			'$secuencia2',
																			'$ano',
																			'$mes',
																			'$_POST[cuentas_por_pagar_integracion_tipo_id]',
																			'$_POST[cuentas_por_pagar_integracion_desc_asiento]',
																			'$cuenta_contable',
																			'$_POST[cuentas_por_pagar_integracion_desc_asiento]',
																			'$_POST[cuentas_por_pagar_db_compromiso_n]',
																			'$debe_haber2',
																			$monto_debito2,
																			$monto_credito2,
																			$contabilidad_comp_pr_ubicacion,
																			$contabilidad_comp_pr_centro_costo,
																			$contabilidad_comp_pr_acc,
																			$contabilidad_comp_pr_utf,
																			$contabilidad_comp_pr_auxiliar,
																			'".$fecha."',
																			 ".$_SESSION['id_usuario'].",
																			 '".date("Y-m-d H:i:s")."',
																			 '0',
																			 '$comprobante',
																			 '".date("Y-m-d H:i:s")."'
																		);
																

																";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//query 2: la misma cuenta de arriba 
$monto_credito3=$total_retenciones;
$monto_debito3=0;
$debe_haber3='2';
$secuencia3=$secuencia2+1;
/////////////////////////////////
///CONSULTO LA CUENTA DE LAS RETENCIONES
$sql_cta="select 
											id,
											cuenta_contable_contabilidad.cuenta_contable,
											cuenta_contable_contabilidad.requiere_auxiliar,
											cuenta_contable_contabilidad.requiere_unidad_ejecutora,
											cuenta_contable_contabilidad.requiere_proyecto,
											cuenta_contable_contabilidad.requiere_utilizacion_fondos
										 from 
											cuenta_contable_contabilidad 
										where nombre='RETENCIONES DE IVA'";
										//die($sql_cta);
										$row_cta=& $conn->Execute($sql_cta);
										if(!$row_cta->EOF)
										{
											$id_cuenta_contable=$row_cta->fields("id");
											$cuenta_contable_iva=$row_cta->fields("cuenta_contable");
											
										}

//////////////////////////////////

$query_ret_iva2="
												INSERT INTO 
																		movimientos_contables
																		(
																			id_organismo,
																			numero_comprobante,
																			secuencia,
																			ano_comprobante,
																			mes_comprobante,
																			id_tipo_comprobante,
																			comentario,
																			cuenta_contable,
																			descripcion,
																			referencia,
																			debito_credito,
																			monto_debito,
																			monto_credito,
																			id_unidad_ejecutora,
																			id_proyecto,
																			id_accion_central,
																			id_utilizacion_fondos,
																			id_auxiliar,
																			fecha_comprobante,
																			ultimo_usuario,
																			ultima_modificacion,
																			estatus,
																			comprobante_ant,
																			fecha_ant  
																			
																		) 
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$comprobante',
																			'$secuencia3',
																			'$ano',
																			'$mes',
																			'$_POST[cuentas_por_pagar_integracion_tipo_id]',
																			'$_POST[cuentas_por_pagar_integracion_desc_asiento]',
																			'$cuenta_contable_iva',
																			'$_POST[cuentas_por_pagar_integracion_desc_asiento]',
																			'$_POST[cuentas_por_pagar_db_compromiso_n]',
																			'$debe_haber3',
																			$monto_debito3,
																			$monto_credito3,
																			$contabilidad_comp_pr_ubicacion,
																			$contabilidad_comp_pr_centro_costo,
																			$contabilidad_comp_pr_acc,
																			$contabilidad_comp_pr_utf,
																			$contabilidad_comp_pr_auxiliar,
																			'".$fecha."',
																			 ".$_SESSION['id_usuario'].",
																			 '".date("Y-m-d H:i:s")."',
																			 '0',
																			 '$comprobante',
																			 '".date("Y-m-d H:i:s")."'
																		);
																

																";

}//fin deif($total_retenciones!="0")
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//creado el query
$sql_mov ="
												INSERT INTO 
																		movimientos_contables
																		(
																			id_organismo,
																			numero_comprobante,
																			secuencia,
																			ano_comprobante,
																			mes_comprobante,
																			id_tipo_comprobante,
																			comentario,
																			cuenta_contable,
																			descripcion,
																			referencia,
																			debito_credito,
																			monto_debito,
																			monto_credito,
																			id_unidad_ejecutora,
																			id_proyecto,
																			id_accion_central,
																			id_utilizacion_fondos,
																			id_auxiliar,
																			fecha_comprobante,
																			ultimo_usuario,
																			ultima_modificacion,
																			estatus,
																			comprobante_ant,
																			fecha_ant  
																			
																		) 
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$comprobante',
																			'$secuencia',
																			'$ano',
																			'$mes',
																			'$_POST[cuentas_por_pagar_integracion_tipo_id]',
																			'$_POST[cuentas_por_pagar_integracion_desc_asiento]',
																			'$cuenta_contable',
																			'$_POST[cuentas_por_pagar_integracion_desc_asiento]',
																			'$_POST[cuentas_por_pagar_db_compromiso_n]',
																			'$debe_haber',
																			$monto_debito,
																			$monto_credito,
																			$contabilidad_comp_pr_ubicacion,
																			$contabilidad_comp_pr_centro_costo,
																			$contabilidad_comp_pr_acc,
																			$contabilidad_comp_pr_utf,
																			$contabilidad_comp_pr_auxiliar,
																			'".$fecha."',
																			 ".$_SESSION['id_usuario'].",
																			 '".date("Y-m-d H:i:s")."',
																			 '0',
																			 '$comprobante',
																			 '".date("Y-m-d H:i:s")."'
																		);
																
																	$query_ret_iva1
																	$query_ret_iva2
																";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
if (!$conn->Execute($sql_mov)) 
{
	
	$responce='Error al Actualizar: '.$conn->ErrorMsg().$sql_mov ;
	//$responce=$responce."*".$debe."*".$haber."*".$comprobante."*".$resta;
	
}	
else
{
	$sql_sumas=" SELECT
																	SUM(monto_debito) as debe,
																	SUM(monto_credito) as haber
																from
																	movimientos_contables
																where numero_comprobante='$comprobante'	
																and
																	movimientos_contables.estatus!='3'
																and	
																	ano_comprobante='$ano';										
												";
												$row_sumas=& $conn->Execute($sql_sumas);
												if(!$row_sumas->EOF)
												{
													$debe=number_format($row_sumas->fields("debe"),2,',','.');
													$haber=number_format($row_sumas->fields("haber"),2,',','.');
													$resta=round($row_sumas->fields("debe"),2)-round($row_sumas->fields("haber"),2);
													$resta=number_format($resta,2,',','.');
													$responce="Actualizado"."*".$debe."*".$haber."*".substr($comprobante,10)."*".$resta."*".$comprobante;
												//	die($responce);
												}
}
die($responce);
?>