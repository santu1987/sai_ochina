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
//************************************************************************
$limit = 15;
$busq_nombre ="";
if(isset($_GET["busq_nombre"]))
$busq_nombre = strtolower($_GET['busq_nombre']);
$busq_custodio ="";
if(isset($_GET["busq_custodio"]))
$busq_custodio = strtolower($_GET['busq_custodio']);
$busq_fecha ="";
if($_GET["busq_fecha"]!='')
$busq_fecha = $_GET['busq_fecha'];
//if(isset($_GET["busq_fecha_compp"]))
//$busq_fecha_compp = strtolower($_GET['busq_fecha_compp']);
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1 = 1 ";
if($busq_nombre!=''){
	$where.= " AND  (lower(bienes.nombre) LIKE '%$busq_nombre%') ";
}
if($busq_custodio!=''){
	$where.= " AND  (lower(custodio.nombre) LIKE '%$busq_custodio%') ";
}
if($busq_fecha!=''){
	$where.= " AND  bienes.fecha_compra = '$busq_fecha' ";
}

////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_bienes) 
			FROM 
				bienes 
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				tipo_bienes
			ON
				tipo_bienes.id_tipo_bienes=bienes.id_tipo_bienes
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
			INNER JOIN
				mayor
			ON 
				mayor.id_mayor=bienes.id_mayor
			INNER JOIN
				\"orden_compra_servicioE\"
			ON
				\"orden_compra_servicioE\".id_orden_compra_servicioe=bienes.id_orden_compra_servicioe
			".$where."
			AND
				bienes.id_organismo = $_SESSION[id_organismo]
				
";

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
$Sql="
			SELECT distinct
				bienes.id_bienes,
				bienes.nombre as bien, 
				valor_compra,
				valor_rescate,
				tipo_bienes.nombre as tipo,
				sitio_fisico.nombre as sitio,
				unidad_ejecutora.nombre as unidad,
				mayor.nombre as mayor,
				vida_util,
				custodio.nombre as custodio,
				descripcion_general as descri,
				marca,
				modelo,
				anobien,
				serial_motor,
				serial_carroceria,
				color,
				placa,
				estatus_bienes as estatus,
				bienes.comentarios as comen,
				codigo_bienes,
				serial_bien,
				bienes.id_tipo_bienes as idtipo,
				bienes.id_sitio_fisico as idsitio,
				bienes.id_custodio as idcustodio,
				bienes.id_unidad_ejecutora as idunidad,
				bienes.id_mayor as idmayor,
				bienes.anobien,
				bienes.calcular_depreciacion as depreciacion,
				bienes.fecha_compra as fecompra,
				bienes.id_orden_compra_servicioe as ordencompra,
				bienes.ano_orden_compra as anocompra,
				numero_factura as factura,
				numero_orden_compra_servicio num_compra,
				bienes.num_seguro
			FROM 
				bienes 
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				tipo_bienes
			ON
				tipo_bienes.id_tipo_bienes=bienes.id_tipo_bienes
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
			INNER JOIN
				mayor
			ON 
				mayor.id_mayor=bienes.id_mayor
			INNER JOIN
				\"orden_compra_servicioE\"
			ON
				\"orden_compra_servicioE\".id_orden_compra_servicioe=bienes.id_orden_compra_servicioe
			".$where."
			AND
				bienes.id_organismo = $_SESSION[id_organismo]
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start
				";
$row=& $conn->Execute($Sql);
// constructing a JSON
/*$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;*/
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_bienes");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_bienes"),
															$row->fields("codigo_bienes"),
															$row->fields("serial_bien"),
															$row->fields("bien"),
															$row->fields("mayor"),
															$row->fields("tipo"),
															$row->fields("marca"),
															$row->fields("modelo"),
															$row->fields("descri"),
															$row->fields("comen"),
															$row->fields("idmayor"),
															$row->fields("idtipo"),
															$row->fields("unidad"),
															$row->fields("sitio"),
															$row->fields("custodio"),
															$row->fields("idunidad"),
															$row->fields("idsitio"),
															$row->fields("idcustodio"),
															$row->fields("vida_util"),
															$row->fields("valor_compra"),
															$row->fields("valor_rescate"),
															$row->fields("serial_motor"),
															$row->fields("serial_carroceria"),
															$row->fields("color"),
															$row->fields("placa"),
															$row->fields("anobien"),
															$row->fields("estatus"),
															$row->fields("depreciacion"),
															$row->fields("fecompra"),
															$row->fields("ordencompra"),
															$row->fields("anocompra"),
															$row->fields("factura"),
															$row->fields("num_compra"),
															$row->fields("num_seguro")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>