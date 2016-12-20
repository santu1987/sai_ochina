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

if(!$sidx) $sidx =1;

if(isset($_GET["nombre_ramas"]))
	$nombre_ramas = strtolower($_GET['nombre_ramas']);		
if(isset($_GET["nombre_nivel"]))
	$nombre_nivel = strtolower($_GET['nombre_nivel']);			
if(isset($_GET["fecha_entrevista"]))
	$fecha_entrevista = strtolower($_GET['fecha_entrevista']);				

$where = " WHERE 1 = 1 ";

if($nombre_ramas!="")
	$where.= " AND  (lower(ramas.nombre) LIKE '%$nombre_ramas%')";
if($nombre_nivel!="")
	$where.= " AND  nivel_academico.id_nivel_academico = $nombre_nivel ";	
if($fecha_entrevista!="")
	$where.= " AND  entrevista.fecha_entrevista = '$fecha_entrevista' ";	
	
$Sql="
			SELECT 
				count(id_entrevista) 
			FROM 
				entrevista
			INNER JOIN
				curriculos
			ON
				curriculos.id_curriculum=entrevista.id_curriculos
			INNER JOIN
				ramas
			ON
				ramas.id_ramas=curriculos.id_ramas
			INNER JOIN
				nivel_academico
			ON
				nivel_academico.id_nivel_academico = ramas.id_nivel_academico
			".$where."
			AND 
				entrevista.id_organismo = $_SESSION[id_organismo]
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

$Sql="
			SELECT 
				entrevista.cedula,
				entrevista.nombre,
				entrevista.fecha_entrevista,
				ramas.nombre as rama,
				nivel_academico.nombre as nivel
			FROM 
				entrevista 
			INNER JOIN
				curriculos
			ON
				curriculos.id_curriculum=entrevista.id_curriculos
			INNER JOIN
				ramas
			ON
				ramas.id_ramas=curriculos.id_ramas
			INNER JOIN
				nivel_academico
			ON
				nivel_academico.id_nivel_academico = ramas.id_nivel_academico	
			".$where."
			AND
				entrevista.id_organismo = $_SESSION[id_organismo]
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("cedula"),
															$row->fields("nombre"),
															$row->fields("fecha_entrevista"),
															$row->fields("rama")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>