<?php session_start();
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
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(tipo_documento_cxp.id_tipo_documento) 
			FROM 
				tipo_documento_cxp
			INNER JOIN 
				organismo 
			ON 
				tipo_documento_cxp.id_organismo = organismo.id_organismo
			WHERE
				tipo_documento_cxp.id_organismo=$_SESSION[id_organismo]	
				
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
				tipo_documento_cxp.id_tipo_documento,
				tipo_documento_cxp.nombre AS nombre,
				tipo_documento_cxp.siglas,
				tipo_documento_cxp.comentarios
			FROM 
				tipo_documento_cxp
			INNER JOIN 
				organismo 
			ON 
				tipo_documento_cxp.id_organismo = organismo.id_organismo
			WHERE
				tipo_documento_cxp.id_organismo=$_SESSION[id_organismo]	
			ORDER BY
				tipo_documento_cxp.nombre
					LIMIT 
				$limit 
					OFFSET 
				$start 				
				
";
				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
				
$responce->rows[$i]['id']=$row->fields("id_banco");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_tipo_documento"),
															$row->fields("nombre"),	
															$row->fields("siglas"),
															$row->fields("comentarios")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>