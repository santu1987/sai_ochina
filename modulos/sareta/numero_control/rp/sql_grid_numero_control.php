<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql);
									$delegacion=0;
									if(!$row1->EOF){
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}
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
$add = " WHERE id_numero_control=id_numero_control and id_delegacion= ".$delegacion."";
if ($busq_nombre!='')
$add.= " AND lower(descripcion) like '%$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_numero_control) 
			FROM 
				sareta.numero_control
".$add;

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
			  id_numero_control,
			  id_delegacion,
			  descripcion,
			  numero_inicial,
			  numero_final,
			  numero_actual,
			  estatus,
			  comentario
			FROM 
				sareta.numero_control
			
			".$add."
			
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
	$activo=$row->fields("estatus");
						
						if(($activo=="t"))
						{	
						$activo="Activo";
						}
						else
						{
						$activo="Inactivo";	
						}
	$responce->rows[$i]['id_numero_control']=$row->fields("id_numero_control");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_numero_control"),
															utf8_encode(substr($row->fields("descripcion"),0,20)),
															utf8_encode($row->fields("descripcion")),
															$row->fields("numero_inicial"),
															$row->fields("numero_final"),
															$row->fields("numero_actual"),
															utf8_encode($activo),
															substr($row->fields("comentario"),0,14),
															$row->fields("comentario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>