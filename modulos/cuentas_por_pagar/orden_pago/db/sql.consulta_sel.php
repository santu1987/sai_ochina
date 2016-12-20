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
$ano = date('Y');
//die("holaaa");
//************************************************************************
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//--
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
///--




if(!$sidx) $sidx =1;
	if(isset($_GET[vector]))
	{
		$vector=$_GET[vector];
		
	}
	if(isset($_GET[proveedor]))
	{
		$proveedor=$_GET[proveedor];
		$where.=" WHERE (documentos_cxp.id_organismo=$_SESSION[id_organismo])
				  AND (documentos_cxp.id_proveedor='$proveedor')";
		if(isset($_GET[ano]))
		{
			$ano=$_GET[ano];
			if($ano!="")
			{	
				$where.=" AND documentos_cxp.ano='$ano'";
			  }
   		 }
	}else
	if(isset($_GET[beneficiario]))
	{
		$proveedor=$_GET[proveedor];
		$where.=" WHERE (documentos_cxp.id_organismo=$_SESSION[id_organismo])
				  AND (documentos_cxp.cedula_rif_beneficiario='$beneficiario')";
		if(isset($_GET[ano]))
		{
			$ano=$_GET[ano];
			if($ano!="")
			{	
				$where.=" AND documentos_cxp.ano='$ano'";
			  }
   		 }
	}

$Sql="SELECT 
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

$limit = 15;
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
				  documentos_cxp.monto_base_imponible2,
				 documentos_cxp.porcentaje_iva2 ,
				 documentos_cxp.retencion_iva2,
				  documentos_cxp.sustraendo
			FROM 
				 documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			
			$where
			ORDER BY
				 documentos_cxp.id_documentos,$sidx $sord 
		LIMIT 
			$limit 
		OFFSET 
			$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

/*while (!$row->EOF) 
{*/
$id_detalles = split( ",", $vector );
sort($id_detalles);
				$contador=count($id_detalles);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
				$monto_total=0;
if($vector!="")
{			
			$monto_total=0;
				while($i < $contador)
					{	
					
								$orden=$id_detalles[$i];
										$Sql2="
												SELECT 
													 documentos_cxp.porcentaje_iva,
													 documentos_cxp.porcentaje_retencion_iva,
													 documentos_cxp.porcentaje_retencion_islr,
													 documentos_cxp.monto_bruto,
													 documentos_cxp.monto_base_imponible,
													 documentos_cxp.numero_compromiso,
													 documentos_cxp.amortizacion,
	       											 documentos_cxp.retencion_ex1,
			                                    	 documentos_cxp.retencion_ex2,
													 documentos_cxp.tipo_documentocxp,
													  documentos_cxp.monto_base_imponible2,
													 documentos_cxp.porcentaje_iva2 ,
													 documentos_cxp.retencion_iva2,
													 documentos_cxp.sustraendo
												FROM 
													documentos_cxp
												WHERE
													documentos_cxp.id_documentos='$orden'
													
									";
								//	die($Sql2);
									$row_orden=& $conn->Execute($Sql2);
							$pagar="0";
							
							if(!$row_orden->EOF) 
							{
								//datos
								$iva=$row_orden->fields("porcentaje_iva");
								$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
								$porcentaje_islr_ret=$row_orden->fields("porcentaje_retencion_islr");
								$retencion1=$row_orden->fields("retencion_ex1");
								$retencion2=$row_orden->fields("retencion_ex2");
								$numero_compromiso=$row_orden->fields("numero_compromiso");
								//calculo doc=factura
								$base=$row_orden->fields("monto_base_imponible");
								$bruto=$row_orden->fields("monto_bruto");
								$sustraendo=$row_orden->fields("sustraendo");
								$islr=(($bruto)*($porcentaje_islr_ret/100))-$sustraendo;
								//calculo doc-factura con amortizacion
								if(($tipos_fact==$row_orden->fields("tipo_documentocxp"))&&($row_orden->fields("amortizacion")!='0'))
								{
								$amortizacion=$row_orden->fields("amortizacion");
								$bruto=$row_orden->fields("monto_bruto");
								$monto_ant=$amortizacion+$bruto;
								$base=$monto_ant;
								//$base=$bruto;
								$islr=(($monto_ant)*($porcentaje_islr_ret/100))-$sustraendo;
								}
								//calculos
								//si tiene doble iva
									if($row_orden->fields("monto_base_imponible2")!='0')
									{
										$base=$row_orden->fields("monto_base_imponible");
										$iva=$row_orden->fields("porcentaje_iva");

										$base_iva1=($base*$iva)/100;
										$base2=$row_orden->fields("monto_base_imponible2");
										$iva2=$row_orden->fields("porcentaje_iva2");
										$base_iva2=($base2)*($iva2/100);
										$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
										$porcentaje_iva_ret2=$row_orden->fields("retencion_iva2");
									
									$monto_restar=($base_iva1*$porcentaje_iva_ret)/100;
									$monto_restar2=($base_iva2*$porcentaje_iva_ret2)/100;
									
									$total_iva=$base_iva-$monto_restar;//
									$total_iva2=$base_iva2-$monto_restar2;//
									$ret_ivass=$monto_restar+$monto_restar2;
									$monto_def=(($bruto)+($total_iva)+($total_iva2))-($islr);
									///
									
									$monto_def=($bruto+$base_iva1+$base_iva2)-($ret_ivass);
									//$monto_def=$monto_restar;
									///
									$retenciones=($retencion1+$retencion2);
									$monto_def1=$monto_def-$retenciones;
									$pagar=$pagar+$monto_def1;
									$pagar=round($pagar,2);
									}
								//else sino tiene doble iva
								else
								{
								$base_iva=($base)*($iva/100);
								$monto_restar=($base_iva)*(($porcentaje_iva_ret)/100);
								$total_iva=$base_iva-$monto_restar;//
								$monto_def=($bruto)+($total_iva)-$islr;
								$retenciones=($retencion1+$retencion2);
								$monto_def1=$monto_def-$retenciones;
								$pagar=$pagar+$monto_def1;
								}
														
							}
					$monto_total=$monto_total+$pagar;
					/*$sql_pago="	SELECT 
									monto_pagar
								FROM 
									\"orden_pagoE\"
								WHERE(\"orden_pagoE\".\"id_orden_pagoE\"='$id_detalles[$i]')";
					
					$rowd= $conn->Execute($sql_pago);
					//echo($sql_pago);
					$monto_pago=$rowd->fields("monto_pagar");
					$monto_total=$monto_total + $monto_pago;*/
					$i=$i+1;	
						
				}		
	//$responce->rows[$i]['id']=$row->fields("numero_orden_pago");
	//$responce =$monto_total;
	$responce =$monto_total ."*". $numero_compromiso;
	
echo ($responce);
 }   



?>