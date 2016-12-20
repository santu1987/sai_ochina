<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$control=0;
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
//DATOS PARA CARGAR DOCUMENTOS..
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
//
$ano = date('Y');
if(!$sidx) $sidx =1;
$islr = $_GET['islr'];
$islr1 = $_GET['islr'];
$retislr2=str_replace(",",".",$islr);
$rif=$_GET['rif'];
$sustraendo=$_GET['sustraendo'];

$id = $_GET['id'];
$where = 'WHERE (1=1)';
if(isset($_GET[id_cheques]))
{
		$id_cheques=$_GET[id_cheques];
}		
//*******************************
/*$Sql_ut="
			SELECT 
				valor_moneda 
			FROM 
				valor_moneda
			INNER JOIN 
				organismo 
			ON 
				valor_moneda.id_organismo =organismo.id_organismo
			INNER JOIN 
				moneda 
			ON 
				valor_moneda.id_moneda=moneda.id_moneda
			WHERE
				 moneda.nombre='unidad tributaria'
		";

$row_ut=& $conn->Execute($Sql_ut);
if (!$row_ut->EOF)
{
	$valor_moneda = $row_ut->fields("valor_moneda");
}*/
//******************************
$sql_porcen = "SELECT  valor_moneda FROM valor_moneda INNER JOIN organismo ON valor_moneda.id_organismo =organismo.id_organismo INNER JOIN moneda on valor_moneda.id_moneda=moneda.id_moneda  WHERE fecha_valor >= '".date("d-m-Y")."' AND  moneda.nombre='unidad tributaria' ORDER BY fecha_valor asc";
$bus0 =& $conn->Execute($sql_porcen);
if ($bus0->fields==""){
	$sql_porcen = "SELECT  valor_moneda FROM valor_moneda INNER JOIN organismo ON valor_moneda.id_organismo =organismo.id_organismo INNER JOIN moneda on valor_moneda.id_moneda=moneda.id_moneda  WHERE fecha_valor <= '".date("d-m-Y")."' AND  moneda.nombre='unidad tributaria' ORDER BY fecha_valor desc";
	$bus0 =& $conn->Execute($sql_porcen);
	}
	$valor_moneda = $bus0->fields("valor_moneda");
//******************************

$sql_fact = "SELECT factor_islr FROM parametros_tesoreria  WHERE  fecha_ultima_modificacion>= '".date("d-m-Y")."'  ORDER BY fecha_ultima_modificacion asc";

$bus =& $conn->Execute($sql_fact);

if ($bus->fields==""){
	$sql_fact = "SELECT factor_islr FROM parametros_tesoreria  WHERE fecha_ultima_modificacion <= '".date("d-m-Y")."'  ORDER BY fecha_ultima_modificacion desc";
	$bus =& $conn->Execute($sql_fact);
	}
//$porcentajexx = $bus->fields("porcentaje_impuesto");
$factor=$bus->fields("factor_islr");		
$monto_det=$valor_moneda*$factor;



