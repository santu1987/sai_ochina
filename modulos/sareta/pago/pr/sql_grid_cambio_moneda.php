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
$where = " WHERE lower(sareta.moneda.nombre) like '%$busq_nombre%' ";


$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
		SELECT 
			  sareta.cambio_moneda.id,
			  sareta.cambio_moneda.id_moneda,
			  sareta.moneda.nombre AS moneda,
			  sareta.cambio_moneda.fecha_cambio AS fecha,
			  sareta.cambio_moneda.valor,
			  sareta.cambio_moneda.obs
			FROM 
				sareta.cambio_moneda

LEFT OUTER JOIN sareta.moneda
ON  sareta.moneda.id_moneda= sareta.cambio_moneda.id_moneda
".$where."	 
			 ORDER BY 
				sareta.moneda.nombre,sareta.cambio_moneda.fecha_cambio 
			 DESC;
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

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	
						
	$ano=substr ($row->fields("fecha"),0,4);
	$paso=substr ($row->fields("fecha"),5);
	$mes=substr ($paso,0,2);
	$paso=substr ($paso,3);
	$dia=substr ($paso,0,2);
	
	$fecha=$dia."/".$mes."/".$ano;			
						
	$responce->rows[$i]['id']=$row->fields("id");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															$row->fields("id_moneda"),
															substr($row->fields("moneda"),0,30),
															utf8_encode($row->fields("moneda")),
															$fecha,
															number_format($row->fields("valor"),2,',','.'),
															substr($row->fields("obs"),0,20),
															$row->fields("obs"),
															$row->fields("valor")
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>