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
$doc=$_POST[['cuentas_por_pagar_db_numero_documento'];
//*******************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT DISTINCT
				count(id_documentos) 
			FROM 
				documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
				proveedor
			ON
				documentos_cxp.id_proveedor=proveedor.id_proveedor
			where
				documentos_cxp.numero_control='$doc'	
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
			SELECT DISTINCT
				 documentos_cxp.id_documentos,	
				 documentos_cxp.id_organismo,
				 documentos_cxp.id_proveedor,
				 proveedor.codigo_proveedor,
				 proveedor.nombre AS nombre,
				 proveedor.rif,
				 documentos_cxp.ano,
				 documentos_cxp.tipo_documentocxp,
				 documentos_cxp.numero_documento,
				 documentos_cxp.numero_control,
				 documentos_cxp.fecha_vencimiento,
				 documentos_cxp.porcentaje_iva,
				 documentos_cxp.porcentaje_retencion_iva,
				 documentos_cxp.porcentaje_retencion_islr,
				 documentos_cxp.monto_bruto,
				 documentos_cxp.monto_base_imponible,
				 documentos_cxp.numero_compromiso,
				 documentos_cxp.comentarios,
				 documentos_cxp.estatus,
				 tipo_documento_cxp.nombre as doc
				 
			FROM 
				 documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
				tipo_documento_cxp
			ON
				documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento		
			INNER JOIN
				proveedor
			ON
				documentos_cxp.id_proveedor=proveedor.id_proveedor
			where
				documentos_cxp.numero_control='$doc'	
				
			ORDER BY
				 documentos_cxp.id_documentos	
			
";
		
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	
if (!$row->EOF) 
{
$tipo=$row->fields("doc"); 
$tipo=substr($tipo,0,5);
//
if($row->fields("numero_compromiso")!="")
{
	$ncomp=$row->fields("numero_compromiso");
	
 }
 	
	
	$responce->rows[$i]['id']=$row->fields("id_proveedor");
	$responce =$row->fields("id_documentos")."*". $row->fields("id_organismo")."*". $row->fields("ano")."*".$row->fields("id_proveedor")."*".$row->fields("codigo_proveedor")."*".substr($row->fields("nombre"),0,20)."*".$row->fields("rif")."*".$row->fields("tipo_documentocxp")."*".$row->fields("numero_documento")."*".$row->fields("numero_control")."*".substr($row->fields("fecha_vencimiento"),0,10)."*".number_format($row->fields("porcentaje_iva"),2,',','.')."*".number_format($row->fields("porcentaje_retencion_iva"),2,',','.')."*".number_format($row->fields("porcentaje_retencion_islr"),2,',','.')."*".number_format($row->fields("monto_base_imponible"),2,',','.')."*".	number_format($row->fields("monto_bruto"),2,',','.')."*".$ncomp."*".$row->fields("comentarios")."*".$tipo."*".$row->fields("estatus");
echo ($responce);
    
}
	
	
?>