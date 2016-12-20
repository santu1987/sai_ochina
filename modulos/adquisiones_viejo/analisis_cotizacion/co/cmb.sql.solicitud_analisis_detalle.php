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
$unidad = $_GET['unidad'];
$id = $_GET['id'];
$requisicion = $_GET['requisicion'];
//************************************************************************

$limit = 15;
if(!$sidx) $sidx =1;
$sql_bus="
SELECT 
	\"solicitud_cotizacionE\".numero_cotizacion
FROM 
	\"solicitud_cotizacionE\"
WHERE
	(\"solicitud_cotizacionE\".id_solicitud_cotizacione = $id)	
";
$row_bus=& $conn->Execute($sql_bus);

$Sql="
SELECT 
	count(\"solicitud_cotizacionD\".id_solicitud_cotizacion)
FROM 
	\"solicitud_cotizacionD\"
WHERE
	(\"solicitud_cotizacionD\".numero_cotizacion = '".$row_bus->fields("numero_cotizacion")."')	
";
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query echo($Sql);
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
	id_solicitud_cotizacion, 
	secuencia, cantidad, descripcion, 
	partida, generica, especifica, subespecifica, 
	monto
FROM 
	\"solicitud_cotizacionD\"
WHERE
	(\"solicitud_cotizacionD\".numero_cotizacion = '".$row_bus->fields("numero_cotizacion")."')	
ORDER BY
	secuencia
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$part = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");
	$total=$row->fields("cantidad")*$row->fields("monto");
	$responce->rows[$i]['id']=$row->fields("id_solicitud_cotizacion");

	$responce->rows[$i]['cell']=array(	
															
															$row->fields("secuencia"),
															$row->fields("descripcion"),
															number_format($row->fields("cantidad"),0,',','.'),
															number_format($row->fields("monto"),2,',','.'),
															number_format($total,2,',','.'),
															$part
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>