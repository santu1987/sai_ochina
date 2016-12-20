<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$where="WHERE 1=1 ";
if($_GET['compromiso']!='')
{
	$compromiso=$_GET['compromiso'];
	$where.=" and \"orden_compra_servicioE\".numero_compromiso='$compromiso'";
}
else
	$where.=" and \"orden_compra_servicioE\".numero_compromiso=''";
	//$fecha=$_GET['fecha'];
	/*$ano=substr($fecha,6,4);
	$mes=substr($fecha,3,2);*/
/*if($_GET['fecha']=='')
{
	
	$mes=date("n");
	$ano=date("Y");

}else
die("erro");
*/
	$mes=date("n");
	$ano=date("Y");
//*******************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$Sql="SELECT count(id_orden_compra_servicioe)
				  
 			 FROM 
					\"orden_compra_servicioE\"
			".$where."	
				";
				//die($Sql);
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} 
else {
	$total_pages = 0;
}
// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;
// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;
// the actual query for the grid data
/////////////////////////////////////////////////////////
$sql_fechas="SELECT  
       fecha_compromiso, fecha_causado, fecha_pagado, usuario_estatus, 
       fecha_estatus, usuario_anula, fecha_anula, comentario, ultimo_usuario, 
       fecha_modificacion, usuario_windows, serial_maquina
  FROM \"presupuesto_ejecutadoD\"
  where		
		numero_compromiso='$compromiso'
  ";
$row_fechas=& $conn->Execute($sql_fechas);
if (!$row->EOF) 
{
	//die($sql_fechas);
	$fecha=$row_fechas->fields("fecha_compromiso");
	$ano_orden=substr($fecha,0,4);
	$mes_orden=substr($fecha,5,2);
}
/////////////////////////////////////////////////////////////////////////
$Sql="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						id_unidad_ejecutora,
						id_proyecto_accion_centralizada,
						id_accion_especifica,
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
					$where
				group by
tipo, id_orden_compra_servicioe , id_unidad_ejecutora, id_proyecto_accion_centralizada, id_accion_especifica, partida, generica, especifica, subespecifica

					order by
partida, generica, especifica, subespecifica
	
					";
				//	die($Sql);
/*$Sql="
			SELECT	    \"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,
				  	\"presupuesto_ejecutadoR\".partida,
				 	\"presupuesto_ejecutadoR\".generica,
				  	\"presupuesto_ejecutadoR\".especifica,
				  	\"presupuesto_ejecutadoR\".sub_especifica,
				  	\"presupuesto_ejecutadoR\".monto_comprometido, 
				   	\"presupuesto_ejecutadoR\".ultimo_usuario,
				     \"presupuesto_ejecutadoR\".fecha_actualizacion
 			 FROM 
					\"presupuesto_ejecutadoR\"
			".$where."		
			ORDER BY
				 \"presupuesto_ejecutadoR\".partida
			LIMIT 
				$limit 
			OFFSET 
				$start  	
			
";
*///
//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$partida_ant=1;
$partida_siguente=0;
while (!$row->EOF) 
{
/////////////////////////// sacando cuanto es que deberia pagar
$sql="SELECT 
											\"orden_compra_servicioE\".numero_compromiso, 
											\"orden_compra_servicioE\".numero_pre_orden,
											\"orden_compra_servicioE\".id_orden_compra_servicioe as id,
											\"orden_compra_servicioE\".id_unidad_ejecutora,
											\"orden_compra_servicioE\".id_proyecto_accion_centralizada,
											\"orden_compra_servicioE\".id_accion_especifica,
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
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										and
											\"orden_compra_servicioD\".partida='".$row->fields("partida")."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$row->fields("generica")."'
						 	    		and	
											\"orden_compra_servicioD\".especifica='".$row->fields("especifica")."'  
						 	    		and
											\"orden_compra_servicioD\".subespecifica='".$row->fields("subespecifica")."'
												
											";
								//die($sql);
									$row_orden_compra=& $conn->Execute($sql);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$total_bi=$total_bi+$total;
										$impuesto=$row_orden_compra->fields("impuesto");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);//+$iva;
										$total_total=$total+$iva;
										
										$total_renglon=$total_renglon+$total_total;
										$row_orden_compra->MoveNext();
									}
									if($total_renglon==$total_bi)
									{
										$ivas=str_replace(".","",$_GET[iva]);
										$ivas2=str_replace(",",".",$ivas);

										if (($ivas2!="")&&($ivas2!=0))
										{
											$valor_del_iva=($ivas2*$total_bi)/100;
											$total_bi=$total_bi-$valor_del_iva;
										}
									}	
