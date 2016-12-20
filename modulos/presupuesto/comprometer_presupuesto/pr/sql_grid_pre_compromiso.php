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
$pre_compromiso = $_POST['compromiso_pr_pre_compromiso'];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(\"orden_compra_servicioE\".id_orden_compra_servicioe)
			FROM 
				\"orden_compra_servicioE\"
			
			INNER JOIN
				proveedor
			ON
				\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor
			INNER JOIN 
				organismo 
			ON
				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo 
			INNER JOIN 
				unidad_ejecutora 
			ON
				\"orden_compra_servicioE\".id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora 
			WHERE 
				(\"orden_compra_servicioE\".id_organismo=$_SESSION[id_organismo] )
			AND
				(\"orden_compra_servicioE\".numero_pre_orden = '$pre_compromiso' )
				

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
				numero_precompromiso,
				numero_orden_compra_servicio,
				numero_cotizacion
			FROM 
				\"orden_compra_servicioE\"
			
			INNER JOIN
				proveedor
			ON
				\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor
			INNER JOIN 
				organismo 
			ON
				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo 
			INNER JOIN 
				unidad_ejecutora 
			ON
				\"orden_compra_servicioE\".id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora
			WHERE 
				(\"orden_compra_servicioE\".id_organismo=$_SESSION[id_organismo] )
			
			AND
				(\"orden_compra_servicioE\".numero_pre_orden = '$pre_compromiso' )
			ORDER BY 
				\"orden_compra_servicioE\".numero_precompromiso
";
$row=& $conn->Execute($Sql);


// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("numero_precompromiso");
	$responce =$row->fields("numero_precompromiso")."*".  $row->fields("numero_orden_compra_servicio");
	echo($responce);
}
// return the formated data
?>