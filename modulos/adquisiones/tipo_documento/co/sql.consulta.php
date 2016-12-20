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
$busq_nombre = $_GET['busq_nombre'];
//************************************************************************

if(!$sidx) $sidx =1;
$sql_where = "WHERE 1=1";
if($busq_nombre!='')
	$sql_where.= " AND  ((lower(tipo_documento.nombre) like '%$busq_nombre%') OR (lower(tipo_documento.nombre) like '$busq_nombre%'))";
	

$Sql="
			SELECT 
				count(tipo_documento.id_tipo_documento) 
			FROM 
				tipo_documento
			INNER JOIN
				organismo
			ON
				tipo_documento.id_organismo = organismo.id_organismo
				
			$sql_where	
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
				tipo_documento.id_tipo_documento, tipo_documento.nombre  
			FROM 
				tipo_documento
			INNER JOIN
				organismo
			ON
				tipo_documento.id_organismo = organismo.id_organismo
			
			".$sql_where."
			ORDER BY 
				$sidx $sord 
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
	$responce->rows[$i]['id']=$row->fields("id_tipo_documento");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_tipo_documento"),
															$row->fields("nombre"),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>