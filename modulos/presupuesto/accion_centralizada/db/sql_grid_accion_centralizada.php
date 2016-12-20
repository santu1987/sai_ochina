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

$Sql="
			SELECT 
				count(accion_centralizada.id_accion_central) 
			FROM 
				accion_centralizada 
			INNER JOIN 
				organismo 
			ON 
				accion_centralizada.id_organismo = organismo.id_organismo
			WHERE
				(organismo.id_organismo =".$_SESSION['id_organismo'].")
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
				accion_centralizada.id_accion_central,
				accion_centralizada.denominacion, 
				accion_centralizada.comentario,
				accion_centralizada.codigo_accion_central,
				jefe_proyecto.nombre_jefe_proyecto,
				jefe_proyecto.id_jefe_proyecto
			FROM 
				accion_centralizada 
			INNER JOIN 
				organismo 
			ON 
				accion_centralizada.id_organismo = organismo.id_organismo
			INNER JOIN 
				jefe_proyecto 
			ON 
				accion_centralizada.id_jefe_proyecto = jefe_proyecto.id_jefe_proyecto
			WHERE
				(organismo.id_organismo =".$_SESSION['id_organismo'].")
			ORDER BY 
				accion_centralizada.id_accion_central,$sidx $sord 
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
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_accion_central"),
															$row->fields("codigo_accion_central"),
															substr($row->fields("denominacion"),0,40),
															$row->fields("denominacion"),
															$row->fields("comentario"),
															$row->fields("nombre_jefe_proyecto"),
															$row->fields("id_jefe_proyecto")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>