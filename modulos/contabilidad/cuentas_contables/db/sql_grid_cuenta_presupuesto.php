<?php
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
$where = "WHERE 1=1";
$busq_cuenta=$_GET['busq_cuenta'];
if($busq_cuenta!='')
{	
		
		$partida =substr($busq_cuenta,0,3);
		if ($partida!=FALSE) $where.= " AND partida like '%$partida%'";
		
		$generica =substr($busq_cuenta,3,2);
		if ($generica!=FALSE) $where.= " AND generica like '%$generica%'";
		
		$especifica=substr($busq_cuenta,5,2);
		if ($especifica!=FALSE)$where.= " AND especifica like '%$especifica%'";
		
		$sub_especifica =substr($busq_cuenta,7,2);
		if ($sub_especifica!=FALSE)	$where.= " AND subespecifica like '%$sub_especifica%'";
		
}

//if($busq_nombre!="") $where.= " AND upper(denominacion) like  '%$busq_nombre%'";
	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(clasificador_presupuestario.id_clasi_presu) 
			FROM 
				clasificador_presupuestario  
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
				*,
				 partida || generica || especifica || subespecifica  as cuenta,
				case tipo 
					when 1 then 'TITULO'				
					when 2 then 'DETALLE'
				end AS tipo_cuenta
			FROM 
				clasificador_presupuestario  
			".$where."
			ORDER BY 
				partida, generica, especifica, subespecifica,denominacion
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_clasi_presu"),
															$row->fields("cuenta"),
															$row->fields("denominacion"),
															$row->fields("tipo_cuenta")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>