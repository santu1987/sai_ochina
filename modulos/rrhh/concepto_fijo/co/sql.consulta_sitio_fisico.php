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

if(!$sidx) $sidx =1;

if(isset($_GET["nomb_sitio_fisico"]))
	$nomb_sitio_fisico = strtolower($_GET['nomb_sitio_fisico']);
if(isset($_GET["nomb_unidad"]))
	$nomb_unidad = strtolower($_GET['nomb_unidad']);

$where = "WHERE 1=1";
if($nomb_sitio_fisico!='')
	$where.= " AND  (lower(sitio_fisico.nombre) LIKE '%$nomb_sitio_fisico%')";
if($nomb_unidad!='')
	$where.= " AND  sitio_fisico.id_unidad_ejecutora=$nomb_unidad";
$Sql="
			SELECT 
				count(id_sitio_fisico) 
			FROM 
				sitio_fisico
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=sitio_fisico.id_unidad_ejecutora
			".$where."
			AND
				sitio_fisico.id_organismo = $_SESSION[id_organismo]
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
				sitio_fisico.nombre,sitio_fisico.comentarios,unidad_ejecutora.nombre as nomb
			FROM 
				sitio_fisico
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=sitio_fisico.id_unidad_ejecutora
			".$where."
			AND
				sitio_fisico.id_organismo = $_SESSION[id_organismo]
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
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
														$row->fields("nomb"),
															$row->fields("nombre"),
															$row->fields("comentarios")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>