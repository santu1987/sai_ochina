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
/*$secuencias ="";
//************************************************************************
//************************************************************************
*/

	
$sql_contar="
	SELECT 
		count(id_parametro_analisis_cotizacion)
	FROM 
		parametro_analisis_cotizacion
	ORDER BY
		id_parametro_analisis_cotizacion";

$row_contar=& $conn->Execute($sql_contar);	
	
	/*if(!$row_otro->EOF)
	{
	
		while (!$row_otro->EOF){
			if($secuencias == "")
				$secuencias = $row_otro->fields("secuencia");
			else
				$secuencias = $secuencias.','. $row_otro->fields("secuencia");
		$row_otro->MoveNext();
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

if (!$row_contar->EOF)
{
	$count = $row_contar->fields("count");
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
	\"solicitud_cotizacionE\".garantia,
	(SELECT SUM(monto) 
	FROM \"solicitud_cotizacionD\"
	WHERE \"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion)AS monto,
	(SELECT SUM(cantidad) 
	FROM \"solicitud_cotizacionD\"
	WHERE \"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion)AS cantidad
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
ORDER BY
	id_solicitud_cotizacione
	
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
// constructing a JSON

$sql_co="
	SELECT 
		id_parametro_analisis_cotizacion, 
		id_organismo, 
		aspecto, 
		peso
	FROM 
		parametro_analisis_cotizacion
	ORDER BY
		id_parametro_analisis_cotizacion";
$row_otro=& $conn->Execute($sql_co);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row_otro->EOF) 
{

	$responce->rows[$i]['id']=$row->fields("id_parametro_analisis_cotizacion");

	$responce->rows[$i]['cell']=array(	
															$row_otro->fields("id_parametro_analisis_cotizacion"),
															$row->fields("aspecto"),
															while (!$row->EOF) 
															{
																$row->fields("monto")*$row->fields("cantidad"),
															$row->MoveNext();
															}
															/*$row->MoveFirst();
															$row->fields("proveedor"),
															$row->fields("titulo"),
															$row->fields("tiempo_entrega"),
															$row->fields("lugar_entrega"),
															$row->fields("condiciones_pago"),
															$row->fields("validez_oferta")*/
															
															
														);
	$i++;
	$row_otro->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>