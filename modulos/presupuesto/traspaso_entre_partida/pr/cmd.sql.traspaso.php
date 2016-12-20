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
		count(id_traspaso_entre_partida)
	FROM 
	traspaso_entre_partidas
INNER JOIN
		unidad_ejecutora
	ON
		unidad_ejecutora.id_unidad_ejecutora = traspaso_entre_partidas.id_unidad_receptora
	LEFT JOIN
		accion_centralizada
	ON
		accion_centralizada.id_accion_central = traspaso_entre_partidas.id_accion_centralizada_receptora
	LEFT JOIN
		proyecto
	ON
		proyecto.id_proyecto = traspaso_entre_partidas.id_proyecto_receptora
	INNER JOIN
		accion_especifica
	ON
		accion_especifica.id_accion_especifica = traspaso_entre_partidas.id_accion_especifica_receptora
	INNER JOIN
		clasificador_presupuestario
	ON
		(clasificador_presupuestario.partida = traspaso_entre_partidas.partida_receptora
		AND
		clasificador_presupuestario.generica = traspaso_entre_partidas.generica_receptora
		AND
		clasificador_presupuestario.especifica = traspaso_entre_partidas.especifica_receptora
		AND
		clasificador_presupuestario.subespecifica = traspaso_entre_partidas.subespecifica_receptora
		)

		";
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

$sql = "
SELECT 
	id_traspaso_entre_partida, traspaso_entre_partidas.id_organismo, 
	anio, 
	id_unidad_receptora, unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre,
	id_proyecto_receptora, proyecto.codigo_proyecto, proyecto.nombre AS proyecto,
	id_accion_centralizada_receptora, accion_centralizada.codigo_accion_central, accion_centralizada.denominacion AS accion_centralizada,
	id_accion_especifica_receptora, accion_especifica.codigo_accion_especifica, accion_especifica.denominacion AS accion_especifica, 
	partida_receptora, generica_receptora, especifica_receptora, subespecifica_receptora, clasificador_presupuestario.denominacion AS clasificador_presupuestario,
	secuencia, 
	monto_receptora, 
	mes_receptora, 
	usuario_traspaso, 
	fecha_traspaso, 
	referencia, 
	traspaso_entre_partidas.comentario,   
	traspaso_entre_partidas.fecha_actualizacion, 
	traspaso_entre_partidas.ultimo_usuario
FROM 
	traspaso_entre_partidas
INNER JOIN
		unidad_ejecutora
	ON
		unidad_ejecutora.id_unidad_ejecutora = traspaso_entre_partidas.id_unidad_receptora
	LEFT JOIN
		accion_centralizada
	ON
		accion_centralizada.id_accion_central = traspaso_entre_partidas.id_accion_centralizada_receptora
	LEFT JOIN
		proyecto
	ON
		proyecto.id_proyecto = traspaso_entre_partidas.id_proyecto_receptora
	INNER JOIN
		accion_especifica
	ON
		accion_especifica.id_accion_especifica = traspaso_entre_partidas.id_accion_especifica_receptora
	INNER JOIN
		clasificador_presupuestario
	ON
		(clasificador_presupuestario.partida = traspaso_entre_partidas.partida_receptora
		AND
		clasificador_presupuestario.generica = traspaso_entre_partidas.generica_receptora
		AND
		clasificador_presupuestario.especifica = traspaso_entre_partidas.especifica_receptora
		AND
		clasificador_presupuestario.subespecifica = traspaso_entre_partidas.subespecifica_receptora
		)
	";
	$row=& $conn->Execute($sql);
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	
while (!$row->EOF) 
{
	$partida = $row->fields("partida_receptora").".".$row->fields("generica_receptora").".".$row->fields("especifica_receptora").".".$row->fields("subespecifica_receptora");
	if($row->fields("id_proyecto_receptora")<>0){
		$accion_proyecto_nombre = $row->fields("codigo_proyecto")/*.' '.$row->fields("proyecto")*/;
	}
	if($row->fields("id_accion_centralizada_receptora")<>0){
		$accion_proyecto_nombre = $row->fields("codigo_accion_central")/*.' '.$row->fields("denominacion")*/;
	}
	$monto = $row->fields("monto_receptora");
	//$monto = $monto + $row->fields("julio")+$row->fields("agosto")+$row->fields("septiembre")+$row->fields("octubre")+$row->fields("noviembre")+$row->fields("diciembre");

	$responce->rows[$i]['id']=$row->fields("id_traspaso_entre_partida");

	$responce->rows[$i]['cell']=array(	
									$row->fields("id_traspaso_entre_partida"),
									$row->fields("codigo_unidad_ejecutora"),									
									$accion_proyecto_nombre,
									$row->fields("codigo_accion_especifica"),
									$partida,
									$row->fields("mes_receptora"),
									number_format($monto,2,',','.'),
									$row->fields("id_unidad_receptora"),
									$row->fields("unidad_ejecutora"),
									$row->fields("anio"),
									$row->fields("id_accion_centralizada_receptora"),
									$row->fields("codigo_accion_central"),
									$row->fields("accion_centralizada"),
									$row->fields("id_proyecto_receptora"),
									$row->fields("codigo_proyecto"),
									$row->fields("proyecto"),
									$row->fields("id_accion_especifica_receptora"),
									$row->fields("accion_especifica")									
									);
	$i++;
	$row->MoveNext();
}
echo $json->encode($responce);
?>