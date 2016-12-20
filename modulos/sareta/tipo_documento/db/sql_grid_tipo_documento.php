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

$sql3 = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql3);
									$delegacion=0;
									if(!$row1->EOF){
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}


if (isset($_GET['nombre']))
$busq_nombre = strtolower($_GET['nombre']);
$add = " WHERE sareta.tipo_documento.id=sareta.tipo_documento.id";
if ($busq_nombre!='')
$add.= " AND lower(sareta.tipo_documento.nombre) like '%$busq_nombre%' and sareta.tipo_documento.id_delegacion=".$delegacion." ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id) 
			FROM 
				sareta.tipo_documento
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
				  tipo_documento.id,
				  nombre ,
				  factor ,
				  vida_propia ,
				  pago_inmediato ,
				  pago_posterior ,
				  calculo_mora ,
				  sareta.tipo_documento.id_numero_control,
				  numero_control.descripcion,
				  ultimo_numero ,
				  obs,
				  id_nombre_documento,
				  nombre_documento.descripcion AS descripcion_documento
			FROM 
				sareta.tipo_documento 
			INNER JOIN
				sareta.numero_control AS numero_control 
			ON
				numero_control.id_numero_control= tipo_documento.id_numero_control
			INNER JOIN
				sareta.nombre_documento AS nombre_documento
			ON
				nombre_documento.id= tipo_documento.id_nombre_documento
			where lower(sareta.tipo_documento.nombre) like '%$busq_nombre%'  and sareta.tipo_documento.id_delegacion=".$delegacion."
			
			ORDER BY 
				sareta.tipo_documento.nombre
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
	$factor=$row->fields("factor");
	if(($factor=="t")){	$factor="SUMA";}
	else{$factor="RESTA"; }
	
	$vida_propia=$row->fields("vida_propia");
	if(($vida_propia=="t")){$vida_propia="SI";}
	else{$vida_propia="NO"; }
	
	$Pg_intermedio=$row->fields("pago_inmediato");
	if(($Pg_intermedio=="t")){	$Pg_intermedio="SI";}
	else{$Pg_intermedio="NO"; }
	
	$Pg_posterior=$row->fields("pago_posterior");
	if(($Pg_posterior=="t")){$Pg_posterior="SI";}
	else{$Pg_posterior="NO"; }
	
	$calculo_mora=$row->fields("calculo_mora");
	if(($calculo_mora=="t")){	$calculo_mora="SI";}
	else{$calculo_mora="NO"; }
	
	
	$responce->rows[$i]['id']=$row->fields("id");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															substr( utf8_encode( $row->fields("nombre")),0,15),
															
															utf8_encode($row->fields("nombre")),
				
															utf8_encode($factor),
															utf8_encode($vida_propia),
															utf8_encode($Pg_intermedio),
															utf8_encode($Pg_posterior),
															utf8_encode($calculo_mora),
															
															substr( utf8_encode( $row->fields("descripcion")),0,15),

															utf8_encode($row->fields("descripcion")),
															substr(utf8_encode($row->fields("descripcion_documento")),0,15),
															$row->fields("id_numero_control"),
															$row->fields("id_nombre_documento"),
															$row->fields("ultimo_numero"),
															utf8_encode($row->fields("obs"))
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>