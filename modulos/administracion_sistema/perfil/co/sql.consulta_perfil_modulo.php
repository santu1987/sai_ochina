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
$id_per_mod=$_GET['id_per_mod'];
$nomb_per_mod=$_GET['nomb_per_mod'];
//************************************************************************

if(!$sidx) $sidx =1;

$where = "WHERE 1=1";
if($nomb_per_mod!='')
	$where.= " AND  (lower(perfil.nombre) LIKE '$nomb_per_mod%')";
if($id_per_mod!='')
	$where.= " AND perfil_modulo.id_perfil=$id_per_mod";

$Sql2="SELECT count(id) FROM perfil_modulo";
$Sql="
			SELECT 
				perfil_modulo.id_perfil as id, perfil.nombre as nombreperfil, modulo.nombre as nombremodulo
 			FROM perfil_modulo  
			INNER JOIN
				perfil
			ON
				perfil.id_perfil=perfil_modulo.id_perfil
			INNER JOIN
				modulo
			ON
				modulo.id=perfil_modulo.id_modulo
			".$where."
			
";

$row=& $conn->Execute($Sql2);
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
				perfil_modulo.id_perfil as id, perfil.nombre as nombreperfil, modulo.nombre as nombremodulo
 			FROM perfil_modulo  
			INNER JOIN
				perfil
			ON
				perfil.id_perfil=perfil_modulo.id_perfil
			INNER JOIN
				modulo
			ON
				modulo.id=perfil_modulo.id_modulo
			".$where."
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start 			;
";
$row=& $conn->Execute($Sql);
//echo $Sql;
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("nombremodulo")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>