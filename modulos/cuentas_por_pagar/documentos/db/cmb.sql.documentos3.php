<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
function cargar_cuadro($numero_compromiso)
{
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	$Sql_partidas="SELECT 
						
								partida, 
							    generica, 
						 	    especifica, 
						 	    subespecifica
					FROM 
						\"orden_compra_servicioE\"
					INNER JOIN
						organismo
					ON
						\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
					INNER JOIN
						\"orden_compra_servicioD\"
					ON
						\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
					WHERE \"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
					//die($Sql_partidas);		
$row_partidas=& $conn->Execute($Sql_partidas);
$is=0;
	while (!$row_partidas->EOF) 
	{
	
			$is++;
			$partida2=$row_partidas->fields("partida").$row_partidas->fields("generica").$row_partidas->fields("especifica").$row_partidas->fields("sub_especifica");
			if($is==1)
				$partida=$partida2;
			else
				$partida=$partida.";".$partida2;
			$contar++;	
			$row_partidas->MoveNext();
	}
	return($contar);
}
//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");

$where.="WHERE 1=1 and documentos_cxp.estatus!='3' ";
if(isset($_GET['prove']))
{
	$busq_proveedor=strtoupper($_GET['prove']);

	if(($busq_proveedor!=""))
	{		$where.="and (upper(proveedor.nombre) like '%$busq_proveedor%')
			 ";
		 //die($sql_prove);
	}
}
if($_GET['busq_fecha_v']!='')
{
	$busq_fecha_v=$_GET['busq_fecha_v'];
}
if($_GET['benef']!='')
{
	$benef=$_GET['benef'];
	$where.="and (numero_documento) like '%$benef%'
			 ";
}

if($_GET['id']!='')
{
	$id=$_GET['id'];
	$where.="and (documentos_cxp.id_documentos)='$id'
			 ";
}

/*if(($busq_proveedor!='')&&($opcion=='1'))
$where.="AND (lower (proveedor.nombre) LIKE '%$busq_proveedor%')";
*/
if($busq_fecha_v!='')
$where.="AND (documentos_cxp.fecha_vencimiento='$busq_fecha_v')";

//*******************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
	
$opcion=$_POST[cuentas_por_pagar_db_op_oculto];
			$Sql2="
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
							proveedor.id_proveedor=documentos_cxp.id_proveedor
						
						$where		
						";
	
//".$where."	
//die($Sql);
$row2=& $conn->Execute($Sql2);
if (!$row2->EOF)
{
	$count = $row2->fields("count");
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
							 documentos_cxp.n_comprobante_co,
							 documentos_cxp.numero_comprobante,
							 documentos_cxp.monto_base_imponible2,
							 documentos_cxp.porcentaje_iva2,
							 documentos_cxp.retencion_iva2,
							 proveedor.nombre as nombre,
                             documentos_cxp.sustraendo
						FROM 
							 documentos_cxp
						INNER JOIN
							 proveedor
						ON
							proveedor.id_proveedor=documentos_cxp.id_proveedor
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
					$start 
					";	
	//".$where."	
	/*
		LIMIT 
						$limit 
					OFFSET 
					$start 
	*/
$row=& $conn->Execute($Sql);
//die($Sql);
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
$fecha_vencimiento=substr($row->fields("fecha_vencimiento"),0,10);
$fecha_vencimiento = substr($fecha_vencimiento,8,2)."".substr($fecha_vencimiento,4,4)."".substr($fecha_vencimiento,0,4);
$id_documentoss=$row->fields("id_documentos");

//
//-------------------
		if($row->fields("id_proveedor")==true)

		{		
					$id_proveedor=$row->fields("id_proveedor");
					$sql_prove="select nombre,codigo_proveedor from proveedor where id_proveedor='$id_proveedor'";
					$row_prove=& $conn->Execute($sql_prove);
					$beneficiario=$row_prove->fields("nombre");
					$codigo_proveedor=$row_prove->fields("codigo_proveedor");
					$opcion='1';
		}	
		else
		{		
					$id_proveedor='0';
					$beneficiario=$row->fields("beneficiario");
					$codigo_proveedor=$row->fields("cedula_rif_beneficiario");
					$opcion='2';
		}			
	//-------------------
	//////////////////////DIF ANTICIPO//////////////////////////////////////////////////
if(($row->fields("numero_compromiso")!="")&&($row->fields("numero_compromiso")!="0")&&($row->fields("tipo_documentocxp")!=$tipos_ant))
{
	
	
	$ncomp=$row->fields("numero_compromiso");
$sql_orden="SELECT 
											\"orden_compra_servicioE\".numero_compromiso, 
											\"orden_compra_servicioE\".numero_pre_orden,
											\"orden_compra_servicioD\".cantidad,
											\"orden_compra_servicioE\".fecha_orden_compra_servicio,
											\"orden_compra_servicioD\".monto,
											\"orden_compra_servicioD\".impuesto
										FROM 
											\"orden_compra_servicioE\"
										INNER JOIN
											organismo
										ON
											\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
										INNER JOIN
											\"orden_compra_servicioD\"
										ON
											\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$ncomp'
										
									";
					 //die($sql_orden);
									$row_orden_compra=& $conn->Execute($sql_orden);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);
										$fecha_ord2=$row_orden_compra->fields("fecha_orden_compra_servicio");
										$total_total=$total+$iva;
										$total_renglon=$total_renglon+$total_total;
										$row_orden_compra->MoveNext();
									}
}
///////////////////ANTICIPO/////////////////////////////////////////////////////////////////////////////////

