<?php
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
$busq_nombre =strtolower($_GET['busq_nombre']);
//************************************************************************

if(!$sidx) $sidx =1;

$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  ((lower(firma_presupuesto.nombre_autoriza) like '%$busq_nombre%') OR (lower(firma_presupuesto.nombre_autoriza) like '$busq_nombre%'))";
	

$Sql="
			SELECT 
				count(firma_presupuesto.id_organismo) 
			FROM 
				firma_presupuesto 
			$where 		
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
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

// the actual query for the grid data 
$Sql="
			SELECT 
				firma_presupuesto.id_organismo,
				organismo.nombre AS organismo,
				firma_presupuesto.nombre_autoriza ,
				firma_presupuesto.nombre_auto_traspaso 
			FROM 
				firma_presupuesto 
			INNER JOIN 
				organismo 
			ON 
				firma_presupuesto.id_organismo = organismo.id_organismo	
			".$where."
			ORDER BY 
				firma_presupuesto.nombre_autoriza,$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_organismo"),
															$row->fields("organismo"),
															$row->fields("nombre_autoriza"),
															$row->fields("nombre_auto_traspaso")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>