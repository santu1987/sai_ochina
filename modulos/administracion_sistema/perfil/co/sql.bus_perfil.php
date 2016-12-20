<!-- LIBRERIA jQajax : Esta libreria se utilizara para los envios por Ajax -->
<!-- COMENTARIO: Implementada a la fecha 11.12.2008						 -->
<script type="text/javascript" src="utilidades/jQajax/jquery.ajaxq-0.0.1.js"></script>
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

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_perfil) 
			FROM 
				perfil
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
$limit=15;
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
if(isset($_GET["nm_mask"]))
	$nm_mask = strtolower($_GET['nm_mask']);
else
	$nm_mask = "";
if(isset($_GET["cd_mask"]))
	$cd_mask = $_GET['cd_mask'];
else
	$cd_mask = "";

$where = "WHERE 1=1";
if($nm_mask!='')
	$where.= " AND  (lower(nombre) like '%$nm_mask%')";
if($cd_mask!='')
	$where.= " AND id_perfil LIKE '$cd_mask%'";
	
// the actual query for the grid data
$Sql="
			SELECT 
				* 
			FROM 
				perfil 
			".$where."
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;/*
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_perfil");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_perfil"),
															$row->fields("nombre"),
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);*/
?>
<div>  
			   Codigo &nbsp; <input type="text" id="id_usuario" onkeydown="doSearch(arguments[0]||event)" /> &nbsp;
			   Nombre &nbsp; <input type="text" id="nombre_usuario" onkeydown="doSearch(arguments[0]||event)" /> 
			  </div>
<table width="100%">
	<tr>
		<td>Codigo</td>
		<td>Nombre</td>
		<!--<td>Seleccionar</td>-->
	</tr>
	<?
	while (!$row->EOF) 
	{
	?>
	<tr>
		<td><?=$row->fields("id_perfil");?></td>
		<td><?=$row->fields("nombre");?></td>
		<!--<td><img id="btn_eliminar" src="imagenes/null.gif" /></td>-->
	</tr>
	<?
		$row->MoveNext();
	}
	?>
</table>