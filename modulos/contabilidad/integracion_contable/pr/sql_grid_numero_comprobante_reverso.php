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
$where = " WHERE 1=1 and 		movimientos_contables.estatus='0'
";

//if($busq!="") $where.= " AND ano = $busq";
	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
if(isset($_GET[busq_cuenta]))
{
$comprobante=$_GET[busq_cuenta];
if($comprobante!="")

	$where.="and substr(movimientos_contables.numero_comprobante::varchar,11)='$comprobante'";
}
if(isset($_GET[ano]))
{
	$ano=$_GET[ano];
	if($ano!="")
		$where.=" and Extract(year from fecha_comprobante) ='$ano' ";
}
if(isset($_GET[tipo]))
{
	$tipo=$_GET[tipo];
	if($tipo!="")
		$where.=" and codigo_tipo_comprobante ='$tipo' ";
}



$Sql="
		select  
				count (distinct(movimientos_contables.numero_comprobante)) 
		from 
				movimientos_contables  
		inner join
		tipo_comprobante
		on
		movimientos_contables.id_tipo_comprobante=tipo_comprobante.id
		$where	";
				
//die($Sql);
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
		select 
				distinct movimientos_contables.numero_comprobante,fecha_comprobante,codigo_tipo_comprobante
			from 
				movimientos_contables
			inner join
			tipo_comprobante
			on
			movimientos_contables.id_tipo_comprobante=tipo_comprobante.id
		$where	
		order by movimientos_contables.numero_comprobante
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
while (!$row->EOF) 
{
		$fecha_comp = substr($row->fields("fecha_comprobante"),0,10);
		$fecha_comp = substr($fecha_comp,8,2)."/".substr($fecha_comp,5,2)."/".substr($fecha_comp,0,4);

	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															substr($row->fields("numero_comprobante"),8),
															$row->fields("numero_comprobante"),
															$fecha_comp
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>