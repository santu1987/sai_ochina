<?

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

if (!$sortname) $sortname = 'organismo';
if (!$sortorder) $sortorder = 'desc';

if($_POST['query']!='')
{
	if ($_POST['qtype']=="organismo")	$where="WHERE upper(organismo) LIKE '%".strtoupper($_POST[query])."%'";
}

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "OFFSET  $start LIMIT $rp";

$sql = "SELECT * FROM organismos $where $sort $limit";
$result = runSQL($sql);

$total = countRec('id_organismo','organismos');

$json = "";
$json .= "{";
$json .= "page:$page,";
$json .= "total:1,";
$json .= "rows:[";
$rc = false;
while ($row = pg_fetch_array($result)) {
	if ($rc) $json .= ",";
	$json .= "{";
		$json .= "id:$row[id_organismo],";
		$json .= "cell:[";
			$json .= $row['id_organismo'];
			$json .= ",'".addslashes(trim($row['organismo']))."'";			
			$json .= ",'".addslashes(trim($row['telefono']))."'";
			$json .= ",'".addslashes(trim($row['email']))."'";
			$json .= ",'".addslashes(trim($row['representante']))."'";						
		$json .= "]";		
	$json .= "}";
	$rc = true;		
}
$json .= "]";
$json .= "}";
echo $json;
?>