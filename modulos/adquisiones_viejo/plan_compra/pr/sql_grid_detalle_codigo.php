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
$codigo = $_GET['detalle_codigo'];
$codigo_demanda = $_GET['demanda_codigo'];

//************************************************************************

$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_detalle_demanda) 
			FROM 
				detalle_demanda
			INNER JOIN
				demanda
			ON
				detalle_demanda.id_demanda = demanda.id_demanda			
			WHERE
				(upper(codigo_detalle_demanda) ='".strtoupper($codigo)."')
			AND
				(upper(codigo_demanda) ='".strtoupper($codigo_demanda)."')
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
				detalle_demanda.id_detalle_demanda, 
				detalle_demanda.nombre
			FROM 
				detalle_demanda
			INNER JOIN
				demanda
			ON
				detalle_demanda.id_demanda = demanda.id_demanda			
			WHERE
				(upper(codigo_detalle_demanda) ='".strtoupper($codigo)."')
			AND
				(upper(codigo_demanda) ='".strtoupper($codigo_demanda)."')
	        ORDER BY 
				codigo_detalle_demanda
			 LIMIT 
				$limit 
			 OFFSET 
				$start 
		  	 ";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_detalle_demanda");
	$responce =$row->fields("id_detalle_demanda")."*".  $row->fields("nombre");
	echo ($responce);    
}
?>