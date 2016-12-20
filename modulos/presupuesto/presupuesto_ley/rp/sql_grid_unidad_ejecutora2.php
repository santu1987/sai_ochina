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
//presupuesto_ley_pr_proyecto_id
//presupuesto_ley_pr_accion_central_id
$unidad =$_GET['unidad'];
//$unidad = $_POST['presupuesto_ley_aprobado_final_rp_unidad_codigo'];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(unidad_ejecutora.id_unidad_ejecutora) 
			FROM 
				unidad_ejecutora
			INNER JOIN 
				organismo 
			ON 
				unidad_ejecutora.id_organismo = organismo.id_organismo
			WHERE
				
			
				(unidad_ejecutora.id_organismo =".$_SESSION['id_organismo'].")
				and codigo_unidad_ejecutora = '".$unidad."'
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

// the actual query for the grid data
$Sql="
			SELECT 
				unidad_ejecutora.id_unidad_ejecutora,
				unidad_ejecutora.codigo_unidad_ejecutora,
				unidad_ejecutora.nombre AS  nombre			 
			FROM 
				unidad_ejecutora
			INNER JOIN 
				organismo 
			ON 
				unidad_ejecutora.id_organismo = organismo.id_organismo
			WHERE
				
				(unidad_ejecutora.id_organismo =".$_SESSION['id_organismo'].")
				and codigo_unidad_ejecutora = '".$unidad."'
			ORDER BY 
				unidad_ejecutora.codigo_unidad_ejecutora,$sidx $sord 
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
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_unidad_ejecutora");
	$responce =$row->fields("id_unidad_ejecutora")."*".  $row->fields("nombre");
	echo($responce);
}

// return the formated data
?>