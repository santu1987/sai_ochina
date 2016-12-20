<?php 
//definiendo que los archivos a crear son txt
$archivo="factura";
header('Content-type: application/xls');
// Creando en archivo con la extencion txt
header('Content-Disposition: attachment; filename="'.$archivo.''.'.xls"');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$hoy = date("Y-m-d H:i:s");
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
///////////////////////////////paso 1 armar el mega query
$mega_qery="
		SELECT 
			cheques.id_cheques, 
			cheques.tipo_cheque, 
			cheques.monto_cheque, 
			cheques.ordenes,
			cheques.numero_cheque,
			cheques.estatus,
			orden_pago.orden_pago,
			documentos_cxp.numero_compromiso as compromiso,
			documentos_cxp.monto_bruto,
			documentos_cxp.monto_base_imponible,
			documentos_cxp.porcentaje_iva,
			documentos_cxp.monto_base_imponible2,
			documentos_cxp.porcentaje_iva2,
			documentos_cxp.retencion_iva2,
			(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100) as iva,
			(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100) as iva2,
			(documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100)) as total_factura,
			(documentos_cxp.porcentaje_retencion_iva*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)/100) AS ret_iva,
			(porcentaje_retencion_islr*documentos_cxp.monto_bruto/100) as islr,
			documentos_cxp.numero_documento,
			((documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100)))-((documentos_cxp.retencion_iva2*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)/100)+(documentos_cxp.porcentaje_retencion_iva*(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)/100)+(porcentaje_retencion_islr*documentos_cxp.monto_bruto/100)+(documentos_cxp.retencion_ex1+documentos_cxp.retencion_ex2))as total_retenciones,
			doc_cxp_detalle.partida,
			doc_cxp_detalle.monto as renglon_sin_iva,
			doc_cxp_detalle.monto+(documentos_cxp.porcentaje_iva*monto/100) as total_renglon,
			proveedor.codigo_proveedor
		FROM cheques
		inner join
			orden_pago
		on
			orden_pago.cheque=cheques.numero_cheque
		inner join
			documentos_cxp
		on
			documentos_cxp.orden_pago =orden_pago.id_orden_pago
		inner join
			doc_cxp_detalle
		on
			doc_cxp_detalle.id_doc=documentos_cxp.id_documentos	
		inner join
			proveedor
		on
		cheques.id_proveedor=proveedor.id_proveedor				
		where
			cheques.estatus!='5'
		and
			numero_cheque!='0'
		order by
			documentos_cxp.numero_documento,
			proveedor.codigo_proveedor::integer,
			documentos_cxp.numero_compromiso,
			cheques.numero_cheque	
 ;";
// die($mega_qery);
$recordset=& $conn->Execute($mega_qery);

		?>
<html>
<th align="center"> CHEQUES POR PAGAR</th>
     <table border="1" >
   		  <tr>
				<th>NUM</th>
				<th>NUMERO_CHEQUE</th>
				<th>ORDEN_PAGO</th>
				<th>NUMERO COMPROMISO</th>
				<th>MONTO B</th>
				<th>IVA</th>
				<th>RET IVA</th>
				<th>ISLR</th>
				<th>FACTURA</th>
				<th>PROVEEDOR</th>
				<th>TOTAL-RETENCIONES</th>
				<th>PARTIDA</th>
				<th>TOTAL RENGLON SIN IVA</th>
				<th>TOTAL FACTURA</th>
				<th>MONTO CHEQUE</th>
		 </tr>
