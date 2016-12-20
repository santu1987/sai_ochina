<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
//die($sql_ant);
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
$where.="WHERE 1=1 and documentos_cxp.estatus!='3' ";
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
						
						$where		
						";
	
//".$where."	
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
							 documentos_cxp.beneficiario,
							 documentos_cxp.cedula_rif_beneficiario,
							 tipo_documento_cxp.nombre as doc,
							 documentos_cxp.retencion_ex1,
							 documentos_cxp.retencion_ex2,
							 documentos_cxp.desc_ex1,
							 documentos_cxp.desc_ex2,
							 documentos_cxp.pret1,
							 documentos_cxp.pret2,
							 documentos_cxp.amortizacion,
							 aplica_bi_ret_ex1,
							 aplica_bi_ret_ex2,
							 documentos_cxp.fecha_documento,
							 documentos_cxp.n_comprobante_co
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
						$where	
						ORDER BY
							 documentos_cxp.id_documentos	
						LIMIT 
						$limit 
					OFFSET 
					$start ";	
	//die($Sql);
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	
while (!$row->EOF) 
{
	$tipo=$row->fields("doc"); 
	$tipo=substr($tipo,0,5);
	$fecha_vencimiento=substr($row->fields("fecha_vencimiento"),0,10);
	$fecha_vencimiento = substr($fecha_vencimiento,8,2)."".substr($fecha_vencimiento,4,4)."".substr($fecha_vencimiento,0,4);
		if($row->fields("id_proveedor")==true)

		{		
					$id_proveedor=$row->fields("id_proveedor");
					$sql_prove="select nombre,codigo_proveedor from proveedor where id_proveedor='$id_proveedor'";
					$row_prove=& $conn->Execute($sql_prove);
					$beneficiario=$row_prove->fields("nombre");
					$codigo_proveedor=$row_prove->fields("codigo_proveedor");
					$opcion='1';
		}	

	$responce->rows[$i]['id']=$row->fields("id_documentos");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_documentos"),	
															$row->fields("id_organismo"),	
															$row->fields("ano"),
															$id_proveedor,	
															$codigo_proveedor,
															substr($beneficiario,0,20),												$row->fields("rif"),  
															$row->fields("tipo_documentocxp"),
															$row->fields("numero_documento"),
															$row->fields("numero_control"),
															$fecha_venc,
															number_format($row->fields("porcentaje_iva"),2,',','.'),
															number_format($row->fields("porcentaje_retencion_iva"),2,',','.'),
 															number_format($row->fields("porcentaje_retencion_islr"),2,',','.'),
															number_format($row->fields("monto_base_imponible"),2,',','.'),
															number_format($row->fields("monto_bruto"),2,',','.'),
															$row->fields("numero_compromiso"), 
															$row->fields("comentarios"),
															$tipo,
															$row->fields("estatus"),
															$opcion,
															number_format($total_renglon,2,',','.'),
															number_format($row->fields("retencion_ex1"),2,',','.'),
															number_format($row->fields("retencion_ex2"),2,',','.'),
															$row->fields("desc_ex1"),
															$row->fields("desc_ex2"),
															number_format($row->fields("pret1"),2,',','.'),
															number_format($row->fields("pret2"),2,',','.'),
															number_format($row->fields("amortizacion"),2,',','.'),
															number_format($resta,2,',','.'),
															$row->fields("aplica_bi_ret_ex1"),
															$row->fields("aplica_bi_ret_ex2"),
															$tipo_doc,
															number_format($porcentaje_ant,2,',','.'),
															number_format($monto_anticipo,2,',','.'),
															$fecha_doc,
															$cuenta_contable,
															$id_tipo_comprobante,
															$descripcion,
															number_format($monto_debito,2,',','.'),
															$id_cuenta_cont,
															$tipo_codigo,
															$nombre_cuenta,
															$tipo_nombre,
															substr($numero_comprobante,2,4),
															$iva_anticipos,																					
															$row->fields("n_comprobante_co"),
															$contar2,
															$fecha_orden
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>