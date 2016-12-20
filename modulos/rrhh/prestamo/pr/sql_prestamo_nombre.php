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
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
$busq_ci ="";
if (isset($_GET['busq_ci']))
$busq_ci = strtolower($_GET['busq_ci']);
$busq_fecha ="";
if (isset($_GET['busq_fecha']))
$busq_fecha = $_GET['busq_fecha'];
//echo($busq_nombre);
////////////////////////////////
$where = " WHERE 1 = 1 ";
if($busq_nombre!='')
	$where.= " AND (lower(persona.nombre) LIKE '%$busq_nombre%')";
if($busq_ci!='')
	$where.= " AND (lower(persona.cedula) LIKE '%$busq_ci%')";
if($busq_fecha!='')
	$where.= " AND prestamo.fecha_prestamo=$busq_fecha)";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_prestamo) 
			FROM 
				prestamo 
			INNER JOIN
				trabajador
			ON
				trabajador.id_trabajador=prestamo.id_trabajador
			INNER JOIN
				persona
			ON
				persona.id_persona=trabajador.id_persona
			INNER JOIN
				conceptos
			ON
				conceptos.id_concepto=prestamo.id_concepto
				".$where."
			AND
				conceptos.id_organismo = $_SESSION[id_organismo]
				
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
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				conceptos.id_concepto,
				conceptos.descripcion,
				prestamo.monto,
				prestamo.cuota,
				prestamo.saldo,
				prestamo.fecha_prestamo,
				prestamo.observacion,
				prestamo.id_frecuencia as frecuencia,
				prestamo.id_prestamo
			FROM 
				prestamo 
			INNER JOIN
				trabajador
			ON
				trabajador.id_trabajador=prestamo.id_trabajador
			INNER JOIN
				persona
			ON
				persona.id_persona=trabajador.id_persona
			INNER JOIN
				conceptos
			ON
				conceptos.id_concepto=prestamo.id_concepto
			".$where."
			AND
				conceptos.id_organismo = $_SESSION[id_organismo]
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start;
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
	$responce->rows[$i]['id']=$row->fields("id_prestamo");
	
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_trabajador"),
															$row->fields("cedula"),
															$row->fields("nombre"),
															$row->fields("apellido"),
															$row->fields("id_concepto"),
															$row->fields("descripcion"),
															$row->fields("monto"),
															$row->fields("cuota"),
															$row->fields("saldo"),
															$row->fields("fecha_prestamo"),
															$row->fields("observacion"),
															$row->fields("frecuencia"),
															$row->fields("id_prestamo")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>