if(($row->fields("numero_compromiso")!="")&&($row->fields("numero_compromiso")!="0")&&($row->fields("tipo_documentocxp")==$tipos_ant))
{
	
	
	$ncomp=$row->fields("numero_compromiso");
	//buscando si el anticipo tiene iva o no
	//die($row->fields("tipo_documentocxp")."!=".$tipos_ant);
	/*if($row->fields("tipo_documentocxp")!=$tipos_ant)
	{
	*/	$sql_ant_fact="
						select porcentaje_iva
									from
										documentos_cxp
											where 
												tipo_documentocxp='$tipos_ant'
											and
												id_documentos='$id_documentoss'			
	
							";
							////											numero_compromiso='$ncomp'	
		$row_ant_fact=& $conn->Execute($sql_ant_fact);
	//	die($sql_ant_fact);
		if(!$row_ant_fact->EOF)
		$iva_anticipos=$row_ant_fact->fields("porcentaje_iva");
		else
		$iva_anticipos=0;
//	}
												
	//
//////////////////////////////////////////////////////////////////////////////////////
$sql_orden="SELECT 
											\"orden_compra_servicioE\".numero_compromiso, 
											\"orden_compra_servicioE\".numero_pre_orden,
											\"orden_compra_servicioD\".cantidad,
											\"orden_compra_servicioE\".fecha_orden_compra_servicio,
											\"orden_compra_servicioD\".monto,
											\"orden_compra_servicioD\".impuesto
										FROM 
											\"orden_compra_servicioE\"
										INNER JOIN
											organismo
										ON
											\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
										INNER JOIN
											\"orden_compra_servicioD\"
										ON
											\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$ncomp'
										
									";
					 //die($sql_orden);
									$row_orden_compra=& $conn->Execute($sql_orden);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);
										$fecha_ord2=$row_orden_compra->fields("fecha_orden_compra_servicio");
										$total_total=$total+$iva;
										$total_renglon=$total_renglon+$total_total;
										$row_orden_compra->MoveNext();
									}
////////////////////////////////////////////////////////////////////////////////////////////////////
								$porcentaje_ant=0;
								$sql_facturas="SELECT 
														   porcentaje_iva,
														   porcentaje_retencion_iva, 
														   monto_bruto,
														   monto_base_imponible,
														   amortizacion,
														   tipo_documentocxp
											 FROM
											 			documentos_cxp
											where						   
														documentos_cxp.numero_compromiso='$ncomp'
											AND
														tipo_documentocxp='$tipos_ant'
											AND
														documentos_cxp.estatus!='3'						
																";		   
													//die($sql_facturas);
									$row_factura=& $conn->Execute($sql_facturas);
								//	$total_renglon=0;
									if(!$row_factura->EOF)
									{	//die($sql_facturas);
													
										$monto_factura_ant1=$row_factura->fields("monto_bruto");
													$iva_anticipo=$row_factura->fields("porcentaje_iva");
													$total_iva=($monto_factura_ant1*$iva_anticipo)/100;
													$monto_factura_ant=$monto_factura_ant1+$total_iva;
										$porcentaje_ant=($monto_factura_ant*100)/$total_renglon;
									}
