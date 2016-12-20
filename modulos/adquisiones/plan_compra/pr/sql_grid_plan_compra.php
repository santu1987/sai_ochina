<?php
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
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(\"id_plan_comprasD\" ) 
			FROM 
				\"plan_comprasD\"
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora = \"plan_comprasD\".id_unidad_ejecutora
			INNER JOIN
				detalle_demanda
			ON
				detalle_demanda.id_detalle_demanda = \"plan_comprasD\".id_detalle_demanda
			INNER JOIN
				demanda
			ON
				detalle_demanda.id_demanda = demanda.id_demanda  
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
			SELECT 
				\"plan_comprasD\".\"id_plan_comprasD\" AS id,
				\"plan_comprasE\".\"id_plan_comprasE\" AS idd, 
				\"plan_comprasD\".ano, 
				\"plan_comprasD\".id_unidad_ejecutora,
				unidad_ejecutora.codigo_unidad_ejecutora,
				unidad_ejecutora.nombre AS unidad_ejecutora,
				unidad_ejecutora.jefe_unidad,
				\"plan_comprasD\".secuencia, 
				\"plan_comprasD\".id_detalle_demanda,
				detalle_demanda.codigo_detalle_demanda,
				detalle_demanda.nombre AS detalle_demanda,
				detalle_demanda.id_demanda, 
				demanda.codigo_demanda,
				demanda.nombre AS demanda,
				\"plan_comprasD\".cantidad, 
				\"plan_comprasD\".valor, 
				\"plan_comprasD\".fecha_propuesta, 
				\"plan_comprasD\".tipo_compra, 
				\"plan_comprasD\".comentario
			FROM 
				\"plan_comprasD\"
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora = \"plan_comprasD\".id_unidad_ejecutora
			INNER JOIN
				\"plan_comprasE\"
			ON
				unidad_ejecutora.id_unidad_ejecutora = \"plan_comprasE\".id_unidad_ejecutora
			INNER JOIN
				detalle_demanda
			ON
				detalle_demanda.id_detalle_demanda = \"plan_comprasD\".id_detalle_demanda
			INNER JOIN
				demanda
			ON
				detalle_demanda.id_demanda = demanda.id_demanda  
			ORDER BY 
				codigo_unidad_ejecutora,secuencia
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	if($row->fields("tipo_compra") == 1){
		$tipo_compra = 'Nacional';
	}else{
		$tipo_compra = 'Internacional';
	}
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															$row->fields("idd"),
															$row->fields("ano"),
															$row->fields("id_unidad_ejecutora"),
															$row->fields("codigo_unidad_ejecutora"),
															$row->fields("unidad_ejecutora"),
															$row->fields("jefe_unidad"),
															$row->fields("secuencia"),
															$row->fields("id_detalle_demanda"),
															$row->fields("codigo_detalle_demanda"),
															$row->fields("detalle_demanda"),
															$row->fields("id_demanda"),
															$row->fields("codigo_demanda"),
															$row->fields("demanda"),
															$row->fields("cantidad"),
															$row->fields("valor"),
															$row->fields("fecha_propuesta"),
															$row->fields("tipo_compra"),
															$tipo_compra,
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>