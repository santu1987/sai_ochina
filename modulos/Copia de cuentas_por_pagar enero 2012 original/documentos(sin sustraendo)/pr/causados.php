<?php
session_start();
ini_set("memory_limit","20M");

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
////
					$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_fact =& $conn->Execute($sql_fact);
					$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
////
////
					$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
					$rs_tipos_ant =& $conn->Execute($sql_ant);
					$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");



$Sql2="
SELECT 
				 documentos_cxp.id_documentos,	
			     documentos_cxp.id_proveedor,
				 documentos_cxp.ano,
				 documentos_cxp.tipo_documentocxp,
				 documentos_cxp.numero_documento,
				 documentos_cxp.numero_control,
				 documentos_cxp.fecha_vencimiento,
				 documentos_cxp.porcentaje_iva,
				 documentos_cxp.porcentaje_retencion_iva,
				 documentos_cxp.porcentaje_retencion_islr,
				 documentos_cxp.monto_bruto,
				 documentos_cxp.monto_base_imponible,
				 documentos_cxp.numero_compromiso,
				 documentos_cxp.comentarios,
				 tipo_documento_cxp.nombre as doc,
				 documentos_cxp.retencion_ex1,
				 documentos_cxp.retencion_ex2,
				 documentos_cxp.desc_ex1,
				 documentos_cxp.desc_ex2,
				 documentos_cxp.pret1,
				 documentos_cxp.pret2,
				 documentos_cxp.amortizacion,
				 aplica_bi_ret_ex1,
				 aplica_bi_ret_ex2,
				 documentos_cxp.monto_base_imponible2,
				 documentos_cxp.porcentaje_iva2 ,
				 documentos_cxp.retencion_iva2,
				 documentos_cxp.fecha_documento
			FROM 
				 documentos_cxp
			INNER JOIN
				tipo_documento_cxp
			ON
				documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento	 		 
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			
			where
			documentos_cxp.estatus!=3
			ORDER BY
				 documentos_cxp.id_documentos
												";
								//echo($Sql2);				
								$row=& $conn->Execute($Sql2);
						//		die($Sql2);
while (!$row->EOF) 
{
	$fecha_doc=$row->fields("fecha_documento");
	$dia=substr($fecha_doc,8,2);
	$mes=substr($fecha_doc,5,2);
	$ano=substr($fecha_doc,0,4);
	$ids=$row->fields("id_documentos");									
	$tipo_doc=$row->fields("tipo_documentocxp");
	
	$sql_doc="SELECT * from tipo_documento_cxp where id_tipo_documento='$tipo_doc'";
	//die($sql_doc);
	$row2=& $conn->Execute($sql_doc);
	$tipo_nom=$row2->fields("nombre");
	if($row->fields("numero_compromiso")!="")
	{
	$numero_compromiso=$row->fields("numero_compromiso");
	
	$sql="SELECT 
					tipo,
					id_orden_compra_servicioe as id,
					id_unidad_ejecutora,
					id_proyecto_accion_centralizada,
					id_accion_especifica,
							partida, 
							generica, 
							especifica, 
							subespecifica,
							fecha_orden_compra_servicio
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
					\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
			$row_orden_compra=& $conn->Execute($sql);
		//die($sql);
	$conta_tor=0;
		while(!$row_orden_compra->EOF)
		{
			
			$fecha_ocs=$row_orden_compra->fields("fecha_orden_compra_servicio");
			$dia_orden=substr($fecha_ocs,8,2);
			$mes_orden=substr($fecha_ocs,5,2);
			$ano_orden=substr($fecha_ocs,0,4);
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
									";
				//die($sql_cmp);
							$row_orden_compra_datos_monto=& $conn->Execute($sql_cmp);
							$total_renglon=0;
							if(!$row_orden_compra_datos_monto->EOF)
							{
								$impuesto=$row_orden_compra_datos_monto->fields("impuesto");
								
							}
		if(($tipo_doc!="4")&&('$numero_compromiso'!=""))
		{
			
			
		//die($tipo_cierre);
			$partida=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
			$partidasss=$row_orden_compra->fields("partida").$row_orden_compra->fields("generica").$row_orden_compra->fields("especifica").$row_orden_compra->fields("subespecifica");
			
			$sql_doc_det="select monto from doc_cxp_detalle where id_doc='$ids'
					and partida='$partida'
					";
					$row_doc_det=& $conn->Execute($sql_doc_det);
					//die($sql_doc_det);
					if(!$row_doc_det->EOF)
					{
						
						$causado=$row_doc_det->fields("monto");
                	    $causado_iva=(($causado*$impuesto)/100);
						$causado=$causado+$causado_iva;
						//die($causado);
					}
				$tipo=$row_orden_compra->fields("tipo");
				$partida_presu=$row_orden_compra->fields("partida");
			
			$partida=$row_orden_compra->fields("partida");
			$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
			if($tipo==1)
					{
						$proyecto=$row_orden_compra->fields("id_proyecto_accion_centralizada");
						$accion_central=0;
					}else
					if($tipo==2)
					{
						$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
						$proyecto=0;
					}
			
///////////////////////////////////////----- realizando el causado en las tablas de presupuesto
									$pre_orden=$row_orden_compra->fields("id_accion_especifica");
									$tipo=$row_orden_compra->fields("tipo");
									if($tipo=='1')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									}else
									$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									
									$resumen_suma = "
														SELECT  
															   (monto_causado[".$mes_orden."]) AS monto
														FROM 
															\"presupuesto_ejecutadoR\"
														WHERE
															id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
														AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
														AND
															partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
														$where
														";
										//die($resumen_suma);		
										$rs_resumen_suma=& $conn->Execute($resumen_suma);
									
										if (!$rs_resumen_suma->EOF) 
											$monto_causado = $rs_resumen_suma->fields("monto");
										
										else
											$monto_causado = 0;
											//$monto_total = $monto_causado + $monto_restar;	
/*$monto_iva=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_ret_iva']);
$monto_iva_causar2=str_replace(".","",$_POST['cuentas_por_pagar_db_monto_iva']);
$monto_iva_causar=str_replace(",",".",$monto_iva_causar2);*/

										
											$monto_total=round($monto_causado,2)+round($causado,2);
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
										///		die($actu1);
											
												echo($numero_compromiso."-----------".$partidasss);
////////	?>
				</br>
				<?								if (!$conn->Execute($actu1)) 
														{
															$error=1;
														}
													if($error==1)
														die ('Error al Registrar: '.$actu1);
		
					//del otro if
		
					}
	
	
			$row_orden_compra->MoveNext();
			}//del row orden compra
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
$row->MoveNext();
}			
?>