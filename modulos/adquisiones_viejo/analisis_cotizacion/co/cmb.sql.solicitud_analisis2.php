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
$unidad = $_GET['unidad'];
$requisicion = $_GET['requisicion'];
$secuencia = $_GET['secuencia'];

/*$secuencias ="";
//************************************************************************
//************************************************************************
if ($cotizacion != ""){
	$sql_co="SELECT 
		secuencia 
	FROM 
		\"solicitud_cotizacionD\"
	WHERE
		numero_requisicion = '$requisicion'
	AND
		numero_cotizacion = '$cotizacion'";
	$row_otro=& $conn->Execute($sql_co);
	
	if(!$row_otro->EOF)
	{
	
		while (!$row_otro->EOF){
			if($secuencias == "")
				$secuencias = $row_otro->fields("secuencia");
			else
				$secuencias = $secuencias.','. $row_otro->fields("secuencia");
		$row_otro->MoveNext();
		}
	}
}*/
//************************************************************************
//************************************************************************

$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
SELECT 
	count(\"solicitud_cotizacionE\".id_solicitud_cotizacione)
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
WHERE
	\"solicitud_cotizacionE\".id_organismo = $_SESSION[id_organismo]
AND
	\"solicitud_cotizacionE\".ano = '2009'
AND
	(\"solicitud_cotizacionE\".id_requisicion = $requisicion)	
";
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query echo($Sql);
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
	\"solicitud_cotizacionE\".id_solicitud_cotizacione, 
	\"solicitud_cotizacionE\".numero_cotizacion, 
	\"solicitud_cotizacionE\".id_proveedor, 
	proveedor.nombre AS proveedor,
	\"solicitud_cotizacionE\".id_requisicion, 
	\"solicitud_cotizacionE\".titulo, 
	\"solicitud_cotizacionE\".tiempo_entrega, 
	\"solicitud_cotizacionE\".lugar_entrega, 
	\"solicitud_cotizacionE\".condiciones_pago, 
	\"solicitud_cotizacionE\".validez_oferta,
	\"solicitud_cotizacionD\".secuencia,
	\"solicitud_cotizacionD\".cantidad,
	\"solicitud_cotizacionD\".monto 
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
WHERE
	\"solicitud_cotizacionE\".id_organismo = $_SESSION[id_organismo]
AND
	\"solicitud_cotizacionE\".ano = '2009'
AND
	(\"solicitud_cotizacionE\".id_requisicion = $requisicion)	
AND
	(\"solicitud_cotizacionD\".secuencia = $secuencia)	
ORDER BY
	id_solicitud_cotizacione
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$valor="";
$valor2="";
while (!$row->EOF) 
{

	//$responce->rows[$i]['id']=$row->fields("id_solicitud_cotizacione");

	//$responce->rows[$i]['cell']=array(	
	//if ()
	$valor = $row->fields("cantidad")*$row->fields("monto");
	
	/*
															echo'<br>'.
															$row->fields("id_solicitud_cotizacione").','.
															$row->fields("numero_cotizacion").','.
															$row->fields("id_proveedor").','.
															$row->fields("proveedor").','.
															$row->fields("titulo").','.
															$row->fields("tiempo_entrega").','.
															$row->fields("lugar_entrega").','.
															$row->fields("condiciones_pago").','.
															$row->fields("validez_oferta").','.
															$row->fields("secuencia").','.
															$valor;
															if ($valor2 == "")
																$valor2 = $valor;
															elseif($valor<$valor2)*/
	//													);
	$i++;
	$row->MoveNext();
}
// return the formated data
//echo $json->encode($responce);
?>