//////////////////////////////////////////////////////////////////////////////////////	
 
 
 
 }/*else
 {
 $total_renglon="";
// $porcentaje_ant="0";
 }*/
 	/// si se encuentra cerrado y con integracion mostrar///numero_documento
	if(($row->fields("numero_comprobante")!='0')&&($row->fields("numero_comprobante")!=''))
	{
	
	
		
				$number=$row->fields("numero_comprobante");
				$id_documento=$row->fields("id_documentos");
		 		$ndoc=$row->fields("numero_documento");	
				$sql_integrado="SELECT distinct integracion_contable.id,
									integracion_contable.cuenta_contable,
									id_tipo_comprobante,
									integracion_contable.descripcion as nombre_asiento, 
									referencia,
									monto_debito, 
									tipo_comprobante.codigo_tipo_comprobante,
									tipo_comprobante.id,
									tipo_comprobante.nombre as tipo_nombre,
									cuenta_contable_contabilidad.nombre as descripcion_cuenta,
									cuenta_contable_contabilidad.id as id_cuenta_cont,
									cuenta_contable_contabilidad.nombre as nombre_cuenta,
									tipo_comprobante.nombre as tipo_nombre,
									integracion_contable.numero_comprobante,
									integracion_contable.fecha_comprobante,
									integracion_contable.secuencia
							 FROM 
								 integracion_contable 
							 inner join 
								organismo 
							 on 
								integracion_contable.id_organismo=organismo.id_organismo 
							 inner join tipo_comprobante 
							 on 
								 integracion_contable.id_tipo_comprobante=tipo_comprobante.id
							inner join 
							cuenta_contable_contabilidad
							on 
							integracion_contable.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
							inner join
								documentos_cxp
							on
								integracion_contable.numero_comprobante=documentos_cxp.numero_comprobante			
							where
								integracion_contable.debito_credito=1	
							AND
								integracion_contable.numero_comprobante='$number'		
							order by
									integracion_contable.secuencia
			";	//integracion_contable.id_documento='$id_documento'						 order by integracion_contable.id asc 
	
			//die($sql_integrado);
			$row_integrado=& $conn->Execute($sql_integrado);
			if(!$row_integrado->EOF)
			{//die($sql_integrado);
				$cuenta_contable=$row_integrado->fields("cuenta_contable");
				$id_tipo_comprobante=$row_integrado->fields("id_tipo_comprobante");
				$descripcion=$row_integrado->fields("nombre_asiento");
				$monto_debito=$row_integrado->fields("monto_debito");
				$id_cuenta_cont=$row_integrado->fields("id_cuenta_cont");
				$tipo_codigo=$row_integrado->fields("codigo_tipo_comprobante");
				$tipo_nombre=$row_integrado->fields("tipo_nombre");
				$nombre_cuenta=$row_integrado->fields("nombre_cuenta");
				$numero_comprobante=$row_integrado->fields("numero_comprobante");
				$fecha_comp=$row_integrado->fields("fecha_comprobante");
				$fecha_orden = substr($fecha_comp,0,10);
				$fecha_orden = substr($fecha_orden,8,2)."".substr($fecha_orden,4,4)."".substr($fecha_orden,0,4);
	
			}else
			if($row_integrado->EOF)
			{
				$cuenta_contable='0';
				$id_tipo_comprobante='0';
				$descripcion='';
				$monto_debito='0';
				$id_cuenta_cont=0;
				$tipo_codigo=0;
			}
		
		
	
	
	
	
	}
	if($row->fields("estatus")==1)
	{
			$cuenta_contable='0';
			$id_tipo_comprobante='0';
			$descripcion='0';
			$monto_debito='0';
			$id_cuenta_cont=0;
			$tipo_codigo=0;
	}
	/////////////////////////////////////////////////////////
	$fecha_venc = substr($row->fields("fecha_vencimiento"),0,10);
	$fecha_venc = substr($fecha_venc,8,2)."".substr($fecha_venc,4,4)."".substr($fecha_venc,0,4);
	$fecha_doc = substr($row->fields("fecha_documento"),0,10);
	$fecha_doc = substr($fecha_doc,8,2)."".substr($fecha_doc,4,4)."".substr($fecha_doc,0,4);
	
		if($row->fields("amortizacion")=='0')
		{
			$resta=($row->fields("monto_base_imponible"))-($row->fields("monto_bruto"));
			$monto_anticipo=0;
			$tipo_doc="documento";
			
		}else
		
		if($row->fields("amortizacion")!='0')
		{
			$monto_anticipo=$row->fields("amortizacion")+$row->fields("monto_bruto");
			$porc_amort=($row->fields("amortizacion")*(100))/$monto_anticipo;
			$resta=0;
			$tipo_doc="anticipo";
		}
	if(($row->fields("numero_compromiso")!="")&&($row->fields("numero_compromiso")!=0)&&($row->fields("numero_compromiso")!="NULL"))
	{
		$contar2=cargar_cuadro($row->fields("numero_compromiso"));	
	}	
	$responce=$row->fields("id_documentos")."*".	
															$row->fields("id_organismo")."*".	
															$row->fields("ano")."*".
															$id_proveedor."*".	
															$codigo_proveedor."*".
															substr($beneficiario,0,20)."*".
															$row->fields("rif")."*".  
															$row->fields("tipo_documentocxp")."*".
															$row->fields("numero_documento")."*".
															$row->fields("numero_control")."*".
															$fecha_venc."*".
															number_format($row->fields("porcentaje_iva"),2,',','.')."*".
															number_format($row->fields("porcentaje_retencion_iva"),2,',','.')."*".
 															number_format($row->fields("porcentaje_retencion_islr"),2,',','.')."*".
															number_format($row->fields("monto_base_imponible"),2,',','.')."*".
															number_format($row->fields("monto_bruto"),2,',','.')."*".
															number_format($row->fields("monto_base_imponible2"),2,',','.')."*".
															number_format($row->fields("porcentaje_iva2"),2,',','.')."*".
															number_format($row->fields("retencion_iva2"),2,',','.')."*".	
															$row->fields("numero_compromiso")."*". 
															$row->fields("comentarios")."*".
															$tipo."*".
															$row->fields("estatus")."*".
															$opcion."*".
															number_format($total_renglon,2,',','.')."*".
															number_format($row->fields("retencion_ex1"),2,',','.')."*".
															number_format($row->fields("retencion_ex2"),2,',','.')."*".
															$row->fields("desc_ex1")."*".
															$row->fields("desc_ex2")."*".
															number_format($row->fields("pret1"),2,',','.')."*".
															number_format($row->fields("pret2"),2,',','.')."*".
															number_format($row->fields("amortizacion"),2,',','.')."*".
															number_format($resta,2,',','.')."*".
															$row->fields("aplica_bi_ret_ex1")."*".
															$row->fields("aplica_bi_ret_ex2")."*".
															$tipo_doc."*".
															number_format($porcentaje_ant,2,',','.')."*".
															number_format($monto_anticipo,2,',','.')."*".
															$fecha_doc."*".
															$cuenta_contable."*".
															$id_tipo_comprobante."*".
															$descripcion."*".
															number_format($monto_debito,2,',','.')."*".
															$id_cuenta_cont."*".
															$tipo_codigo."*".
															$nombre_cuenta."*".
															$tipo_nombre."*".
															substr($numero_comprobante,10)."*".
															$iva_anticipos."*".																					
															$row->fields("n_comprobante_co")."*".
															$contar2."*".
															$fecha_orden."*".
															$fecha_ord2."*".
															$numero_comprobante."*".
															number_format($row->fields("sustraendo"),2,',','.')."*".
															round($porc_amort,2);
															
													
	
}
// return the formated data
echo ($responce);

?>