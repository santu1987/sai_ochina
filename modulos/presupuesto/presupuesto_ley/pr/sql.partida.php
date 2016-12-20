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
//presupuesto_ley_pr_proyecto_id
//presupuesto_ley_pr_accion_central_id
$partidadd = $_POST['presupuesto_aprobado_pr_partida'];
//$partidadd = '301010100';

//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

				$partida =substr($partidadd,0,3);
				
				$generica =substr($partidadd,3,2);
				
				$especifica=substr($partidadd,5,2);
				
				$sub_especifica =substr($partidadd,7,2);
$Sql="
			SELECT 
				count(clasificador_presupuestario.id_clasi_presu) 
			FROM 
				clasificador_presupuestario
			WHERE
				(clasificador_presupuestario.partida ='$partida')
			AND	
				(clasificador_presupuestario.generica ='$generica')
			AND	
				(clasificador_presupuestario.especifica ='$especifica')
			AND	
				(clasificador_presupuestario.subespecifica ='$sub_especifica')
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
				clasificador_presupuestario.id_clasi_presu,
				clasificador_presupuestario.partida,
				clasificador_presupuestario.generica,
				clasificador_presupuestario.especifica,
				clasificador_presupuestario.subespecifica,
				clasificador_presupuestario.denominacion			 
			FROM 
				clasificador_presupuestario
			WHERE
				(clasificador_presupuestario.partida ='$partida')
			AND	
				(clasificador_presupuestario.generica ='$generica')
			AND	
				(clasificador_presupuestario.especifica ='$especifica')
			AND	
				(clasificador_presupuestario.subespecifica ='$sub_especifica')
			ORDER BY 
				clasificador_presupuestario.partida,$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
//echo $Sql;

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");
	$responce = $row->fields("partida").'.'.$row->fields("generica").'.'.$row->fields("especifica").'.'.$row->fields("subespecifica")."*".utf8_decode($row->fields("denominacion"));
	echo($responce);
}
// return the formated data
?>