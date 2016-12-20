<?php 
//definiendo que los archivos a crear son txt
$archivo="factura";
header('Content-type: application/xls');
// Creando en archivo con la extencion txt
header('Content-Disposition: attachment; filename="'.$archivo.''.'.xls"');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$hoy = date("Y-m-d H:i:s");
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$suma=0;
///////////////////////////////paso 1 armar el mega query
$mega_qery="
SELECT 
		distinct
		documentos_cxp.numero_compromiso::integer
		
		
  FROM documentos_cxp
  where
	documentos_cxp.estatus!='3'
  and 
	orden_pago!='0'	
  and
  	tipo_documentocxp!='4'				
  order by
  numero_compromiso::integer
  ;";
//  die($mega_qery);	//	substring(documentos_cxp.fecha_documento::varchar,6,2)as mes,

$recordset=& $conn->Execute($mega_qery);
while(!$recordset->EOF)
{
////////////////////////////////////////////////amortizacion
	
////////////////////////////////////////////////////////
$compromiso=$recordset->fields("numero_compromiso");
//sacando la fecha de la factura
$fecha_fact="SELECT 
		fecha_ultima_modificacion,
		substring(documentos_cxp.fecha_ultima_modificacion::varchar,6,2)as mes
  FROM documentos_cxp
  where
	documentos_cxp.estatus!='3'
  and 
	orden_pago!='0'	
  and
  	tipo_documentocxp!='4'
  and
  	numero_compromiso='$compromiso'
  order by
  numero_compromiso::integer
  ;";
 // die($fecha_fact);
$row_fecha_fact=& $conn->Execute($fecha_fact);
if(!$row_fecha_fact->EOF)
{	
	$fecha_causado=$row_fecha_fact->fields("fecha_ultima_modificacion");
	$fecha_pagado=$row_fecha_fact->fields("fecha_ultima_modificacion");
	$mes=$row_fecha_fact->fields("mes");
}
//////////////////////////////////////


//$mes=$recordset->fields("mes2");
	//////////////////////////////////////////////////////////////////////////
			$sql_orden="SELECT 
						\"orden_compra_servicioE\".tipo,
						\"orden_compra_servicioE\".id_orden_compra_servicioe as id,
						\"orden_compra_servicioE\".id_unidad_ejecutora,
						\"orden_compra_servicioE\".id_proyecto_accion_centralizada,
						\"orden_compra_servicioE\".id_accion_especifica,
								partida, 
							    generica, 
						 	    especifica, 
						 	    subespecifica,					
					substring(\"orden_compra_servicioE\".fecha_orden_compra_servicio::varchar,6,2)as mes2
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
						\"orden_compra_servicioE\".numero_compromiso='$compromiso'
					and
						estatus!='3'
					group by
				tipo,
						id_orden_compra_servicioe,
						id_unidad_ejecutora,
						id_proyecto_accion_centralizada,
						id_accion_especifica,
								partida, 
							    generica, 
						 	    especifica, 
						 	    subespecifica,					
					substring(\"orden_compra_servicioE\".fecha_orden_compra_servicio::varchar,6,2)
				order by
						tipo,
						id_orden_compra_servicioe,
						id_unidad_ejecutora,
						id_proyecto_accion_centralizada,
						id_accion_especifica,
								partida, 
							    generica, 
						 	    especifica, 
						 	    subespecifica,					
					substring(\"orden_compra_servicioE\".fecha_orden_compra_servicio::varchar,6,2)
				
					";
				$row_orden_compra=& $conn->Execute($sql_orden);
				//die($sql_orden);
				
				while(!$row_orden_compra->EOF)
				{	
					$sql_cmp="SELECT 
						\"orden_compra_servicioE\".numero_compromiso, 
						\"orden_compra_servicioE\".numero_pre_orden,
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
						\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
					where
						\"orden_compra_servicioE\".numero_compromiso='$compromiso'
					AND 
										(\"orden_compra_servicioD\".ano = '".date("Y")."')
									AND
										\"orden_compra_servicioD\".partida = '".$row_orden_compra->fields("partida")."'  
									AND	
										\"orden_compra_servicioD\".generica = '".$row_orden_compra->fields("generica")."'
									AND	
										\"orden_compra_servicioD\".especifica = '".$row_orden_compra->fields("especifica")."'  
									AND
										\"orden_compra_servicioD\".subespecifica = '".$row_orden_compra->fields("subespecifica")."'
									AND
										id_unidad_ejecutora='".$row_orden_compra->fields("id_unidad_ejecutora")."'
									and
										id_accion_especifica='".$row_orden_compra->fields("id_accion_especifica")."'
						";/*if(	$row_orden_compra->fields("partida")=='403' and$row_orden_compra->fields("generica")=='04'and $row_orden_compra->fields("especifica")=='01' and $row_orden_compra->fields("subespecifica")=='00') 
								{
									echo($sql_cmp);
								}*/
//					die($sql_cmp);
					$row_orden_compra_datos_monto=& $conn->Execute($sql_cmp);
					//$total_renglon=0;
					while(!$row_orden_compra_datos_monto->EOF)
					{
					$total=$row_orden_compra_datos_monto->fields("monto")*$row_orden_compra_datos_monto->fields("cantidad");
					$iva=$total*($row_orden_compra_datos_monto->fields("impuesto")/100);
					//$iva=0;
					$ivas=$ivas+$iva;
					$total_total=$total+$iva;
					$total_renglon=$total_renglon+$total_total;
					$row_orden_compra_datos_monto->MoveNext();
					}
///////////////////////////// para determinar los montos por renglon							
				/*	if(	$row_orden_compra->fields("partida")=='401' and$row_orden_compra->fields("generica")=='06'and $row_orden_compra->fields("especifica")=='12' and $row_orden_compra->fields("subespecifica")=='00' and $row_orden_compra->fields("id_unidad_ejecutora")=='3' and	$row_orden_compra->fields("id_accion_especifica")=='247')
								{ //402020500 403120100 403090100
						//		402080300 402090100 402069900 173496 401070400 401080100
									echo('</br>');
									echo("compromios=".$compromiso."u ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora").""."id_accion especifica=".$row_orden_compra->fields("id_accion_especifica"));
									echo('</br>');
									echo($total_renglon."+");
									echo("fecha".$mes);///	41 	240 	403020100
							//	echo($sql_cmp);
							
					}*/
				
					$mes2=$row_orden_compra->fields("mes2");
				

						$resumen_suma = "
										SELECT  
											   (monto_causado[".$mes."]) AS monto,
											   (monto_pagado[".$mes."]) AS monto_pagado
										FROM 
											\"presupuesto_ejecutadoR\"
										WHERE
											id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
										AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
										AND
											partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
											
								
								";
									$rs_resumen_suma=& $conn->Execute($resumen_suma);
									if (!$rs_resumen_suma->EOF) 
									{	
										$monto_causado = $rs_resumen_suma->fields("monto");
										$monto_pagado = $rs_resumen_suma->fields("monto_pagado");
									}
									
									//-----------------------------------------------------------------------------------------------------------------------
								$monto_total=round($monto_causado,2)+round($total_renglon,2);
								$monto_total2=round($monto_pagado,2)+round($total_renglon,2);
					if($row_orden_compra->fields("tipo")=='1')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									}else
									$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
					
									//echo($monto_total."=".$monto_causado."+".$total_renglon);
											$actu1=
											"UPDATE 
													\"presupuesto_ejecutadoR\"
											SET 
													monto_causado[".$mes."]= '$monto_total',
													monto_pagado[".$mes."]= '$monto_total2'
											WHERE
													(id_organismo = '1') 
												AND
													(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
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
												;	
												UPDATE \"presupuesto_ejecutadoD\"
												SET
												fecha_causado='$fecha_causado',
												fecha_pagado='$fecha_pagado'
												WHERE
												numero_compromiso='$compromiso'
												";
									/*if($compromiso=='110476')
									{					
										die($actu1);				
									}	*/					
							 			/*if(	$row_orden_compra->fields("partida")=='402' and$row_orden_compra->fields("generica")=='02'and $row_orden_compra->fields("especifica")=='05' and $row_orden_compra->fields("subespecifica")=='00' and $row_orden_compra->fields("id_unidad_ejecutora")=='3' and	$row_orden_compra->fields("id_accion_especifica")=='237')
								{ //402020500
									echo('</br>');
									echo($actu1);
									echo('</br>');
								}*/
					
							/*if (!$conn->Execute($actu1))
							{
								echo ('Error al CAUSAR: '.$conn->ErrorMsg());
								$errores="fallo";
							}
						*/
								$errores="ninguno";					
								$monto_total=0;
								$monto_causado=0;
								$total_renglon=0;
							
					//echo($compromiso."-");
				if($compromiso=='110483')
				{
				 	die($row_orden_compra->fields("id_orden_compra_servicioe"));
				
				}
					$row_orden_compra->MoveNext();				
				}		
	//////////////////////////////////////////////////////////////////////////
	
	$recordset->MoveNext();
}
?>
