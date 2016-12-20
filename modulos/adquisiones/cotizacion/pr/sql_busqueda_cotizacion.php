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
//************************************************************************
$limit = 15;
$busq_nombre ="";
if(isset($_GET["busq_nombre"]))
$busq_nombre = strtolower($_GET['busq_nombre']);
$busq_coti ="";
if(isset($_GET["busq_coti"]))
$busq_coti= strtolower($_GET['busq_coti']);
if(isset($_GET["busq_fecha"]))
$busq_fecha= strtolower($_GET['busq_fecha']);
$busq_prove = "";
if(isset($_GET['busq_prove']))
$busq_prove = strtolower($_GET['busq_prove']);
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1=1 ";
if($busq_nombre!='')
	$where.= " AND  (lower(requisicion_encabezado.numero_requisicion) LIKE '%$busq_nombre%') ";
if($busq_coti!='')
	$where.= " AND  (lower( \"solicitud_cotizacionE\".numero_cotizacion) LIKE '%$busq_coti%') ";
if($busq_fecha!='')
	$where.= " AND  ( \"solicitud_cotizacionE\".fecha_elabora_solicitud) = '%$busq_fecha%' ";
if($busq_prove)
	$where.= " AND (lower(proveedor.nombre) LIKE '%$busq_prove%') ";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_requisicion_encabezado) 
			FROM 
				   requisicion_encabezado
			INNER JOIN
					accion_especifica
			ON
					requisicion_encabezado.id_accion_especifica = accion_especifica.id_accion_especifica
			INNER JOIN
					\"solicitud_cotizacionE\"
			ON
					requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
			INNER JOIN
					proveedor
			ON
					proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
".$where;

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
				   \"solicitud_cotizacionE\".id_solicitud_cotizacione, 
				   \"solicitud_cotizacionE\".numero_cotizacion,
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
				   proveedor.id_proveedor,
				   proveedor.codigo_proveedor,
				   proveedor.nombre AS proveedor,
				   \"solicitud_cotizacionE\".fecha_elabora_solicitud
				   
			FROM 
				   requisicion_encabezado
			INNER JOIN
					accion_especifica
			ON
					requisicion_encabezado.id_accion_especifica = accion_especifica.id_accion_especifica
			INNER JOIN
					\"solicitud_cotizacionE\"
			ON
					requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
			INNER JOIN
					proveedor
			ON
					proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor ". $where." ORDER BY requisicion_encabezado.numero_requisicion desc, \"solicitud_cotizacionE\".numero_cotizacion asc";
				
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
$fecha = $row->fields("fecha_elabora_solicitud");
$fecha = substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);
	$responce->rows[$i]['id']=$row->fields("id_solicitud_cotizacione");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_solicitud_cotizacione"),
															$row->fields("numero_requisicion"),
															$row->fields("numero_cotizacion"),
															$row->fields("ano"),
															substr($row->fields("asunto"),0,50),
															$row->fields("id_proyecto"),
															$rowproyectoaccion->fields("codigo"),
															substr($rowproyectoaccion->fields("nombre"),0,40),
															/*$rowproyectoaccion->fields("nombre"),*/
															$row->fields("id_accion_centralizada"),
															$row->fields("prioridad"),
															$row->fields("comentario"),
															$row->fields("id_accion_especifica"),
															$row->fields("codigo_accion_especifica"),
															$row->fields("accion_especifica"),
															$row->fields("observacion"),
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															$row->fields("proveedor"),
															$fecha	
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>
