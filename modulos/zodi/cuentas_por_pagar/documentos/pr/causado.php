<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sql1="SELECT 
				doc_cxp_detalle.id,
				doc_cxp_detalle.id_doc,
				doc_cxp_detalle.partida,
				doc_cxp_detalle.monto,
				doc_cxp_detalle.id_organismo,
				documentos_cxp.fecha_documento,
				documentos_cxp.numero_compromiso,
				documentos_cxp.numero_compromiso
				
  FROM 
  				doc_cxp_detalle
  inner join
  				documentos_cxp
  on
  				documentos_cxp.id_documentos=doc_cxp_detalle.id_doc
	
";
$row1=& $conn->Execute($sql1);
					//die($sql_doc_det);
					while(!$row1->EOF)
					{
						$fecha=$row1->fields("fecha_documento");
						$mes=substr($fecha,5,2);
						$ano=substr($fecha,0,4);
						$partida=substr($row1->fields("partida"),0,3);
						$generica=substr($row1->fields("partida"),3,2);
						$especifica=substr($row1->fields("partida"),5,2);
						$sub_especifica=substr($row1->fields("partida"),7,2);
						$numero_compromiso=$row1->fields("numero_compromiso");
						$causado=$row1->fields("numero_compromiso");;
						$sql="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						id_unidad_ejecutora,
						id_proyecto_accion_centralizada,
						id_accion_especifica,
						\"orden_compra_servicioD\".cantidad,
									\"orden_compra_servicioD\".monto,
									\"orden_compra_servicioD\".impuesto
									

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
						\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
					AND
						partida = '$partida'
					AND	generica = '$generica'
					AND	especifica = '$especifica'
					AND	subespecifica = '$sub_especifica'	
						";
						//die($sql);
							$row_orden_compra=& $conn->Execute($sql);
								while(!$row_orden_compra->EOF)
								{
																		$pre_orden=$row_orden_compra->fields("id_accion_especifica");
									$tipo=$row_orden_compra->fields("tipo");
									if($tipo=='1')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									}else
									$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada")	.""; 
								$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
								$iva=$total*($row_orden_compra->fields("impuesto")/100);
								$total_total=$total+$iva;
									$resumen_suma = "
														SELECT  
															   (monto_causado[".$mes."]) AS monto
														FROM 
															\"presupuesto_ejecutadoR\"
														WHERE
															id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
														AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
														AND
															partida = '$partida'
														AND	generica = '$generica'
														AND	especifica = '$especifica'
														AND	sub_especifica = '$sub_especifica'
														$where
														";
										//die($resumen_suma);		
										$rs_resumen_suma=& $conn->Execute($resumen_suma);
									
										if (!$rs_resumen_suma->EOF) 
											$monto_causado = $rs_resumen_suma->fields("monto");
										
										else
											$monto_causado = 0;
											$monto_total=$monto_causado+$total_total;
											//die($causado);
											$actu1=
											"UPDATE 
													\"presupuesto_ejecutadoR\"
											SET 
													monto_causado[".$mes."]= '$monto_total'
											WHERE
													(id_organismo = ".$_SESSION['id_organismo'].") 
												AND
													(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
												AND 
													(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
												AND 
													(ano = '".date("Y")."')
											AND
															partida = '$partida'
														AND	generica = '$generica'
														AND	especifica = '$especifica'
														AND	sub_especifica = '$sub_especifica';
												
												";	
					//				$actu2=$actu2.";".$actu1;
									if (!$conn->Execute($actu1))
									die ('Error al CAUSAR: '.$conn->ErrorMsg());
									$actu1="";

								$row_orden_compra->MoveNext();
								}//cerrando orden compra

					$row1->MoveNext();	
					}
	//die($actu2);
	die("leesssto");
?>