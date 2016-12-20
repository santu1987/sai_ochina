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
//************************************************************************
//************************************************************************

$examp = $_GET["q"]; //query number

$id = $_GET['id'];
if(!$sidx) $sidx =1;

switch ($examp) {
    case 1:
		$result = mysql_query("SELECT COUNT(*) AS count FROM invlines WHERE id=".$id);
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
        if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) $start = 0;
        $SQL = "SELECT num, item, qty, unit FROM invlines WHERE id=".$id." ORDER BY $sidx $sord LIMIT $start , $limit";
		$result = mysql_query( $SQL ) or die("Couldn?t execute query.".mysql_error());
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$responce->rows[$i]['id']=$row[num];
            $responce->rows[$i]['cell']=array($row[num],$row[item],$row[qty],$row[unit],number_format($row[qty]*$row[unit],2,'.',' '));
            $i++;
		}        
        echo json_encode($responce);
           
        break;
}

?>