/*while (!$row->EOF) 
{*/
$id_detalles = split( ",", $_GET['vector'] );
sort($id_detalles);
$contador=count($id_detalles);  ///$_POST['covertir_req_cot_titulo']
	$i=0;
	$monto_total=0;
	while($i < $contador)
		{	
			
			
			
			$orden=$id_detalles[$i];
				if($orden!='')
				{
						
					/////////////////////////proceso que saca la cuenta de cuanto va pagado por una misma orden////////
					if($contador==1)
					{
						
						//die($saldo);
						////////////////////////////
						$Sql="
								SELECT 
									orden_pago.id_orden_pago as id,
									orden_pago.orden_pago,
									fecha_orden_pago,
									orden_pago.saldo						
								FROM 
									orden_pago
								INNER JOIN 
									organismo 
								ON 
									orden_pago.id_organismo =organismo.id_organismo
								INNER JOIN 
									documentos_cxp  
								ON 
									orden_pago.orden_pago=documentos_cxp.orden_pago
								INNER JOIN 
									proveedor
								ON 
									orden_pago.id_proveedor=proveedor.id_proveedor
							 where (orden_pago.id_orden_pago= ".$orden.")
							order by
								id_orden_pago
							desc";
						$row=& $conn->Execute($Sql);
						$saldo=$row->fields("saldo"); 
						////////////////////////////consultando el mon to del cheque
						if(isset($_GET[id_cheques]))
						{
								//die($_GET[id_cheques]);
								$id_cheques=$_GET[id_cheques];
								if($id_cheques!='')
								{
									$sql_cheque2="SELECT SUM(monto_cheque) as monto_suma,ordenes from cheques where id_cheques='$id_cheques' group by monto_cheque,ordenes";
									//die($sql_cheque2);
									$row_cheque2=& $conn->Execute($sql_cheque2);
									if(!$row_cheque2->EOF) 
									{
									//$control=1;
											$ordenes=substr($row_cheque2->fields("ordenes"),1,strlen($row_cheque2->fields("ordenes"))-2);
											$vectorx = split( ",",$ordenes);
											$contadorx=count($vectorx);  ///$_POST['covertir_req_cot_titulo']
											$alphas=0;
											$venus="igual";
											if($contadorx==$contador)
											{
												while(($alphas<$contadorx)&&($venus=="igual"))
												{
													//echo($vectorx[$alphas]."==".$id_detalles[$alphas]."-");
													if($vectorx[$alphas]==$id_detalles[$alphas])
														$venus="igual";
													else
														$venus="dif";
													$alphas++;		
												}
										 //		die($venus);
											}else
												$venus="dif";
										
											if($venus=="igual")
											{
											$monto_pagado_cheque=$row_cheque2->fields("monto_suma");
											$monto_pagado_cheque2=$monto_pagado_cheque;
											
											}else
											{
											$monto_pagado_cheque=$saldo;
											$monto_pagado_cheque2=$saldo;
											$contador=2;
											}
											
									}
									else
										{
											$monto_pagado_cheque="0";
											$monto_pagado_cheque2="0";
										}	
								}else
										{
											$monto_pagado_cheque="0";
											$monto_pagado_cheque2="0";
										}	
						}else
						{
							$monto_pagado_cheque="0";
							$monto_pagado_cheque2="0";
						}
						////////////////////////////
				}
					
					//////////////////////////////////////////////////////////////////////////////////////////////////
									$Sql2="
											SELECT 
												documentos_cxp .monto_bruto,
												documentos_cxp .monto_base_imponible,
												documentos_cxp .porcentaje_iva,
												documentos_cxp .porcentaje_retencion_iva,
												documentos_cxp .porcentaje_retencion_islr,
												monto_base_imponible,
												tipo_documentocxp,
												documentos_cxp.retencion_ex1,
												documentos_cxp.retencion_ex2,
												amortizacion,
												documentos_cxp.monto_base_imponible2,
												documentos_cxp.porcentaje_iva2 ,
												documentos_cxp.retencion_iva2,
												documentos_cxp.sustraendo
											FROM 
												documentos_cxp 
											WHERE
												documentos_cxp .orden_pago='$orden'
								";
								$row_orden=& $conn->Execute($Sql2);
								//die($Sql2);
						//$pagar="";
						//$base_imponible=0;
						//$monto_bruto=0;
						while (!$row_orden->EOF) 
						{
							/*$bruto=$row_orden->fields("monto_bruto");
							$iva=$row_orden->fields("porcentaje_iva");
							$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
							$bruto_iva=(($bruto)*($iva))/100;
							$monto_restar=(($bruto_iva)*($porcentaje_iva_ret))/100;
							$bruto_iva=($bruto_iva)-($monto_restar);//
							//-
							$islr=((($bruto)*($retislr))/100);
							$monto_def=(($bruto)+($bruto_iva))-$islr;
							$pagar=$pagar+$monto_def;
							//-
							$row_orden->MoveNext();		*/	
							
							/*
							$base_imponible=$row_orden->fields("monto_base_imponible");
							$monto_bruto=$row_orden->fields("monto_bruto");
							$iva=$row_orden->fields("porcentaje_iva");
							$ret1=$row_orden->fields("retencion_ex1");
							$ret2=$row_orden->fields("retencion_ex2");
							if(($retislr2==0)||($retislr2=="0.00"))
							{
								$retislr2=$row_orden->fields("porcentaje_retencion_islr");
							}
							$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
						
							if((($row_orden->fields("tipo_documentocxp"))==$tipos_fact)&&($row_orden->fields("amortizacion")!='0,00'))
							{
								$monto_bruto=$row_orden->fields("monto_bruto");
								$amort=$row_orden->fields("amortizacion");
								$base_imponible=$monto_bruto+$amort;
								$islr=((($base_imponible)*($retislr2))/100);
							}else
							$islr=((($monto_bruto)*($retislr2))/100);
							//////----calculos
									$base_iva=(($base_imponible)*($iva))/100;
									$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
									$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);
							//-
								if(($sustraendo=='1')&&($monto_bruto>='4132'))
								$islr=$islr+138;
									$retenciones=$ret1+$ret2;
									$monto_def=($bruto_iva)-($islr+$retenciones);
									$total_documento=$total_documento+$monto_def;
							//*/
							$monto_bruto=$row_orden->fields("monto_bruto");
							$iva=$row_orden->fields("porcentaje_iva");
							$ret1=$row_orden->fields("retencion_ex1");
							$ret2=$row_orden->fields("retencion_ex2");
							$sustraendos=$row_orden->fields("sustraendo");
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
									$islr=((($base_imponible)*($retislr2))/100)-($sustraendos);
							 }else
							{
							$base_imponible=$row_orden->fields("monto_base_imponible");
							$islr=((($monto_bruto)*($retislr2))/100)-$sustraendos;
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
							
					/*	if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
							{
							
								$islr=$islr-138;
								
							}*/
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
							
						/*if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
							{
							
								$islr=$islr-138;
								
							}
						*/	//die(islr);	
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
					
						}
						$pagar=$pagar+$total_orden;
						$total_orden=0;
						//$retislr2=0;
							$retenciones=0;
							$ret1=0;
							$ret2=0;
				}			
				$i=$i+1;//$retislr2=0;
		
			
					
		}		
	//$responce->rows[$i]['id']=$row->fields("numero_orden_pago");
	//$responce =$monto_total;
	//$responce=number_format($pagar,2,',','.');
	
	if($monto_pagado_cheque=='0')
	{
		$monto_pagado_cheque=$pagar;
		//$monto_pagado_cheque2=$monto_pagado_cheque;
	}
	/*if($saldo=='0')
	$saldo=$pagar;*/
	if(($saldo!=$pagar)&&($monto_pagado_cheque2==0))
	{
		$monto_pagado_cheque=$saldo;
		//$monto_pagado_cheque2=$monto_pagado_cheque;
	}
	if($contador>1)
	{
		$monto_pagado_cheque=$pagar;
		$monto_pagado_cheque2=$pagar;
		if($id_cheques!='')
		{
			$saldo=0;
		}else
		$saldo=$pagar;
	}
	/*echo($control);
	if($control==1)
	die("error");*/
	if(round($monto_pagado_cheque,2)<round($pagar,2))
	{
		$prorateo=1;
		//die($monto_pagado_cheque."<".$pagar);
	}else
	$prorateo=0;
echo number_format($pagar,2,',','.')."*".number_format($monto_pagado_cheque,2,',','.')."*".number_format($saldo,2,',','.')."*".number_format($monto_pagado_cheque2,2,',','.')."*".$contador."*".$prorateo;
  ?>