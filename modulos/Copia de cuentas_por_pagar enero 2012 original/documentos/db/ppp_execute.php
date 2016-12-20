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
		documentos_cxp.ano,	
		substring(documentos_cxp.fecha_documento::varchar,9,2)as dia,
		documentos_cxp.id_documentos,
		documentos_cxp.tipo_documentocxp, 
		documentos_cxp.numero_documento,
		documentos_cxp.monto_bruto,
		documentos_cxp.monto_base_imponible,
		documentos_cxp.porcentaje_iva,
		substring(documentos_cxp.fecha_ultima_modificacion::varchar,6,2)as mes,
		(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100) as iva,
		(documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)) as total_factura,
		partida,
		monto as renglon_sin_iva,
		monto+(documentos_cxp.porcentaje_iva*monto/100) as total_renglon,
		compromiso
		
  FROM documentos_cxp
  INNER JOIN
  	doc_cxp_detalle
  ON
  doc_cxp_detalle.id_doc=documentos_cxp.id_documentos
  where
	monto_base_imponible2=0
  		
  and
	documentos_cxp.estatus!='3'
  and 
	orden_pago!='0'	
  and
  	tipo_documentocxp!='4'				
  order by
  	compromiso,
  	fecha_documento,
	id_documentos
  ;";
 // die($mega_qery);		substring(documentos_cxp.fecha_documento::varchar,6,2)as mes,

$recordset=& $conn->Execute($mega_qery);

		?>
<html>
<th align="center"> FACTURAS POR CAUSAR Y PAGAR</th>
     <table border="1" >
   		  <tr>
				<th>numero </th>
				<th>fecha</th>
				<th>id</th>
				<th>NUMERO</th>
				<th>MONTO B</th>
				<th>BASE IMP</th>
				<th>%</th>
				<th>iva</th>
				<th>TOTAL FACTURA</th>
				<th>PARTIDA</th>
				<th>renglon sin iva</th>
				<th>TOTAL RENGLON</th>
				<th>COMPROMISO</th>
				<th>ERROR</th>
		 </tr>
<?php
$conta=1;
while(!$recordset->EOF)
	{
		$total_renglon=$recordset->fields("total_renglon");
		if($recordset->fields("monto_bruto")!=$recordset->fields("monto_base_imponible"))
		{
			$iva=($recordset->fields("monto_base_imponible")*$recordset->fields("porcentaje_iva"))/100;
			$total_renglon=$recordset->fields("renglon_sin_iva")+$iva;
			//die("entro");
		}
		$total_factura=round($recordset->fields("total_factura"),2);
		/// consultando los datos en presupuesto
		$partida=substr($recordset->fields("partida"),0,3);
		$generica=substr($recordset->fields("partida"),3,2);
		$especifica=substr($recordset->fields("partida"),5,2);
		$sub_especifica=substr($recordset->fields("partida"),7,2);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// relacionando on la orden de compra
		$compromiso=$recordset->fields("compromiso");
			$sql_orden="SELECT 
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
					$resumen_suma = "
										SELECT  
											   (monto_causado[".$row_orden_compra->fields("mes2")."]) AS monto
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
													monto_causado[".$row_orden_compra->fields("mes2")."]= '$monto_total'
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
							/*if (!$conn->Execute($actu1))
							{
								echo ('Error al CAUSAR: '.$conn->ErrorMsg());
								$errores="fallo";
							}
								else
								$errores="ninguno";			*/		
				}
		///
?>		 
	  <tr>
     			<td><?php echo($conta);?></td>
	 	     	<td><?php echo($recordset->fields("dia")."/".$recordset->fields("mes")."/".$recordset->fields("ano"));?></td>
     			<td><?php echo($recordset->fields("id_documentos"));?></td>
		     	<td><?php echo($recordset->fields("numero_documento"));?></td>
		     	<td><?php echo($recordset->fields("monto_bruto"));?></td>
		     	<td><?php echo($recordset->fields("monto_base_imponible"));?></td>
		     	<td><?php echo($recordset->fields("porcentaje_iva"));?></td>
				<td><?php echo(number_format($recordset->fields("iva"),2,',','.'));?></td>
		     	<td><?php echo($total_factura);?></td>
		     	<td><?php echo($recordset->fields("partida"));?></td>
            	<td><?php echo(number_format($recordset->fields("renglon_sin_iva"),2,',','.'));?></td>
				<td><?php echo(number_format($total_renglon,2,',','.'));?></td>
				<td><?php echo($recordset->fields("compromiso"));?></td>
				<td><?php echo($SI);?></td>
				
				
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