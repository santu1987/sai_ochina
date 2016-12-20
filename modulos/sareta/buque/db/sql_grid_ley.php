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
$add = " WHERE sareta.ley.id_ley=sareta.ley.id_ley ";
if ($busq_nombre!='')
$add.= " AND lower(sareta.ley.descripcion) like '%$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_ley) 
			FROM 
				sareta.ley
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
			sareta.tipo_tasa.id_tipo_tasa,
						id_ley,
						articulo,
  						paragrafo,
  						descripcion,
  						tipo_tasa.nombre,
  						tarifa,
  						tonelaje_inicial,
  						tonelaje_final,
  						activo,
  						ley.obs,
  						ley.ultimo_usuario
			FROM 
				sareta.ley,sareta.tipo_tasa 
			
			".$add."
			and sareta.ley.id_tipo_tasa= sareta.tipo_tasa.id_tipo_tasa 
			ORDER BY 
				sareta.ley.id_ley
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
	$activo=$row->fields("activo");
						
						if(($activo=="t"))
						{	
						$activo="Si";
						}
						else
						{
						$activo="No";	
						}
	$responce->rows[$i]['id_ley']=$row->fields("id_ley");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_ley"),
															utf8_encode($row->fields("articulo")),
															utf8_encode($row->fields("paragrafo")),
															substr($row->fields("descripcion"),0,15),
															substr(utf8_encode($row->fields("descripcion")),0,60),
															utf8_encode($row->fields("nombre")),
															$row->fields("id_tipo_tasa"),
															number_format($row->fields("tarifa"),2,',','.'),
															utf8_encode($activo),
															number_format($row->fields("tonelaje_inicial"),2,',','.'),
															number_format($row->fields("tonelaje_final"),2,',','.'),
															substr($row->fields("obs"),0,14),
															$row->fields("obs")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>