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
$busq_unidad ="";
if(isset($_GET["busq_unidad"]))
$busq_unidad = $_GET['busq_unidad'];
$busq_nombre ="";
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1 = 1 ";
if($busq_unidad!='')
	$where.= " AND  sitio_fisico.id_unidad_ejecutora = $busq_unidad ";
if($busq_nombre!='')
	$where.= " AND (lower(sitio_fisico.nombre) LIKE '%$busq_nombre%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_sitio_fisico) 
			FROM 
				sitio_fisico 
			INNER JOIN
				unidad_ejecutora
			ON
				sitio_fisico.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
				".$where."
			AND
				sitio_fisico.id_organismo = $_SESSION[id_organismo]
				
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
				sitio_fisico.id_sitio_fisico,
				sitio_fisico.nombre, 
				sitio_fisico.comentarios,
				sitio_fisico.id_unidad_ejecutora,
				unidad_ejecutora.nombre as unidad
			FROM 
				sitio_fisico 
			INNER JOIN
				unidad_ejecutora
			ON	
				sitio_fisico.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora ".$where."
			AND
				sitio_fisico.id_organismo = $_SESSION[id_organismo]
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start;
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
//$fecha = substr($fecha, 0,10);
//$fecha = substr($fecha,8,2).substr($fecha,4,4).substr($fecha,0,4);
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_sitio_fisico");
	
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_sitio_fisico"),
															$row->fields("nombre"),
															$row->fields("comentarios"),
															$row->fields("id_unidad_ejecutora"),
															$row->fields("unidad")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>