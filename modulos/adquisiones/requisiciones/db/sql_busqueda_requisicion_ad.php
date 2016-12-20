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
$busq_requi ="";
if(isset($_GET["busq_requi"]))
$busq_requi = strtolower($_GET['busq_requi']);
$busq_asunt = "";
if(isset($_GET['busq_asunt']))
$busq_asunt = strtolower($_GET['busq_asunt']);
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1=1 ";
if($busq_requi!='')
	$where.= " AND  (lower(requisicion_encabezado.numero_requisicion) LIKE '%$busq_requi%') ";
if($busq_asunt!='')
	$where.= " AND (lower(requisicion_encabezado.asunto) LIKE '%$busq_asunt%') ";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_requisicion_encabezado) 
			FROM 
				requisicion_encabezado".$where."
			AND	
				(id_organismo =".$_SESSION['id_organismo'].")
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
				   requisicion_encabezado.numero_requisicion, 
				   requisicion_encabezado.ano,
				   requisicion_encabezado.id_unidad_ejecutora,
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
				   accion_especifica.codigo_accion_especifica,
				   unidad_ejecutora.nombre AS unidad_ejecutora,
				   unidad_ejecutora.codigo_unidad_ejecutora
			FROM 
				   requisicion_encabezado
			INNER JOIN
					accion_especifica
			ON
					requisicion_encabezado.id_accion_especifica = accion_especifica.id_accion_especifica
			INNER JOIN
					unidad_ejecutora
			ON
					requisicion_encabezado.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
				".$where;
$row=& $conn->Execute($Sql);
// constructing a JSON
/*$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;*/
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
//$fecha = substr($fecha, 0,10);
//$fecha = substr($fecha,8,2).substr($fecha,4,4).substr($fecha,0,4);
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
															$row->fields("id_proyecto"),
															$row->fields("id_accion_centralizada"),
															$row->fields("id_accion_especifica"),
															$row->fields("asunto"),
															$rowproyectoaccion->fields("codigo"),
															substr($rowproyectoaccion->fields("nombre"),0,40),
															$row->fields("prioridad"),
															$row->fields("comentario"),
															$row->fields("ano_csc"),
															$row->fields("id_tipo_documento"),
															$row->fields("observacion"),
															substr($row->fields("accion_especifica"),0,40),
															$row->fields("codigo_accion_especifica"),
															$row->fields("id_unidad_ejecutora"),
															substr($row->fields("unidad_ejecutora"),0,40),
															$row->fields("codigo_unidad_ejecutora")
														
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>