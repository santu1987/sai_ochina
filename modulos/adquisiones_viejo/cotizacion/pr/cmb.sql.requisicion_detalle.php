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
$unidad = $_GET['unidad'];
$requisicion = $_GET['requisicion'];
$cotizacion = $_GET['cotizacion'];
/*$secuencias ="";
//************************************************************************
//************************************************************************
if ($cotizacion != ""){
	$sql_co="SELECT 
		secuencia 
	FROM 
		\"solicitud_cotizacionD\"
	WHERE
		numero_requisicion = '$requisicion'
	AND
		numero_cotizacion = '$cotizacion'";
	$row_otro=& $conn->Execute($sql_co);
	
	if(!$row_otro->EOF)
	{
	
		while (!$row_otro->EOF){
			if($secuencias == "")
				$secuencias = $row_otro->fields("secuencia");
			else
				$secuencias = $secuencias.','. $row_otro->fields("secuencia");
		$row_otro->MoveNext();
		}
	}
}*/
//************************************************************************
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(requisicion_detalle.id_requisicion_detalle)
			FROM 
					organismo 
				INNER JOIN 
					requisicion_detalle 
				ON
					requisicion_detalle.id_organismo=organismo.id_organismo 
				
				WHERE 
					(requisicion_detalle.id_organismo=$_SESSION[id_organismo] )
				AND
					(id_unidad_ejecutora='$unidad')
				AND
					(numero_requision = '$requisicion')	
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

$Sql = "	
				SELECT 
					id_requisicion_detalle,
					secuencia,
					cantidad,
					unidad_medida.id_unidad_medida,
					unidad_medida.nombre,
					descripcion,
					coalesce(
					(select 
						true
					 from 
						\"solicitud_cotizacionD\" where (numero_requision = '$requisicion')
					 and
						(secuencia = requisicion_detalle.secuencia)
					 and
						(numero_cotizacion = '$cotizacion'))
					 ,false)AS cotizado
				FROM 
					organismo 
				INNER JOIN 
					requisicion_detalle
				ON
					requisicion_detalle.id_organismo=organismo.id_organismo 
				INNER JOIN 
					unidad_medida 
				ON
					requisicion_detalle.id_unidad_medida = unidad_medida.id_unidad_medida 
				WHERE 
					(requisicion_detalle.id_organismo=$_SESSION[id_organismo] )
				AND
					(numero_requision = '$requisicion')	
				ORDER BY 
					requisicion_detalle.id_requisicion_detalle 
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{

	$responce->rows[$i]['id']=$row->fields("id_requisicion_detalle");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_requisicion_detalle"),
															$row->fields("secuencia"),
															$row->fields("cantidad"),
															$row->fields("id_unidad_medida"),
															$row->fields("nombre"),
															$row->fields("descripcion"),
															$row->fields("cotizado")
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>