<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
//$tipo="documento";
$where="WHERE 
					(\"orden_compra_servicioE\".id_organismo=$_SESSION[id_organismo] )
		AND
					(\"orden_compra_servicioE\".numero_compromiso!='0')			
";
if($_GET['proveedor']!='')
{
	$proveedor=$_GET['proveedor'];
	$where.="AND \"orden_compra_servicioE\".id_proveedor='$proveedor'";
}
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql_count="
			SELECT 
				count(\"orden_compra_servicioE\".id_orden_compra_servicioe)
			FROM 
					\"orden_compra_servicioE\"
			INNER JOIN 		
					organismo 
				ON
					\"orden_compra_servicioE\".id_organismo=organismo.id_organismo 
				
			INNER JOIN	
					proveedor
				ON
					\"orden_compra_servicioE\".id_proveedor=proveedor.id_proveedor
			".$where."
";
//die($Sql_count);
$row_count=& $conn->Execute($Sql_count);
if (!$row_count->EOF)
{
	$count = $row_count->fields("count");
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
$Sql = "	
				SELECT  DISTINCT
				   \"orden_compra_servicioE\".numero_orden_compra_servicio,
				   \"orden_compra_servicioE\".id_orden_compra_servicioe,
					\"orden_compra_servicioE\".numero_compromiso,
					\"orden_compra_servicioE\".fecha_orden_compra_servicio,
					proveedor.nombre,
					proveedor.id_proveedor as id_proveedor,
					proveedor.codigo_proveedor as codigo_proveedor
				FROM 
					\"orden_compra_servicioE\"
				INNER JOIN 		
						organismo 
					ON
						\"orden_compra_servicioE\".id_organismo=organismo.id_organismo 
					
				INNER JOIN	
						proveedor
					ON
						\"orden_compra_servicioE\".id_proveedor=proveedor.id_proveedor		
					".$where."
					ORDER BY 
						\"orden_compra_servicioE\".numero_compromiso

";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 

{////////////////////////////////////////////////////////////////////////
$fecha=substr($row->fields("fecha_orden_compra_servicio"),0,10);
$fecha = substr($fecha,8,2)."".substr($fecha,4,4)."".substr($fecha,0,4);

////////////////////////////////////////////////////////////////////////
$compromiso=$row->fields("numero_compromiso");
$sql="SELECT 
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
											\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
								
									$row_orden_compra=& $conn->Execute($sql);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);
										$total_total=$total+$iva;
										$total_renglon=$total_renglon+$total_total;
										$row_orden_compra->MoveNext();
									}
									
////////////////////////////////////////////////////////////////////////////////////////////////////
								$sql_facturas="SELECT 
														   porcentaje_iva,
														   porcentaje_retencion_iva, 
														   monto_bruto,
														   monto_base_imponible,
														   amortizacion,
														   tipo_documentocxp
											 FROM
											 			documentos_cxp
											where						   
														documentos_cxp.numero_compromiso='$compromiso'
											AND
														estatus='1'				
																";		   
									//die($sql_facturas);					
									$row_factura=& $conn->Execute($sql_facturas);
								//	$total_renglon=0;
									if($row_factura->EOF)
									{
										$fact_ord=$total_renglon;
									}else
									{		
											while(!$row_factura->EOF)
											{
												$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
												$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;
												
										//	if($row_factura->fields("tipo_documentocxp")=='9')
												if(($row_factura->fields("tipo_documentocxp")==$tipos_ant))
												{
													$tipo="anticipo";
													$monto_ant=$row_factura->fields("monto_bruto");
													$iva_anticipo=$row_factura->fields("porcentaje_iva");
													$total_iva=($monto_ant*$iva_anticipo)/100;
													$monto_ant=$monto_ant+$total_iva;
													//$monto_factura=$row_factura->fields("monto_bruto");*/
													$monto_factura=0;
												}else
										if(($row_factura->fields("tipo_documentocxp")==$tipos_fact)&&($row_factura->fields("amortizacion")!='0,00'))
												{
													$monto_factura="";	
													$monto_ante=($row_factura->fields("monto_bruto")+$row_factura->fields("amortizacion"));
													$p_iva_factura=$monto_ante*$row_factura->fields("porcentaje_iva")/100;
													//$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
													$monto_factura=$monto_ante+$p_iva_factura;	

													}
												
												$total_facturas=$total_facturas+$monto_factura;
												$row_factura->MoveNext();
											}		
											$fact_ord=($total_renglon)-($total_facturas);
							$monto_anticipo=$total_facturas;
							$porcentaje_ant=($monto_ant*100)/$total_renglon;	
							//die($monto_ant."/*/".$total_renglon);	
									}							
//////////////////////////////////////////////consultando todas las partidas segun el numero de compromiso///////////////////////////////////////////////////
	$Sql_partidas="SELECT 
						
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
					WHERE \"orden_compra_servicioE\".numero_compromiso='$compromiso'";		
$row_partidas=& $conn->Execute($Sql_partidas);
$is=0;
while (!$row_partidas->EOF) 
{

		$is++;
		$partida2=$row_partidas->fields("partida").$row_partidas->fields("generica").$row_partidas->fields("especifica").$row_partidas->fields("sub_especifica");
		if($is==1)
			$partida=$partida2;
		else
			$partida=$partida.";".$partida2;
		$contar++;	
		$row_partidas->MoveNext();
}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-
	/*if($total_facturas>0)
	{*/
//echo($total_facturas);

//-
	$responce->rows[$i]['numero_compromiso']=$row->fields("numero_compromiso");
	$responce->rows[$i]['cell']=array(	
															$row->fields("nombre"),
															$row->fields("numero_compromiso"),
															$row->fields("numero_orden_compra_servicio"),	
															$fecha,	
															number_format($total_renglon,2,',','.'),
															number_format($total_facturas,2,',','.'),
															$tipo,
															number_format($fact_ord,2,',','.'),
															number_format($monto_ant,2,',','.'),
															number_format($porcentaje_ant,2,',','.'),
															$iva_anticipo,
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															$contar
														);
														
	$i++;
//-
	//}
//-	
	$porcentaje_ant=0;
	$monto_ant=0;
	$tipo="";	
	$row->MoveNext();

	$total_facturas=0;
	$total_renglon=0;
}
// return the formated data
echo $json->encode($responce);
?>