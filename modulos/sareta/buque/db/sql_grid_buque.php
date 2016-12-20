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
$where = " WHERE  lower(buque.nombre) like '%$busq_nombre%'";
if ($busq_nombre!='')

$limit = 15;
if(!$sidx) $sidx =1;

$Sql.="
		SELECT
			buque.id,
			matricula,
			call_sign,
			sareta.buque.nombre 
				AS nombre_buque,	
			sareta.bandera.id 
				AS id_bandera,
			sareta.bandera.nombre 
				AS nombre_bandera,
			r_bruto,
			actividad.id_tipo_actividad 
				AS id_actividad,
			actividad.nombre 
				AS nombre_actividad,
			clase.id_clases_buques 
				AS id_clase,
			clase.nombre 
				AS clase_buque,
			buque.nacionalidad 
				AS nac,
			buque.pago_anual,
			ley.id_ley,
			ley.descripcion,
			buque.exonerado,
			buque.comentario
		FROM  
			sareta.buque
		
		INNER JOIN
			sareta.bandera
		ON
			sareta.bandera.id=id_bandera
		INNER JOIN
			sareta.tipo_actividad 
			AS actividad
		ON
			actividad.id_tipo_actividad=id_actividad
		INNER JOIN
			sareta.clases_buques 
			AS  clase
		ON
			clase.id_clases_buques=id_clase
		INNER JOIN
			sareta.ley AS ley
		ON
			ley.id_ley=sareta.buque.id_ley
			".$where;

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
					$nacional=$row->fields("nac");
						if(($nacional=="t"))
						{	
						$nacional="NACIONAL";
						}
						else
						{
						$nacional="EXTRANJERO";	
						}
						
						$pago_anual=$row->fields("pago_anual");
							if(($pago_anual=="t"))
							{	
							$pago_anual="SI";
							}
							else
							{
							$pago_anual="NO";	
							}
							$exonerado=$row->fields("exonerado");
								if(($exonerado=="t"))
								{	
								$exonerado="SI";
								}
								else
								{
								$exonerado="NO";	
								}
						
	$responce->rows[$i]['id']=$row->fields("id");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															substr(utf8_encode($row->fields("matricula")),0,10),
															substr(utf8_encode($row->fields("call_sign")),0,10),
															
															substr(utf8_encode($row->fields("nombre_buque")),0,10),
															$row->fields("nombre_buque"),
															
															$row->fields("id_bandera"),
															substr(utf8_encode($row->fields("nombre_bandera")),0,10),
															$row->fields("nombre_bandera"),
															
															number_format($row->fields("r_bruto"),2,',','.'),
															
															$row->fields("id_actividad"),
															substr(utf8_encode($row->fields("nombre_actividad")),0,10),
															$row->fields("nombre_actividad"),
															
															$row->fields("id_clase"),
															substr(utf8_encode($row->fields("clase_buque")),0,10),
															$row->fields("clase_buque"),
															
															$nacional,
															$pago_anual,
															
															$row->fields("id_ley"),
															substr(utf8_encode($row->fields("descripcion")),0,14),
															substr(str_replace("\n"," ",$row->fields("descripcion")),0,60),
															
															$exonerado,
															utf8_encode($row->fields("comentario"))
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>