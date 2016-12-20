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
if (isset($_GET['busq_codigo']))
$busq_codigo = strtolower($_GET['busq_codigo']);
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
$where = " WHERE 1=1 ";
if ($busq_codigo!='')
$where.= " AND lower(documento.codigo_documento) like '%$busq_codigo%' ";
if ($busq_nombre!='')
$where.= " AND lower(documento.nombre) like '%$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(documento.id_documento_proveedor) 
			FROM 
				documento
			INNER JOIN 
				organismo 
			ON 
				documento.id_organismo = organismo.id_organismo ".$where."
			AND
				(documento.id_organismo = ".$_SESSION['id_organismo'].")";
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
				documento.id_documento_proveedor, 
				documento.codigo_documento,
				documento.nombre,
				documento.estatus,
				documento.comentario
			FROM 
				documento
			INNER JOIN 
				organismo 
			ON 
			documento.id_organismo = organismo.id_organismo ".$where."
	    	AND
				(documento.id_organismo = ".$_SESSION['id_organismo'].")
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ; ";
				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	if ($row->fields("estatus")=="0")
		$estatus="Activo";
else
	if ($row->fields("estatus")=="1")
			$estatus="Inactivo";	
$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");

	$responce->rows[$i]['id']=$row->fields("id_documento_proveedor");

	$responce->rows[$i]['cell']=array(	
															
															$row->fields("id_documento_proveedor"),
															$row->fields("codigo_documento"),
															$row->fields("nombre"),
															$row->fields("comentario"),
															$estatus
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>