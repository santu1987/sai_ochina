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
$ano = $_GET['ano'];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_orden_compra_servicioe)
			FROM 
				\"orden_compra_servicioE\"
			inner join
				unidad_ejecutora
			on
				unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
			where
				numero_orden_compra_servicio = '0'
			and
				revisado_presupuesto = 0
			and
				numero_precompromiso <> '0'
			AND
				estatus <> 3";

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

$sql = "
SELECT 
	id_orden_compra_servicioe, 
	numero_precompromiso ,
	unidad_ejecutora.id_unidad_ejecutora,
	codigo_unidad_ejecutora,
	nombre,
	accion_especifica.id_accion_especifica,
	codigo_accion_especifica,
	denominacion AS accion_especifica,
	tipo,
	id_accion_central,
	id_proyecto
 
FROM 
	\"orden_compra_servicioE\"
inner join
	unidad_ejecutora
on
	unidad_ejecutora.id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora
inner join
	accion_especifica
on
	accion_especifica.id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica
where
	numero_orden_compra_servicio = '0'
and
	revisado_presupuesto = 0
and
	numero_precompromiso <> '0'
AND
	estatus <> 3
ORDER BY
	numero_precompromiso
";
/*
"
SELECT 
	
	numero_pre_orden,
	partida,
	generica,
	especifica,
	subespecifica,
	SUM((cantidad * monto) +
	(((cantidad * monto)/100)*impuesto)) AS TOTAL
FROM 
	\"orden_compra_servicioD\"
GROUP BY
	numero_pre_orden,
	partida,
	generica,
	especifica,
	subespecifica
ORDER BY
	numero_pre_orden,
	partida,
	generica,
	especifica,
	subespecifica
";


SELECT 
	
	"orden_compra_servicioD".numero_pre_orden,
	"orden_compra_servicioD".partida,
	"orden_compra_servicioD".generica,
	"orden_compra_servicioD".especifica,
	"orden_compra_servicioD".subespecifica,
	SUM((cantidad * monto) +
	(((cantidad * monto)/100)*impuesto)) AS TOTAL,
	(

	SELECT
		(monto_presupuesto[2] +
		monto_traspasado[2] +
		monto_modificado[2] 
		)-monto_precomprometido[2]
	FROM
	"presupuesto_ejecutadoR"
	WHERE
	"presupuesto_ejecutadoR".id_unidad_ejecutora ="orden_compra_servicioE".id_unidad_ejecutora
	AND
	"orden_compra_servicioE".id_accion_especifica = "presupuesto_ejecutadoR".id_accion_especifica
	AND
	"orden_compra_servicioD".partida = "presupuesto_ejecutadoR".partida
	AND
	"orden_compra_servicioD".generica = "presupuesto_ejecutadoR".generica
	AND
	"orden_compra_servicioD".especifica = "presupuesto_ejecutadoR".especifica
	AND
	"orden_compra_servicioD".subespecifica = "presupuesto_ejecutadoR".sub_especifica
	)
FROM 
	"orden_compra_servicioD"
	
inner join
	"orden_compra_servicioE"
on
	"orden_compra_servicioE".numero_precompromiso = "orden_compra_servicioD".numero_pre_orden

	
GROUP BY
	"orden_compra_servicioD".numero_pre_orden,
	"orden_compra_servicioD".partida,
	"orden_compra_servicioD".generica,
	"orden_compra_servicioD".especifica,
	"orden_compra_servicioD".subespecifica,
	"orden_compra_servicioE".id_unidad_ejecutora,
	"orden_compra_servicioE".id_accion_especifica 
ORDER BY
	"orden_compra_servicioD".numero_pre_orden,
	"orden_compra_servicioD".partida,
	"orden_compra_servicioD".generica,
	"orden_compra_servicioD".especifica,
	"orden_compra_servicioD".subespecifica

*/
$row=& $conn->Execute($sql);
// constructing a JSON 



$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$id_proyecto = $row->fields("id_proyecto");
$id_accion_central = $row->fields("id_accion_central");
if($row->fields("tipo") ==1)
{
	$sql_proyecto_acc = "
	SELECT
		id_proyecto AS id,
		codigo_proyecto AS codigo,
		nombre AS proyecto
	FROM
		 proyecto
	WHERE
		id_proyecto = ".$id_proyecto."
	";
}else{
	$sql_proyecto_acc = "
	SELECT
		id_accion_central AS id,
		codigo_accion_central AS codigo,
		denominacion AS proyecto
	FROM
		 accion_centralizada
	WHERE
		id_accion_central = ".$id_accion_central."
	";
}
$row_proyecto_acc=& $conn->Execute($sql_proyecto_acc);
	$responce->rows[$i]['id']=$row->fields("id_orden_compra_servicioe");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_orden_compra_servicioe"),
															$row->fields("numero_precompromiso"),
															$row->fields("id_unidad_ejecutora"),
															$row->fields("codigo_unidad_ejecutora"),
															utf8_decode($row->fields("nombre")),
															$row->fields("id_accion_especifica"),
															$row->fields("codigo_accion_especifica"),
															utf8_decode($row->fields("accion_especifica")),
															$row->fields("tipo"),
															$row_proyecto_acc->fields("id"),
															$row_proyecto_acc->fields("codigo"),
															utf8_decode($row_proyecto_acc->fields("proyecto"))/*.'<BR><BR>'*/
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);


?>