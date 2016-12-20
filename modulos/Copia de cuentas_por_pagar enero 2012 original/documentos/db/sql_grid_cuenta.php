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

if(isset($_GET["busq_partida"]))
$busq_partida =$_GET["busq_partida"];
$where = "WHERE 1=1";

$busq_cuenta=strtoupper($_GET["busq_cuenta"]);
$busq_nom=strtoupper($_GET["busq_nom"]);
$busq_partida=strtoupper($_GET["busq_partida"]);
if($busq_partida!='')
{
	$partida=substr($busq_partida,0,3);
	$generica=substr($busq_partida,3,2);
	$especifica=substr($busq_partida,5,2);
	$subespecifica=substr($busq_partida,7,2);
	if($partida!="") $where_cl.=" AND clasificador_presupuestario.partida='$partida'";
	if($generica!="") $where_cl.="AND clasificador_presupuestario.generica='$generica'";
	if($especifica!="") $where_cl.="AND clasificador_presupuestario.especifica='$especifica'";
	if($subespecifica!="") $where_cl.="AND clasificador_presupuestario.subespecifica='$subespecifica'";

}


if($busq_cuenta!="") $where.= " AND (cuenta_contable_contabilidad.cuenta_contable) like  '%$busq_cuenta%'";
if($busq_nom!="") $where.= " AND upper(cuenta_contable_contabilidad.nombre) like  '%$busq_nom%'";

	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(cuenta_contable_contabilidad.id) 
			FROM 
				cuenta_contable_contabilidad 
			INNER JOIN 
				clasificador_presupuestario
			ON
				clasificador_presupuestario.id_clasi_presu=cuenta_contable_contabilidad.id_cuenta_presupuesto 
			$where
		    $where_cl

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
			SELECT 
				cuenta_contable_contabilidad.id,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre,
				case cuenta_contable_contabilidad.tipo 
					when 't' then 'TITULO'				
					when 'd' then 'DETALLE'
					when 'e' then 'ENCABEZADO'
				end AS tipo_cuenta
			FROM 
				cuenta_contable_contabilidad  
			INNER JOIN 
				clasificador_presupuestario
			ON
				clasificador_presupuestario.id_clasi_presu=cuenta_contable_contabilidad.id_cuenta_presupuesto		
	 
			".$where."
			".$where_cl."
			ORDER BY 
				cuenta_contable 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
//die($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															$row->fields("cuenta_contable"),
															$row->fields("nombre"),
															$row->fields("tipo_cuenta")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>