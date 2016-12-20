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
$limit = 15;
if(!$sidx) $sidx =1;
$orden=$_POST[cuentas_por_pagar_db_reporte_orden_numero_control];
$opcion=$_POST[cuentas_por_pagar_orden_db_reporte_op_oculto];
if(($orden=="")||($orden==" ")||($orden==NULL))
{
	die("a");
}
$Sql="
			SELECT 
				count(orden_pago.id_orden_pago)
			FROM 
				orden_pago
			INNER JOIN	
				organismo 
			ON
				orden_pago.id_organismo=organismo.id_organismo 
			INNER JOIN 
				proveedor 
			ON
				orden_pago.id_proveedor=proveedor.id_proveedor				
			WHERE 
				(orden_pago.id_organismo=$_SESSION[id_organismo] )
			AND
				orden_pago.orden_pago='$orden'
			
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
							orden_pago.estatus,
							orden_pago.beneficiario,
							orden_pago.cedula_rif_beneficiario
						FROM 
							orden_pago
						INNER JOIN	
							organismo 
						ON
							orden_pago.id_organismo=organismo.id_organismo 
						WHERE 
							(orden_pago.id_organismo=$_SESSION[id_organismo] )
						AND
							orden_pago.orden_pago='$orden'
							
					";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
$orden=$row->fields("orden");
$sql2="SELECT  distinct tipo_documentocxp from documentos_cxp where orden_pago='$orden'";

$row2=& $conn->Execute($sql2);
$tipo=$row2->fields("tipo_documentocxp");

if($opcion=='1')

		{		
					$id_proveedor=$row->fields("id_proveedor");
					$sql_prove="select nombre,codigo_proveedor from proveedor where id_proveedor='$id_proveedor'";
					$row_prove=& $conn->Execute($sql_prove);
					$beneficiario=$row_prove->fields("nombre");
					$codigo_proveedor=$row_prove->fields("codigo_proveedor");
					$opcion='1';
								
					
		}	
		else if($opcion=='2')
		{		
					$id_proveedor='0';
					$beneficiario=$row->fields("beneficiario");
					$codigo_proveedor=$row->fields("cedula_rif_beneficiario");
					$opcion='2';
		}
	$fecha=substr($row->fields("fecha_orden_pago"),0,10);
	$responce->rows[$i]['id']=$row->fields("id_orden_pago");
	$responce =$row->fields("id_orden_pago")."*".  $row->fields("orden")."*".$row->fields("documentos")."*".$id_proveedor."*".$codigo_proveedor."*".$beneficiario."*".  $row->fields("rif")."*".  $row->fields("ano")."*".  $row->fields("fecha_orden_pago")."*".$row->fields("comentarios")."*".$fecha."*".  $row->fields("estatus")."*".$opcion."*".$tipo;
	echo ($responce);

}
	
?>