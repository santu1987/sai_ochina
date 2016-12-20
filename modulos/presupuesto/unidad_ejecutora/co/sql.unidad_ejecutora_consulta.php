<?php
foreach ($_REQUEST as $key => $value) $salida .="$key:$value<br>";

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

$where = 'WHERE 1=1';

	
if ($_GET[unidad_ejecutora_busq_nombre]!='') 
	$where.=" AND lower(unidad_ejecutora.nombre) LIKE '%".strtolower($_GET[unidad_ejecutora_busq_nombre])."%' OR lower(unidad_ejecutora.nombre) LIKE '".strtolower($_GET[unidad_ejecutora_busq_nombre])."%'";

$Sql="
			SELECT 
				count(unidad_ejecutora.id_unidad_ejecutora),
				unidad_ejecutora.id_unidad_ejecutora,
				organismo.nombre AS organismonombre,
				unidad_ejecutora.nombre,
				unidad_ejecutora.comentario AS comentario      
			FROM  
				unidad_ejecutora	
			INNER JOIN
				organismo
			ON
				unidad_ejecutora.id_organismo = organismo.id_organismo  
			$where  
			
			GROUP BY 
				unidad_ejecutora.id_unidad_ejecutora,
				organismo.nombre,
				unidad_ejecutora.nombre,
				unidad_ejecutora.comentario 
			
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

$row=& $conn->Execute($Sql."ORDER BY $sidx $sord LIMIT $limit OFFSET $start");

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_unidad_ejecutora");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_unidad_ejecutora"),
															$row->fields("nombre"),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>