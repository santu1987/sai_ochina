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
if(isset($_GET['busq_cod']))
{
	$busq_codo=$_GET['busq_cod'];
	if($busq_cod!=" ")
	$where.="and (proveedor.codigo_proveedor) like '%$busq_cod%'";
}
if(isset($_GET['busq_nom']))
{
	$busq_nom=strtolower($_GET['busq_nom']);
	if($busq_nom!=" ")
	$where.="and lower(proveedor.nombre) like '%$busq_nom%'";
}

$Sql="
			SELECT 
				count(proveedor.id_proveedor)
			FROM 
					organismo 
				INNER JOIN 
					proveedor 
				ON
					proveedor.id_organismo=organismo.id_organismo 
				
				WHERE 
					(proveedor.id_organismo=$_SESSION[id_organismo] )
$where
";
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

$Sql = "	
				SELECT 
					proveedor.id_proveedor,
					proveedor.codigo_proveedor,
					proveedor.nombre,
					proveedor.rif,
					proveedor.ret_iva,
					proveedor.ret_islr
				FROM 
					organismo 
				INNER JOIN 
					proveedor 
				ON
					proveedor.id_organismo=organismo.id_organismo 
				
				WHERE 
					(proveedor.id_organismo=$_SESSION[id_organismo] )$where
				ORDER BY 
					proveedor.nombre 
			";
$row=& $conn->Execute($Sql);

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
//$rif=split("-",$row->fields("rif"));
	$responce->rows[$i]['id']=$row->fields("id_proveedor");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															$row->fields("nombre"),
															$row->fields("rif"),
															number_format($row->fields("ret_iva"),2,',','.'),
															number_format($row->fields("ret_islr"),2,',','.'),
													
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>