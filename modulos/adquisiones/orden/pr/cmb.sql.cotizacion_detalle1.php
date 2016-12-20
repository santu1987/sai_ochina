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
$cotizacion = $_GET['cotizacion'];

//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
//********************************************************************************
/*$sql_busrequi = "
SELECT 
	distinct requisicion_detalle.numero_requision
FROM 
	\"solicitud_cotizacionD\" 
INNER JOIN 
	requisicion_detalle 
ON
	requisicion_detalle.numero_requision = \"solicitud_cotizacionD\".numero_requisicion 
INNER JOIN 
	unidad_medida 
ON
	\"solicitud_cotizacionD\".id_unidad_medida = unidad_medida.id_unidad_medida 
WHERE 
	(\"solicitud_cotizacionD\".id_organismo=1)
AND
	(\"solicitud_cotizacionD\".numero_cotizacion = '$cotizacion')
ORDER BY 
	requisicion_detalle.numero_requision
"
$busrequi=& $conn->Execute($sql_busrequi);	

$Sql33="SELECT 
	distinct requisicion_detalle.numero_requision,
	\"solicitud_cotizacionD\".id_solicitud_cotizacion,
	\"solicitud_cotizacionD\".numero_cotizacion ,
	\"solicitud_cotizacionD\".secuencia,
	\"solicitud_cotizacionD\".cantidad,
	unidad_medida.id_unidad_medida,
	unidad_medida.nombre,
	\"solicitud_cotizacionD\".descripcion,
	\"solicitud_cotizacionD\".monto,
	\"solicitud_cotizacionD\".impuesto,
	\"solicitud_cotizacionD\".partida,
	\"solicitud_cotizacionD\".generica,
	\"solicitud_cotizacionD\".especifica,
	\"solicitud_cotizacionD\".subespecifica
FROM 

	\"solicitud_cotizacionD\" 
INNER JOIN 
	requisicion_detalle 
ON
	requisicion_detalle.numero_requision = \"solicitud_cotizacionD\".numero_requisicion 
INNER JOIN 
	unidad_medida 
ON
	\"solicitud_cotizacionD\".id_unidad_medida = unidad_medida.id_unidad_medida 
WHERE 
	(\"solicitud_cotizacionD\".id_organismo=1)
AND
	(requisicion_detalle.numero_requision = '090001')
ORDER BY 
	requisicion_detalle.numero_requision";
*/
//*****************************************************************

$Sql="
			SELECT 
				count(\"solicitud_cotizacionD\".id_solicitud_cotizacion)
			FROM 
					organismo 
				INNER JOIN 
					\"solicitud_cotizacionD\" 
				ON
					\"solicitud_cotizacionD\".id_organismo=organismo.id_organismo 
				INNER JOIN
					requisicion_detalle
				ON
					(
						\"solicitud_cotizacionD\".secuencia = requisicion_detalle.secuencia
					AND
						\"solicitud_cotizacionD\".numero_requisicion = requisicion_detalle.numero_requision
					AND
						requisicion_detalle.numero_cotizacion = '0'
					)
				WHERE 
					(\"solicitud_cotizacionD\".id_organismo=$_SESSION[id_organismo] )

				AND
					(\"solicitud_cotizacionD\".numero_cotizacion = '$cotizacion')	
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
					\"solicitud_cotizacionD\".secuencia,
					\"solicitud_cotizacionD\".cantidad,
					unidad_medida.id_unidad_medida,
					unidad_medida.nombre,
					\"solicitud_cotizacionD\".descripcion,
					\"solicitud_cotizacionD\".monto,
					\"solicitud_cotizacionD\".impuesto,
					\"solicitud_cotizacionD\".partida,
					\"solicitud_cotizacionD\".generica,
					\"solicitud_cotizacionD\".especifica,
					\"solicitud_cotizacionD\".subespecifica
				FROM 
					organismo 
				INNER JOIN 
					\"solicitud_cotizacionD\" 
				ON
					\"solicitud_cotizacionD\".id_organismo=organismo.id_organismo 
				INNER JOIN 
					unidad_medida 
				ON
					\"solicitud_cotizacionD\".id_unidad_medida = unidad_medida.id_unidad_medida 
				INNER JOIN
					requisicion_detalle
				ON
					(
						\"solicitud_cotizacionD\".secuencia = requisicion_detalle.secuencia
					AND
						\"solicitud_cotizacionD\".numero_requisicion = requisicion_detalle.numero_requision
					AND
						requisicion_detalle.numero_cotizacion = '0'
					)
				WHERE 
					(\"solicitud_cotizacionD\".id_organismo=$_SESSION[id_organismo] )
				AND
					(\"solicitud_cotizacionD\".numero_cotizacion = '$cotizacion')	
				ORDER BY 
					\"solicitud_cotizacionD\".secuencia 
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;$monto=0;
while (!$row->EOF) 
{
	$monto +=$row->fields("monto");
	$m_total = $row->fields("monto") * $row->fields("cantidad");
	$responce->rows[$i]['id']=$row->fields("id_solicitud_cotizacion");

	$responce->rows[$i]['cell']=array(	
										$row->fields("id_solicitud_cotizacion"),
										$row->fields("secuencia"),
										$row->fields("cantidad"),
										$row->fields("cantidad"),
										$row->fields("id_unidad_medida"),
										$row->fields("nombre"),
										$row->fields("descripcion"),
										number_format($row->fields("monto"),2,',','.'),
										number_format($row->fields("monto"),2,',','.'),
										number_format($m_total,2,',','.'),
										number_format($row->fields("impuesto"),2,',','.'),
										$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica")
									);
	$i++;
	$row->MoveNext();
}
$responce->userdata['monto']=$monto;
$responce->userdata['descripcion']='Total';
// return the formated data
echo $json->encode($responce);
?>