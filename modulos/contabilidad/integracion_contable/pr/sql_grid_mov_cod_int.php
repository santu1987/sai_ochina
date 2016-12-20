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

$busq=strtoupper($_GET["busq"]);
$ano=$_GET["ano"];

$where = " WHERE 1=1 and movimientos_contables.estatus='0'";
$numero_comprobante=$_POST[contabilidad_integracion_reverso_numero_c_desde];
$ano=$_POST[contabilidad_reverso_int_pr_ayo];
if($ano!="")
{
	$where.=" and ano_comprobante='$ano'";
}
if($numero_comprobante!="")
{
	$where.="and substr(numero_comprobante::varchar,9)='$numero_comprobante'";
}else
die("vacio");
//if($busq!="") $where.= " AND ano = $busq";
	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
		select  
				count (distinct(movimientos_contables.numero_comprobante)) 
		from 
				movimientos_contables  
		$where	
				";

$row=& $conn->Execute($Sql);
//die($Sql);
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
		select 
				distinct (movimientos_contables.numero_comprobante::varchar) as numero_comprobante
			from 
				movimientos_contables
		$where
			
		order by numero_comprobante
		LIMIT 
				$limit 
			OFFSET 
				$start
";
//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
	$responce=$row->fields("numero_comprobante");
}else
	$responce="vacio";
// return the formated data
echo ($responce);

?>