<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
include_once('../../../../controladores/numero_to_letras.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$check = $_POST["tesoreria_cheques_db_itf"]; 
//
$fecha2 = date("Y-m-d H:i:s");
$causado=0;
$causados=0;
$ext=0;
																$fechass=$_POST['tesoreria_cheque_pr_fecha'];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
*** SE TUVO QUE MODIFICAR NUEVAMENTE ESTE SCRIPT FECHA 29/02/2012 CAMBIANDO LAS RELACIONES CON LAS TABLAS REQUISICION ENCABEZADO  A ORDEN ENCABEZADO, PARA AGOSTO DE 2011 ESTABA ASI PERO DEBIDO A INDICACIONES DADAS PARA CREA UN PROCESO QUE PERMITIERA CREAR ORDENES A CONTABILIDAD (PROGRAMA PRESUPUESTARIO) SE REALIZÓ EL CAMBIO Y AHORA DEBE REVERTIRSE EN TODOS LOS PROGRAMAS DE LOS MODULOS DE CXP Y TESORERIA...
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_tesoreria WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF)
{
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
{
*/			//DATOS PARA CARGAR DOCUMENTOS..
			$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
			$rs_tipos_ant =& $conn->Execute($sql_ant);
			$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
			//
			$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
			$rs_tipos_fact =& $conn->Execute($sql_fact);
			$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
			 //
			//------------------------------------------------------------------------------------------------------
			$Sql_firmas="SELECT 
							firmas_voucher.id_firmas_voucher,
							firmas_voucher.codigo_director_ochina,
							firmas_voucher.codigo_director_administracion,
							firmas_voucher.codigo_jefe_finanzas,
							firmas_voucher.codigo_preparado_por,
							firmas_voucher.comentarios,
							firmas_voucher.fecha_firma
						FROM 
							firmas_voucher
						INNER JOIN 
							organismo 
						ON 
							firmas_voucher.id_organismo = organismo.id_organismo
						WHERE
								firmas_voucher.estatus='1'
								";	 
			$row_firmas=& $conn->Execute($Sql_firmas);
			if($row_firmas->EOF)
			{
				die("firma_inactiva");
			}else
			$firmas=$row_firmas->fields("fecha_firma");
			///////////////////////////////////// verificando si el monto pagado no excede a lo comprometido/////////////////////////////////////
			//------ verificando si la cuenta y el banco tienen chequera creada
			$sql_cheque = "SELECT 
								id_cheques
						   FROM 
								cheques 
						   WHERE 
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
						   AND 
								 id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND 
								id_proveedor='$_POST[tesoreria_cheques_pr_proveedor_id]'
							AND
								numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
							AND
								cheques.tipo_cheque='1'			 
							AND		
								cheques.id_organismo=$_SESSION[id_organismo]		 
								 ";
								 //die ($sql_cheque);
					if (!$conn->Execute($sql_cheque))die ($sql_cheque);
						$row_verificacion1= $conn->Execute($sql_cheque);
			//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			$sql_saldo_actual = "SELECT 
										 saldo_actual
								   FROM 
										 banco_cuentas
									WHERE
										cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
									AND
										estatus='1'		
								  ";
				$row_saldo_actual= $conn->Execute($sql_saldo_actual);
				if(!$row_saldo_actual->EOF)	
				{		
			
							$saldo_actual=$row_saldo_actual->fields("saldo_actual");
							$monto_cheque=$_POST[tesoreria_cheques_db_monto_pagar];
							$monto_cheque= str_replace(".","",$monto_cheque);
							$monto_cheque=str_replace(",",".",$monto_cheque);
			
							$saldo_total=($saldo_actual)-($monto_cheque);
							//die($saldo_total);
								if($saldo_total<'0')
								{
									$astre="0";
									//die("no_disponible_saldo");
								}
								else
								{
									///
									$sql = "UPDATE banco_cuentas 
									 SET
										saldo_actual='$saldo_total'
									WHERE cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND
										id_organismo=$_SESSION[id_organismo]
												";
								
								if (!$conn->Execute($sql)) {
								
								die ($sql);}
								////
								}
									
				}
			//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------			
			//------------------- VERIFICANDO SI LAS ORDENES DE PAGO NO FUERON CANCELADAS POR OTROS CHEQUES//----------------------------//
							$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
							$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
							$i=0;
							while($i < $contador)
							{
									$sql_orden="SELECT * 
												FROM
													orden_pago
												WHERE
													(orden_pago.id_orden_pago='$vector[$i]')
												AND
													(orden_pago.cheque='$_POST[tesoreria_cheques_db_n_precheque]')
												";
							
									//echo($sql_pago);
									$i=$i+1;	
								if (!$conn->Execute($sql_orden))die ('No_relacion');
									$row_verificacion2= $conn->Execute($sql_orden);
							}		
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////¡
			//---------------busqueda del ultimo CHEQUE----------------
			$sql_ultimo_emitido = "SELECT 
										ultimo_emitido,cantidad_cheques,secuencia
								   FROM 
										chequeras 
									WHERE
										cuenta='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
									AND
										estatus='1'		
								  ";
								  //die($sql_ultimo_emitido);
			if (!$conn->Execute($sql_ultimo_emitido))
					die ('Error_impresion' );
			//die ('Error 	consulta: '.$conn->ErrorMsg());
			//die($sql_ultimo_emitido);	
				$row_emitido= $conn->Execute($sql_ultimo_emitido);
				if(!$row_emitido->EOF)	
				{
					$cantidad=$row_emitido->fields("cantidad_cheques");
					$n_cheque=$row_emitido->fields("ultimo_emitido");
					$secuencia=$row_emitido->fields("secuencia");
					$secuencia2=$secuencia;
					$n_cheque_resultado=intval($n_cheque)+1;
					$n_ultimo=intval($n_cheque_resultado)+1;
							$proximo_emitir=$n_cheque_resultado;
							$estatus=1;
						
///////////////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
							$vector = split( ",", $_POST['tesoreria_cheques_db_ordenes_pago'] );
							$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
							$i=0;
							while($i < $contador)
							{///////////consultando las orden de pago
								$sql_orden="SELECT  orden_pago,documentos,saldo
																	FROM
																		orden_pago
																	WHERE(orden_pago.id_orden_pago='$vector[$i]')";		
								$row_orden=$conn->Execute($sql_orden); 
								$documentos=$row_orden->fields("documentos");
								$saldo=$row_orden->fields("saldo");
								$doc1=str_replace("{","",$documentos);
								$doc2=str_replace("}","",$doc1);
								$facturas= split(",",$doc2);
								$contador_fact=count($facturas);
								$i_fact=0;
								while($i_fact < $contador_fact)
								{
								//////////consultando las facturas			
														$sql_facturas="
																			SELECT 
																					numero_compromiso,monto_bruto
																			FROM
																					documentos_cxp
																			where
																					id_documentos='$facturas[$i_fact]'";
																					
																								
														$row_documentos=& $conn->Execute($sql_facturas);
														$numero_compromiso=$row_documentos->fields("numero_compromiso");
														$monto_restar=$row_documentos->fields("monto_bruto");
														if(($numero_compromiso==0)or($numero_compromiso=="")or($numero_compromiso=="NULL"))
															{
																$partida=0;
																$unidad_ejecutora=0;
																$accion_central=0;
																$accion_especifica=0;
																$pre_orden=0;
																$tipo=0;
															}
														else{
																													//	die("entreo");

														///////////////////////////////////////////////////////////////////////////////////
																		$sql_facturas="SELECT 
																								id_documentos,
																								porcentaje_iva,
																								porcentaje_retencion_iva, 
																								monto_bruto,
																								monto_base_imponible,
																								tipo_documentocxp,
																								amortizacion,
																								retencion_ex1,
																								retencion_ex2,
																								pret1,
																								pret2,
																								aplica_bi_ret_ex2,
																								aplica_bi_ret_ex1,
																								porcentaje_retencion_islr,
																								porcentaje_retencion_iva,
											  ((documentos_cxp.monto_bruto+
											  (documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+
											  (documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100)))-
										 ((documentos_cxp.retencion_iva2*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible2/100)/100)+
										  (documentos_cxp.porcentaje_retencion_iva*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)/100)+
										  (porcentaje_retencion_islr*documentos_cxp.monto_bruto/100)+
										  (documentos_cxp.retencion_ex1+documentos_cxp.retencion_ex2))as total_retenciones
																						FROM
																								documentos_cxp
																								where						   
																									documentos_cxp.numero_compromiso='$numero_compromiso'
																								and	
																									id_documentos='$facturas[$i_fact]'";
																										   
																								$row_factura=& $conn->Execute($sql_facturas);
																								$total_renglon=0;
																								if(!$row_factura->EOF)
																								{
																								  //trabajando con el valor de las retenciones
																								 $pret1=$row_factura->fields("pret1");
																								 $pret2=$row_factura->fields("pret2");
																								 $monto_factura2=$row_factura->fields("total_retenciones");
																								 //
																								 $porcentaje_retencion_iva=$row_factura->fields("porcentaje_retencion_iva");
																								 $porcentaje_retencion_islr=$row_factura->fields("porcentaje_retencion_islr");
																								 $retencion_ex1=$row_factura->fields("retencion_ex1");
																								 $retencion_ex2=$row_factura->fields("retencion_ex2");
																								 $ret_totality=$retencion_ex1+$retencion_ex2;
																								 $aplica_bi_ret_ex1=$row_factura->fields("aplica_bi_ret_ex1");
																								 $aplica_bi_ret_ex2=$row_factura->fields("aplica_bi_ret_ex2");
																								 //
																								 
																										$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
																										$monto_factura=$row_factura->fields("monto_bruto");
																										if(($row_factura->fields("tipo_documentocxp")==$tipos_ant))
																										{
																											$monto_factura=0;
																										}
																										if((($row_factura->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura->fields("amortizacion")!='0,00'))
																										{
																											$monto_factura="";	
																											//$monto_ante=($row_factura->fields("monto_bruto")+$row_factura->fields("amortizacion"));
																											$monto_ante=$row_factura->fields("monto_bruto");
																											$p_iva_factura=$monto_ante*$row_factura->fields("porcentaje_iva")/100;
																											
																										//	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
																											$monto_factura=$monto_ante+$p_iva_factura;
																											
																										}
																										
																										$p_iva_total=$p_iva_total+$p_iva_factura;
																									//	echo($p_iva_total."-");
																									//esta variable ya no se utiliza....
																										$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
																								
																								
																								
																								
																								//echo($monto_factura."-");
																								//echo($monto_factura."-");
																								/*$total_documento=$monto_restar; 
																								$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento; 
																								$fact_ord=($total_compromiso)-$lo_q_qeda_fact;*/
																								}	
												//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
												//// seleccionando los datos sobre las partidas
												/*proveedor.nombre,
																			proveedor.id_proveedor as id_proveedor,proveedor.codigo_proveedor as codigo_proveedor ,INNER JOIN	
																					proveedor
																			ON
																			\"orden_compra_servicioE\".id_proveedor=proveedor.id_proveedor		 proveedor.nombre,

																			proveedor.id_proveedor,

																			proveedor.codigo_proveedor,
*/
															  $sql="SELECT  distinct
																			
																			
																			\"orden_compra_servicioE\".id_unidad_ejecutora, 
																			\"orden_compra_servicioE\".tipo,
																			\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
																			\"orden_compra_servicioE\".id_accion_especifica, 
																			\"orden_compra_servicioE\".numero_compromiso, 
																			\"orden_compra_servicioE\".numero_pre_orden,
																			\"orden_compra_servicioE\".tipo,
																			   partida, 
																			   generica, 
																			   especifica, 
																			   subespecifica
																			FROM 
																				\"orden_compra_servicioE\"
																			INNER JOIN
																				organismo
																			ON
																				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
																			INNER JOIN
																				\"orden_compra_servicioD\"
																			ON
																				\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																			
																			where
																				\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
																			GROUP BY
																				
																			\"orden_compra_servicioE\".id_unidad_ejecutora, 

																			\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 

																			\"orden_compra_servicioE\".id_accion_especifica, 

																			\"orden_compra_servicioE\".numero_compromiso, 

																			\"orden_compra_servicioE\".numero_pre_orden,

																			\"orden_compra_servicioE\".tipo,

																			   partida, 

																			   generica, 

																			   especifica, 

																			   subespecifica
																				";	
																			//	die($sql);
																		$row_orden_compra=& $conn->Execute($sql);
																		while(!$row_orden_compra->EOF)
																		{
																		///////////////////////////////////////////////
																			$sql_compromiso="SELECT 
																									monto,cantidad,fecha_orden_compra_servicio,impuesto
																								FROM 
																									\"orden_compra_servicioE\"
																								INNER JOIN
																									\"orden_compra_servicioD\"
																								ON
																									\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																								where
																									\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
																								AND 
																									(\"orden_compra_servicioD\".ano = '".date("Y")."')
																								AND
																									\"orden_compra_servicioD\".partida = '".$row_orden_compra->fields("partida")."'  
																								AND	
																									\"orden_compra_servicioD\".generica = '".$row_orden_compra->fields("generica")."'
																								AND	
																									\"orden_compra_servicioD\".especifica = '".$row_orden_compra->fields("especifica")."'  
																								AND
																									\"orden_compra_servicioD\".subespecifica = '".$row_orden_compra->fields("subespecifica")."'	";
																									//die($sql_compromiso);
																			if (!$conn->Execute($sql_compromiso)) die ('Error al Registrar: '.$conn->ErrorMsg());
																			$row_compromiso= $conn->Execute($sql_compromiso);
																			/// calculando totales segun compromisos
																								while(!$row_compromiso->EOF)
																									{
																									  //sacando el total de la orden de compra
																										  $monto_compromiso=$row_compromiso->fields("monto");
																										  $cantidad_compromiso=$row_compromiso->fields("cantidad");
																										  $total_compromiso=$monto_compromiso*$cantidad_compromiso;
																										  $imp=$row_compromiso->fields("impuesto");
									$iva=($total_compromiso*$imp)/100;
									$total_compromiso=$total_compromiso+$iva;
									
																										  $total_compromiso_a=$total_compromiso_a+total_compromiso;
																										  $fecha_ord=$row_compromiso->fields("fecha_orden_compra_servicio");
																										  $row_compromiso->MoveNext();
																									}//fin del mientras de row compromiso																					
											//////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
											/*************************/
											//proceso para sacara contra que va el causado
											$partidas=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
										//echo("partidas=".$partidas);
										$sql_doc_det="select sum(monto) as causado,partida,impuesto from doc_cxp_detalle
															inner join
															documentos_cxp
															ON doc_cxp_detalle.id_doc=documentos_cxp.id_documentos
															where partida='$partidas'
															and
															documentos_cxp.numero_compromiso='$numero_compromiso'
															and
															id_doc='$facturas[$i_fact]'
															group by
															partida,impuesto";
									//if($partidas=='403100300')
									//die($sql_doc_det);	
													$row_doc_det=& $conn->Execute($sql_doc_det);
													if(!$row_doc_det->EOF)
													{
													//die($sql_doc_det);
														
														$causado=$row_doc_det->fields("causado");
														$impuesto=$row_doc_det->fields("impuesto");
														$iva_causado=($causado*$impuesto)/100;
														$causado2=$causado+$iva_causado;
														$causados=round($causados,2)+round($causado2,2);
/////////////MEGA PROCES CREADO POR EL SUPER PROGRAMADOR OSEA "YO"///////
//*-QUE HACE: DEBIDO A LOS CONTINUOS CAMBIOS DE LAS ESTRUCUTRAS Y PROCESOS DE ADMINISTRACIÓN RESULTA QUE ES NECESARIO QUE EL MÓDULO DE CHEQUES PERMITA PAGAR CHEQUES DE MANERA PRO RATEADA ES DECIR  VARIOS CHEQUES A EMITIRSE PARA UN MISMO COMPROMISO O VARIOS INCLUSIVE, LO CUAL HIZO NECESARIO LA CREACIÓN DE UN SUPER FUME, PARA QUE ESTE PROGRAMA HECHO POR SU HUMILDE SERVIDOR PERMITA REALIZAR EL PAGADO PRESUPUESTARIO EN VISTA A ESA SITUACION GRACIAS A LAS INNOVACIONES DEL SISTEMA :) ...*/
//sacando valores de las retenciones para usar estas variables al momento de pagar presupuestariamente	---como no se definio si realmente si las facturas prorateadas deben pagarse este proceso qudara bajo comentaios {
/* if(($pret1=='')or($pret1=='0'))
 {
	$pret1=($retencion_ex1*100)/$monto_factura2;
 }
 if(($pret2=='')or($pret2=='0'))
 {
	$pret2=($retencion_ex2*100)/$monto_factura2;
 }*/
// echo($monto_factura2."*".$pret1."-".$pret2);
//aqi culminan los calculos para hallar los vlores de las retenciones	 

/*if($alpha!='1')
{
	$monto_total=$_POST[tesoreria_cheques_db_monto_pagar];
	$monto_total0=str_replace(".","",$monto_total);
	$monto_pro=str_replace(",",".",$monto_total0);
	if($monto_pro > $saldo)
	{
		die($monto_pro." > ".$saldo);
		die("supera al saldo de la orden");
	}
}//fin if($alpha!='1')*/
/*$islr12=($row_doc_det->fields("causado")*$porcentaje_retencion_islr)/100;
$iva12=($iva_causado*$porcentaje_retencion_iva)/100;*/											
//retsando las retenciones iva e islr
/*  $total_ivac1=$iva_causado-$iva12;
 // echo($total_ivac1.":".$iva_causado."-".$iva12);
  $total_islr_base=$causado-$islr12;
 // echo($total_islr_base.":".$causado."-".$islr12);
if($impuesto!='0')
{
  	if($pret1!='')
	{
		 $ret1c=($row_doc_det->fields("causado")*$pret1)/100;
	}
	if($pret2!='')
	{
		 $ret2c=($row_doc_det->fields("causado")*$pret2)/100;
	}
	$ret_totalc=$ret1c+$ret2c;
	
	//echo($ret1c."+".$ret2c);
	//echo($row_doc_det->fields("causado")."*".$pret2);
	//$total_islr_base=$total_islr_base-$ret_totalc;
}
else
$ret_totalc=0;	
$facturasc1=($total_islr_base+$total_ivac1)-$ret_totalc;
*///echo($facturasc1."=".$total_islr_base."+".$total_ivac1."-".$ret_totalc);																								
//$porcentaje_ultra=ES UNA VARIABLE CREADA PARA DETERMINAR EL VALOR % DE  EL TOTAL DE LA FACTURA CONSIDERANDO QUE EL 100% ES EL TOTAL DE LA BASE IMPONIBLE
/*$porcentaje_ultra=($facturasc1*100)/$causado;
echo($porcentaje_ultra)*/;
//PROCESO QUE VERIFICA SI EL MONTO COLOCADO MANUALMENTE EXCEDE O NO A LAS PARTIDAS INICIALES
/*$restas=$monto_pro-$facturasc1;
echo($monto_pro."-".$facturasc1);*/
/*if($restas>='0')
{
	$monto_pro2=$facturasc1;
	$monto_pro=$restas;
	//LA VARIABLE $alpha ES UN SWITCHE 0/1 QUE SE ACTIVA SI EL MONTO DEBE SER PAGADO FRACCIONADAMENTE ENTRE DIFERENTES PARTIDAS....
	$alpha="1";	
}*/


//EL SIGUIENTE PROCESO ES PARA HALLAR ER VALOR DE CUANTO DEBO DE PAGAR PRESUPUESTARIAMENTE SEGUN EL MONTO QUE COLOCO MANUALMENTE LA PERSONA
//$valor_pagado0=(100*$monto_pro2)/$porcentaje_ultra;
//echo($valor_pagado0);
//die("hola");
/////////////////////////////////////////////////////////////////////////	}
													//	$causados=$causados+$causado;
														//echo($causado);
													
											/****************************/
																$partida=$row_orden_compra->fields("partida");
																$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
																$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
																$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
																$pre_orden=$row_orden_compra->fields("id_accion_especifica");
																$tipo=$row_orden_compra->fields("tipo");
																//modificado 02/03/2012:se bloqueo porque cambiaron las rleaciones de la bd
																/*if($row_orden_compra->fields("id_proyecto")!='')
																{
																	$accion_central=$row_orden_compra->fields("id_proyecto");
																	$where="AND id_proyecto = '$accion_central'";
																	$tipo=1; 
																}else
																{
																	$accion_central=$row_orden_compra->fields("id_accion_centralizada");
																	$where="AND id_accion_centralizada ='$accion_central'"; 
																	$tipo=2;
																}*/
																if($tipo=='1')
																{
																	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
																}else
																	if($tipo=='2')
																$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
															//	die($fecha2);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*** modificacion  MARZO 2012:tuve q cambiar la variable fecha2 por el post de la fecha del cheque del formulario  volver a cambiar*/															
																/*$mes_orde=substr($fecha2,5,2);
																$ano_orde=substr(fecha2,0,4);*/
															//	$dia=substr($fecha,0,2);
																$mes_orde=substr($fechass,3,2);
																$ano_orde=substr($fechass,6,4);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
																$resumen_suma = "
																					SELECT  
																						   (monto_pagado[".$mes_orde."]) AS monto
																					FROM 
																						\"presupuesto_ejecutadoR\"
																					WHERE
																						id_unidad_ejecutora='$unidad_ejecutora'
																					AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																					AND
																						partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																					$where
																					";
//die($resumen_suma);
																	$rs_resumen_suma=& $conn->Execute($resumen_suma);
																	
																	if (!$rs_resumen_suma->EOF) 
																		$monto_pagado = $rs_resumen_suma->fields("monto");
																	else
																		$monto_pagado = 0;
																		//$monto_total = $monto_pagado + $monto_restar;
																		
																		//$monto_total = $monto_pagado + $valor_pagado0;
																		$monto_total = $monto_pagado + $causado2;
																	//	echo($monto_total." = ".$monto_pagado." + ".$causado2);

																		$monto_total=round($monto_total,2);
																		
																
																//	echo($monto_total." = ".$monto_pagado." + ".$monto_restar);
																		$actu1=
																			"UPDATE 
																					\"presupuesto_ejecutadoR\"
					
																			SET 
																					monto_pagado[".$mes_orde."]= '$monto_total'
																			WHERE
																					(id_organismo = ".$_SESSION['id_organismo'].") 
																				AND
																					(id_unidad_ejecutora = '$unidad_ejecutora') 
																				AND 
																					(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																				AND 
																					(ano = '".date("Y")."')
																				AND
																					partida = '".$row_orden_compra->fields("partida")."'  
																				AND	
																					generica = '".$row_orden_compra->fields("generica")."'
																				AND	
																					especifica = '".$row_orden_compra->fields("especifica")."'  
																				AND
																					sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																				$where;
																				UPDATE 
																						\"presupuesto_ejecutadoD\"
																				SET
																						estatus='4'
																				WHERE
																						numero_compromiso='$numero_compromiso';										
																			";
												/*		//////////////////////////////////causando el iva/////////////////////////////////////////////////////////////////////////
														$resumen_suma = "
																		SELECT  
																			   (monto_causado[".$mes_orde."]) AS monto
																		FROM 
																			\"presupuesto_ejecutadoR\"
																		WHERE
																					(id_organismo = ".$_SESSION['id_organismo'].") 
																				AND
																					(id_unidad_ejecutora = '$unidad_ejecutora') 
																				AND 
																					(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																				AND 														
																					partida = '403'  AND	generica = '18'  AND	especifica = '01'  AND	sub_especifica = '00'
																		$where
																		";
														//die($resumen_suma);		
														$rs_resumen_suma=& $conn->Execute($resumen_suma);
													
														if (!$rs_resumen_suma->EOF) 
															$monto_causado_iva = $rs_resumen_suma->fields("monto");
														
														else
															$monto_causado_iva = 0;
															//$monto_total = $monto_causado + $monto_restar;	
															$monto_total=$monto_causado_iva+$p_iva_total;
															$monto_total=round($monto_total,2);
															//die($monto_total);
															$sql_iva="UPDATE 
																	\"presupuesto_ejecutadoR\"
															SET 
																	monto_causado[".$mes_orde."]= '$p_iva_total'
															WHERE
																	(id_organismo = ".$_SESSION['id_organismo'].") 
																AND
																	(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
																AND 
																	(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																AND 
																	(ano = '".date("Y")."')
																AND
																	partida = '403'  
																AND	
																	generica = '18'
																AND	
																	especifica = '01'  
																AND
																	sub_especifica = '00'
																$where;";
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////														
											  */
														  $actu=$actu.";".$actu1;
														// die($actu1);
														 if (!$conn->Execute($actu1))
													die ('Error al Actulizar: '.$conn->ErrorMsg());

														$ext=$ext+1;
													
													//echo($ext."$");
													}//fin de if de doc detalle
													$row_orden_compra->MoveNext();
													
													}//fin del mientras
													//echo("-".$total_facturas_comprometidas."-");
									}//fin del else	
												$i_fact++;
													}						
											$i++;							
											}		
														//	die($actu);		
													 $fact_ord1=str_replace(".","",$_POST[tesoreria_cheques_db_monto_pagar]);
													 $fact_ord=str_replace(",",".",$fact_ord1);
				
													/*echo($total_facturas_comprometidas."+".$fact_ord);*/
												//	die($actu);
													
												/*if($ext!=0)
												{	if($total_facturas_comprometidas<$fact_ord)
														  {
															//die($fact_ord."<".$total_documento);
															//die("El monto a pagar no puede superar al monto comprometido");
														  }*/
														  												//die($actu);

											//	$actu=$actu.";".$sql_iva;	
									//	}
								
																
									
			///////////////////////////////////////////////////////////////////////////////////////////////////		
					
			//////////////////////////////////////////////////
					$sql_def=" UPDATE chequeras
									SET
											ultimo_emitido='$proximo_emitir',
											estatus=$estatus,
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
									WHERE
										id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
									AND	
										cuenta='$_POST[tesoreria_cheques_db_n_cuenta]'
									AND
										secuencia='$secuencia2'";
				//	die($sql_def);		
					if (!$conn->Execute($sql_def))
						{	echo ('Error_impresion' );}
					//die ('Error al modificar datos chequera: '.$sql_chequeras);
					
				}
				else
					die('chequera_agotada');
				if($n_cheque!="")
				{
					if($_POST[tesoreria_cheque_db_otro_beneficiario_oc]==1)
					{
						$tipos=2;
						}else
					if($_POST[tesoreria_cheque_db_otro_beneficiario_oc]==0)
					{
						$tipos=1;
						}
					//--- modificando el n_cheque en la tabla cheques: aqui cambie la fecha del ae por el valor de la variable
					$sql_cheques=" UPDATE cheques
							SET
									numero_cheque='$n_cheque',
									estatus='2',
									secuencia='$secuencia',
									fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
									ultimo_usuario=".$_SESSION['id_usuario'].",
									fecha_firma='$firmas',
									fecha_cheque='$fechass',
									estado[1]='2',
									estado_fecha[1]='".date("Y-m-d H:i:s")."'
							WHERE
								id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND	
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
							AND
								numero_cheque='$_POST[tesoreria_cheques_db_n_precheque]'
							AND
								cheques.tipo_cheque='$tipos'			 
							AND		
							cheques.id_organismo=$_SESSION[id_organismo]	
							";
					//die($sql_cheques);			
					if (!$conn->Execute($sql_cheques)) 
							die ('Error_impresion' );
					//die ('Error al modificar datos de cheques: '.$sql_cheques);
					
					$sql_orden=" UPDATE orden_pago
							SET
									cheque='$n_cheque',
									secuencia='$secuencia'
							WHERE
								id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND	
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
							AND
								cheque='$_POST[tesoreria_cheques_db_n_precheque]'
							";	
					//die($sql_orden);					
					if (!$conn->Execute($sql_orden)) 
						die ('Error_impresion' );
					//die ('Error al modificar orden de pago: '.$sql_orden);
				
				
					$sql_ultimo_emitido2 = $sql_cheque = "SELECT 
								numero_cheque
						   FROM 
								cheques 
						   WHERE 
								cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
						   AND 
								 id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
							AND 
								id_proveedor='$_POST[tesoreria_cheques_pr_proveedor_id]'
							AND
								numero_cheque='$n_cheque'
							AND
								cheques.tipo_cheque='1'			 
							AND		
								cheques.id_organismo=$_SESSION[id_organismo]		 
								 ";
					if (!$conn->Execute($sql_ultimo_emitido2))
						die ('Error_impresion' );
					//die ('Error consulta numero de cheque: '.$conn->ErrorMsg());
					$row_emitido2= $conn->Execute($sql_ultimo_emitido2);
			
				}
				if($tipo==1)
				{
					$sql_proyecto="SELECT id_proyecto,codigo_proyecto,nombre FROM proyecto WHERE id_proyecto='$accion_central'";
					$row_proyecto=& $conn->Execute($sql_proyecto);
					$proyecto=$row_proyecto->fields("codigo_proyecto");
				}else
				if($tipo==2)
				{
					$sql_proyecto="SELECT codigo_accion_central,denominacion FROM accion_centralizada WHERE id_accion_central='$accion_central'";
					$row_proyecto=& $conn->Execute($sql_proyecto);
					$proyecto=$row_proyecto->fields("codigo_accion_central");
				}
				
				$sql_ejec="SELECT id_unidad_ejecutora, nombre FROM unidad_ejecutora WHERE id_unidad_ejecutora='$unidad_ejecutora'";
				//die($sql_ejec);
				$row_ejec=& $conn->Execute($sql_ejec);
				$unidad_ejec=$row_ejec->fields("nombre");
				$responce=$n_cheque."*".$secuencia."*".$unidad_ejecutora."*".$proyecto."*".$partida;
				die($responce);
			
				//die($row_emitido2->fields("numero_cheque"));
				 //}
/*}else
die("cerrado");	*/			 
?>