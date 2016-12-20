<?
	require_once '../CONFIGURACION/main.php';
	require_once '../CONTROLADORES/dbdatos.php';
	require_once '../MODELOS/modelosai.php';

function runSQL($rsql) {

	$db['default']['hostname'] = "localhost";
	$db['default']['username'] = '';
	$db['default']['password'] = "";
	$db['default']['database'] = "";
	
	$db['live']['hostname'] = 'localhost';
	$db['live']['username'] = '';
	$db['live']['password'] = '';
	$db['live']['database'] = '';
	
	$active_group = 'default';
	
	$base_url = "http://".$_SERVER['HTTP_HOST'];
	$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
	if (strpos($base_url,'webplicity.net')) $active_group = "live";
	if(!$Id_Connection = pg_connect("host=localhost port=5432 dbname=sai_ochina user=postgres password=batusay"))
		{
			echo "Error de Coneccion"/*.pg_last_notice($Id_Connection)*/;
		}
	$result = pg_query($rsql) or die ('sai_ochina');

	return $result;
	//pg_close($Id_Connection);
}

function countRec($fname,$tname) {
	$sql = "SELECT count($fname) FROM $tname ";
	$result = runSQL($sql);
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
$result = runSQL($sql);

$total = countRec('id_banco','banco');

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