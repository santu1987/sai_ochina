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
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(impuesto.id_impuesto) 
			FROM 
				impuesto
			INNER JOIN 
				organismo 
			ON 
				impuesto.id_organismo = organismo.id_organismo
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
				impuesto.id_impuesto,
				impuesto.codigo_impuesto,
				impuesto.nombre, 
				impuesto.id_organismo,
				organismo.nombre as organismo,
				impuesto.comentario,
				impuesto.partida,
				impuesto.generica,
				impuesto.especifica,
				impuesto.sub_especifica,
				clasificador_presupuestario.denominacion
			FROM 
				impuesto 
			INNER JOIN 
				organismo 
			ON 
				impuesto.id_organismo = organismo.id_organismo
			INNER JOIN 
				clasificador_presupuestario 
			ON 
				(clasificador_presupuestario.partida = impuesto.partida
				AND
				clasificador_presupuestario.generica = impuesto.generica
				AND
				clasificador_presupuestario.especifica = impuesto.especifica
				AND
				clasificador_presupuestario.subespecifica = impuesto.sub_especifica
				)
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
	$responce->rows[$i]['id']=$row->fields("id_impuesto");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_impuesto"),
															$row->fields("codigo_impuesto"),
															$row->fields("nombre"),
															$row->fields("id_organismo"),
															$row->fields("organismo"),
															$row->fields("comentario"),
															$row->fields("partida"),
															$row->fields("generica"),
															$row->fields("especifica"),
															$row->fields("sub_especifica"),
															$row->fields("denominacion"),
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>