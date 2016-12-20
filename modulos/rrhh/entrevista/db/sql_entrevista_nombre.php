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
//************************************************************************
$limit = 15;
$busq_nombre ="";
if(isset($_GET["busq_nombre"]))
$busq_nombre = strtolower($_GET['busq_nombre']);
$busq_rama ="";
if(isset($_GET["busq_rama"]))
$busq_rama = strtolower($_GET['busq_rama']);
//echo($busq_nombre);
////////////////////////////////busq_rama
$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  nivel_academico.id_nivel_academico=$busq_nombre";
if($busq_rama!='')
	$where.= " AND  (lower(ramas.nombre) LIKE '%$busq_rama%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_entrevista) 
			FROM 
				entrevista 
			INNER JOIN
				curriculos
			ON 
				entrevista.id_curriculos=curriculos.id_curriculum
			INNER JOIN 
				ramas
			ON
				ramas.id_ramas=curriculos.id_ramas
            INNER JOIN
                nivel_academico
            ON 
                nivel_academico.id_nivel_academico=ramas.id_nivel_academico
			".$where."
			AND
				entrevista.id_organismo = $_SESSION[id_organismo]
				
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
				entrevista.id_entrevista,
				entrevista.cedula,
				entrevista.nombre, 
				entrevista.observaciones,
				curriculos.imagen,
				entrevista.id_curriculos,
				entrevista.fecha_entrevista as fech,
				nivel_academico.nombre as nivel,
				ramas.nombre as rama
			FROM 
				entrevista 
			INNER JOIN
				curriculos
			ON 
				entrevista.id_curriculos=curriculos.id_curriculum
			INNER JOIN 
				ramas
			ON
				ramas.id_ramas=curriculos.id_ramas
            INNER JOIN
                nivel_academico
            ON 
                nivel_academico.id_nivel_academico=ramas.id_nivel_academico	
			".$where."
			AND
				entrevista.id_organismo = $_SESSION[id_organismo]
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
	$responce->rows[$i]['id']=$row->fields("id_entrevista");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_entrevista"),
															$row->fields("cedula"),
															$row->fields("nombre"),
															$row->fields("observaciones"),
															$row->fields("imagen"),
															$row->fields("id_curriculos"),
															$row->fields("fech"),
															$row->fields("nivel"),
															$row->fields("rama")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>