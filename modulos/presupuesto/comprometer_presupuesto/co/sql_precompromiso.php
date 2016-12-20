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
$requisicion = $_GET['requisicion'];
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

			";
//echo $Sql;
$roww=& $conn->Execute($Sqll);


$Sql = "	
SELECT 
	numero_pre_orden,
	descripcion,
	nombre,   
	secuencia, 
	cantidad, 
	monto, 
	impuesto,
	( (cantidad * monto) + (((cantidad * monto)*impuesto)/100) ) as total,	  
	partida, generica, especifica, subespecifica
FROM 
	\"orden_compra_servicioD\"
INNER JOIN
	unidad_medida
ON
	unidad_medida.id_unidad_medida = \"orden_compra_servicioD\".id_unidad_medida

WHERE
	numero_pre_orden = '".$pre_compromiso."'
ORDER BY 
	secuencia			";
//echo $Sql;
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
while(!$row->EOF)  {
$partidad = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");
    $responce->rows[$i]['id']=$row->fields("secuencia");

	$responce->rows[$i]['cell']=array(	
															$row->fields("secuencia"),
															$row->fields("descripcion"),
															$row->fields("nombre"),
															number_format($row->fields("cantidad"),2,',','.'),
															number_format($row->fields("monto"),2,',','.'),
															number_format($row->fields("impuesto"),2,',','.'),
															number_format($row->fields("total"),2,',','.'),
															$partidad 
															
														);
    $i++;
	$row->MoveNext();
}        
echo json_encode($responce);

?>