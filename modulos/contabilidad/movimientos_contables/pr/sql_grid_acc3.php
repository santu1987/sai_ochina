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
$codigo_acc=$_POST['cxp_pr_acc'];
$where="AND codigo_accion_central='$codigo_acc'";
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count( DISTINCT accion_centralizada.id_accion_central)
			FROM 
					organismo 
				INNER JOIN 
					accion_centralizada 
				ON
					accion_centralizada.id_organismo=organismo.id_organismo 
			
				WHERE 
					(accion_centralizada.id_organismo=$_SESSION[id_organismo] )
				$where
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
					DISTINCT accion_centralizada.id_accion_central,
					accion_centralizada.codigo_accion_central,
					accion_centralizada.denominacion    
				FROM 
					organismo 
				INNER JOIN 
					accion_centralizada 
				ON
					accion_centralizada.id_organismo=organismo.id_organismo 
				
				WHERE 
					(accion_centralizada.id_organismo=$_SESSION[id_organismo] )
				$where
				ORDER BY 
					accion_centralizada.denominacion 
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
if (!$row->EOF) 
{
////////////////////////////////////////////////////	 
$responce->rows[$i]['id']=$row->fields("id_accion_central");
$responce =$row->fields("id_accion_central")."*".$row->fields("codigo_accion_central")."*".$row->fields("denominacion");	
///////////////////////////////////////////////////
}else
$responce="vacio";
// return the formated data
echo $responce;
?>