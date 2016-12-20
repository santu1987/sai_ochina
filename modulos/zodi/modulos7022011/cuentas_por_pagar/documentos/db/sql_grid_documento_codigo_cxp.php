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
						\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
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

//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$ncontrol=$_POST['cuentas_por_pagar_db_numero_control'];
	$doc=$_POST['cuentas_por_pagar_db_numero_documento'];
	if(($doc!="")&&($ncontrol!=""))
	{
		$where="WHERE documentos_cxp.numero_documento='$doc'
	     		 AND documentos_cxp.numero_control='$ncontrol'";
	}else
	if(($doc=="")&&($ncontrol!=""))
	{
		$where="WHERE documentos_cxp.numero_control='$ncontrol'";
	}
	else
	if(($doc!="")&&($ncontrol==""))
	{
		$where="WHERE documentos_cxp.numero_documento='$doc'";
	}else
	if(($doc=="")&&($ncontrol==""))
	{
		die("blanco");
	}	
	$where.="AND documentos_cxp.estatus!='3'";
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");

/*if(isset($_GET['busq_proveedor']))
{
	$busq_proveedor=strtolower($_GET['busq_proveedor']);
}*/
if($_GET['busq_fecha_v']!='')
{
	$busq_fecha_v=$_GET['busq_fecha_v'];
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
			$Sql="
						SELECT DISTINCT
							count(id_documentos) 
						FROM 
							documentos_cxp
						INNER JOIN
							organismo
						ON
							documentos_cxp.id_organismo=organismo.id_organismo
						where
								documentos_cxp.estatus!='3' 	
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
						
			";
							
	//".$where."					
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	
if (!$row->EOF) 
{
//die($Sql);
$tipo=$row->fields("doc"); 
$tipo=substr($tipo,0,5);
$fecha_vencimiento=substr($row->fields("fecha_vencimiento"),0,10);
$fecha_vencimiento = substr($fecha_vencimiento,8,2)."".substr($fecha_vencimiento,4,4)."".substr($fecha_vencimiento,0,4);
//--------------------------
$fecha_documento=substr($row->fields("fecha_documento"),0,10);
$fecha_documento=substr($fecha_documento,8,2)."".substr($fecha_documento,4,4)."".substr($fecha_documento,0,4);

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
if(($row->fields("numero_compromiso")!="")&&($row->fields("numero_compromiso")!="0"))
{
	$ncomp=$row->fields("numero_compromiso");
	//buscando si el anticipo tiene iva o no
	if($row->fields("tipo_documentocxp")!=$tipos_ant)
	{
		$sql_ant_fact="
						select porcentaje_iva
									from
										documentos_cxp
											where 
												tipo_documentocxp='$tipos_ant'
											and
												numero_compromiso='$ncomp'	
							";
		$row_ant_fact=& $conn->Execute($sql_ant_fact);
		if(!$row_ant_fact->EOF)
		$iva_anticipos=$row_ant_fact->fields("porcentaje_iva");
		else
		$iva_anticipos=0;
	}
//////////////////////////////////////////////////////////////////////////////////////
$sql_orden="SELECT 
											\"orden_compra_servicioE\".numero_compromiso, 
											\"orden_compra_servicioE\".numero_pre_orden,
											\"orden_compra_servicioD\".cantidad,
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
											\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$ncomp'";
						
									$row_orden_compra=& $conn->Execute($sql_orden);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);
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
														tipo_documentocxp=$tipos_ant			
																";		   
									//die($sql_facturas);					
									$row_factura=& $conn->Execute($sql_facturas);
								//	$total_renglon=0;
									if(!$row_factura->EOF)
									{
										$monto_factura_ant=$row_factura->fields("monto_bruto");
										$porcentaje_ant=($monto_factura_ant*100)/$total_renglon;
									}
//////////////////////////////////////////////////////////////////////////////////////	
 }else
 {
 $total_renglon="";
// $porcentaje_ant="0";
 }
 if($row->fields("estatus")==2)
	{$id_documento=$row->fields("id_documentos");
		
		$sql_integrado="SELECT distinct integracion_contable.id,
								integracion_contable.cuenta_contable,
								id_tipo_comprobante,
								integracion_contable.descripcion nombre_asiento, 
								referencia,
								monto_debito, 
								tipo_comprobante.codigo_tipo_comprobante,
								tipo_comprobante.id,
								tipo_comprobante.nombre as tipo_nombre,
								cuenta_contable_contabilidad.nombre as descripcion_cuenta,
								cuenta_contable_contabilidad.id as id_cuenta_cont,
								cuenta_contable_contabilidad.nombre as nombre_cuenta,
								tipo_comprobante.nombre as tipo_nombre,
								integracion_contable.numero_comprobante
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
						$where
						and
							integracion_contable.debito_credito=1				
		
		";	//integracion_contable.id_documento='$id_documento'						 order by integracion_contable.id asc 

		//die($sql_integrado);
		$row_integrado=& $conn->Execute($sql_integrado);
		if(!$row_integrado->EOF)
		{
			$cuenta_contable=$row_integrado->fields("cuenta_contable");
			$id_tipo_comprobante=$row_integrado->fields("id_tipo_comprobante");
			$descripcion=$row_integrado->fields("descripcion_cuenta");
			$monto_debito=$row_integrado->fields("monto_debito");
			$id_cuenta_cont=$row_integrado->fields("id_cuenta_cont");
			$tipo_codigo=$row_integrado->fields("codigo_tipo_comprobante");
			$tipo_nombre=$row_integrado->fields("tipo_nombre");
			$nombre_cuenta=$row_integrado->fields("nombre_cuenta");
			$numero_comprobante=$row_integrado->fields("numero_comprobante");
		}else
		if(!$row_integrado->EOF)
		{
			$cuenta_contable='0';
			$id_tipo_comprobante='0';
			$descripcion='0';
			$monto_debito='0';
			$id_cuenta_cont=0;
			$tipo_codigo=0;
		}
	
	}else
	if($row->fields("estatus")==1)
	{
			$cuenta_contable='0';
			$id_tipo_comprobante='0';
			$descripcion='0';
			$monto_debito='0';
			$id_cuenta_cont=0;
			$tipo_codigo=0;
	}
 
 
 	$fecha_venc = substr($row->fields("fecha_vencimiento"),0,10);
	$fecha_venc = substr($fecha_venc,8,2)."".substr($fecha_venc,4,4)."".substr($fecha_venc,0,4);
	
		if($row->fields("amortizacion")=='0')
		{
			$resta=($row->fields("monto_base_imponible"))-($row->fields("monto_bruto"));
			$monto_anticipo=0;
			$tipo_doc="documento";
			
		}else
		
		if($row->fields("amortizacion")!='0')
		{
			$monto_anticipo=$row->fields("amortizacion")+$row->fields("monto_bruto");
			$resta=0;
			$tipo_doc="anticipo";
		}


if(($row->fields("numero_compromiso")!="")&&($row->fields("numero_compromiso")!=0)&&($row->fields("numero_compromiso")!="NULL"))
	{
		$contar2=cargar_cuadro($row->fields("numero_compromiso"));	
	}	
	$responce->rows[$i]['id']=$row->fields("id_proveedor");
	$responce =$row->fields("id_documentos")."*". $row->fields("id_organismo")."*". $row->fields("ano")."*".$id_proveedor."*".$codigo_proveedor."*".substr($beneficiario,0,20)."*".$rif."*".$row->fields("tipo_documentocxp")."*".$row->fields("numero_documento")."*".$row->fields("numero_control")."*".substr($row->fields("fecha_vencimiento"),0,10)."*".number_format($row->fields("porcentaje_iva"),2,',','.')."*".number_format($row->fields("porcentaje_retencion_iva"),2,',','.')."*".number_format($row->fields("porcentaje_retencion_islr"),2,',','.')."*".number_format($row->fields("monto_base_imponible"),2,',','.')."*".number_format($row->fields("monto_bruto"),2,',','.')."*".$ncomp."*".$row->fields("comentarios")."*".$tipo."*".$row->fields("estatus")."*".$opcion."*".number_format($total_renglon,2,',','.')."*".number_format($row->fields("retencion_ex1"),2,',','.')."*".number_format($row->fields("retencion_ex2"),2,',','.')."*".$row->fields("desc_ex1")."*".$row->fields("desc_ex2")."*".number_format($row->fields("pret1"),2,',','.')."*".number_format($row->fields("pret2"),2,',','.')."*".number_format($row->fields("amortizacion"),2,',','.')."*".number_format($resta,2,',','.')."*".$row->fields("aplica_bi_ret_ex1")."*".$row->fields("aplica_bi_ret_ex2")."*".$tipo_doc."*".number_format($porcentaje_ant,2,',','.')."*".number_format($monto_anticipo,2,',','.')."*".substr($row->fields("fecha_documento"),0,10)."*".$cuenta_contable."*".$id_tipo_comprobante."*".$descripcion."*".$monto_debito."*".$id_cuenta_cont."*".$tipo_codigo."*".$nombre_cuenta."*".$tipo_nombre."*".substr($numero_comprobante,2,4)."*".$iva_anticipos."*".$row->fields("n_comprobante_co")."*".$contar2."*".$row->fields("vector_partida")."*".$row->fields("vector_montos");
echo ($responce);
}
?>