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
$ano = date('Y');
if(!$sidx) $sidx =1;

$numero_requision = $_GET['numero_requision'];
//$numero_requision = 12;
//$numero_requision = substr($numero_requision,2);
$id_unidad = $_GET['id_unidadd'];
$where = 'WHERE (1=1)';

if ($numero_requision != "")
	$where.= " AND (numero_pre_orden = '".$numero_requision."') ";
else
	$where.= " AND (numero_pre_orden = '0') ";

$Sql="
			SELECT 
				count(id_orden_compra_serviciod) 
			FROM 
				\"orden_compra_servicioD\"	
			$where	
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

$limit = 5;
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
			\"orden_compra_servicioD\".id_orden_compra_serviciod,
			\"orden_compra_servicioD\".secuencia,
				\"orden_compra_servicioD\".cantidad, 	 
				\"orden_compra_servicioD\".id_unidad_medida, 	 
				\"orden_compra_servicioD\".descripcion,
				\"orden_compra_servicioD\".numero_pre_orden,
				unidad_medida.nombre,
				partida,
				generica,
				especifica,
				subespecifica ,
				monto,
				impuesto
		FROM 
			\"orden_compra_servicioD\"
		INNER JOIN
			unidad_medida
		ON
			unidad_medida.id_unidad_medida = \"orden_compra_servicioD\".id_unidad_medida
		$where	
		ORDER BY 
			$sidx $sord 
		LIMIT 
			$limit 
		OFFSET 
			$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$partida = $row->fields("partida").'.'.$row->fields("generica").'.'.$row->fields("especifica").'.'.$row->fields("subespecifica");
	$responce->rows[$i]['id']=$row->fields("id_orden_compra_serviciod");
	//echo($row->fields("monto") . " awwwwwwwwwwwwwwwwwwwwww<br>");
	if ( $row->fields("impuesto") != 0){
		$va = $row->fields("impuesto") / 100;
		$ivas = (($row->fields("monto") * $row->fields("cantidad")) * $va);
	}else{
		$ivas = 0;
	}
	$totali = (($row->fields("monto") * $row->fields("cantidad")) + $ivas);
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_orden_compra_serviciod"),
															$row->fields("secuencia"),
															number_format($row->fields("cantidad"), 0, ',', '.'),
															$row->fields("id_unidad_medida"),
															$row->fields("nombre"),
															($row->fields("descripcion")),
															$row->fields("numero_pre_orden"),
															$partida,
															$row->fields("partida"),
															number_format($row->fields("monto"),2,',','.'),
															number_format($row->fields("impuesto"),2,',','.'),
															number_format($totali,2,',','.')
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>