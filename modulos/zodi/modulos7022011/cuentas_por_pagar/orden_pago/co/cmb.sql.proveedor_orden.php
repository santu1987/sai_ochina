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
$where="WHERE 1=1 
		AND	(orden_pago.id_organismo=$_SESSION[id_organismo] )

";
if($_GET['cuentas_por_pagar_orden_busqueda_proveedor']!="")
{
	$busq_proveedor_orden=strtolower($_GET['cuentas_por_pagar_orden_busqueda_proveedor']);
	$where.="AND (lower (proveedor.nombre) LIKE '%$busq_proveedor_orden%')";
}
if($_GET['cuentas_por_pagar_orden_busqueda_fecha']!="")
{
	$busq_fecha_orden=$_GET['cuentas_por_pagar_orden_busqueda_fecha'];
	$where.="AND (orden_pago.fecha_orden_pago='$busq_fecha_orden')";
}
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(orden_pago.id_orden_pago)
			FROM 
				orden_pago
			INNER JOIN	
				organismo 
			ON
				orden_pago.id_organismo=organismo.id_organismo 
			INNER JOIN 
				proveedor 
			ON
				orden_pago.id_proveedor=proveedor.id_proveedor				
		 ".$where."
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
					proveedor.codigo_proveedor,
					proveedor.nombre,
					proveedor.rif,
					orden_pago.orden_pago AS orden,
					orden_pago.id_orden_pago,
					orden_pago.documentos,
					orden_pago.ano,
					orden_pago.fecha_orden_pago,
					orden_pago.comentarios,
					orden_pago.estatus as estatus
				FROM 
					orden_pago
				INNER JOIN	
					organismo 
				ON
					orden_pago.id_organismo=organismo.id_organismo 
				INNER JOIN 
					proveedor 
				ON
					orden_pago.id_proveedor=proveedor.id_proveedor				
				".$where."
				ORDER BY 
					orden_pago.orden_pago
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

	$fecha=substr($row->fields("fecha_orden_pago"),0,10);
	$responce->rows[$i]['id']=$row->fields("id_orden_pago");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_orden_pago"),
															$row->fields("orden"),
															$row->fields("documentos"),
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															$row->fields("nombre"),
															$row->fields("rif"),
															$row->fields("ano"),
															$row->fields("fecha_orden_pago"),
															$row->fields("comentarios"),
															$fecha,
															$row->fields("estatus")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>