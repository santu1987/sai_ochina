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

if (isset($_GET['busq_usuario']))
$busq_usuario = strtolower($_GET['busq_usuario']);

if (isset($_GET['busq_aux_nom']))
$busq_aux_nom = strtolower($_GET['busq_aux_nom']);

$where = " WHERE 1=1 ";
if ($busq_nombre!='')
$where.= " AND lower(cuenta_contable_contabilidad.cuenta_contable) like '%$busq_nombre%' ";
if ($busq_usuario!='')
$where.= " AND  (lower(usuario.nombre) like '%$busq_usuario%')";
if ($busq_cod!='')
$where.= " AND  ( cuenta_auxiliar like '%$busq_cod%')";
if ($busq_aux_nom!='')
$where.= " AND  (lower(auxiliares.nombre) like '%$busq_aux_nom%')";


$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(auxiliares.id_auxiliares) 
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
			INNER JOIN 
				usuario 
			ON 
				auxiliares.ultimo_usuario = usuario.id_usuario
			INNER JOIN
				rel_aux_cont
			ON
				auxiliares.id_auxiliares=rel_aux_cont.id_auxiliar	
			INNER JOIN 
				cuenta_contable_contabilidad
			ON
				rel_aux_cont.id_contab=cuenta_contable_contabilidad.id			
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
				auxiliares.id_auxiliares,
				cuenta_contable_contabilidad.cuenta_contable,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				usuario.usuario
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
			INNER JOIN 
				usuario 
			ON 
				auxiliares.ultimo_usuario = usuario.id_usuario
			INNER JOIN
				rel_aux_cont
			ON
				auxiliares.id_auxiliares=rel_aux_cont.id_auxiliar	
			INNER JOIN 
				cuenta_contable_contabilidad
			ON
				rel_aux_cont.id_contab=cuenta_contable_contabilidad.id		
			".$where."
			order by
			cuenta_contable_contabilidad.cuenta_contable,auxiliares.cuenta_auxiliar
			";
		//	die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{

	$responce->rows[$i]['id']=$row->fields("id_auxiliares");

	$responce->rows[$i]['cell']=array(	
															
															$row->fields("cuenta_contable"),
															$row->fields("cuenta_auxiliar"),
															$row->fields("nombre"),
															$row->fields("usuario")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>
