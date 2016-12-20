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
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(\"orden_compra_servicioE\".numero_cotizacion)
			FROM 
				\"orden_compra_servicioE\"
			INNER JOIN 
				organismo 
			ON
				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo 
				
				WHERE 
					(\"orden_compra_servicioE\".id_organismo=$_SESSION[id_organismo] )

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

$Sql = "	
			SELECT 
				\"orden_compra_servicioE\".numero_cotizacion
			FROM 
				\"orden_compra_servicioE\"
			INNER JOIN 
				organismo 
			ON
				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo 
				
			WHERE 
				(\"orden_compra_servicioE\".id_organismo=$_SESSION[id_organismo] )
			ORDER BY 
				\"orden_compra_servicioE\".numero_cotizacion
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{

	$responce->rows[$i]['id']=$row->fields("numero_cotizacion");

	$responce->rows[$i]['cell']=array(	
															$row->fields("numero_cotizacion")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>