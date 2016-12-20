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

		$add = " WHERE id_puerto=id_puerto and id=id_bandera and sareta.bandera.nombre='VENEZUELA'";
		if ($busq_nombre!='')
		$add.= " AND lower(sareta.puerto.nombre) like '%$busq_nombre%' ";
		$limit = 15;
		if(!$sidx) $sidx =1;
		$Sql="
					SELECT 
						count(id_puerto) 
					FROM 
						sareta.puerto,sareta.bandera
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
						id_puerto ,
						puerto.id_bandera,
						puerto.nombre,
						bandera.nombre as nombre_bandera,
						puerto.obs
					FROM 
						sareta.puerto, sareta.bandera ".$add." 
					ORDER BY 
						sareta.bandera.nombre,sareta.puerto.nombre
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
			$responce->rows[$i]['id_puerto']=$row->fields("id_puerto");
		
			$responce->rows[$i]['cell']=array(	
																	$row->fields("id_puerto"),
																	utf8_encode($row->fields("nombre")),
																	$row->fields("id_bandera"),
																	utf8_encode($row->fields("nombre_bandera")),
																	substr($row->fields("obs"),0,40),
																	$row->fields("obs")
																	//$row->fields("icono")
																);
			$i++;
			$row->MoveNext();
		}
		// return the formated data
		echo $json->encode($responce);

?>