///////////////////////////////////////////////////////////////////////////////////	
	
	
	$sql2="SELECT    \"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,
				  	\"presupuesto_ejecutadoR\".partida,
				 	\"presupuesto_ejecutadoR\".generica,
				  	\"presupuesto_ejecutadoR\".especifica,
				  	\"presupuesto_ejecutadoR\".sub_especifica,
				  	\"presupuesto_ejecutadoR\".monto_comprometido[".$mes_orden."], 
					\"presupuesto_ejecutadoR\".monto_causado[".$mes."], 
				   	\"presupuesto_ejecutadoR\".ultimo_usuario,
				     \"presupuesto_ejecutadoR\".fecha_actualizacion
 			 FROM 
					\"presupuesto_ejecutadoR\"
			where
					(\"presupuesto_ejecutadoR\".ano = '".date("Y")."')
				AND
					\"presupuesto_ejecutadoR\".partida = '".$row->fields("partida")."'  
				AND	
					\"presupuesto_ejecutadoR\".generica = '".$row->fields("generica")."'
				AND	
					\"presupuesto_ejecutadoR\".especifica = '".$row->fields("especifica")."'  
				AND
					\"presupuesto_ejecutadoR\".sub_especifica = '".$row->fields("subespecifica")."'
				AND
					\"presupuesto_ejecutadoR\".id_unidad_ejecutora = '".$row->fields("id_unidad_ejecutora")."'	
				AND
					monto_comprometido[".$mes_orden."]!='0'
				AND   
				    id_accion_especifica='".$row->fields("id_accion_especifica")."'	
								
			ORDER BY
			\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador";
		//die($sql2);
	$row2=& $conn->Execute($sql2);
	if($partida_ant!=$partida_siguente)
	{//echo($partida_ant."=".$partida_siguente."-");
	$a_causar="0,00";
			while(!$row2->EOF)		
			{ 
			$idss=$_GET[cuentas_por_pagar_db_id];
			$partida=$row2->fields("partida").$row2->fields("generica").$row2->fields("especifica").$row2->fields("sub_especifica");
			/*if($idss!="")
				{*/
					//////////////////////////////////////////
					if($compromiso!="")
					{
							$sql_doc_det3="select  monto 
											from 
												doc_cxp_detalle
											inner join
												documentos_cxp
											on
												doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
											where
													
													  partida='$partida'
											and
													  compromiso='$compromiso'			  
							";
							$row_doc_det3=& $conn->Execute($sql_doc_det3);
							//die($sql_doc_det3);
							while(!$row_doc_det3->EOF)
							{
								
								$montos_a_causar=$row_doc_det3->fields("monto");
								$a_causar=$a_causar+$montos_a_causar;
								$row_doc_det3->MoveNext();
							}
					}else
					{
						$a_causar="0,00";
					}		
					////////////////////////////////
					$sql_doc_det="select sum(monto) as monto 
									from 
										doc_cxp_detalle
									inner join
										documentos_cxp
									on
										doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
									where
											
											  partida='$partida'
					";
					$row_doc_det=& $conn->Execute($sql_doc_det);
					//die($sql_doc_det);
					if(!$row_doc_det->EOF)
					{
						$causado=number_format($row_doc_det->fields("monto"),2,',','.');
					}else{$causado=number_format($row2->fields("monto_causado"),2,',','.');}
				/*}else{$causado=number_format($row2->fields("monto_causado"),2,',','.');}
				//echo($causado);*/
				/*if($row2->fields("monto_comprometido")!=0)
				{*/
				/*if($total_renglon==$total_bi)
				{
					$ivasss=$total_bi*(12/100);
					$total_bi=$total_bi-$ivasss;
				}
				*/	$responce->rows[$i]['id']=$row2->fields("id_presupuesto_ejecutador");
					$responce->rows[$i]['cell']=array(	$row2->fields("id_presupuesto_ejecutador"),	
														$partida,	
														number_format($row2->fields("monto_comprometido"),2,',','.'),
														$causado,
														number_format($total_renglon,2,',','.'),
														number_format($total_bi,2,',','.'),
														number_format($a_causar,2,',','.')
																			);
					
					$i++;
					$a_causar="0";
				//}
			$row2->MoveNext();
			$total_bi="";
			}
	}//end if
	$partida_ant=$partida;
	$row->MoveNext();
	$partida_siguente=$row->fields("partida").$row->fields("generica").$row->fields("especifica").$row->fields("subespecifica");
}
// return the formated data
echo $json->encode($responce);

?>