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
$cotizacion = $_GET['cotizacion'];



if ($accion == "")
	$accion = 0;
if ($proyecto == "")
	$proyecto = 0;
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$primero ="
SELECT 
	\"solicitud_cotizacionE\".id_unidad_ejecutora, 
	\"solicitud_cotizacionE\".id_requisicion, 
	\"solicitud_cotizacionE\".id_tipo_documento, 
	numero_cotizacion,
	id_proyecto,
	id_accion_centralizada,
	id_accion_especifica
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	requisicion_encabezado
ON
	requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
WHERE
	numero_cotizacion = '$cotizacion'
";
$row_primero=& $conn->Execute($primero);
if (!$row_primero->EOF)
{
	

	$Sql="
				SELECT 
					count(DISTINCT id_clasi_presu)
				FROM 
					clasificador_presupuestario
				INNER JOIN
					presupuesto_ley
				ON
					clasificador_presupuestario.partida = presupuesto_ley.partida
					AND
					clasificador_presupuestario.generica = presupuesto_ley.generica
					AND
					clasificador_presupuestario.especifica = presupuesto_ley.especifica
					AND
					clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica 
				WHERE
					(presupuesto_ley.id_unidad_ejecutora = ".$row_primero->fields("id_unidad_ejecutora").")
					AND
					(presupuesto_ley.id_accion_especifica =".$row_primero->fields("id_accion_especifica").")
					AND
					(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
					AND
					(presupuesto_ley.id_accion_central =".$row_primero->fields("id_accion_centralizada").")
					AND
					(presupuesto_ley.id_proyecto=".$row_primero->fields("id_proyecto").")
					AND
					(presupuesto_ley.partida='".$row_primero->fields("id_tipo_documento")."')
	";
	//echo $Sql;
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
					DISTINCT clasificador_presupuestario.id_clasi_presu, clasificador_presupuestario.denominacion,  presupuesto_ley.partida, 
					presupuesto_ley.generica, presupuesto_ley.especifica, presupuesto_ley.sub_especifica
				FROM 
					clasificador_presupuestario
				INNER JOIN
					presupuesto_ley
				ON
					clasificador_presupuestario.partida = presupuesto_ley.partida
					AND
					clasificador_presupuestario.generica = presupuesto_ley.generica
					AND
					clasificador_presupuestario.especifica = presupuesto_ley.especifica
					AND
					clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica 
				WHERE
					(presupuesto_ley.id_unidad_ejecutora = ".$row_primero->fields("id_unidad_ejecutora").")
					AND
					(presupuesto_ley.id_accion_especifica =".$row_primero->fields("id_accion_especifica").")
					AND
					(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
					AND
					(presupuesto_ley.id_accion_central =".$row_primero->fields("id_accion_centralizada").")
					AND
					(presupuesto_ley.id_proyecto=".$row_primero->fields("id_proyecto").")
					AND
					(presupuesto_ley.partida='".$row_primero->fields("id_tipo_documento")."')
				ORDER BY 
					partida, generica, especifica, sub_especifica
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
	$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
	
		$responce->rows[$i]['id']=$row->fields("id_clasi_presu");
	
		$responce->rows[$i]['cell']=array(	
																$row->fields("id_clasi_presu"),
																$partida,
																$row->fields("denominacion")
															);
		$i++;
		$row->MoveNext();
	}
}
// return the formated data
echo $json->encode($responce);
?>