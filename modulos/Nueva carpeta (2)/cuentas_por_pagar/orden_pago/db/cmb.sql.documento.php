<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
//include_once('../../../../controladores/numero_to_letras.php');
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
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
//die($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
///////////////////////////////////////////////////////////////////////////////////////////////-/-/-/-/---///////////////////////////////////////////////////////////////////////////////////////
$limit = 15;
if(!$sidx) $sidx =1;
	//$where="WHERE(documentos_cxp.id_organismo=$_SESSION[id_organismo])";
	$orden=$_GET[orden];
	
	if(isset($_GET[proveedor]))
	{
		$proveedor=$_GET[proveedor];
		$where.=" WHERE (documentos_cxp.id_organismo=$_SESSION[id_organismo])
				  AND   (documentos_cxp.id_proveedor=$proveedor)
				 
				  
				 	  
				  ";
//				  AND   (documentos_cxp.numero_compromiso!='0') 
		if(isset($_GET[ano]))
		{
			$ano=$_GET[ano];
			if($ano!="")
			{	
				$where.=" AND documentos_cxp.ano='$ano'";
			  }
   		 }
	}else
	$where="WHERE 1=2";
	if($orden=="")
			{
				$where.="AND (documentos_cxp.orden_pago='0')";
			}
	if(isset($_GET[orden]))
		{
			
			if($orden!="")
			{
			$where.="AND 
						(documentos_cxp.orden_pago='$orden'
					 OR	
					  	(documentos_cxp.orden_pago='0'))	
					";
			}			
			/*$documentos=$_GET[documentos];
			$vector = split( ",", $ordenes);
			sort($vector);*/
			
   		 }
	if(isset($_GET[tipo]))
	{
		$tipo=$_GET[tipo];
		$where.="AND documentos_cxp.tipo_documentocxp='$tipo'";
		
	}
	
	//die ($where);
	//AND id_banco='$banco'
   //AND cuenta_banco='$ncuenta'
$where.="AND documentos_cxp.estatus!='3'";	
$Sql=" SELECT 
			  count(id_documentos) 
			FROM 
				documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			
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
				 documentos_cxp.id_documentos,	
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
				 documentos_cxp.monto_base_imponible2,
				 documentos_cxp.porcentaje_iva2 ,
				 documentos_cxp.retencion_iva2
			FROM 
				 documentos_cxp
			INNER JOIN
				tipo_documento_cxp
			ON
				documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento	 		 
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			
			$where
			ORDER BY
				 documentos_cxp.id_documentos
		
";	
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	//die($Sql);
$row=& $conn->Execute($Sql);
///

///
while (!$row->EOF) 
{
	///************
	$tipo=$row->fields("tipo_documentocxp");
	$sql_doc="SELECT * from tipo_documento_cxp where id_tipo_documento='$tipo'";
	$row2=& $conn->Execute($sql_doc);
	$tipo_nom=$row2->fields("nombre");
	
	///************
//
if($row->fields("numero_compromiso")!="")
{
	$ncomp=$row->fields("numero_compromiso");
	
 }
 ///////////////////// calculo de total por factura
//datos
		$monto=$row->fields("monto_bruto");
		$iva=$row->fields("porcentaje_iva");
		$ret_iva=$row->fields("porcentaje_retencion_iva");
		$ret_islr=$row->fields("porcentaje_retencion_islr");
		$retencion1=$row->fields("retencion_ex1");
		$retencion2=$row->fields("retencion_ex2");
							
//operacion
//-/si es factura con anticipo
 if(($row->fields("tipo_documentocxp")==$tipos_fact)&&($row->fields("amortizacion")!='0'))
 {
		$monto=$row->fields("monto_bruto");
		$amort=$row->fields("amortizacion");
		//$base=$monto+$amort;
		$base=$monto;
		$p_islr=$base*($ret_islr/100);
		
 }
 //otro documento
 else
	{
		$base=$row->fields("monto_base_imponible");
		$p_islr=$monto*($ret_islr/100);
	}
		
		if($row->fields("monto_base_imponible2")!='0')
		{
			$monto_base=$row->fields("monto_base_imponible2")+$row->fields("monto_base_imponible");
			$retencion_iva=$row->fields("porcentaje_retencion_iva")+$row->fields("retencion_iva2");
			$base2=$row->fields("monto_base_imponible2");
			$ret_iva2=$row->fields("retencion_iva2");
			$iva2=$row->fields("porcentaje_iva2");
			
			
			//--- calculo---//
			$p_iva=($base*$iva)/100;
			$p_iva2=($base2*$iva2)/100;
			
			$p_ret_iva=($p_iva*$ret_iva)/100;
			$p_ret_iva2=($p_iva2*$ret_iva2)/100;

			$iva_total=($p_iva+$p_iva2)-($p_ret_iva+$p_ret_iva2);
			
			$retenciones=($retencion1+$retencion2);
			$total=($monto+$iva_total)-($p_islr)-($retenciones);
		}else
		{
			$monto_base=$row->fields("monto_base_imponible");
			$retencion_iva=$row->fields("porcentaje_retencion_iva");
			
			//--- calculo---//
		$p_iva=($base*$iva)/100;
		$p_ret_iva=($p_iva*$ret_iva)/100;
		$iva_total=$p_iva-$p_ret_iva;
		$retenciones=($retencion1+$retencion2);
		
		$total=($monto+$iva_total)-($p_islr)-($retenciones);
		//die($monto."+".$iva_total."-".$p_islr."-".$retenciones);
		}
	$responce->rows[$i]['id']=$row->fields("id_documentos");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_documentos"),	
															$row->fields("ano"),
															$row->fields("tipo_documentocxp"),
															$row->fields("numero_documento"),
															$row->fields("numero_control"),
															substr($row->fields("fecha_vencimiento"),0,10),
															number_format($row->fields("monto_bruto"),2,',','.'),
															number_format($monto_base,2,',','.'),
															number_format($row->fields("porcentaje_iva"),2,',','.'),
															number_format($retencion_iva,2,',','.'),
 															number_format($row->fields("porcentaje_retencion_islr"),2,',','.'),
															$ncomp, 
															$row->fields("comentarios"),
															$tipo_nom,
															number_format($total,2,',','.')
																																												
														);
	$i++;
	$row->MoveNext();
}
// return the formated data     number_format($row->fields("monto_pagar"),2,',','.')   $tipo_nom

echo $json->encode($responce);

?>