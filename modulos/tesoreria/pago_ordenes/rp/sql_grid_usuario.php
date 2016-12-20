<?php
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
$where="WHERE 1=1 ";
 if(isset($_GET['busq_nombre'])|| isset($_GET['busq_usuario']))
 {
	 $division=strtoupper($_GET['busq_nombre']);
	 $usuario=strtoupper($_GET['busq_usuario']);
	 $where.=" AND upper(unidad_ejecutora.nombre) like '%$division%'
	 		   AND upper(usuario.nombre) like '%$usuario%'";	
}
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(usuario.id_usuario) 
			FROM 
				usuario
			INNER JOIN
				unidad_ejecutora
			ON
				usuario.id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora
			$where
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
				id_usuario,
				usuario.nombre as nombre,
				apellido,
				unidad_ejecutora.nombre AS unidad
			FROM 
				usuario	
			INNER JOIN
				unidad_ejecutora
			ON
				usuario.id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora
			$where	
			ORDER BY
				nombre	
";
		
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	
while (!$row->EOF) 
{
	$nom=$row->fields("nombre");
	$ape=$row->fields("apellido");
	$nombre=$nom."  ". $ape;	
	$responce->rows[$i]['id']=$row->fields("id_usuario");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_usuario"),
														    $nombre,
															$row->fields("unidad"),
																																														
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>