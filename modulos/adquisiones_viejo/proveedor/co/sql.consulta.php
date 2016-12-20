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
$busq_ramo 	 = $_GET['busqueda_ramo'];
//************************************************************************

if(!$sidx) $sidx =1;
$sql_where = "WHERE 1=1";
if($busq_nombre!='')
	$sql_where.= " AND  ((lower(proveedor.nombre) like '%$busq_nombre%') OR (lower(proveedor.nombre) like '$busq_nombre%'))";
if($busq_ramo!='')
	$sql_where.= " AND  ((lower(ramo.nombre) like '%$busq_ramo%') OR (lower(ramo.nombre) like '$busq_ramo%'))";

$Sql="
			SELECT 
				count(proveedor.id_proveedor) 
			FROM 
				proveedor
			INNER JOIN
				organismo
			ON
				proveedor.id_organismo = organismo.id_organismo
			INNER JOIN
				ramo
			ON
				proveedor.id_ramo = ramo.id_ramo				
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
					proveedor.*,
					ramo.nombre AS ramo
			FROM 
				proveedor
			INNER JOIN
				organismo
			ON
				proveedor.id_organismo = organismo.id_organismo
			INNER JOIN
				ramo
			ON
				proveedor.id_ramo = ramo.id_ramo
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
	$responce->rows[$i]['id']=$row->fields("id_proveedor");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_proveedor"),
															$row->fields("nombre"),
															$row->fields("rif"),
															$row->fields("rnc"),
															$row->fields("nombre_persona_contacto"),
															$row->fields("telefono"),
															$row->fields("ramo")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>