<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$opcion=$_POST[tesoreria_cheque_anular_db_tipo];
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

		//----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
				if($_POST[tesoreria_cheque_anular_pr_ordenes]!="")
				{
						$vector = split( ",",$_POST[tesoreria_cheque_anular_pr_ordenes]);
						
						$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
						$i=0;
						//
						while($i < $contador)
						{
								
									$orden=$vector[$i];
												$Sql2="
														SELECT 
															documentos_cxp.numero_documento,
															documentos_cxp.monto_bruto,
															documentos_cxp.monto_base_imponible,
															documentos_cxp.porcentaje_iva,
															documentos_cxp.porcentaje_retencion_iva,
															documentos_cxp.porcentaje_retencion_islr,
															documentos_cxp.tipo_documentocxp,
															documentos_cxp.amortizacion,
															documentos_cxp.retencion_ex1,
															documentos_cxp.retencion_ex2,
															documentos_cxp.monto_base_imponible2,
															documentos_cxp.porcentaje_iva2 ,
															documentos_cxp.retencion_iva2
														FROM 
															documentos_cxp
														WHERE
															documentos_cxp.orden_pago='$orden'
											";//die($Sql2);
											$row_orden=& $conn->Execute($Sql2);
											$monto_bruto=$row_orden->fields("monto_bruto");
											$iva=$row_orden->fields("porcentaje_iva");
											$ret1=$row_orden->fields("retencion_ex1");
											$ret2=$row_orden->fields("retencion_ex2");
											if(($retislr2==0)||($retislr2=='0.00'))
											{
											$retislr2=$row_orden->fields("porcentaje_retencion_islr");
											}
											
											$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
											//-/si es factura con anticipo
											if(($row_orden->fields("tipo_documentocxp")==$tipos_fact)&&($row_orden->fields("amortizacion")!='0'))
											{
												$monto_bruto=$row_orden->fields("monto_bruto");
												$amort=$row_orden->fields("amortizacion");
												$base_imponible=$monto_bruto;//+$amort
												$islr=((($base_imponible)*($retislr2))/100);
											}else
											{
													$base_imponible=$row_orden->fields("monto_base_imponible");
													$islr=((($monto_bruto)*($retislr2))/100);
											}
											//----calculos
											
											if($row_orden->fields("monto_base_imponible2")!='0')
											{
											$base2=$row_orden->fields("monto_base_imponible2");
											$iva2=$row_orden->fields("porcentaje_iva2");
											$retiva2=$row_orden->fields("retencion_iva2");
											
											$base_iva=(($base_imponible)*($iva))/100;
											$base_iva2=(($base2)*($iva2))/100;
											
											
											$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
											$monto_restar2=(($base_iva2)*($retiva2))/100;
											
											$bruto_iva=($monto_bruto)+($base_iva+$base_iva2)-($monto_restar+$monto_restar2);//
											//-
											//die($monto_bruto);
											
											if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
											{
											
											$islr=$islr-138;
											
											}
											//die(islr);	
											$retenciones=$ret1+$ret2;
											$monto_def=($bruto_iva)-($islr+$retenciones);
											//	echo($monto_def);
											$total_orden=$total_orden+$monto_def;
											$monto_def=0;
											$islr=0;
											$base_imponible=0;
											$monto_bruto=0;
											//die($monto_det);
											}
											else
											{
											$base_iva=(($base_imponible)*($iva))/100;
											if($porcentaje_iva_ret!='0')
											{
											$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
											$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);//
											}else
											$bruto_iva=($monto_bruto)+($base_iva);
											//-
											//die($monto_bruto);
											
											if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
											{
											
											$islr=$islr-138;
											
											}
											//die(islr);	
											$retenciones=$ret1+$ret2;
											$monto_def=($bruto_iva)-($islr+$retenciones);
											//	echo($monto_def);
											$total_orden=$total_orden+$monto_def;
											$monto_def=0;
											$islr=0;
											$base_imponible=0;
											$monto_bruto=0;
											
											}
											$row_orden->MoveNext();	
											
										
											$pagar=$pagar+$total_orden;
											$total_orden=0;
											//$retislr2=0;
											$retenciones=0;
											$ret1=0;
											$ret2=0;
														
											//$retislr2=0;
								           ///////////////////////actualizando ordenees
										/*   if($contador==1)
										   {
												$pagar=$n_saldo;  
											}*/
										
								           ///////////////////////actualizando ordenees
										  //quite del sql:cheque='0',id_banco='0',cuenta_banco='0',
								
								
								$sql_ordenes_pago="UPDATE orden_pago
											SET
													cheque='0',	
													id_banco='0',
													cuenta_banco='0',
													secuencia='0',
													saldo='$pagar'
											WHERE
													(orden_pago.id_orden_pago='$vector[$i]')
														
											;
										
													
											";	
						
								//echo($sql_orden);
								$pagar=0;
								if($i==0)
								$sql_orden1=$sql_ordenes_pago;
								else
								$sql_orden1=$sql_orden1.";".$sql_ordenes_pago;
								$i=$i+1;
						}//fin del while
					}			
							
		//-----------------------------------------------------------
		
			if (!$conn->Execute($sql_orden1))
				echo ('Error al anular'.$sql_orden1);	
			else
			die("ANULADO");
     //}
?>