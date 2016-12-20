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
if(isset($_GET['ano']))
$busq_ano = $_GET['ano'];
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1=1 
			AND 
			(\"orden_compra_servicioE\".orden_especial = 1) 
			 AND 
			(\"orden_compra_servicioE\".numero_compromiso = '0')
			 ";
			 /*
			 AND 
			(\"orden_compra_servicioE\".numero_compromiso = '0')
			 */
if($busq_requi!='')
	$where.= " AND  (lower(\"orden_compra_servicioE\".numero_orden_compra_servicio) = '$busq_requi') ";
/*if($busq_asunt!='')
	$where.= " AND (lower(\"orden_compra_servicioE\".concepto) LIKE '%$busq_asunt%') ";
if($busq_ano!='')
	$where.= " AND (\"orden_compra_servicioE\".ano = $busq_ano) ";	*/
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_orden_compra_servicioe) 
			FROM 
				\"orden_compra_servicioE\" ".$where."
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
				   \"orden_compra_servicioE\".numero_orden_compra_servicio, 
				    \"orden_compra_servicioE\".numero_precompromiso, 
				   \"orden_compra_servicioE\".ano,
				   \"orden_compra_servicioE\".id_unidad_ejecutora,
				   \"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
				   \"orden_compra_servicioE\".id_accion_especifica, 
				   \"orden_compra_servicioE\".concepto, 
				   \"orden_compra_servicioE\".comentarios, 
				   \"orden_compra_servicioE\".ano, 
				   \"orden_compra_servicioE\".id_tipo_documento,
				   accion_especifica.denominacion AS accion_especifica,
				   accion_especifica.codigo_accion_especifica,
				   unidad_ejecutora.nombre AS unidad_ejecutora,
				   unidad_ejecutora.codigo_unidad_ejecutora,
				   \"orden_compra_servicioE\".tipo,
				    \"orden_compra_servicioE\".id_proveedor,
					proveedor.codigo_proveedor,
					proveedor.nombre AS proveedor,
					custodio
			FROM 
				   \"orden_compra_servicioE\"
			INNER JOIN
					accion_especifica
			ON
					\"orden_compra_servicioE\".id_accion_especifica = accion_especifica.id_accion_especifica
			INNER JOIN
					unidad_ejecutora
			ON
					\"orden_compra_servicioE\".id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
			INNER JOIN
					proveedor
			ON
					\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor
				".$where."
			ORDER BY
					numero_orden_compra_servicio
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
//$fecha = substr($fecha, 0,10);
//$fecha = substr($fecha,8,2).substr($fecha,4,4).substr($fecha,0,4);
while (!$row->EOF) 
{
	if ($row->fields("tipo") == 1)
		$sqlproyectoaccion="SELECT nombre,codigo_proyecto AS codigo FROM proyecto WHERE (id_proyecto =".$row->fields("id_proyecto_accion_centralizada").") ";
	
	if ($row->fields("tipo") == 2)
		$sqlproyectoaccion="SELECT denominacion AS nombre,codigo_accion_central AS codigo FROM accion_centralizada WHERE (id_accion_central =".$row->fields("id_proyecto_accion_centralizada").") ";
	$rowproyectoaccion=& $conn->Execute($sqlproyectoaccion);
	
	if ($row->fields("custodio") != 0){
		$sqlcust="SELECT id_unidad_ejecutora,nombre AS unidad_ejecutora_cus,codigo_unidad_ejecutora AS codigo_cus FROM unidad_ejecutora WHERE (id_unidad_ejecutora =".$row->fields("custodio").") ";
		$rowcust=& $conn->Execute($sqlcust);
		$id_cus = $rowcust->fields("id_unidad_ejecutora");
		$codigo_cus = $rowcust->fields("codigo_cus");
		$unidad_ejecutora_cus = $rowcust->fields("unidad_ejecutora_cus");
	}else{
		$id_cus = 0;
		$codigo_cus = '0000';
		$unidad_ejecutora_cus = 'NO INDICADO';
	}
	
	$responce->rows[$i]['id']=$row->fields("numero_orden_compra_servicio");
	$responce->rows[$i]['cell']=array(	
													
															$row->fields("numero_orden_compra_servicio"),
															$row->fields("ano"),
															$row->fields("id_proyecto_accion_centralizada"),
															$row->fields("id_accion_especifica"),
															utf8_encode($row->fields("concepto")),
															$rowproyectoaccion->fields("codigo"),
															substr($rowproyectoaccion->fields("nombre"),0,40),
															$row->fields("prioridad"),
															$row->fields("comentarios"),
															$row->fields("ano"),
															$row->fields("id_tipo_documento"),
															substr($row->fields("accion_especifica"),0,40),
															$row->fields("codigo_accion_especifica"),
															$row->fields("id_unidad_ejecutora"),
															substr($row->fields("unidad_ejecutora"),0,40),
															$row->fields("codigo_unidad_ejecutora"),
															$row->fields("tipo"),
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															$row->fields("proveedor"),
															$id_cus,
															$codigo_cus,
															$unidad_ejecutora_cus ,
															$row->fields("numero_precompromiso")
														
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>