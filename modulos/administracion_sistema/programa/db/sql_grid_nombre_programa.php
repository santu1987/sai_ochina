<?php
header("Content-Type: text/html; charset=iso-8859-1");
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
//************************************************************************
$limit = 15;
$busq_nombre ="";
if(isset($_GET["busq_nombre"]))
$busq_nombre = strtolower($_GET['busq_nombre']);
//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  (lower(programa.nombre) LIKE '%$busq_nombre%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(programa.id) 
			FROM 
				programa 
			INNER JOIN 
				modulo 
			ON 
				programa.id_modulo = modulo.id
			INNER JOIN 
				proceso 
			ON 
				programa.id_proceso = proceso.id
				".$where."
";

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

// the actual query for the grid data
$Sql="
			SELECT 
				programa.id,
				programa.nombre, 
				programa.icono, 
				programa.id_modulo, 
				programa.id_proceso, 
				programa.pagina,
				programa.obs,
				modulo.nombre AS nombre_modulo,
				proceso.nombre AS nombre_proceso 
			FROM 
				programa 
			INNER JOIN 
				modulo 
			ON 
				programa.id_modulo = modulo.id 
			INNER JOIN 
				proceso  
			ON 
				programa.id_proceso = proceso.id 
				".$where."
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start
				";
$row=& $conn->Execute($Sql);
// constructing a JSON
/*$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;*/
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															$row->fields("nombre"),
															$row->fields("icono"),
															$row->fields("id_modulo"),
															$row->fields("nombre_modulo"),
															$row->fields("id_proceso"),
															$row->fields("nombre_proceso"),
															$row->fields("pagina"),
															$row->fields("obs")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>