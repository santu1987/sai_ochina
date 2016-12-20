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
$anio = $_GET['anio'];
$anio = 2011;

//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(DISTINCT unidad_ejecutora.id_unidad_ejecutora)
	FROM 
					organismo 
				INNER JOIN 
					unidad_ejecutora 
				ON
					unidad_ejecutora.id_organismo=organismo.id_organismo  
				WHERE 
					(unidad_ejecutora.id_organismo=$_SESSION[id_organismo])
				
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
					DISTINCT unidad_ejecutora.id_unidad_ejecutora,
					unidad_ejecutora.nombre AS unidad_ejecutora ,
					unidad_ejecutora.codigo_unidad_ejecutora
					
				FROM 
					organismo 
				INNER JOIN 
					unidad_ejecutora 
				ON
					unidad_ejecutora.id_organismo=organismo.id_organismo  
				WHERE 
					(unidad_ejecutora.id_organismo=$_SESSION[id_organismo])
				ORDER BY 
					codigo_unidad_ejecutora
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
//$saldo_actual = $row->fields("saldo")+$row->fields("saldo_tras")+$row->fields("saldo_modi")-$row->fields("saldo_compro");
	$responce->rows[$i]['id']=$row->fields("id_unidad_ejecutora");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_unidad_ejecutora"),
															$row->fields("codigo_unidad_ejecutora"),
															$row->fields("unidad_ejecutora")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>