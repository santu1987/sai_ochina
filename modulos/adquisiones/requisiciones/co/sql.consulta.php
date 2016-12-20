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
$ano = date('Y');
if(!$sidx) $sidx =1;

$numero_requision = $_GET['numero_requision'];

$where = 'WHERE (1=1)';

if ($numero_requision != "")
	$where.= " AND (numero_requision = '".$numero_requision."') AND (id_unidad_ejecutora = ".$_SESSION["id_unidad_ejecutora"].")";
else
	$where.= " AND (numero_requision = '0') AND (id_unidad_ejecutora = ".$_SESSION["id_unidad_ejecutora"].")";

$Sql="
			SELECT 
				count(id_requisicion_detalle) 
			FROM 
				requisicion_detalle	
			$where	
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

$limit = 15;
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
			requisicion_detalle.id_requisicion_detalle,
			requisicion_detalle.secuencia,
				requisicion_detalle.cantidad, 	 
				requisicion_detalle.id_unidad_medida, 	 
				requisicion_detalle.descripcion,
				requisicion_detalle.numero_requision,
				unidad_medida.nombre 
		FROM 
			requisicion_detalle
		INNER JOIN
			unidad_medida
		ON
			unidad_medida.id_unidad_medida = requisicion_detalle.id_unidad_medida
		$where	
		ORDER BY 
			$sidx $sord 
		LIMIT 
			$limit 
		OFFSET 
			$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("secuencia");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_requisicion_detalle"),
															$row->fields("secuencia"),
															number_format($row->fields("cantidad"), 0, ',', '.'),
															$row->fields("id_unidad_medida"),
															$row->fields("nombre"),
															$row->fields("descripcion"),
															$row->fields("numero_requision"),
															'<img id="requisiones_pr_btn_elminar" src="imagenes/close.png" onclick="jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta.php?numero_requision="+'.$row->fields("id_requisicion_detalle").',page:1}).trigger("reloadGrid");"   />'
														);
	$i++;
	$row->MoveNext();
}
$id = $_GET['id'];
$descripcion = $_GET['descripcion'];
if($_GET['id'] !=""){


$sqlBus = "SELECT * FROM requisicion_detalle WHERE (id_requisicion_detalle = ".$id.")";
$row=& $conn->Execute($sqlBus);
$sql="
UPDATE 
	requisicion_detalle
SET 
	descripcion= '$descripcion' 
WHERE 
	(id_requisicion_detalle = ".$id.")";


	if (!$conn->Execute($sql)) {
		die ('Error al actualizar: '.$conn->ErrorMsg());
	}else{
		die("Ok,".$numero_requision);
	}
}
// return the formated data
echo $json->encode($responce);
?>