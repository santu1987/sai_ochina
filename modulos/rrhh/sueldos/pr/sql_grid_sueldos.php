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
$id_trabajador = $_POST['aumento_sueldos_pr_id_trabajador'];
$where.= " WHERE id_trabajador = $id_trabajador ";
$Sql="
			SELECT 
				count(aumento_sueldo.id_aumento_sueldo) 
			FROM 
				aumento_sueldo
			INNER JOIN 
				organismo 
			ON 
				aumento_sueldo.id_organismo = organismo.id_organismo
			".$where."
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
$Sql="
			SELECT 
				aumento_sueldo.id_aumento_sueldo,
				aumento_sueldo.sueldo_aumento,
				aumento_sueldo.observacion
			FROM 
				aumento_sueldo
			INNER JOIN 
				organismo 
			ON 
				aumento_sueldo.id_organismo = organismo.id_organismo
				".$where."
";
				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_aumento_sueldo");
	$responce =$row->fields("id_aumento_sueldo")."*". $row->fields("sueldo_aumento")."*". $row->fields("observacion");
	
}else
{
	$responce="";
}
echo ($responce);
//echo $json->encode($responce);
	/*else
	{
		$responce="";
		echo($responce);	
		
	}*/
?>