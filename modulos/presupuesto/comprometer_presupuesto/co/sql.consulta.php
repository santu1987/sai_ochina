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
if(isset($_GET["unidad"]))
$busq_nombre =strtolower($_GET["unidad"]);
if(isset($_GET["orden"]))
$busq_partida =$_GET["orden"];

//************************************************************************
if(!$sidx) $sidx =1;
$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  ((lower(unidad_ejecutora.nombre) like '%$busq_nombre%') OR (lower(unidad_ejecutora.nombre) like '$busq_nombre%'))";

if($busq_partida!='')
	{	
	   
		$where.= " AND numero_orden_compra_servicio like '$busq_partida%'";
		
	}	
$Sql="
SELECT 
	count(id_orden_compra_servicioe)
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor

$where	
AND
	numero_compromiso  ='0'
AND
	numero_orden_compra_servicio <> '0'

";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
$limit = 15;
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
	id_orden_compra_servicioe, 
	id_tipo_documento, 
	\"orden_compra_servicioE\".id_proveedor,
	proveedor.nombre AS proveedor, 
	\"orden_compra_servicioE\".id_unidad_ejecutora, 
	unidad_ejecutora.nombre AS unidad_ejecutora,
	numero_cotizacion, 
	numero_requisicion, 
	numero_orden_compra_servicio, 
	numero_precompromiso,  
	usuario_elabora, 
	fecha_orden_compra_servicio,
	concepto, 
	orden_especial
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"orden_compra_servicioE\".id_proveedor

			".$where."
	AND		
		numero_compromiso  ='0'
AND
	numero_orden_compra_servicio <> '0'		
			ORDER BY 
				numero_orden_compra_servicio, numero_cotizacion, numero_requisicion,$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
if ($row->fields("orden_especial")==0)
	$nombreGrupo = "Normal";
if ($row->fields("orden_especial")==1)
	$nombreGrupo = "Especial";
	
	
$fe = split("-", $row->fields("fecha_orden_compra_servicio"));	
list($ano, $mes, $dia) = $fe;

$dia = substr($dia,0,2);
$fecha = $dia.'-'.$mes.'-'.$ano;
	$responce->rows[$i]['id']=$row->fields("id_orden_compra_servicioe");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_orden_compra_servicioe"),
															$row->fields("numero_orden_compra_servicio"),
															$row->fields("numero_cotizacion"),
															$row->fields("numero_requisicion"),
															$row->fields("unidad_ejecutora"),
															$fecha,
															$nombreGrupo
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>