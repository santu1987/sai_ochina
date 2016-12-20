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
//************************************************************************
if (isset($_GET['nombre']))
$busq_nombre = strtolower($_GET['nombre']);
$where = " WHERE id_dia_feriado=id_dia_feriado";
if ($busq_nombre!='')
$where.= " AND lower(descripcion) like '%$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_dia_feriado)
 	 		FROM 
				sareta.dias_feriados
			LEFT OUTER JOIN unidad_ejecutora
			ON sareta.dias_feriados.delegacion=unidad_ejecutora.id_unidad_ejecutora
			".$where;

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
			  sareta.dias_feriados.id_dia_feriado,
			  sareta.dias_feriados.descripcion,
			  sareta.dias_feriados.fecha_dia_feriado,
			  sareta.dias_feriados.tipo,
			  sareta.dias_feriados.delegacion,
			  sareta.dias_feriados.comentario,
			  sareta.dias_feriados.ultimo_usuario,
			  unidad_ejecutora.nombre
			FROM 
				sareta.dias_feriados

LEFT OUTER JOIN unidad_ejecutora

ON sareta.dias_feriados.delegacion=unidad_ejecutora.id_unidad_ejecutora
".$where."	 
			 ORDER BY 
				descripcion
			;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$tipo=$row->fields("tipo");
	$Delegaciones="";
						if(($tipo=="1"))
						{	
						$tipo="NACIONAL";
						}
						else if(($tipo=="2"))
						{	
						$tipo="REGIONAL";
						}
						else if(($tipo=="3"))
						{
						$tipo="VARIABLE";	
						}
						
						
						
						
	$responce->rows[$i]['id_dia_feriado']=$row->fields("id_dia_feriado");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_dia_feriado"),
															substr($row->fields("descripcion"),0,30),
															utf8_encode($row->fields("descripcion")),
															substr($row->fields("fecha_dia_feriado"),8,2)."/".substr($row->fields("fecha_dia_feriado"),5,2)."/".substr($row->fields("fecha_dia_feriado"),0,4),
															utf8_encode($tipo),
															$row->fields("tipo"),
															substr($row->fields("comentario"),0,20),
															$row->fields("comentario"),
															substr($row->fields("nombre"),0,50),
															$row->fields("delegacion")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>