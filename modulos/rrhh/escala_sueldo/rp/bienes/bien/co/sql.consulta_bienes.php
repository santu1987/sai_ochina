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

if(isset($_GET["nomb_bienes"]))
	$nomb_modulo = strtolower($_GET['nomb_bienes']);
if(isset($_GET["estatus_bienes"]))
	$estatus_bienes = $_GET['estatus_bienes'];	

$where = "WHERE 1=1";
if($nomb_modulo!='')
	$where.= "  AND  (lower(bienes.nombre) LIKE '%$nomb_bienes%')";
if($estatus_bienes!='')
	$where.= " AND  bienes.estatus_bienes = $estatus_bienes ";	
$Sql="
			SELECT 
				count(id_bienes) 
			FROM 
				bienes
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
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
				bienes.id_bienes,
				bienes.nombre as bien,
				bienes.codigo_bienes,
				bienes.serial_bien,
				unidad_ejecutora.nombre as unidad,
				sitio_fisico.nombre as sitio,
				custodio.nombre as custodio
			FROM 
				bienes
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
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
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
														$row->fields("id_bienes"),
														$row->fields("bien"),
														$row->fields("codigo_bienes"),
														$row->fields("serial_bien"),
														$row->fields("unidad"),
														$row->fields("sitio"),
														$row->fields("custodio")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>