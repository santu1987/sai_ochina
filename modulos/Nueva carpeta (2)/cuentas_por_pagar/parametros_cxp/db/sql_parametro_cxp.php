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
$where = " WHERE 1 = 1 ";
if ($busq_nombre!='')
$where.= " AND lower(organismo.nombre) like '%$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(parametros_cxp.id_organismo)
 	 		FROM 
				parametros_cxp 
			INNER JOIN
				organismo
			ON	
				parametros_cxp.id_organismo = organismo.id_organismo ".$where."
			AND
				parametros_cxp.id_organismo = ".$_SESSION['id_organismo'];
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
				parametros_cxp.id_parametros_cxp,
				parametros_cxp.ano,
				parametros_cxp.fecha_ultimo_cierre_mensual,
				parametros_cxp.usuario_cierre_mensual,
				parametros_cxp.fecha_ultimo_cierre_anual, 
				parametros_cxp.comentarios,
				parametros_cxp.ultimo_mes_cerrado,
				organismo.nombre
			 FROM
				parametros_cxp
			INNER JOIN 
				organismo 
			ON 
				parametros_cxp.id_organismo = organismo.id_organismo ".$where."
			AND
				(organismo.id_organismo =".$_SESSION['id_organismo'].")
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;";
				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$fecha_mensual=substr($row->fields("fecha_ultimo_cierre_mensual"),0,10);
$fecha_mensual = substr($fecha_mensual,8,2)."".substr($fecha_mensual,4,4)."".substr($fecha_mensual,0,4);
//--
$fecha_anual=substr($row->fields("fecha_ultimo_cierre_anual"),0,10);
$fecha_anual = substr($fecha_anual,8,2)."".substr($fecha_anual,4,4)."".substr($fecha_anual,0,4);


	$responce->rows[$i]['id']=$row->fields("id_parametros_cxp");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_parametros_cxp"),
															$row->fields("nombre"),
															$row->fields("ano"),
															$fecha_mensual,
															$fecha_anual,
															$row->fields("comentarios"),
															$row->fields("ultimo_mes_cerrado")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>