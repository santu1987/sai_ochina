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
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
$where = " WHERE 1=1 ";
if ($busq_nombre!='')
$where.= " AND lower(utilizacion_fondos.nombre) like '%$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(utilizacion_fondos.id_utilizacion_fondos) 
			FROM 
				utilizacion_fondos
			INNER JOIN 
				organismo 
			ON 
				utilizacion_fondos.id_organismo = organismo.id_organismo ".$where;
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
				utilizacion_fondos.id_utilizacion_fondos,
				utilizacion_fondos.cuenta_utilizacion_fondos,
				utilizacion_fondos.nombre, 
				utilizacion_fondos.tipo,
				utilizacion_fondos.comentarios
			FROM 
				utilizacion_fondos 
			INNER JOIN 
				organismo 
			ON 
				utilizacion_fondos.id_organismo = organismo.id_organismo
			".$where;
		
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$tipo=$row->fields("tipo");
if($tipo=='0')
$tipos="DETALLE";
if($tipo=='1')
$tipos="TOTAL";
if($tipo=='2')
$tipos="AUTOMÁTICO";
if($tipo=='3')
$tipos="ENCABEZADO";
if($tipo=='')
$tipos="";




	$responce->rows[$i]['id']=$row->fields("id_auxiliares");

	$responce->rows[$i]['cell']=array(	
															
															$row->fields("cuenta_utilizacion_fondos"),
															$row->fields("nombre"),
															$tipos,
															$row->fields("comentarios")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>
