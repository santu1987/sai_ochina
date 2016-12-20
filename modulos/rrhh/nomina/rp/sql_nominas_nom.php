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
$busq_numero ="";
if(isset($_GET["busq_numero"]))
$busq_numero = $_GET['busq_numero'];
$id_tipo_nomina ="";
if($_GET["id_tipo_nomina"]!="")
$id_tipo_nomina = $_GET['id_tipo_nomina'];
$id_trabajador ="";
if($_GET["id_trabajador"]!="")
$id_trabajador = $_GET['id_trabajador'];
//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1=1";
if($busq_numero!='')
	$where.= " AND  nominas.numero_nomina = $busq_numero";
if($id_tipo_nomina!="")
	$where.= " AND  tipo_nomina.id_tipo_nomina = $id_tipo_nomina ";	
if($id_trabajador!='')
	$where.= " AND  trabajador.id_trabajador = $id_trabajador ";		
	
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
	 			count(distinct nominas.id_nominas) 
			FROM 
				nominas
			INNER JOIN
				nomina
			ON
				nominas.id_nominas = nomina.id_nominas 
			INNER JOIN
				tipo_nomina
			ON
				nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina 
			INNER JOIN
				trabajador
			ON
				nomina.id_trabajador = trabajador.id_trabajador 
			INNER JOIN
				frecuencia
			ON
				tipo_nomina.id_frecuencia = frecuencia.id_frecuencia 
			".$where."
			AND
				nominas.id_organismo = $_SESSION[id_organismo]
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
			SELECT distinct
				nominas.id_nominas,
				nominas.numero_nomina,
				nominas.desde,
				nominas.hasta,
				frecuencia.descripcion
			FROM 
				nominas 
			INNER JOIN
				nomina
			ON
				nominas.id_nominas = nomina.id_nominas 
			INNER JOIN
				tipo_nomina
			ON
				nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina 
			INNER JOIN
				trabajador
			ON
				nomina.id_trabajador = trabajador.id_trabajador
			INNER JOIN
				frecuencia
			ON
				tipo_nomina.id_frecuencia = frecuencia.id_frecuencia
			".$where."
			AND
				nominas.id_organismo = $_SESSION[id_organismo]
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
	$desde = substr($row->fields("desde"),8,2)."-".substr($row->fields("desde"),5,2)."-".substr($row->fields("desde"),0,4);
	$hasta = substr($row->fields("hasta"),8,2)."-".substr($row->fields("hasta"),5,2)."-".substr($row->fields("hasta"),0,4);
	$responce->rows[$i]['id']=$row->fields("id_nominas");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_nominas"),
															$row->fields("numero_nomina"),
															$desde,
															$hasta,
															$row->fields("descripcion")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>