<?php
$conta=1;
while(!$recordset->EOF)
	{
		
		$total_factura=round($recordset->fields("total_factura"),2);
		/// consultando los datos en presupuesto
		$partida=substr($recordset->fields("partida"),0,3);
		$generica=substr($recordset->fields("partida"),3,2);
		$especifica=substr($recordset->fields("partida"),5,2);
		$sub_especifica=substr($recordset->fields("partida"),7,2);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// relacionando on la orden de compra
		$compromiso=$recordset->fields("compromiso");
	/*		$sql_orden="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						id_unidad_ejecutora,
						id_proyecto_accion_centralizada,
						id_accion_especifica,
								
				substring(\"orden_compra_servicioE\".fecha_orden_compra_servicio::varchar,6,2)as mes2

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
						\"orden_compra_servicioE\".numero_compromiso='$compromiso'";
				//die($sql_orden);
				$row_orden_compra=& $conn->Execute($sql_orden);
				if(!$row_orden_compra->EOF)
				{		
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					/*$resumen_suma = "
										SELECT  
											   (monto_causado[".$recordset->fields("mes")."]) AS monto
										FROM 
											\"presupuesto_ejecutadoR\"
										WHERE
											id_unidad_ejecutora=".$row_orden_compra->fields("id_unidad_ejecutora")."
										AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
										AND
											partida = '".$partida."'  AND	generica = '".$generica."'  AND	especifica = '".$especifica."'  AND	sub_especifica = '".$sub_especifica."'
								";
									$rs_resumen_suma=& $conn->Execute($resumen_suma);
									if (!$rs_resumen_suma->EOF) 
										$monto_causado = $rs_resumen_suma->fields("monto");
									
									else
										$monto_causado = 0;
					//------------------------------------------------------------------------------------------------------------------------------
								$monto_total=round($monto_causado,2)+round($total_renglon,2);
								//	echo($monto_total."=".$monto_causado."+".$total_renglon);
								if($row_orden_compra->fields("tipo")=='1')
									{
								    	$where="AND id_proyecto = ".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
									}else
									$where="AND id_accion_centralizada =".$row_orden_compra->fields("id_proyecto_accion_centralizada").""; 
											$actu1=
											"UPDATE 
													\"presupuesto_ejecutadoR\"
											SET 
													monto_causado[".$recordset->fields("mes")."]= '$monto_total'
											WHERE
													(id_organismo = '1') 
												AND
													(id_unidad_ejecutora = ".$row_orden_compra->fields("id_unidad_ejecutora").") 
												AND 
													(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
												AND 
													(ano = '".date("Y")."')
												AND
													partida = '".$partida."'  
												AND	
													generica = '".$generica."'
												AND	
													especifica = '".$especifica."'  
												AND
													sub_especifica = '".$sub_especifica."'
												$where	
												";
								
								//	die($actu1);	/*	
							if (!$conn->Execute($actu1))
							{
								echo ('Error al CAUSAR: '.$conn->ErrorMsg());
								$errores="fallo";
							}
								else
								$errores="ninguno";				
				}
		*/
?>		 
	  <tr>
     			<td><?php echo($conta);?></td>
	 	     	<td><?php echo($recordset->fields("numero_cheque"));?></td>
     			<td><?php echo($recordset->fields("ordenes"));?></td>
		     	<td><?php echo($recordset->fields("compromiso"));?></td>
		     	<td><?php echo(number_format($recordset->fields("monto_bruto"),2,',','.'));?></td>
		     	<td><?php echo($recordset->fields("iva"));?></td>
				<td><?php echo(number_format($recordset->fields("ret_iva"),2,',','.'));?></td>
		     	<td><?php echo(number_format($recordset->fields("islr"),2,',','.'));?></td>
		     	<td><?php echo($recordset->fields("numero_documento"));?></td>
				<td><?php echo($recordset->fields("codigo_proveedor"));?></td>
		     	<td><?php echo(number_format($recordset->fields("total_retenciones"),2,',','.'));?></td>
            	<td><?php echo(number_format($recordset->fields("partida"),2,',','.'));?></td>
				<td><?php echo(number_format($recordset->fields("renglon_sin_iva"),2,',','.'));?></td>
				<td><?php echo(number_format($recordset->fields("total_factura"),2,',','.'));?></td>
				<td><?php echo(number_format($recordset->fields("monto_cheque"),2,',','.'));?></td>
	  </tr>
<?php
        //  die($resumen_suma);				
    	  echo("\r\n");
?>
      <?php  
	$recordset->MoveNext();
	$conta++;
	}
?>
</table>
</html> 


<?php	
/*
documentos_cxp.orden_pago,
		documentos_cxp.numero_compromiso, 
        documentos_cxp.fecha_documento,
		documentos_cxp.porcentaje_retencion_islr,
		documentos_cxp.estatus,
		documentos_cxp.monto_base_imponible2,
		documentos_cxp.porcentaje_iva2,
		*/

?>
