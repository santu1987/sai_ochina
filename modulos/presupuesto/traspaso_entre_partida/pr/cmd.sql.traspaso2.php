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
$precompromiso = $_GET['precompromiso'];
$central = $_GET['central'];
$proyecto = $_GET['proyecto'];
$especifica = $_GET['especifica'];
$partida = $_GET['partida'];
$busqueda_anio=$_GET['busqueda_anio'];
//************************************************************************
//************************************************************************

	$contar = "SELECT 
		count(id_orden_compra_serviciod)
	FROM 
	\"orden_compra_servicioD\"
WHERE
	numero_pre_orden = '".$precompromiso."'

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
	sum((cantidad * monto) + (((cantidad * monto)/100)*impuesto) ) AS monto, 
	partida, 
	generica, 
	especifica, 
	subespecifica
FROM 
	\"orden_compra_servicioD\"
WHERE
	numero_pre_orden = '".$precompromiso."'
AND
	disponible = 0
 GROUP BY 
 	partida, 
	generica, 
	especifica, 
	subespecifica

Order by
 	partida, 
	generica, 
	especifica, 
	subespecifica
	";
	$row=& $conn->Execute($sql);
	
$sqlxx = "
SELECT 
	id_unidad_ejecutora,
	id_accion_especifica
FROM 
	\"orden_compra_servicioE\"
WHERE
	numero_precompromiso = '".$precompromiso."'
	";
	$rowxx=& $conn->Execute($sqlxx);
	
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	
$mes= date('n');
$bb = 0;
$desde =1;
//$monto_precomprometido = 0;
while($desde<=$mes){
	if ($bb == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
	}
$bb++;
$desde++;
}
	
while (!$row->EOF) 
{
$sql_presu = "
SELECT   
	((monto_presupuesto [".$mes."])+ 
	(monto_traspasado [".$mes."])+
	(monto_modificado [".$mes."])) AS correjido
FROM 
	\"presupuesto_ejecutadoR\"
WHERE
	id_unidad_ejecutora = ".$rowxx->fields("id_unidad_ejecutora")."
AND
	id_accion_especifica =  ".$rowxx->fields("id_accion_especifica")."
AND
	partida= '".$row->fields("partida")."'
AND
	generica= '".$row->fields("generica")."'
AND
	especifica='".$row->fields("especifica")."'
AND
	sub_especifica='".$row->fields("subespecifica")."'
";
$row_presu=& $conn->Execute($sql_presu);
if (!$row_presu->EOF)
{
	$correjido = $row_presu->fields("correjido");
}else{
	$correjido = 0;
}
$sqlyy="
SELECT 
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS precomprometido
FROM
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
WHERE
	id_unidad_ejecutora = ".$rowxx->fields("id_unidad_ejecutora")."
AND
	id_accion_especifica = ".$rowxx->fields("id_accion_especifica")."
AND
	partida = '".$row->fields("partida")."'
AND
	generica = '".$row->fields("generica")."'
AND
	especifica = '".$row->fields("especifica")."'
AND
	subespecifica = '".$row->fields("subespecifica")."'
AND 
	disponible = 1
AND
	fecha_elabora BETWEEN '".date('Y')."-".date('n')."-01' AND '".date('Y-n-d')."'
";
//echo $sqlyy;
$rowyy=& $conn->Execute($sqlyy);
if (!$rowyy->EOF)
{
	$precomprometido = $rowyy->fields("precomprometido");
}else{
	$precomprometido = 0;
}
//////////////////////////////////////////
$sqlclasifica="
SELECT 
	denominacion AS clasificador_presupuestario
FROM
	clasificador_presupuestario

WHERE
	
	partida = '".$row->fields("partida")."'
AND
	generica = '".$row->fields("generica")."'
AND
	especifica = '".$row->fields("especifica")."'
AND
	subespecifica = '".$row->fields("subespecifica")."'

";
//echo $sqlyy;
$rowclasifica=& $conn->Execute($sqlclasifica);
$hay = $correjido - $precomprometido;
$falta = $row->fields("monto") - $hay;
if ($falta < 0) 
	$falta = 0;
	$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");

	$responce->rows[$i]['id']=$partida;

	$responce->rows[$i]['cell']=array(	
									$partida,
									number_format($row->fields("monto"),2,',','.'),
									number_format($correjido	,2,',','.')	,
									number_format($precomprometido	,2,',','.'),
									number_format($falta	,2,',','.'),
									utf8_decode($rowclasifica->fields("clasificador_presupuestario"))
									);
	$i++;
	$row->MoveNext();
}
echo $json->encode($responce);
?>