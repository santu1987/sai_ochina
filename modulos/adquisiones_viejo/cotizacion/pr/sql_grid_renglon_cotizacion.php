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
$nro = $_POST['cargar_cotizacion_pr_numero_cotizacion'];
/*if(isset($_POST["busq_nombre"]))
{*/
	$codigo=$_POST['cargar_cotizacion_pr_renglon_codigo'];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				count(\"solicitud_cotizacionD\".numero_cotizacion)
			FROM 
				\"solicitud_cotizacionD\"
			INNER JOIN
				unidad_medida
			ON
				\"solicitud_cotizacionD\".id_unidad_medida = unidad_medida.id_unidad_medida
				
			WHERE 
				(\"solicitud_cotizacionD\".id_organismo=$_SESSION[id_organismo] )
			AND 
				(\"solicitud_cotizacionD\".numero_cotizacion='$nro' )
			AND
				(\"solicitud_cotizacionD\".secuencia='$codigo')	

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
			\"solicitud_cotizacionD\".id_solicitud_cotizacion, 
			\"solicitud_cotizacionD\".numero_cotizacion, 
			\"solicitud_cotizacionD\".secuencia, 
			\"solicitud_cotizacionD\".cantidad, 
			\"solicitud_cotizacionD\".id_unidad_medida,
			unidad_medida.nombre AS unidad_medida, 
			\"solicitud_cotizacionD\".descripcion, 
			\"solicitud_cotizacionD\".monto
		FROM 
			\"solicitud_cotizacionD\"
		INNER JOIN
			unidad_medida
		ON
			\"solicitud_cotizacionD\".id_unidad_medida = unidad_medida.id_unidad_medida
		WHERE 
			(\"solicitud_cotizacionD\".id_organismo=$_SESSION[id_organismo] )
		AND 
			(\"solicitud_cotizacionD\".numero_cotizacion='$nro' )
		AND
			(\"solicitud_cotizacionD\".secuencia='$codigo')	
		ORDER BY 
			\"solicitud_cotizacionD\".numero_cotizacion
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_solicitud_cotizacion");
	$responce=$row->fields("id_solicitud_cotizacion").'*'.$row->fields("numero_cotizacion").'*'.$row->fields("secuencia").'*'.$row->fields("cantidad").'*'.$row->fields("unidad_medida").'*'.$row->fields("descripcion").'*'.$row->fields("monto").'*';
}
else
	$responce="";
//die($Sql);
// return the formated data
//echo $json->encode($responce);
echo($responce);

?>