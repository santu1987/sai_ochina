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
$busq_tip_nomina ="";
if(isset($_GET["busq_tip_nomina"]))
$busq_tip_nomina = $_GET['busq_tip_nomina'];
$busq_ci ="";
if (isset($_GET['busq_ci']))
$busq_ci = strtolower($_GET['busq_ci']);
//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1 = 1 ";
if($busq_tip_nomina!='')
	$where.= " AND  nomina.id_tipo_nomina = $busq_tip_nomina ";
if($busq_ci!='')
	$where.= " AND (lower(nomina.cedula) LIKE '%$busq_ci%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_nomina) 
			FROM 
				nomina
			INNER JOIN
				trabajador
			ON
				nomina.id_trabajador = trabajador.id_trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona = persona.id_persona
			INNER JOIN
				cargos
			ON
				trabajador.id_cargo = cargos.id_cargos
			INNER JOIN
				tipo_nomina
			ON
				nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
			INNER JOIN
				conceptos
			ON
				nomina.id_concepto = conceptos.id_concepto
			INNER JOIN
				nominas
			ON
				nomina.id_nominas = nominas.id_nominas 
				".$where."
			AND
				nomina.id_organismo = $_SESSION[id_organismo]
				
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
				nomina.id_nomina,
				nomina.id_tipo_nomina, 
				nomina.id_trabajador,
				nomina.id_concepto,
				nomina.id_nominas,
				nomina.monto_concepto,
				nomina.cedula,
				nomina.asignacion_deduccion,
				persona.nombre,
				persona.apellido,
				cargos.descripcion as cargo,
				tipo_nomina.nombre as tipo_nomina,
				conceptos.descripcion as concepto,
				nominas.numero_nomina,
				nominas.desde,
				nominas.hasta
			FROM 
				nomina 
			INNER JOIN
				trabajador
			ON
				nomina.id_trabajador = trabajador.id_trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona = persona.id_persona
			INNER JOIN
				cargos
			ON
				trabajador.id_cargo = cargos.id_cargos
			INNER JOIN
				tipo_nomina
			ON
				nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
			INNER JOIN
				conceptos
			ON
				nomina.id_concepto = conceptos.id_concepto
			INNER JOIN
				nominas
			ON
				nomina.id_nominas = nominas.id_nominas
			".$where."	
			AND	
				nomina.id_organismo = $_SESSION[id_organismo]
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
//$fecha = substr($fecha, 0,10);
//$fecha = substr($fecha,8,2).substr($fecha,4,4).substr($fecha,0,4);
while (!$row->EOF) 
{
	$desde = substr($row->fields("desde"),8,2)."-".substr($row->fields("desde"),5,2)."-".substr($row->fields("desde"),0,4);
	$hasta = substr($row->fields("hasta"),8,2)."-".substr($row->fields("hasta"),5,2)."-".substr($row->fields("hasta"),0,4);
	$responce->rows[$i]['id']=$row->fields("id_nomina");
	
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_nomina"),
															$row->fields("id_tipo_nomina"),
															$row->fields("id_trabajador"),
															$row->fields("id_concepto"),
															$row->fields("id_nominas"),
															$row->fields("monto_concepto"),
															$row->fields("cedula"),
															$row->fields("nombre"),
															$row->fields("apellido"),
															$row->fields("asignacion_deduccion"),
															$row->fields("cargo"),
															$row->fields("tipo_nomina"),
															$row->fields("concepto"),
															$row->fields("numero_nomina"),
															$desde,
															$hasta
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>