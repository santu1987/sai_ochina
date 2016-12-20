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
$busq_nombre =strtolower($_GET['busq_proyecto']);
$busq_anio =strtolower($_GET['busq_anio']);

//************************************************************************

if(!$sidx) $sidx =1;

$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  (lower(proyecto.nombre) like '%$busq_nombre%')";
	
if($busq_codigo!='')
	$where.= " AND id_proyecto = $busq_codigo";

if($busq_anio!='')
	$where.= " AND (proyecto.ano = $busq_anio)";	
else
	$where.= " AND (proyecto.ano = 2010)";	

$Sql="
			SELECT 
				count(id_proyecto) 
			FROM 
				proyecto			
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
				proyecto.id_proyecto,
				proyecto.nombre AS proyectnombre,
				organismo.nombre AS organismonombre,
				proyecto.comentario AS comentario
			FROM 
				proyecto
			INNER JOIN
				organismo
			ON
				proyecto.id_organismo = organismo.id_organismo
			".$where."
			ORDER BY 
				 proyectnombre,$sidx $sord 
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
	$responce->rows[$i]['id']=$row->fields("id_proyecto"); 

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_proyecto"),
															$row->fields("proyectnombre"),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>