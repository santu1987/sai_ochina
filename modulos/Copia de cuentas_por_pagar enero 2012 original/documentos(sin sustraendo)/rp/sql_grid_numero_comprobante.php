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
if(isset($_GET[busq_cuenta]))
{
	$busq_cuenta=$_GET[busq_cuenta];
	//die($busq_cuenta);
	if($busq_cuenta!="")
	$where.="and substring(integracion_contable.numero_comprobante::varchar,9,6) like'%$busq_cuenta%'";

}
//if($busq!="") $where.= " AND ano = $busq";
	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
		select  
				count (distinct(documentos_cxp.numero_comprobante)) 
		from 
				integracion_contable  
		INNER JOIN
				documentos_cxp
		ON	
				integracion_contable.numero_comprobante=integracion_contable.numero_comprobante	
		where
				documentos_cxp.numero_comprobante!=0	
		$where			
				";
//die($Sql);
$row=& $conn->Execute($Sql);

if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query
if( $count >0 )
{
	$total_pages = ceil($count/$limit);
} 
else
{
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
				distinct integracion_contable.numero_comprobante,fecha_comprobante
			from 
				integracion_contable
			INNER JOIN
				documentos_cxp
		ON	
				integracion_contable.numero_comprobante=documentos_cxp.numero_comprobante
		$where			
		order by integracion_contable.numero_comprobante
		LIMIT 
				$limit 
			OFFSET 
				$start
";
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