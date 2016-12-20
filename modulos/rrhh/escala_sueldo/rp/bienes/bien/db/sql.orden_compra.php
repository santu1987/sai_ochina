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
$busq_orden ="";
if(isset($_GET["busq_orden"]))
$busq_orden = strtolower($_GET['busq_orden']);
$where = "WHERE 1=1";
if($busq_orden!='')
	$where.= " AND  (lower(descripcion) LIKE '%$busq_orden%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT  distinct
				count(id_orden_compra_servicioe) 
			FROM 
				\"orden_compra_servicioE\" 
			INNER JOIN
				\"orden_compra_servicioD\"
			ON
				\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
			INNER JOIN
				documentos_cxp
			ON
				documentos_cxp.numero_compromiso=\"orden_compra_servicioE\".numero_compromiso
				".$where."
			AND
				numero_orden_compra_servicio LIKE '404%'
			AND
				\"orden_compra_servicioE\".numero_compromiso != '0'
			AND	
				\"orden_compra_servicioE\".numero_compromiso != ''
			AND
				\"orden_compra_servicioE\".id_organismo = $_SESSION[id_organismo]
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
			SELECT  distinct
				id_orden_compra_servicioe as id_orden,
				numero_orden_compra_servicio as orden,
				concepto,
				numero_documento,
				\"orden_compra_servicioE\".ano,
				\"orden_compra_servicioE\".numero_pre_orden
			FROM 
				\"orden_compra_servicioE\" 	
			INNER JOIN
				\"orden_compra_servicioD\"
			ON
				\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
			INNER JOIN
				documentos_cxp
			ON
				documentos_cxp.numero_compromiso=\"orden_compra_servicioE\".numero_compromiso
			".$where." 
			AND
				numero_orden_compra_servicio LIKE '404%'
			AND
				\"orden_compra_servicioE\".numero_compromiso != '0'
			AND
				\"orden_compra_servicioE\".numero_compromiso != ''
			AND
				\"orden_compra_servicioE\".id_organismo = $_SESSION[id_organismo]
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start
				";
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
while (!$row->EOF) 
{
	//
	$id = $row->fields("id_orden");
	$numero = $row->fields("numero_pre_orden");
	$sql_cant="
			SELECT  SUM(\"orden_compra_servicioD\".cantidad) as total
			FROM 
				\"orden_compra_servicioD\"
			INNER JOIN 
				\"orden_compra_servicioE\"
			ON
				\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
	where
				\"orden_compra_servicioD\".numero_pre_orden = '".$numero."'
			AND
				\"orden_compra_servicioD\".id_organismo = $_SESSION[id_organismo]
				";
	$row2=& $conn->Execute($sql_cant);
	$sql_bien = "SELECT  COUNT(numero_pre_orden) as bien
			FROM 
				bienes
			INNER JOIN 
				\"orden_compra_servicioE\"
			ON
				\"orden_compra_servicioE\".id_orden_compra_servicioe = bienes.id_orden_compra_servicioe
			where
				\"orden_compra_servicioE\".id_orden_compra_servicioe = ".$id."
			AND
				\"orden_compra_servicioE\".numero_pre_orden = '".$numero."'
			AND
				\"orden_compra_servicioE\".id_organismo = $_SESSION[id_organismo]"; 
	$row3=& $conn->Execute($sql_bien);
	$total=$row2->fields("total") - $row3->fields("bien");
	
	//
	if($total>0){
	$responce->rows[$i]['id']=$row->fields("id_orden_compra_servicioe");
	
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_orden"),
															$row->fields("orden"),
															$row->fields("numero_documento"),
															$row->fields("concepto"),
															$row->fields("ano"),
															$row2->fields("total"),
															$total
														);
	$i++;
	}
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>