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
$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
$unidad = $_GET['unidad'];
$diferencia = 0 ;
//$requisicion = $_GET['requisicion'];
$pre_compromiso = $_GET['pre_compromiso'];
//************************************************************************
//************************************************************************

$limit = 5;
if(!$sidx) $sidx =1;
// connect to the database
$Sqll = "	
SELECT 
	count(id_orden_compra_serviciod)
FROM 
	\"orden_compra_servicioD\"
WHERE
	numero_pre_orden = '".$pre_compromiso."'
GROUP BY
	partida, generica, especifica, subespecifica
ORDER BY 
	partida, generica, especifica, subespecifica

			";
//echo $Sql;
$roww=& $conn->Execute($Sqll);


$Sql = "	
SELECT 
	id_unidad_ejecutora,id_accion_especifica , 
	partida, generica, especifica, subespecifica,
	SUM( (cantidad * monto) + (((cantidad * monto)*impuesto)/100) ) as total
FROM 
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\" 
ON
	\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
WHERE
	\"orden_compra_servicioD\".numero_pre_orden = '".$pre_compromiso."'

GROUP BY
	\"orden_compra_servicioE\" .id_unidad_ejecutora,id_accion_especifica ,	partida, generica, especifica, subespecifica
ORDER BY 
 partida, generica, especifica, subespecifica			";
//die ($Sql);
$row=& $conn->Execute($Sql);
$count = $roww->fields('count');

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$signo = -1;
while(!$row->EOF)  {

$sqlk = "
SELECT 
	(((monto_presupuesto[1]+monto_presupuesto[2]) + 
	(monto_traspasado[1]+monto_traspasado[2]) + 
	(monto_modificado[1]+monto_modificado[2]))- 
	(monto_precomprometido[1]+monto_precomprometido[2])) AS total_l
FROM 
	\"presupuesto_ejecutadoR\"
WHERE
	id_accion_especifica = ".$row->fields("id_accion_especifica")."
AND
	id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora")."
AND
	partida = '".$row->fields("partida")."'
AND
	generica = '".$row->fields("generica")."'
AND 
	especifica = '".$row->fields("especifica")."'
AND 
	sub_especifica = '".$row->fields("subespecifica")."'
";
//die($sqlk);
$rowkk=& $conn->Execute($sqlk);
$partidad = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");
if ($rowkk->fields("total_l") < 0)
	$monto_re = $rowkk->fields("total_l") * $signo;
else
	$monto_re = $rowkk->fields("total_l");
$diferencia =   $monto_re- $row->fields("total");
    $responce->rows[$i]['id']=$row->fields("partida");

	$responce->rows[$i]['cell']=array(	
															$partidad ,
															number_format($row->fields("total"),2,',','.'),
															number_format($rowkk->fields("total_l"),2,',','.'),
															number_format($diferencia ,2,',','.')
															
															
														);
    $i++;
	$row->MoveNext();
}        
echo json_encode($responce);

?>