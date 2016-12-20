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

if(isset($_GET["unidad"]))
	$busq_unidad=strtoupper($_GET["unidad"]);
if(isset($_GET["ano"]))
	$ano=$_GET["ano"];
if(isset($_GET["acion_esp"]))
	$acion_esp =$_GET["acion_esp"];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
	
		
		
			   if($busq_partida!='')
			{	
					$partida =substr($busq_partida,0,3);
				if ($partida!=FALSE) $where2.= " AND  clasificador_presupuestario.partida like '%$partida%'";
				
				$generica =substr($busq_partida,3,2);
				if ($generica!=FALSE) $where2.= " AND  clasificador_presupuestario.generica like '%$generica%'";
				
				$especifica=substr($busq_partida,5,2);
				if ($especifica!=FALSE)$where2.= " AND  clasificador_presupuestario.especifica like '%$especifica%'";
				
				$sub_especifica =substr($busq_partida,7,2);
				if ($sub_especifica!=FALSE)	$where2.= " AND  clasificador_presupuestario.subespecifica like '%$sub_especifica%'";
			}	
$Sql="
			SELECT 
				count(DISTINCT id_clasi_presu)
 	 		FROM 
				clasificador_presupuestario
			INNER JOIN
				anteproyecto_presupuesto
			ON
				anteproyecto_presupuesto.partida = clasificador_presupuestario.partida
			WHERE
				clasificador_presupuestario.generica ='00' AND clasificador_presupuestario.especifica ='00' AND clasificador_presupuestario.subespecifica ='00'
			AND
				(id_unidad_ejecutora = $busq_unidad)
			AND
				(anio = '$ano')
			AND
				(id_accion_especifica = $acion_esp)
			
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
				DISTINCT id_clasi_presu,denominacion,  clasificador_presupuestario.partida
			FROM 
				clasificador_presupuestario
			INNER JOIN
				anteproyecto_presupuesto
			ON
				anteproyecto_presupuesto.partida = clasificador_presupuestario.partida
			WHERE
				clasificador_presupuestario.generica ='00' AND clasificador_presupuestario.especifica ='00' AND clasificador_presupuestario.subespecifica ='00'
			AND
				(id_unidad_ejecutora = $busq_unidad)
			AND
				(anio = '$ano')
			AND
				(id_accion_especifica = $acion_esp)
			ORDER BY 
				clasificador_presupuestario.partida
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
//$partida = $row->fields("partida");

	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");

	$responce->rows[$i]['cell']=array(	
															$row->fields("partida"),
															$row->fields("denominacion")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>