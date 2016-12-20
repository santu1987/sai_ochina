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

$where="WHERE 1=1 
		AND	(orden_pago.id_organismo=".$_SESSION[id_organismo]." )
		AND orden_pago.estatus!='3'
";
/*if($_GET['busq_proveedor_orden']!="")
{
	$busq_proveedor_orden=$_GET['busq_proveedor_orden'];
	$where.="AND (lower (proveedor.nombre) LIKE '%$busq_proveedor_orden%')";
}
*/
if($_GET['busq_ano']!="")
{
	$busq_fecha_orden=$_GET['bus_ano'];
	$where.="AND (orden_pago.ano='$busq_ano')";
}
if($_GET['busq_fecha_orden']!="")
{
	$busq_fecha_orden=$_GET['busq_fecha_orden'];
	$where.="AND (orden_pago.fecha_orden_pago='$busq_fecha_orden')";
}
	$where.="AND (orden_pago.id_proveedor!='0')";
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(orden_pago.id_orden_pago)
			FROM 
				orden_pago
			INNER JOIN	
				organismo 
			ON
				orden_pago.id_organismo=organismo.id_organismo 
						
		 ".$where."
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
					orden_pago.id_proveedor,
					orden_pago.orden_pago AS orden,
					orden_pago.id_orden_pago,
					orden_pago.documentos,
					orden_pago.ano,
					orden_pago.fecha_orden_pago,
					orden_pago.comentarios,
					orden_pago.estatus as estatus,
					orden_pago.beneficiario,
					orden_pago.cedula_rif_beneficiario
				FROM 
					orden_pago
				INNER JOIN	
					organismo 
				ON
					orden_pago.id_organismo=organismo.id_organismo 
				".$where."
				ORDER BY 
					orden_pago.orden_pago
			";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$orden=$row->fields("orden");
$sql2="SELECT  distinct tipo_documentocxp from documentos_cxp where orden_pago='$orden'";

$row2=& $conn->Execute($sql2);
$tipo=$row2->fields("tipo_documentocxp");

//---
					$id_proveedor=$row->fields("id_proveedor");
					$sql_prove="select nombre,codigo_proveedor from proveedor where id_proveedor='$id_proveedor'";
					$row_prove=& $conn->Execute($sql_prove);
					$beneficiario=$row_prove->fields("nombre");
					$codigo_proveedor=$row_prove->fields("codigo_proveedor");
					$opcion='1';
								
					
//-
	$fecha=substr($row->fields("fecha_orden_pago"),0,10);
	$responce->rows[$i]['id']=$row->fields("id_orden_pago");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_orden_pago"),
															$row->fields("orden"),
															$row->fields("documentos"),
															$id_proveedor,
															$codigo_proveedor,
															$beneficiario,
															$row->fields("rif"),
															$row->fields("ano"),
															$row->fields("fecha_orden_pago"),
															$row->fields("comentarios"),
															$fecha,
															$row->fields("estatus"),
															$opcion,
															$tipo
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>