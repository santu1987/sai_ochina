<?php
session_start();
ini_set("memory_limit","20M");

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql_fact='SELECT id_orden_pago, id_organismo, fecha_orden_pago, id_banco, cuenta_banco, 
       comentarios, ultimo_usuario, fecha_ultima_modificacion, orden_pago, 
       id_proveedor, ano, documentos, secuencia, cheque, estatus, beneficiario, 
       cedula_rif_beneficiario, estatus_orden, saldo
  FROM orden_pago
  where
  	estatus!=3
  order by
  orden_pago 
  ;';
  $row=& $conn->Execute($sql_fact);
			
				while(!$row->EOF)
				{
					$orden_pago=$row->fields("orden_pago");
					$monto_total=0;
					//die($orden_pago);
					
						

									$Sql2="
											
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
			
			where
			(documentos_cxp.orden_pago='$orden_pago')
			and
			documentos_cxp.estatus!=3
			ORDER BY
				 documentos_cxp.id_documentos
												";
								//echo($Sql2);				
								$row_orden=& $conn->Execute($Sql2);
								//die($Sql2);
									while (!$row_orden->EOF) 
									{
										
										$tipo=$row_orden->fields("tipo_documentocxp");
	$sql_doc="SELECT * from tipo_documento_cxp where id_tipo_documento='$tipo'";
	$row2=& $conn->Execute($sql_doc);
	$tipo_nom=$row2->fields("nombre");
	
	///************
//
if($row_orden->fields("numero_compromiso")!="")
{
	$ncomp=$row_orden->fields("numero_compromiso");
	
 }
 ///////////////////// calculo de total por factura
//datos
		$monto=$row_orden->fields("monto_bruto");
		$iva=$row_orden->fields("porcentaje_iva");
		$ret_iva=$row_orden->fields("porcentaje_retencion_iva");
		$ret_islr=$row_orden->fields("porcentaje_retencion_islr");
		$retencion1=$row_orden->fields("retencion_ex1");
		$retencion2=$row_orden->fields("retencion_ex2");
							
//operacion
//-/si es factura con anticipo
 if(($row_orden->fields("tipo_documentocxp")==$tipos_fact)&&($row_orden->fields("amortizacion")!='0'))
 {
		$monto=$row_orden->fields("monto_bruto");
		$amort=$row_orden->fields("amortizacion");
		//$base=$monto+$amort;
		$base=$monto;
		$p_islr=$base*($ret_islr/100);
		
 }
 //otro documento
 else
	{
		$base=$row_orden->fields("monto_base_imponible");
		$p_islr=$monto*($ret_islr/100);
	}
		
		if($row_orden->fields("monto_base_imponible2")!='0')
		{
			$monto_base=$row_orden->fields("monto_base_imponible2")+$row_orden->fields("monto_base_imponible");
			$retencion_iva=$row_orden->fields("porcentaje_retencion_iva")+$row_orden->fields("retencion_iva2");
			$base2=$row_orden->fields("monto_base_imponible2");
			$ret_iva2=$row_orden->fields("retencion_iva2");
			$iva2=$row_orden->fields("porcentaje_iva2");
			
			
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
			$monto_base=$row_orden->fields("monto_base_imponible");
			$retencion_iva=$row_orden->fields("porcentaje_retencion_iva");
			
			//--- calculo---//
		$p_iva=($base*$iva)/100;
		$p_ret_iva=($p_iva*$ret_iva)/100;
		$iva_total=$p_iva-$p_ret_iva;
		$retenciones=($retencion1+$retencion2);
		
		$total=($monto+$iva_total)-($p_islr)-($retenciones);
		}
				
										$row_orden->MoveNext();
											if($row_orden->fields("cheque")!="0")
											$total=0;	
										$total_orden=$total_orden+$total;
										}			
				
						$sql_updates="UPDATE orden_pago
										SET  saldo='$total_orden'
										WHERE 
											orden_pago='$orden_pago'
										";
										//die($sql_updates."-");
										if (!$conn->Execute($sql_updates)) 
												die ('Error al Actualizar: '.$conn->ErrorMsg());
										else
										echo($orden_pago."-");
						$pagar=$pagar+$total_orden;
						$total_orden=0;
						//$retislr2=0;
							$retenciones=0;
							$ret1=0;
							$ret2=0;	
					$row->MoveNext();
			}		

			
?>