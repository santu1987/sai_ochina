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
$busq_nombre=strtoupper($_GET["busq_nombre"]);
$cuenta=$_POST['cuentas_por_pagar_integracion_cuenta'];

$where = "WHERE 1=1";
$where.="AND cuenta_contable_contabilidad.cuenta_contable='$cuenta'";
if($busq_nombre!="") $where.= " AND upper(nombre) like  '%$busq_nombre%'";
	
		
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
				id,
				requiere_auxiliar,
				requiere_proyecto,
				requiere_unidad_ejecutora,
				requiere_utilizacion_fondos,
				cuenta_contable,
				nombre,
				case tipo 
					when 't' then 'TITULO'				
					when 'd' then 'DETALLE'
					when 'e' then 'ENCABEZADO'
				end AS tipo_cuenta
			FROM 
				cuenta_contable_contabilidad   
			".$where."
			ORDER BY 
				cuenta_contable 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
			if (!$row->EOF) 
			{
						$responce->rows[$i]['id']=$row->fields("id");
						$responce =$row->fields("id")."*".$row->fields("cuenta_contable")."*".$row->fields("nombre");
					
				}else
				$responce="vacio";
			echo ($responce);
							
?>
