<?
require_once '../../../controladores/main.php';
require_once '../../../controladores/dbdatos.php';
require_once '../../../controladores/ClaseBase.php';
require_once 'buscar.php';

$conexion = new dbdatos();
function countRec() {
$conexion = new dbdatos();
	$sql = "SELECT count(id_banco) FROM banco ";
	$result = $conexion->consulta($sql);
	while ($row = pg_fetch_array($result)) {
		return $row[0];
	}	
}
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'nombre';
if (!$sortorder) $sortorder = 'desc';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "OFFSET  $start LIMIT $rp";

$sql = "SELECT * FROM banco $sort $limit";
$result = $conexion->consulta($sql);

$total = Buscar::countRec();

$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while ($row = pg_fetch_array($result)) {
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:".$row[0].",";
	$json .= "cell:[".$row[0]."";
	$json .= ",'".addslashes($row[1])."'";
	$json .= ",'".addslashes($row[2])."'";
	$json .= ",'".addslashes($row[3])."'";
	$json .= ",'".addslashes($row[4])."-".$row[5]."'";
	$json .= ",'".addslashes($row[6])."'";
	$json .= ",'".addslashes($row[7])."']";
	$json .= "}";
	$rc = true;		
}
$json .= "]\n";
$json .= "}";
echo $json;

?>