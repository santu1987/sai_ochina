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
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];

if($_GET['cuentas_por_pagar_busqueda_fecha_detalle']!='')
{
	$busq_fecha_v=$_GET['cuentas_por_pagar_busqueda_fecha_detalle'];
	$where.="AND (documentos_cxp.fecha_vencimiento='$busq_fecha_v')";
}
if($_GET['cuentas_por_pagar_busqueda_tipo_detalle']!='')
{
	$bus_tipo_doc=strtoupper($_GET['cuentas_por_pagar_busqueda_tipo_detalle']);
	$where.="AND (upper(tipo_documento_cxp.nombre) like '$bus_tipo_doc')";
}
//if($busq_fecha_v!='')
//******************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				count(id_documentos) 
			FROM 
				 documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
				tipo_documento_cxp
			ON
				documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento		
			INNER JOIN
				proveedor
			ON
				documentos_cxp.id_proveedor=proveedor.id_proveedor
			where numero_compromiso!='0'	
			$where	";

//die($count );			
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = 1;
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
$Sql="
			SELECT  
					 documentos_cxp.id_documentos,
					 documentos_cxp.numero_control,
					 documentos_cxp.porcentaje_iva,
					 documentos_cxp.porcentaje_retencion_iva,
					 documentos_cxp.porcentaje_retencion_islr,
					 documentos_cxp.monto_bruto,
					 documentos_cxp.monto_base_imponible,
					 documentos_cxp.numero_compromiso,
					 documentos_cxp.tipo_documentocxp,
					 tipo_documento_cxp.nombre as doc
			FROM 
				 documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
				tipo_documento_cxp
			ON
				documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento
			where numero_compromiso!='0'
			$where		
			LIMIT 
				$limit 
			OFFSET 
				$start 	 	
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$c=0;
while (!$row->EOF) 
{	
	$c=$c+1;	
	$numero_compromiso=$row->fields("numero_compromiso");
									$sql="SELECT 
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
																		\"orden_compra_servicioD\"
																	ON
																		\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
																	where
																		\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
									$row_orden_compra=& $conn->Execute($sql);
									$partida=$row_orden_compra->fields("partida");
									$generica=$row_orden_compra->fields("generica");
									$especifica=$row_orden_compra->fields("especifica");
									$subespecifica=$row_orden_compra->fields("subespecifica");
									$partidas=$partida.".".$generica.".".$especifica.".".$subespecifica;
									$tipo_id=$row->fields("tipo_documentocxp"); 
									$responce->rows[$i]['id']=$row->fields("id_documentos");
									$responce->rows[$i]['cell']=array(	
																		$row->fields("numero_control"),	
																		$row->fields("doc"),
																		$partidas,	
																		number_format($row->fields("monto_bruto"),2,',','.')
																			);
									$total_general=$total_general+$total_partidas;
									$i++;
			$row->MoveNext();	
	 }	
// return the formated data
	
echo $json->encode($responce);
?>