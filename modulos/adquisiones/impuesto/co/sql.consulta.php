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

if(!$sidx) $sidx =1;

if(isset($_GET["busq_nombre"]))
	$busq_nombre = strtolower($_GET['busq_nombre']);

$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  (lower(impuesto.nombre) LIKE '%$busq_nombre%')";
$Sql="
			SELECT 
				count(id_impuesto) 
			FROM 
				impuesto
			".$where."
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
$Sql="
			SELECT 
				* 
			FROM 
				impuesto 
			".$where."
			ORDER BY 
				$sidx $sord 
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
	$responce->rows[$i]['id_impuesto']=$row->fields("id_impuesto");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_impuesto"),
															$row->fields("nombre"),
															$row->fields("comentario")															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>