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
if (isset($_GET['nombre']))
$busq_nombre = strtolower($_GET['nombre']);
$where = " WHERE  lower(puerto_hasta.nombre) like '%$busq_nombre%' or lower(puerto_desde.nombre) like '%$busq_nombre%'";
if ($busq_nombre!='')

$limit = 15;
if(!$sidx) $sidx =1;

$Sql.="SELECT
	id_distancia 
		AS id,
	bandera_desde.id 
		AS id_org,	
	bandera_desde.nombre 
		AS puerto_org,
	puerto_desde.id_puerto 
		AS id_puerto_org,
	puerto_desde.nombre 
		AS nombre_pto_org,
	bandera_hasta.id 
		AS id_rec,
	bandera_hasta.nombre 
		AS puerto_rec,
	puerto_hasta.id_puerto 
		AS id_puerto_rec,
	puerto_hasta.nombre 
		AS nombre_pto_rec,
		millas,
		comentario
FROM  
	sareta.distancia_puerto
INNER JOIN
	sareta.bandera AS bandera_desde
ON
	bandera_desde.id=id_bandera_desde
INNER JOIN 
	sareta.bandera AS bandera_hasta
ON
	bandera_hasta.id=id_bandera_hasta
INNER JOIN
	sareta.puerto AS puerto_desde
ON
	puerto_desde.id_puerto=id_puerto_desde
INNER JOIN 
	sareta.puerto AS puerto_hasta
ON
	puerto_hasta.id_puerto=id_puerto_hasta
			".$where;

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
															$row->fields("id"),
															substr($row->fields("id_org"),0,30),
															utf8_encode($row->fields("puerto_org")),
															$row->fields("id_puerto_org"),
															substr($row->fields("nombre_pto_org"),0,20),
															$row->fields("nombre_pto_org"),
															$row->fields("id_rec"),
															$row->fields("puerto_rec"),
															$row->fields("id_puerto_rec"),
															
															substr($row->fields("nombre_pto_rec"),0,20),
															$row->fields("nombre_pto_rec"),
															$row->fields("millas"),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>