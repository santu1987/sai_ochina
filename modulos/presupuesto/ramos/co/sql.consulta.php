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
$busq_nombre =strtolower($_GET['busq_ramo']);
//************************************************************************

if(!$sidx) $sidx =1;

$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  ((lower(ramo.nombre) like '%$busq_nombre%') OR (lower(ramo.nombre) like '$busq_nombre%'))";
	

$Sql="
			SELECT 
				count(id_ramo) 
			FROM 
				ramo			
			$where	
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
$limit = 15;
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
				ramo.id_ramo,
				organismo.nombre AS organismonombre,
				ramo.nombre,ramo.comentario
			FROM 
				ramo
			INNER JOIN
				organismo
			ON
				ramo.id_organismo = organismo.id_organismo
			".$where."
			ORDER BY 
				ramo.nombre,$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_ramo");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_ramo"),
															$row->fields("nombre"),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>