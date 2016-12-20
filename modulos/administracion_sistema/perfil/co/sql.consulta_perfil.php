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
$cd_mask=$_GET['cd_mask'];
$nm_mask=$_GET['nm_mask'];
//************************************************************************

if(!$sidx) $sidx =1;

$where = "WHERE 1=1";
if($nm_mask!='')
	$where.= " AND  (lower(nombre) like '%$nm_mask%')";
if($cd_mask!='')
	$where.= " AND id_perfil=$cd_mask";

$Sql="
			SELECT 
				count(id_perfil) 
			FROM 
				perfil
				".$where."
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
$limit=15;
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
/*if(isset($_GET["nm_mask"]))
	$nm_mask = strtolower ($_GET['nm_mask']);
else
	$nm_mask = "";
if(isset($_GET["cd_mask"]))
	$cd_mask = $_GET['cd_mask'];
else
	$cd_mask = "";
*/


	
// the actual query for the grid data
$Sql="
			SELECT 
				* 
			FROM 
				perfil 
			".$where."
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_perfil");

	$responce->rows[$i]['cell']=array(	
											
															$row->fields("nombre"),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>