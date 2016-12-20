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
if (isset($_GET['nombre']))
$busq_nombre = strtolower($_GET['nombre']);
$busq_documento = strtolower($_GET['documento']);

$add = " and estatus='0' and id_delegacion=$_SESSION[id_unidad_ejecutora]  and lower(nombre_buque) like '$busq_nombre%'and numero_documento::text like '$busq_documento%' ORDER BY numero_documento desc";


$limit = 15;
if(!$sidx) $sidx =1;

$Sql.="
		select 
  
  id ,
  id_buque,
  matricula_buque,
  call_sign_buque,
  nombre_buque,
  registro_bruto_buque,
  id_ley_buque,
  tarifa_buque,
  id_bandera_buque,
  (SELECT nombre FROM sareta.bandera WHERE sareta.bandera.id=planilla.id_bandera_buque) as nombre_bandera_buque,
  id_clase_buque,
  (SELECT nombre FROM sareta.clases_buques WHERE sareta.clases_buques.id_clases_buques=planilla.id_clase_buque) as nombre_clase_buque,
  id_actividad_buque,
  (SELECT nombre FROM sareta.tipo_actividad WHERE id_tipo_actividad=planilla.id_actividad_buque) as nombre_actividad_buque,
  obs,
  numero_documento,
  id_agencia_naviera,
  (SELECT nombre FROM sareta.agencia_naviera WHERE sareta.agencia_naviera.id_agencia_naviera=planilla.id_agencia_naviera) as nombre_agencia_naviera,
  estatus,
  monto,
  moneda_cambio,
  id_cambio_moneda,
  (SELECT sareta.moneda.nombre FROM sareta.cambio_moneda,sareta.moneda WHERE sareta.cambio_moneda.id=planilla.id_cambio_moneda 
  and sareta.moneda.id_moneda=sareta.cambio_moneda.id_moneda )as nombre_moneda,
  id_armador,
  (SELECT nombre FROM sareta.armador WHERE sareta.armador.id_armador=planilla.id_armador) as nombre_armador,
  id_bandera_origen,
  (SELECT nombre FROM sareta.bandera WHERE sareta.bandera.id=planilla.id_bandera_origen) as nombre_bandera_org,
  id_puerto_origen,
  (SELECT nombre FROM sareta.puerto WHERE sareta.puerto.id_puerto=planilla.id_puerto_origen) as nombre_puerto_org,
  id_bandera_recalada,
  (SELECT nombre FROM sareta.bandera WHERE sareta.bandera.id=planilla.id_bandera_recalada) as nombre_bandera_rec,
  id_puerto_recalada,
  (SELECT nombre FROM sareta.puerto WHERE sareta.puerto.id_puerto=planilla.id_puerto_recalada) as nombre_puerto_rec,
  id_bandera_destino,
  (SELECT nombre FROM sareta.bandera WHERE sareta.bandera.id=planilla.id_bandera_destino) as nombre_bandera_det,
  id_puerto_destino,
  (SELECT nombre FROM sareta.puerto WHERE sareta.puerto.id_puerto=planilla.id_puerto_destino) as nombre_puerto_det,
  id_remolcador,
  nombre_remolcador,
  matricula_remolcador,
  registro_bruto_remolcador,
  tarifa_remolcador,
  id_bandera_remolcador,
  (SELECT nombre FROM sareta.bandera WHERE sareta.bandera.id=planilla.id_bandera_remolcador) as nombre_bandera_remolcador,
  call_sign_remolcador,
  id_ley_remolcador,
  (SELECT descripcion FROM sareta.ley WHERE id_ley=planilla.id_ley_remolcador) as nombre_ley_remolcador,
  id_clase_remolcador,
  (SELECT nombre FROM sareta.clases_buques WHERE sareta.clases_buques.id_clases_buques=planilla.id_clase_remolcador) as nombre_clase_remolcador,
  id_actividad_remolcador,
  (SELECT nombre FROM sareta.tipo_actividad WHERE id_tipo_actividad=planilla.id_actividad_remolcador) as nombre_actividad_remolcador,
  fecha_recalada,
  fecha_zarpe,
  tipo_documento_codigo
  FROM sareta.planilla

   WHERE tipo_documento_codigo=1 
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
			
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
						
						
	$responce->rows[$i]['id']=$row->fields("id");
	$responce->rows[$i]['cell']=array(	
															$row->fields("numero_documento"),
															$row->fields("id"),
															$row->fields("id_buque"),
															substr(utf8_encode($row->fields("matricula_buque")),0,10),
															substr(utf8_encode($row->fields("call_sign_buque")),0,10),
															
															substr(utf8_encode($row->fields("nombre_buque")),0,10),
															$row->fields("nombre_buque"),
															number_format($row->fields("registro_bruto_buque"),2,',','.'),
															$row->fields("registro_bruto_buque"),
															
															$row->fields("id_ley_buque"),
															
															number_format($row->fields("tarifa_buque"),2,',','.'),
															$row->fields("tarifa_buque"),
															
															$row->fields("id_bandera_buque"),
															substr(utf8_encode($row->fields("nombre_bandera_buque")),0,10),
															$row->fields("nombre_bandera_buque"),
															
															$row->fields("id_clase_buque"),
															$row->fields("nombre_clase_buque"),
															
															$row->fields("id_actividad_buque"),
															$row->fields("nombre_actividad_buque"),
															
															utf8_encode($row->fields("obs")),
															
															$row->fields("id_armador"),
															$row->fields("nombre_armador"),
															
															$row->fields("id_agencia_naviera"),
															$row->fields("nombre_agencia_naviera"),
															
															$row->fields("id_cambio_moneda"),
															$row->fields("nombre_moneda"),
															number_format($row->fields("moneda_cambio"),2,',','.'),
															$row->fields("moneda_cambio"),
															
															$row->fields("id_bandera_origen"),
															$row->fields("nombre_bandera_org"),
															$row->fields("id_puerto_origen"),
															$row->fields("nombre_puerto_org"),
															
															$row->fields("id_puerto_recalada"),
															$row->fields("nombre_puerto_rec"),
															
															$row->fields("id_bandera_destino"),
															$row->fields("nombre_bandera_det"),
															$row->fields("id_puerto_destino"),
															$row->fields("nombre_puerto_det"),
															
															$row->fields("id_remolcador"),
															substr(utf8_encode($row->fields("nombre_remolcador")),0,16),
															$row->fields("nombre_remolcador"),
															$row->fields("id_bandera_remolcador"),
															$row->fields("nombre_bandera_remolcador"),
															
															$row->fields("id_ley_remolcador"),
															$row->fields("id_clase_remolcador"),
															$row->fields("id_actividad_remolcador"),
															
															$row->fields("matricula_remolcador"),
															$row->fields("call_sign_remolcador"),
															
															number_format($row->fields("tarifa_remolcador"),2,',','.'),
															$row->fields("tarifa_remolcador"),
															
															number_format($row->fields("registro_bruto_remolcador"),2,',','.'),
															$row->fields("registro_bruto_remolcador"),
															
															substr($row->fields("fecha_recalada"),0,16),
															substr($row->fields("fecha_recalada"),8,2)."/".substr($row->fields("fecha_recalada"),5,2)."/".substr($row->fields("fecha_recalada"),0,4),
														    substr($row->fields("fecha_recalada"),11,5),
															substr($row->fields("fecha_zarpe"),0,16),
															substr($row->fields("fecha_zarpe"),8,2)."/".substr($row->fields("fecha_zarpe"),5,2)."/".substr($row->fields("fecha_zarpe"),0,4),
															substr($row->fields("fecha_zarpe"),11,5),
															
															number_format($row->fields("monto"),2,',','.')													
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>