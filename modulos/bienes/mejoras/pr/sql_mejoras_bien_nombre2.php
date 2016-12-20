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
$busq_numero ="";
if(isset($_GET["busq_numero"]))
$busq_numero = strtolower($_GET['busq_numero']);
$busq_fecha_mejora ="";
if(isset($_GET["busq_fecha_mejora"]))
$busq_fecha_mejora = strtolower($_GET['busq_fecha_mejora']);
$busq_fecha_comprobante ="";
if(isset($_GET["busq_fecha_comprobante"]))
$busq_fecha_comprobante = strtolower($_GET['busq_fecha_comprobante']);
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1 = 1 ";
if($busq_nombre!='')
	$where.= " AND  (lower(bienes.nombre) LIKE '%$busq_nombre%')";
if($busq_codigo!='')
	$where.= " AND  (lower(bienes.codigo_bienes) LIKE '%$busq_codigo%')";
if($busq_numero!='')
	$where.= " AND  (lower(mejoras.numero_comprobante) LIKE '%$busq_numero%')";	
if($busq_fecha_mejora!='')
	$where.= " AND  mejoras.fecha_mejora = '$busq_fecha_mejora' ";		
if($busq_fecha_comprobante!='')
	$where.= " AND  mejoras.fecha_comprobante = '$busq_fecha_comprobante' ";		
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_mejoras) 
			FROM 
				mejoras 
			INNER JOIN
				bienes
			ON
				mejoras.id_bienes = bienes.id_bienes
				".$where." 
			AND
				mejoras.id_organismo = $_SESSION[id_organismo]
				
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
				mejoras.id_mejoras,
				mejoras.nombre_mejora, 
				mejoras.fecha_mejora,
				mejoras.valor_rescate,
				mejoras.usuario_carga_mejora,
				mejoras.numero_comprobante,
				mejoras.fecha_comprobante,
				mejoras.descripcion_general,
				mejoras.comentarios,
				bienes.id_bienes,
				bienes.codigo_bienes,
				bienes.nombre,
				mejoras.vida_util
			FROM 
				mejoras 
			INNER JOIN
				bienes
			ON
				mejoras.id_bienes = bienes.id_bienes
				".$where."
			AND
				mejoras.id_organismo = $_SESSION[id_organismo]
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
	$responce->rows[$i]['id']=$row->fields("id_mejoras");
	$fecha_mejora = substr($row->fields("fecha_mejora"),0,10);
	$fecha_mejora = substr($fecha_mejora,8,2)."".substr($fecha_mejora,4,4)."".substr($fecha_mejora,0,4);
	$fecha_comprobante = substr($row->fields("fecha_comprobante"),0,10);
	$fecha_comprobante = substr($fecha_comprobante,8,2)."".substr($fecha_comprobante,4,4)."".substr($fecha_comprobante,0,4);
	
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_mejoras"),
															$row->fields("nombre_mejora"),
															$fecha_mejora,
															$row->fields("valor_rescate"),
															$row->fields("usuario_carga_mejora"),
															$row->fields("numero_comprobante"),
															$fecha_comprobante,
															$row->fields("descripcion_general"),
															$row->fields("comentarios"),
															$row->fields("id_bienes"),
															$row->fields("codigo_bienes"),
															$row->fields("nombre"),
															$row->fields("vida_util")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>