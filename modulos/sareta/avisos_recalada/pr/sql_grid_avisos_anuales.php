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
$where = " WHERE  lower(sareta.planilla.nombre_buque) like '%$busq_nombre%'";
if ($busq_nombre!='')

$limit = 15;
if(!$sidx) $sidx =1;

$Sql.="SELECT 
 	sareta.planilla.matricula_buque,
  	sareta.planilla.call_sign_buque,
  	sareta.planilla.nombre_buque,
  	sareta.planilla.registro_bruto_buque,
  	sareta.planilla.id_ley_buque,
	sareta.ley.descripcion 
	AS descripcion_ley,
  	sareta.planilla.tarifa_buque,
  	sareta.planilla.id_bandera_buque,
  	sareta.bandera.nombre 
	AS nombre_bandera,
  	sareta.planilla.id_clase_buque,
  	clase.nombre
	AS nombre_clase,
  	sareta.planilla.id_actividad_buque,
  	actividad.nombre
	AS nombre_actividad,
  	sareta.planilla.obs,
  	sareta.planilla.ano_pago 
  	FROM 
			sareta.planilla
  	INNER JOIN
			sareta.bandera
		ON 
		 sareta.bandera.id=id_bandera_buque
	INNER JOIN
		 sareta.clases_buques AS  clase
		ON
			clase.id_clases_buques=id_clase_buque
	INNER JOIN
			sareta.tipo_actividad AS actividad
		ON
			actividad.id_tipo_actividad=id_actividad_buque
	INNER JOIN
			sareta.ley
		ON	
			sareta.ley.id_ley=id_ley_buque
			".$where."
			ORDER BY 
				sareta.planilla.nombre_buque,
				sareta.planilla.ano_pago DESC
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
										
										$row->fields("ano_pago"),
										utf8_encode($row->fields("matricula_buque")),
										utf8_encode($row->fields("call_sign_buque")),
										substr($row->fields("nombre_buque"),0,12),
										utf8_encode($row->fields("nombre_buque")),
										
										
										substr($row->fields("nombre_clase"),0,12),
										utf8_encode($row->fields("nombre_clase")),
										
										substr($row->fields("nombre_actividad"),0,12),
										utf8_encode($row->fields("nombre_actividad")),
										
										substr($row->fields("nombre_bandera"),0,12),
										utf8_encode($row->fields("nombre_bandera")),
										number_format($row->fields("registro_bruto_buque"),2,',','.'),
										
										substr($row->fields("descripcion_ley"),0,12),
										number_format($row->fields("tarifa_buque"),2,',','.'),
										
										utf8_encode($row->fields("obs"))
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
<? ?>