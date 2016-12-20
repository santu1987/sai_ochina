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
$codigo = $_POST['ordenes_pr_nro_cotizacion'];
$Sql="
			SELECT 
				count(\"solicitud_cotizacionE\".id_solicitud_cotizacione)
			FROM 
				\"solicitud_cotizacionE\"
			
			INNER JOIN
				proveedor
			ON
				\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
			INNER JOIN 
				organismo 
			ON
				\"solicitud_cotizacionE\".id_organismo=organismo.id_organismo 
			INNER JOIN 
				unidad_ejecutora 
			ON
				\"solicitud_cotizacionE\".id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora 
				
				WHERE 
					(\"solicitud_cotizacionE\".id_organismo=$_SESSION[id_organismo] )
				AND
					(\"solicitud_cotizacionE\".numero_cotizacion= '".$codigo."')

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
				proveedor.id_proveedor, 
				proveedor.nombre AS proveedor, 
				unidad_ejecutora.id_unidad_ejecutora,
				unidad_ejecutora.nombre AS unidad_ejecutora,
				\"solicitud_cotizacionE\".numero_cotizacion,
				(SELECT  numero_requisicion  FROM requisicion_encabezado
				WHERE (id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion) 
				)AS numero_requisicion
			FROM 
				\"solicitud_cotizacionE\"
			
			INNER JOIN
				proveedor
			ON
				\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
			INNER JOIN 
				organismo 
			ON
				\"solicitud_cotizacionE\".id_organismo=organismo.id_organismo 
			INNER JOIN 
				unidad_ejecutora 
			ON
				\"solicitud_cotizacionE\".id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora
			WHERE 
				(\"solicitud_cotizacionE\".id_organismo=$_SESSION[id_organismo] )
			AND
			(\"solicitud_cotizacionE\".numero_cotizacion= '".$codigo."')	
			ORDER BY 
				\"solicitud_cotizacionE\".numero_cotizacion
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("numero_cotizacion");
	$responce =$row->fields("numero_cotizacion")."*".  $row->fields("id_proveedor")."*".  $row->fields("proveedor")."*".  $row->fields("id_unidad_ejecutora")."*".  $row->fields("unidad_ejecutora")."*".  $row->fields("numero_requisicion");
echo ($responce);
//	  echo $json->encode($responce  );
    
}

?>