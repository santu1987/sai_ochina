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
$nro_requisicion = $_GET['nro_requisicion'];
//************************************************************************
//************************************************************************

$limit = 5;
if(!$sidx) $sidx =1;
// connect to the database
$Sqll = "	
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
//echo $Sql;
$roww=& $conn->Execute($Sqll);


$Sql = "	
SELECT 
	id_requisicion_encabezado, 
	numero_requision, 
	secuencia, 
	descripcion, 
	cantidad
FROM 

	requisicion_detalle
INNER JOIN
	requisicion_encabezado
ON
	requisicion_encabezado.numero_requisicion = requisicion_detalle.numero_requision
WHERE
	requisicion_detalle.id_organismo = 1
AND
	requisicion_detalle.ano = '2009'
AND
	(numero_requision = '$nro_requisicion')	
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
    $responce->rows[$i]['id']=$row->fields("secuencia");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_requisicion_encabezado"),
															$row->fields("numero_requision"),
															$row->fields("secuencia"),
															$row->fields("descripcion"),
															$row->fields("cantidad")
															
														);
    $i++;
	$row->MoveNext();
}        
echo json_encode($responce);

?>