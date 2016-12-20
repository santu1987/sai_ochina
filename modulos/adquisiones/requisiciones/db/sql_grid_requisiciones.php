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
				count(id_requisicion_encabezado) 
			FROM 
				requisicion_encabezado 
			WHERE
				(id_organismo =".$_SESSION['id_organismo'].")
			AND
				(id_unidad_ejecutora =".$_SESSION['id_unidad_ejecutora'].")
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
				   requisicion_encabezado.ano, 
				   requisicion_encabezado.numero_requisicion, 
				   requisicion_encabezado.id_proyecto, 
				   requisicion_encabezado.id_accion_centralizada, 
				   requisicion_encabezado.id_accion_especifica, 
				   requisicion_encabezado.asunto, 
				   requisicion_encabezado.prioridad, 
				   requisicion_encabezado.comentario, 
				   requisicion_encabezado.ano_csc, 
				   requisicion_encabezado.id_tipo_documento,
				   requisicion_encabezado.observacion,
				   accion_especifica.denominacion AS accion_especifica,
				   accion_especifica.codigo_accion_especifica
			FROM 
				   requisicion_encabezado
			INNER JOIN
					accion_especifica
			ON
					requisicion_encabezado.id_accion_especifica = accion_especifica.id_accion_especifica
		
			WHERE
				(requisicion_encabezado.id_organismo =".$_SESSION['id_organismo'].")
			AND
				(requisicion_encabezado.id_unidad_ejecutora =".$_SESSION['id_unidad_ejecutora'].")

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
	if ($row->fields("id_proyecto") != 0)
		$sqlproyectoaccion="SELECT nombre,codigo_proyecto AS codigo FROM proyecto WHERE (id_proyecto =".$row->fields("id_proyecto").") ";
	
	if ($row->fields("id_accion_centralizada") != 0)
		$sqlproyectoaccion="SELECT denominacion AS nombre,codigo_accion_central AS codigo FROM accion_centralizada WHERE (id_accion_central =".$row->fields("id_accion_centralizada").") ";
		
	$rowproyectoaccion=& $conn->Execute($sqlproyectoaccion);
	
	
	$responce->rows[$i]['id']=$row->fields("numero_requisicion");

	$responce->rows[$i]['cell']=array(	
															$row->fields("numero_requisicion"),
															$row->fields("ano"),
															substr($row->fields("asunto"),0,30),
															$row->fields("id_proyecto"),
															$rowproyectoaccion->fields("codigo"),
															substr($rowproyectoaccion->fields("nombre"),0,55),
															$rowproyectoaccion->fields("nombre"),
															$row->fields("id_accion_centralizada"),
															$row->fields("prioridad"),
															$row->fields("comentario"),
															$row->fields("id_accion_especifica"),
															$row->fields("codigo_accion_especifica"),
															substr($row->fields("accion_especifica"),0,52),
															$row->fields("observacion")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>