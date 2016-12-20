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
$busq_codigo ="";
if(isset($_GET["busq_codigo"]))
$busq_codigo = strtolower($_GET['busq_codigo']);
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1 = 1 ";
if($busq_nombre!='')
	$where.= " AND  (lower(bienes.nombre) LIKE '%$busq_nombre%')";
////////////////////

if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				count(depreciacion_mensual.id_bienes) 
			FROM 
				depreciacion_mensual
			INNER JOIN
				bienes
			ON
				bienes.id_bienes=depreciacion_mensual.id_bienes
				".$where."
			AND
				depreciacion_mensual.id_organismo = $_SESSION[id_organismo]
			
				
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
				depreciacion_mensual.id_bienes as id_dep, 
				bienes.nombre,
				bienes.valor_compra,
				bienes.fecha_compra,
				bienes.vida_util,
				depreciacion_mensual.valor_depreciacion_acumula as valor_dep_acu,
				depreciacion_mensual.valor_libros,
				depreciacion_mensual.fecha_depreciacion as fecha_dep,
				vida_util_dep,
				depreciacion_mensual.valor_depreciacion_mensual as valor_dep_men
			FROM 
				depreciacion_mensual
			INNER JOIN
				bienes
			ON
				bienes.id_bienes=depreciacion_mensual.id_bienes
			".$where."
			AND
				depreciacion_mensual.id_organismo = $_SESSION[id_organismo]
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
	$responce->rows[$i]['id']=$row->fields("id_dep");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_dep"),
															$row->fields("nombre"),
															$row->fields("valor_compra"),
															$row->fields("fecha_compra"),
															$row->fields("vida_util"),
															$row->fields("valor_dep_acu"),
															$row->fields("valor_libros"),
															$row->fields("fecha_dep"),
															$row->fields("vida_util_dep"),
															$row->fields("valor_dep_men")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>