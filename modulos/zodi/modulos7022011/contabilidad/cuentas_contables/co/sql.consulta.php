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

$busq_cuenta=$_GET["busq_cuenta"];
$busq_nombre=strtoupper($_GET["busq_nombre"]);
$where = "WHERE 1=1";


if($busq_cuenta!="") $where.= " AND (cuenta_contable_contabilidad.cuenta_contable) like  '%$busq_cuenta%'";
if($busq_nombre!="") $where.= " AND upper(cuenta_contable_contabilidad.nombre) like  '%$busq_nombre%'";
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(cuenta_contable_contabilidad.id) 
			FROM 
				cuenta_contable_contabilidad  
			$where	
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
				cuenta_contable_contabilidad.id_cuenta_suma,
				cuenta_contable_contabilidad.id_cuenta_presupuesto,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre,
				CASE cuenta_contable_contabilidad.tipo 
					when 't' then 'TOTAL'				
					when 'd' then 'DETALLE'
					when 'e' then 'ENCABEZADO'
				END AS tipo_cuenta,
				cuenta_contable_contabilidad.tipo,
				cuenta_suma.id AS id_cuenta_suma,
				cuenta_suma.cuenta_contable AS cuenta_suma,
				cuenta_contable_contabilidad.id_cuenta_presupuesto,
				cuenta_contable_contabilidad.id_naturaleza_cuenta,
				cuenta_contable_contabilidad.requiere_auxiliar,
				cuenta_contable_contabilidad.requiere_proyecto,
				cuenta_contable_contabilidad.requiere_unidad_ejecutora,
				cuenta_contable_contabilidad.requiere_utilizacion_fondos,
				clasificador_presupuestario.partida || clasificador_presupuestario.generica || clasificador_presupuestario.especifica || clasificador_presupuestario.subespecifica  as cuenta_presupuesto				
			FROM 
				cuenta_contable_contabilidad 
			LEFT JOIN 
				clasificador_presupuestario 
			ON
				clasificador_presupuestario.id_clasi_presu=cuenta_contable_contabilidad.id_cuenta_presupuesto 
			LEFT JOIN 
				cuenta_contable_contabilidad AS cuenta_suma 
			ON 
				cuenta_contable_contabilidad.id_cuenta_suma=cuenta_suma.id 
			".$where."
			ORDER BY 
				cuenta_contable 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
//die($Sql);
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
															$row->fields("id"),
															$row->fields("cuenta_contable"),
															strtoupper($row->fields("nombre")),
															$row->fields("tipo"),
															$row->fields("cuenta_suma"),
															$row->fields("requiere_auxiliar"),
															$row->fields("requiere_proyecto"),
															$row->fields("requiere_unidad_ejecutora"),
															$row->fields("requiere_utilizacion_fondos"),
																																					$row->fields("cuenta_presupuesto")

														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>
