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
//VARIABLES DE BUSQUEDAD

$unidad = $_GET['unidad'];
$id = $_GET['requisicion_id'];
$requisicion = $_GET['requisicion'];
//************************************************************************
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
//************************************************************************

$sql_requisicion = "
SELECT 
	numero_requisicion,  
	fecha_requisicion, 
	asunto, 
	estatus, 
	fecha_anula, 
	fecha_requerida, 
	ultimo_usuario, 
	fecha_actualizacion
FROM 
	requisicion_encabezado
WHERE
	id_organismo = $_SESSION[id_organismo]
AND
	id_unidad_ejecutora = $unidad
AND
	numero_requisicion = '$requisicion'
";
//echo $sql_requisicion.'<br>';
$bus_requisicion=& $conn->Execute($sql_requisicion);
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

$sql_solicitud = "
SELECT 
	estatus
FROM 
	\"solicitud_cotizacionE\"
WHERE
	id_organismo = $_SESSION[id_organismo]
AND
	id_unidad_ejecutora = $unidad
AND
	id_requisicion = $id
";
//echo $sql_solicitud.'<br>';
$bus_solicitud=& $conn->Execute($sql_solicitud);
if(!$bus_solicitud->EOF){
	$estatus_solicitud = $bus_solicitud->fields("estatus");
	if ($estatus_solicitud >=1)
		$respuesta = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
	else
		$respuesta = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
}
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$sql_orden_compra="
SELECT 
	numero_cotizacion, 
	numero_orden_compra_servicio,
	numero_compromiso, 
	estatus, 
	numero_pre_orden
FROM 
	\"orden_compra_servicioE\"
WHERE
	numero_requisicion = '$requisicion'
";
$bus_orden_compra=& $conn->Execute($sql_orden_compra);
$orden = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
$compromiso = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
if(!$bus_orden_compra->EOF){
$x = 0;
$y = 0;
	$preorden = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
	while(!$bus_orden_compra->EOF){
		if($bus_orden_compra->fields("numero_orden_compra_servicio")<>'0')
			$x++;
			//
			//echo $x.'&nbsp;'.$bus_orden_compra->fields("numero_orden_compra_servicio").'<br>';
		$bus_orden_compra->MoveNext();
	}
	if($x<>0)
		$orden = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
	else
		$orden = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
//--------------------------------------------------------------------------------------------
	$bus_orden_compra->MoveFirst();
	while(!$bus_orden_compra->EOF){
		if($bus_orden_compra->fields("numero_compromiso")<>'0')
			$y++;
			//
			//echo $x.'&nbsp;'.$bus_orden_compra->fields("numero_orden_compra_servicio").'<br>';
		$bus_orden_compra->MoveNext();
	}
	if($y<>0)
		$compromiso = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
	else
		$compromiso = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
}else{
	$preorden = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
}
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$count = 1;
/*
if (!$roww->EOF)
{*/
//echo($count.'<br>aqui<br>');
//}
//die ($count);
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

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;$monto=0;$secu=0;
//echo $bus_solicitud->fields("estatus").'<br>';
if ($bus_requisicion->fields("estatus") >=1){
	$estatus = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
}else{
	$estatus = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
}	
if ($bus_requisicion->fields("estatus") >=2)
	$enviado = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
else
	$enviado = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
	
if (($estatus_solicitud >=0)and ($estatus_solicitud <>""))
	$solicitud = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/bien.png' />";
else
	$solicitud = "<img id='requisicion_seguimiento_co_imagen' src='imagenes/close.png' />";
	
	
	//$respuesta = $estatus_solicitud ;	
/*while (!$row->EOF) 
{
	
	//echo($cantidad."<br>");
	if($cantidad   <> ""){*/
	//$respuesta = $estatus_solicitud ;	
	$responce->rows[$i]['id']=$bus_requisicion->fields("numero_requisicion");
	
			$responce->rows[$i]['cell']=array(	
												$estatus,
												$enviado,
												$solicitud ,
												$respuesta,
												$preorden,
												$orden,
												$compromiso
											);
	
/*	}
	$i++;
	$row->MoveNext();
}*/
/*$responce->userdata['monto']=$monto;
$responce->userdata['descripcion']='Total';*/
// return the formated data
echo $json->encode($responce);
?>