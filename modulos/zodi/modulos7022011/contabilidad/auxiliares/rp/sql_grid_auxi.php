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
$aux=$_POST['contabilidad_auxiliares_rp_aux'];
if($aux!="")
{
	$where="WHERE cuenta_auxiliar='$aux'";
}else
die("vacio");
$id=$_POST['contabilidad_auxiliares_rp_id_cuenta'];
	$where.=" AND rel_aux_cont.id_contab='$id'";

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
				rel_aux_cont
			ON
				auxiliares.id_auxiliares=rel_aux_cont.id_auxiliar ".$where;
$row=& $conn->Execute($Sql);
//die($Sql);
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
				auxiliares.cuenta_contable as id,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre as nombre2
				
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
		
			INNER JOIN
				rel_aux_cont
			ON
				auxiliares.id_auxiliares=rel_aux_cont.id_auxiliar	
					INNER JOIN 
				cuenta_contable_contabilidad
			ON
				rel_aux_cont.id_contab=cuenta_contable_contabilidad.id
			".$where;
			//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
////////////////////////////////////////////////////	 
$responce->rows[$i]['id']=$row->fields("id_auxiliares");
$responce =$row->fields("id_auxiliares")."*".$row->fields("id")."*".$row->fields("cuenta_contable")."*".$row->fields("cuenta_auxiliar")."*".$row->fields("nombre")."*".$row->fields("comentarios")."*".$row->fields("nombre2");	
}else
$responce="vacio";
// return the formated data
die ($responce);

?>