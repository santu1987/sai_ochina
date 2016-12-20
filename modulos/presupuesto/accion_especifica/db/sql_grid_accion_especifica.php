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
				count(accion_especifica.id_accion_especifica) 
			FROM 
				accion_especifica 
			INNER JOIN 
				organismo 
			ON 
				accion_especifica.id_organismo = organismo.id_organismo
			WHERE
				(accion_especifica.id_organismo =".$_SESSION['id_organismo'].")
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
				accion_especifica.id_accion_especifica,
				accion_especifica.denominacion AS nombreespe, 
				accion_especifica.comentario,
				accion_especifica.id_jefe_proyecto,
				jefe_proyecto.nombre_jefe_proyecto,
				accion_especifica.codigo_accion_especifica,
				accion_especifica.id_accion_central,
				accion_centralizada.codigo_accion_central,
				accion_centralizada.denominacion,
				proyecto.id_proyecto,
				proyecto.codigo_proyecto,
				proyecto.nombre
			FROM 
				accion_especifica 
			INNER JOIN 
				organismo 
			ON 
				accion_especifica.id_organismo = organismo.id_organismo
			INNER JOIN 
				jefe_proyecto 
			ON 
				accion_especifica.id_jefe_proyecto = jefe_proyecto.id_jefe_proyecto	
			LEFT JOIN 
				accion_centralizada 
			ON 
				accion_especifica.id_accion_central = accion_centralizada.id_accion_central
			LEFT JOIN 
				proyecto 
			ON 
				accion_especifica.id_proyecto = proyecto.id_proyecto
			WHERE
				(accion_especifica.id_organismo =".$_SESSION['id_organismo'].")
			ORDER BY 
				accion_especifica.codigo_accion_especifica,$sidx $sord 
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
	$responce->rows[$i]['id']=$row->fields("id_accion_especifica");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_accion_especifica"),
															$row->fields("codigo_accion_especifica"),
															substr($row->fields("nombreespe"),0,42),
															$row->fields("nombreespe"),
															$row->fields("comentario"),
															$row->fields("id_jefe_proyecto"),
															$row->fields("nombre_jefe_proyecto"),
															$row->fields("id_accion_central"),
															$row->fields("codigo_accion_central"),
															substr($row->fields("denominacion"),0,42),
															$row->fields("denominacion"),
															$row->fields("id_proyecto"),
															$row->fields("codigo_proyecto"),
															substr($row->fields("nombre"),0,42),
															$row->fields("nombre")																														
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>