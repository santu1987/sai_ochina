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
$unidad = $_GET['unidad'];
$unidad_es = $_GET['unidad_es'];
//************************************************************************
if ($_GET['patidaa'] != "")
{
	list( $parti, $generica, $especifica, $subespecifica ) = split( '[.]', $_GET['patidaa']);
	
	$where = "AND (presupuesto_ley.partida = '".$parti."')";
}

//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(DISTINCT id_clasi_presu)
 	 		FROM 
				clasificador_presupuestario
			INNER JOIN
				\"presupuesto_ejecutadoR\"
			ON
				clasificador_presupuestario.partida = \"presupuesto_ejecutadoR\".partida
				AND
				clasificador_presupuestario.generica = \"presupuesto_ejecutadoR\".generica
				AND
				clasificador_presupuestario.especifica = \"presupuesto_ejecutadoR\".especifica
				AND
				clasificador_presupuestario.subespecifica = \"presupuesto_ejecutadoR\".sub_especifica 
			WHERE
				(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad.")
				AND
				(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es.")
				AND
					(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
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
				DISTINCT clasificador_presupuestario.id_clasi_presu, clasificador_presupuestario.denominacion,  \"presupuesto_ejecutadoR\".partida, 
				\"presupuesto_ejecutadoR\".generica, \"presupuesto_ejecutadoR\".especifica, \"presupuesto_ejecutadoR\".sub_especifica 
			FROM 
				clasificador_presupuestario
			INNER JOIN
				\"presupuesto_ejecutadoR\"
			ON
				clasificador_presupuestario.partida = \"presupuesto_ejecutadoR\".partida
				AND
				clasificador_presupuestario.generica = \"presupuesto_ejecutadoR\".generica
				AND
				clasificador_presupuestario.especifica = \"presupuesto_ejecutadoR\".especifica
				AND
				clasificador_presupuestario.subespecifica = \"presupuesto_ejecutadoR\".sub_especifica 
			WHERE
				(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad.")
				AND
				(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es.")
				AND
					(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
					$where
			ORDER BY 
				 \"presupuesto_ejecutadoR\".partida, 
				\"presupuesto_ejecutadoR\".generica, \"presupuesto_ejecutadoR\".especifica, \"presupuesto_ejecutadoR\".sub_especifica 
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
$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");

	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_clasi_presu"),
															$partida,
															utf8_decode($row->fields("denominacion"))
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>