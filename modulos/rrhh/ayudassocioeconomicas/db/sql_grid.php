<?php
header("Content-Type: text/html; charset=iso-8859-1");
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
$busq_nombre ="";
if(isset($_GET["busq_nombre"]))
$busq_nombre = strtolower($_GET['busq_nombre']);
//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1=1";
if($busq_nombre!='')
	$where.= " AND  (lower(usuario.nombre) LIKE '%$busq_nombre%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(ayudasocioeconomica.id) 
			FROM 
				ayudasocioeconomica 
			INNER JOIN 
				beneficiario_ayudas   
			ON 
				ayudasocioeconomica.id_usuario = beneficiario_ayudas.id_beneficiario_ayudas 
				".$where."
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
				ayudasocioeconomica.id,
				to_char(ayudasocioeconomica.fecha, 'DD/MM/YYYY') as fecha,
				ayudasocioeconomica.concepto,
				(substr(ayudasocioeconomica.concepto, 1,60) || '...') as resumen_concepto, 
				ayudasocioeconomica.id_usuario, 
				ayudasocioeconomica.monto, 
				beneficiario_ayudas.nombre,
				beneficiario_ayudas.apellido				
			FROM 
				ayudasocioeconomica  
			INNER JOIN 
				beneficiario_ayudas 
			ON 
				ayudasocioeconomica.id_usuario = beneficiario_ayudas.id_beneficiario_ayudas  
				".$where."
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start
				";
$row=& $conn->Execute($Sql);
// constructing a JSON
/*$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;*/
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),															
															$row->fields("fecha"),
															utf8_encode($row->fields("resumen_concepto")),
															utf8_encode($row->fields("concepto")),															
															number_format($row->fields("monto"),2,',','.'),
															$row->fields("id_usuario"),
															utf8_encode($row->fields("nombre").' '.$row->fields("apellido"))
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>