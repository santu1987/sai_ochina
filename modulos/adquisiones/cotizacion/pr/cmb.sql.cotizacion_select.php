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
$requisicion = $_GET['requisicion'];
$cotizacion = $_GET['cotizacion'];
$secuencias ="";
//************************************************************************
//************************************************************************
	

//************************************************************************
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;


	$total_pages = ceil(1/$limit);


// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

$sql_co="SELECT 
		secuencia 
	FROM 
		\"solicitud_cotizacionD\"
	WHERE
		numero_requisicion = '$requisicion'
	AND
		numero_cotizacion = '$cotizacion'";
	$row_otro=& $conn->Execute($sql_co);
	
	if(!$row_otro->EOF)
	{
	
		while (!$row_otro->EOF){
			if($secuencias == "")
				$secuencias = $row_otro->fields("secuencia");
			else
				$secuencias = $secuencias.','. $row_otro->fields("secuencia");
		$row_otro->MoveNext();
		}
	}
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = 1;
$i=0;


	$responce->rows[$i]['id']=$secuencias;

	$responce = $secuencias;

// return the formated data
echo ($responce);
?>