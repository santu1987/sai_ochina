$sql_facturas="SELECT 
					   id_documentos,
					   porcentaje_iva,
					   porcentaje_retencion_iva, 
					   monto_bruto,
					   monto_base_imponible,
					   tipo_documentocxp,
					   amortizacion
		 FROM
					documentos_cxp
		where						   
					documentos_cxp.numero_compromiso='$numero_compromiso'";		   
$row_factura=& $conn->Execute($sql_facturas);
$total_renglon=0;
while(!$row_factura->EOF)
{
	$p_iva_factura=$row_factura->fields("monto_base_imponible")*$row_factura->fields("porcentaje_iva")/100;
	$monto_factura=$row_factura->fields("monto_bruto");
	$total_facturas_comprometidas=$total_facturas_comprometidas+$monto_factura;
	if(($row_factura->fields("tipo_documentocxp")==$tipos_ant))
	{
		$monto_factura=0;
	}
	if((($row_factura->fields("tipo_documentocxp"))==$tipos_fact)&&($row_factura->fields("amortizacion")!='0,00'))
	{
		$monto_factura="";	
		$monto_ante=($row_factura->fields("monto_bruto")+$row_factura->fields("amortizacion"));
		$p_iva_factura=$monto_ante*$row_factura->fields("porcentaje_iva")/100;
	//	$monto_factura=$row_factura->fields("monto_bruto")+$p_iva_factura;	
		$monto_factura=$monto_ante+$p_iva_factura;	
	}
																									
																									
																									
																									
																									
																									
																									
																									$total_documento=$monto_restar;
																						$lo_q_qeda_fact=$total_facturas_comprometidas-$total_documento;
																						$fact_ord=($total_compromiso)-$lo_q_qeda_fact;