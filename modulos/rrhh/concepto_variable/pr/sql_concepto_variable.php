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
$busq_tipo_nomina ="";
if($_GET["busq_tipo_nomina"]!=0)
$busq_tipo_nomina = $_GET['busq_tipo_nomina'];
$busq_concepto ="";
if(isset($_GET["busq_concepto"]))
$busq_concepto =$_GET['busq_concepto'];

//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1=1";
if($busq_tipo_nomina!='')
	$where.= " AND  concepto_variable.id_tipo_nomina = $busq_tipo_nomina";
if($busq_concepto!='')
	$where.= " AND  (lower(concepto.descripcion) LIKE '%$busq_concepto%')";	
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(concepto_variable.id_concepto_variable) 
			FROM 
				concepto_variable
			INNER JOIN
				conceptos
			ON
				concepto_variable.id_concepto = conceptos.id_concepto
			INNER JOIN
				trabajador
			ON
				concepto_variable.id_trabajador = trabajador.id_trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona = persona.id_persona
				".$where."
			AND
				concepto_variable.estatus = 1 
			AND
				concepto_variable.id_organismo = $_SESSION[id_organismo]
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
				concepto_variable.id_concepto_variable,
				conceptos.descripcion,
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				concepto_variable.porcentaje, 
				concepto_variable.monto,
				concepto_variable.observacion,
				concepto_variable.id_tipo_nomina,
				conceptos.id_concepto
			FROM 
				concepto_variable	
			INNER JOIN
				conceptos
			ON
				concepto_variable.id_concepto = conceptos.id_concepto
			INNER JOIN
				trabajador
			ON
				concepto_variable.id_trabajador = trabajador.id_trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona = persona.id_persona
				".$where."
			AND
				concepto_variable.estatus = 1
			AND
				concepto_variable.id_organismo = $_SESSION[id_organismo]
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
	$monto = $row->fields("monto");
	if(strpos($monto,'.')==0)
		$monto = $monto.",00";
	else
		$monto = str_replace('.',',',$monto);
	$responce->rows[$i]['id']=$row->fields("id_concepto_variable");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_concepto_variable"),
															$row->fields("descripcion"),
															$row->fields("id_trabajador"),
															$row->fields("cedula"),
															$row->fields("nombre"),
															$row->fields("apellido"),
															$row->fields("porcentaje"),
															$monto,
															$row->fields("observacion"),
															$row->fields("id_tipo_nomina"),
															$row->fields("id_concepto")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>