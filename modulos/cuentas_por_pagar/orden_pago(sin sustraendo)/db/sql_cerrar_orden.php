<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$orden_pago_num=$_POST[cuentas_por_pagar_db_orden_numero_control];
$fecha = date("Y-m-d H:i:s");
$monto = str_replace(".","",$_POST[cuentas_por_pagar_db_facturas_total]);
$numero_compromiso=$_POST['cuentas_por_pagar_db_numero_compromiso'];
$vector=$_POST[cuentas_por_pagar_db_facturas_oculto];
			$facturas=split(",",$vector);
			sort($facturas);
			if($facturas!="")
			{
				$contador=count($facturas);
			
				$is=0;
				while($is<$contador)
				{
						$sql_facturas="
											SELECT 
													numero_compromiso,monto_bruto,estatus
											FROM
													documentos_cxp
											where
												    id_documentos='$facturas[$is]'";
													
						$row_documentos=& $conn->Execute($sql_facturas);
						$numero_compromiso=$row_documentos->fields("numero_compromiso");
						$estatus_docx=$row_documentos->fields("estatus");
						$monto_restar=$row_documentos->fields("monto_bruto");
						if($numero_compromiso=="0")
						{
							//die("compromiso_no_cierre");
						}
						if($estatus_docx=="1")
						{
							die("doc_no");
						}
///////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
								/*	$sql="SELECT 
											\"orden_compra_servicioE\".id_proveedor, 
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
										FROM 
											\"orden_compra_servicioE\"
										INNER JOIN
											organismo
										ON
											\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
										INNER JOIN
											\"orden_compra_servicioD\"
										ON
											\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
									$row_orden_compra=& $conn->Execute($sql);
									
									$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
									$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
									$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
									$pre_orden=$row_orden_compra->fields("id_accion_especifica");
									$tipo=$row_orden_compra->fields("tipo");
									if($tipo=='1')
									{
									$where="AND id_proyecto = '$accion_central'"; 
									}else
									$where="AND id_accion_centralizada ='$accion_central'"; 
									$resumen_suma = "
														SELECT  
															   (monto_causado[".date("n")."]) AS monto
														FROM 
															\"presupuesto_ejecutadoR\"
														WHERE
															id_unidad_ejecutora='$unidad_ejecutora'
														AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
														AND
															partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
														$where
														";
										$rs_resumen_suma=& $conn->Execute($resumen_suma);
										
										if (!$rs_resumen_suma->EOF) 
											$monto_causado = $rs_resumen_suma->fields("monto");
										else
											$monto_causado = 0;
											$monto_total = $monto_causado + $monto_restar;			
											$actu=
											"UPDATE 
													\"presupuesto_ejecutadoR\"
											SET 
													monto_causado[".date("n")."]= '$monto_total'
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
														estatus='3'
												WHERE
														numero_compromiso='$numero_compromiso'
												";				
												
								*/		
//////////////////////////////////////////////////////////////////////////////////////////////////				
	
				$is=$is+1;
				}
			}			
								$sql_pago="UPDATE orden_pago
											SET
												estatus='$_POST[cuentas_por_pagar_db_orden_abrir_cerrar]',
												ultimo_usuario=".$_SESSION['id_usuario']."	,
												fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
												documentos='{".$_POST[cuentas_por_pagar_db_facturas_oculto]."}'	
											WHERE 
												orden_pago='$_POST[cuentas_por_pagar_db_orden_numero_control]'
											AND
												orden_pago.id_organismo=".$_SESSION["id_organismo"].";
											INSERT INTO \"presupuesto_ejecutadoD\"(
											id_tipo_documento, 
											id_organismo, 
											ano, 
											numero_documento, 
											numero_compromiso, 
											fecha_compromiso, 
											ultimo_usuario, 
											fecha_modificacion
										)VALUES (
											'5', 
											".$_SESSION['id_organismo'].",
											'".date("Y")."', 
											'".$orden_pago_num."', 
											'".$numero_compromiso."', 
											'".date("Y-m-d H:i:s")."', 
											".$_SESSION['id_usuario'].",
											'".date("Y-m-d H:i:s")."'
										);
			
				";
								
												
											if (!$conn->Execute($sql_pago)) 
												die ('Error al registrar: '.$sql_pago);
					
////////////////////////////////////////////////////////-ACTUALIZANDO LOS DOCUMENTOS SELECCIONADOS-///////////////////////////////////////////////////////////
											$orden=$_POST[cuentas_por_pagar_db_orden_numero_control];
											$vector=$_POST[cuentas_por_pagar_db_facturas_oculto];
											$facturas=split(",",$vector);
											sort($facturas);
											if($facturas!="")
											{
												$contador=count($facturas);
											
												$is=0;
												//////////////////////////blanqeando campos a modificar
															/////////////////////////modificando
												while($is<$contador)
												{
															
															$sql = "UPDATE documentos_cxp 
															 SET
																estatus='$_POST[cuentas_por_pagar_db_orden_abrir_cerrar]',
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
											}	
							//--- CERRANDO ELSE//
							die("Actualizado");
	?>