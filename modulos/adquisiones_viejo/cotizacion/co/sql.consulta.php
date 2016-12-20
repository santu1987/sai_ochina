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
$ano = date('Y');
if(!$sidx) $sidx =1;

$numero_requision = $_GET['numero_requision'];

$where = 'WHERE (1=1)';

if ($numero_requision != "")
	$where.= " AND (numero_cotizacion = '".$numero_requision."')";
else
	$where.= " AND (numero_cotizacion = '0') ";

$Sql="
			SELECT 
				count(id_solicitud_cotizacion) 
			FROM 
				\"solicitud_cotizacionD\"	
			$where	
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
	\"solicitud_cotizacionD\".id_solicitud_cotizacion,
	\"solicitud_cotizacionD\".secuencia,
	\"solicitud_cotizacionD\".cantidad, 	 
	\"solicitud_cotizacionD\".id_unidad_medida, 	 
	\"solicitud_cotizacionD\".descripcion,
	\"solicitud_cotizacionD\".numero_cotizacion,
	\"solicitud_cotizacionD\".monto,
	\"solicitud_cotizacionD\".impuesto,
	unidad_medida.nombre 
FROM 
	\"solicitud_cotizacionD\"
INNER JOIN
	unidad_medida
ON
	unidad_medida.id_unidad_medida = \"solicitud_cotizacionD\".id_unidad_medida

		$where	
		ORDER BY 
			$sidx $sord 
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
	$responce->rows[$i]['id']=$row->fields("secuencia");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_solicitud_cotizacion"),
															$row->fields("secuencia"),
															$row->fields("cantidad"),
															$row->fields("id_unidad_medida"),
															$row->fields("nombre"),
															$row->fields("descripcion"),
															$row->fields("numero_cotizacion"),
															number_format($row->fields("monto"),2,',','.'),
															number_format($row->fields("impuesto"),2,',','.')
															);
	$i++;
	$row->MoveNext();
}

// return the formated data
echo $json->encode($responce);
?>