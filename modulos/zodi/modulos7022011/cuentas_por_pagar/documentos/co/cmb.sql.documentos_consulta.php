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
$where="WHERE 1=1 ";
if($_GET['cuentas_por_pagar_busqueda_tipo']!='')
{
	$bus_tipo_doc=strtoupper($_GET['cuentas_por_pagar_busqueda_tipo']);
	$where.="AND (upper(tipo_documento_cxp.nombre) like '$bus_tipo_doc')";
}

if($_GET['cuentas_por_pagar_busqueda_estatus']!='')
{
	$busq_estatus=$_GET['cuentas_por_pagar_busqueda_estatus'];
}
if($_GET['cuentas_por_pagar_busqueda_proveedor']!='')
{
	$busq_proveedor=strtolower($_GET['cuentas_por_pagar_busqueda_proveedor']);
}
if($_GET['cuentas_por_pagar_busqueda_fecha']!='')
{
	$busq_fecha_v=$_GET['cuentas_por_pagar_busqueda_fecha'];
}
if($busq_proveedor!='')
$where.="AND (lower (proveedor.nombre) LIKE '%$busq_proveedor%')";
if($busq_fecha_v!='')
$where.="AND (documentos_cxp.fecha_vencimiento='$busq_fecha_v')";
if($busq_estatus!='')
{
	if($busq_estatus=="Emitidos")
	{
		$busq_estatus=1;
		$where.="AND (documentos_cxp.estatus='$busq_estatus')";
	}
	else
	if($busq_estatus=="Anulados")
	{
		$busq_estatus=2;
		$where.="AND (documentos_cxp.estatus='$busq_estatus')";
	}
}
//*******************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_documentos) 
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
				 tipo_documento_cxp.nombre as doc,
				 documentos_cxp.retencion_ex1,
				 documentos_cxp.retencion_ex2
				 
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
			".$where."		
			ORDER BY
				 documentos_cxp.id_documentos
			LIMIT 
				$limit 
			OFFSET 
				$start ;	 	
			
";
//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
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
//--------------------------------------------------------------------------------------------
		$base=$row->fields("monto_base_imponible");
		$bruto=$row->fields("monto_bruto");
		$iva=$row->fields("porcentaje_iva");
		$porcentaje_iva_ret=$row->fields("porcentaje_retencion_iva");
		$porcentaje_islr_ret=$row->fields("porcentaje_retencion_islr");
		$islr=($bruto)*($porcentaje_islr_ret/100);
	
		//operaciones
		//si el documento es factura con anticipo
		if(($tipos_fact==$row->fields("tipo_documentocxp"))&&($row->fields("amortizacion")!='0'))
		{
			$bruto=$row->fields("monto_bruto");
			$amort=$row->fields("amortizacion");
			$m_ant=$bruto+$amort;
			$base=$m_ant;
			$islr=($m_ant)*($porcentaje_islr_ret/100);
		}
		//$retislr=$islr;
		$base_iva=($base)*($iva/100);
		$monto_restar=($base_iva)*(($porcentaje_iva_ret)/100);
		$total_iva=$base_iva-$monto_restar;//
		$ret1=$row->fields("retencion_ex1");
		$ret2=$row->fields("retencion_ex2");
		$retenciones=$ret1+$ret2;
		//-----------------------------------------
		$monto_total=($bruto)+($total_iva)-$islr;	
		$total_facturado=($bruto)+($base_iva);	
		$sub_total_ret_iva=$total_facturado-$monto_restar;	
		$monto_total=($bruto)+($total_iva)-($islr+$retenciones);
//--------------------------------------------------------------------------------------------
//
if($row->fields("numero_compromiso")!="")
{
	$ncomp=$row->fields("numero_compromiso");
 }
 	$fecha_venc = substr($row->fields("fecha_vencimiento"),0,10);
	$fecha_venc = substr($fecha_venc,8,2)."".substr($fecha_venc,4,4)."".substr($fecha_venc,0,4);
	$responce->rows[$i]['id']=$row->fields("id_documentos");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_documentos"),	
															$row->fields("id_organismo"),	
															$row->fields("ano"),
															$row->fields("id_proveedor"),	
															$row->fields("codigo_proveedor"),
															substr($row->fields("nombre"),0,20),
															$row->fields("rif"),  
															$row->fields("tipo_documentocxp"),
															$row->fields("numero_documento"),
															$row->fields("numero_control"),
															$fecha_venc,
															number_format($row->fields("monto_bruto"),2,',','.'),
															number_format($row->fields("monto_base_imponible"),2,',','.'),
															number_format($base_iva,2,',','.'),
															number_format($monto_restar,2,',','.'),
 															number_format($islr,2,',','.'),
															number_format($row->fields("retencion_ex1"),2,',','.'),
															number_format($row->fields("retencion_ex2"),2,',','.'),
															$ncomp, 
															$row->fields("comentarios"),
															$tipo,
															$row->fields("estatus"),
															number_format($monto_total,2,',','.')
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>