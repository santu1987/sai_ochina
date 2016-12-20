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
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_presupuesto_ley)
 	 		FROM 
				presupuesto_ley
			WHERE (id_organismo = ".$_SESSION['id_organismo'].")
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
			SELECT presupuesto_ley.*, accion_especifica.denominacion,  unidad_ejecutora.nombre
   FROM presupuesto_ley
   INNER JOIN accion_especifica ON presupuesto_ley.id_accion_especifica = accion_especifica.id_accion_especifica
   INNER JOIN unidad_ejecutora ON presupuesto_ley.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora 
   INNER JOIN organismo ON presupuesto_ley.id_organismo = organismo.id_organismo
   WHERE (organismo.id_organismo = ".$_SESSION['id_organismo'].")  
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
$i=0;
while (!$row->EOF) 
{
$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
$monto = $row->fields("enero")+$row->fields("febrero")+$row->fields("marzo")+$row->fields("abril")+$row->fields("mayo")+$row->fields("junio")+$row->fields("julio")+$row->fields("agosto")+$row->fields("septiembre")+$row->fields("octubre")+$row->fields("noviembre")+$row->fields("diciembre");
	$responce->rows[$i]['id']=$row->fields("id_presupuesto_ley");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_presupuesto_ley"),
															$row->fields("id_organismo"),
															$row->fields("id_accion_central"),
															$row->fields("nombre"),
															$row->fields("denominacion"),
															$row->fields("id_proyecto"),
															$row->fields("anio"),
															$partida,
															$row->fields("comentario"),
															$row->fields("id_accion_especifica"),
															$row->fields("id_unidad_ejecutora"),
															$row->fields("enero"),
															$row->fields("febrero"),
															$row->fields("marzo"),
															$row->fields("abril"),
															$row->fields("mayo"),
															$row->fields("junio"),
															$row->fields("julio"),
															$row->fields("agosto"),
															$row->fields("septiembre"),
															$row->fields("octubre"),
															$row->fields("noviembre"),
															$row->fields("diciembre"),
															$row->fields("total_monto"),
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>