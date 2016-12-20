<?
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
$central = $_GET['central'];
$proyecto = $_GET['proyecto'];
$especifica = $_GET['especifica'];
$partida = $_GET['partida'];
$busqueda_anio=$_GET['busqueda_anio'];
//************************************************************************
if($unidad <> "" or $unidad <> 0)
	$where = " AND (presupuesto_ley.id_unidad_ejecutora = $unidad)";
if($central <> "" or $central <> 0)
	$where = $where." AND (presupuesto_ley.id_accion_central = $central)";
if($proyecto <> "" or $central <> 0)
	$where = $where." AND (presupuesto_ley.id_proyecto = $proyecto)";
if($especifica <> "" or $central <> 0)
	$where = $where." AND (presupuesto_ley.id_accion_especifica = $especifica)";
if($busqueda_anio <> "" or $busqueda_anio <> 0)
	$where = $where." AND (presupuesto_ley.anio = '$busqueda_anio')";	
//************************************************************************

	$contar = "SELECT 
		count(id_presupuesto_ley)
	FROM 
		presupuesto_ley
	INNER JOIN
		unidad_ejecutora
	ON
		unidad_ejecutora.id_unidad_ejecutora = presupuesto_ley.id_unidad_ejecutora
	LEFT JOIN
		accion_centralizada
	ON
		accion_centralizada.id_accion_central = presupuesto_ley.id_accion_central
	LEFT JOIN
		proyecto
	ON
		proyecto.id_proyecto = presupuesto_ley.id_proyecto
	INNER JOIN
		accion_especifica
	ON
		accion_especifica.id_accion_especifica = presupuesto_ley.id_accion_especifica
	INNER JOIN
		clasificador_presupuestario
	ON
		(clasificador_presupuestario.partida = presupuesto_ley.partida
		AND
		clasificador_presupuestario.generica = presupuesto_ley.generica
		AND
		clasificador_presupuestario.especifica = presupuesto_ley.especifica
		AND
		clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
		)
	WHERE
		1=1
		".$where;
	$recorset_contar=& $conn->Execute($contar);
	if (!$recorset_contar->EOF)
	{
		$count = /*$recorset_contar->fields("count")*/0;
	}
//************************************************************************
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
//************************************************************************

$sql = "SELECT 
	id_presupuesto_ley,
	anio,
	presupuesto_ley.id_unidad_ejecutora,unidad_ejecutora.codigo_unidad_ejecutora , unidad_ejecutora.nombre AS unidad_ejecutora ,
	presupuesto_ley.id_accion_central, accion_centralizada.codigo_accion_central, accion_centralizada.denominacion,
	presupuesto_ley.id_proyecto, proyecto.codigo_proyecto , proyecto.nombre AS proyecto ,
	presupuesto_ley.id_accion_especifica, accion_especifica.codigo_accion_especifica , accion_especifica.denominacion AS accion_especifica,  
	presupuesto_ley.partida, presupuesto_ley.generica, presupuesto_ley.especifica, presupuesto_ley.sub_especifica, clasificador_presupuestario.denominacion AS clasificador_presupuestario,
	enero, febrero, marzo, abril, mayo, junio, 
	julio, agosto, septiembre, octubre, noviembre, diciembre, 
	total_monto
FROM 
	presupuesto_ley
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = presupuesto_ley.id_unidad_ejecutora
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = presupuesto_ley.id_accion_central
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = presupuesto_ley.id_proyecto
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = presupuesto_ley.id_accion_especifica
INNER JOIN
	clasificador_presupuestario
ON
	(clasificador_presupuestario.partida = presupuesto_ley.partida
	AND
	clasificador_presupuestario.generica = presupuesto_ley.generica
	AND
	clasificador_presupuestario.especifica = presupuesto_ley.especifica
	AND
	clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
	)
	WHERE
		1=1
		".$where."
	ORDER BY
		id_unidad_ejecutora, id_proyecto, id_accion_central, id_accion_especifica,
		partida, generica, especifica
	";
	$row=& $conn->Execute($sql);
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	
while (!$row->EOF) 
{
	$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
	if($row->fields("id_proyecto")<>0){
		$accion_proyecto_nombre = $row->fields("codigo_proyecto")/*.' '.$row->fields("proyecto")*/;
	}
	if($row->fields("id_accion_central")<>0){
		$accion_proyecto_nombre = $row->fields("codigo_accion_central").' '.$row->fields("denominacion");
	}
	$monto = $row->fields("enero")+$row->fields("febrero")+$row->fields("marzo")+$row->fields("abril")+$row->fields("mayo")+$row->fields("junio");
	$monto = $monto + $row->fields("julio")+$row->fields("agosto")+$row->fields("septiembre")+$row->fields("octubre")+$row->fields("noviembre")+$row->fields("diciembre");

	$responce->rows[$i]['id']=$row->fields("id_presupuesto_ley");

	$responce->rows[$i]['cell']=array(	
									$row->fields("id_presupuesto_ley"),
									$accion_proyecto_nombre,
									$row->fields("codigo_accion_especifica"),
									$partida,
									number_format($monto,2,',','.'),
									$row->fields("id_unidad_ejecutora"),
									$row->fields("codigo_unidad_ejecutora"),
									$row->fields("unidad_ejecutora"),
									$row->fields("id_accion_central"),
									$row->fields("codigo_accion_central"),
									$row->fields("denominacion"),
									$row->fields("id_proyecto"),
									$row->fields("codigo_proyecto"),
									$row->fields("proyecto"),
									$row->fields("id_accion_especifica"),
									$row->fields("codigo_accion_especifica"),
									$row->fields("accion_especifica"),
									$row->fields("clasificador_presupuestario"),
									$row->fields("anio"),
									number_format($row->fields("enero"),2,',','.'),
									number_format($row->fields("febrero"),2,',','.'),
									number_format($row->fields("marzo"),2,',','.'),
									number_format($row->fields("abril"),2,',','.'),
									number_format($row->fields("mayo"),2,',','.'),
									number_format($row->fields("junio"),2,',','.'),
									number_format($row->fields("julio"),2,',','.'),
									number_format($row->fields("agosto"),2,',','.'),
									number_format($row->fields("septiembre"),2,',','.'),
									number_format($row->fields("octubre"),2,',','.'),
									number_format($row->fields("noviembre"),2,',','.'),
									number_format($row->fields("diciembre"),2,',','.')/**/
									);
	$i++;
	$row->MoveNext();
}
echo $json->encode($responce);
?>