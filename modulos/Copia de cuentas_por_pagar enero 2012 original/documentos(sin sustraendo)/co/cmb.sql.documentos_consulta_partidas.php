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

if($_GET['cuentas_por_pagar_busqueda_fecha_partida']!='')
{
	$busq_fecha_v=$_GET['cuentas_por_pagar_busqueda_fecha_partida'];
	$where.="AND (documentos_cxp.fecha_vencimiento='$busq_fecha_v')";
}

if($_GET['cuentas_por_pagar_busqueda_tipo_partida']!='')
{
	$bus_tipo_doc=strtoupper($_GET['cuentas_por_pagar_busqueda_tipo_partida']);
	$where.="AND (upper(tipo_documento_cxp.nombre) like '$bus_tipo_doc')";
}

//if($busq_fecha_v!='')
//*11******************************************************************
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
			".$where."";
	//die($Sql);			
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count2 = $row->fields("count");
	$count =1;
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
			SELECT  DISTINCT 
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
			".$where."		
			ORDER BY
				 documentos_cxp.tipo_documentocxp
			LIMIT 
				$limit 
			OFFSET 
				$start ;	 	
";
//die($Sql);
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$valor_o=0;$valor_a=0;$valor_b=0;$valor_c=0;
		$tipo_doc=$row->fields("tipo_documentocxp"); 
		$sql_datos="SELECT
					 documentos_cxp.id_documentos,
					 documentos_cxp.porcentaje_iva,
					 documentos_cxp.porcentaje_retencion_iva,
					 documentos_cxp.porcentaje_retencion_islr,
					 documentos_cxp.monto_bruto,
					 documentos_cxp.monto_base_imponible,
					 documentos_cxp.numero_compromiso
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
					usuario
				ON	
					documentos_cxp.ultimo_usuario=usuario.id_usuario			
				WHERE 
					documentos_cxp.tipo_documentocxp='$tipo_doc'
				ORDER BY
					 documentos_cxp.tipo_documentocxp";			
		$row_datos=& $conn->Execute($sql_datos);
		//ciclo para sacara las partidas por documento
		while (!$row_datos->EOF) 
		{
		
					$numero_compromiso=$row_datos->fields("numero_compromiso");
					if($numero_compromiso!=0)
					{
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
															\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
														where
															\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
													$row_orden_compra=& $conn->Execute($sql);
													$partida=$row_orden_compra->fields("partida");
													if($partida==401) $valor_o=$valor_o+$row_datos->fields("monto_bruto");
													if($partida==402) $valor_a=$valor_a+$row_datos->fields("monto_bruto");
													if($partida==403) $valor_b=$valor_b+$row_datos->fields("monto_bruto");
													if($partida==404) $valor_c=$valor_c+$row_datos->fields("monto_bruto");
					}
			$row_datos->MoveNext();	
			}
						$tipo=$row->fields("doc"); 
						$tipo_id=$row->fields("tipo_documentocxp"); 
						$cont_partida=1;
						while($cont_partida<=4)
						{
									if($cont_partida==1)
											{
												$partidas=401;
												$total_partidas=$valor_o;
											}
									if($cont_partida==2)
											{
												$partidas=402;
												$total_partidas=$valor_a;
											}
									if($cont_partida==3)
											{
												$partidas=403;
												$total_partidas=$valor_b;
											}
									if($cont_partida==4)
											{
												$partidas=404;
												$total_partidas=$valor_c;
											}
								
						$responce->rows[$i]['id']=$row->fields("id_documentos");
						$responce->rows[$i]['cell']=array(	
															$row->fields("id_documentos"),	
															$row->fields("id_organismo"),	
															$tipo,
															$tipo_id,	
															$partidas,
															number_format($total_partidas,2,',','.')
																);
						$total_general=$total_general+$total_partidas;
						$cont_partida=$cont_partida+1;
						 }	
						//$partidas=0;
						//$total_partidas=0;
			$i++;
			$row->MoveNext();
		}	
// return the formated data
echo $json->encode($responce);
?>