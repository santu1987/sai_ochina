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
$codigo = $_POST['cotizacones_pr_numero_cotizacion'];

$Sql="
			SELECT 
				count(\"solicitud_cotizacionE\".id_solicitud_cotizacione)
			FROM 
				\"solicitud_cotizacionE\"
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
				   proveedor.nombre AS proveedor
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
		
			WHERE
				(requisicion_encabezado.id_organismo =".$_SESSION['id_organismo'].")
			AND
				(\"solicitud_cotizacionE\".numero_cotizacion ='".$codigo."')

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

if (!$row->EOF) 
{
	if ($row->fields("id_proyecto") != 0)
		$sqlproyectoaccion="SELECT nombre,codigo_proyecto AS codigo FROM proyecto WHERE (id_proyecto =".$row->fields("id_proyecto").") ";
	
	if ($row->fields("id_accion_centralizada") != 0)
		$sqlproyectoaccion="SELECT denominacion AS nombre,codigo_accion_central AS codigo FROM accion_centralizada WHERE (id_accion_central =".$row->fields("id_accion_centralizada").") ";
		
	$rowproyectoaccion=& $conn->Execute($sqlproyectoaccion);
	
	$responce->rows[$i]['id']=$row->fields("numero_cotizacion");
	$responce =$row->fields("numero_cotizacion")."*".$row->fields("numero_requisicion")."*".  $row->fields("id_proyecto")."*".  $row->fields("id_accion_centralizada")."*".  $row->fields("id_accion_especifica")."*".  $row->fields("asunto")."*".$row->fields("comentario")."*".$row->fields("observacion")."*".$row->fields("accion_especifica")."*".$row->fields("codigo_accion_especifica")."*".$row->fields("id_proveedor")."*".$row->fields("codigo_proveedor")."*".$row->fields("proveedor")."*".$rowproyectoaccion->fields("codigo")."*".$rowproyectoaccion->fields("nombre");
echo ($responce);
//	  echo $json->encode($responce  );
    
}

?>