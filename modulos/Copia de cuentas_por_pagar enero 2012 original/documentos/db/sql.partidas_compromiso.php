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
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$a_causar="0,00";
$limit = 15;
$valor_fact='0';
if(!$sidx) $sidx =1;
$where="WHERE 1=1 ";
$id_presu=$_GET['id'];
$Sql="SELECT count(id_presupuesto_ejecutador)
				  
 			 FROM 
					\"presupuesto_ejecutadoR\"
			$where
					
					";
if($_GET['compromiso']!='')
{
	$compromiso=$_GET['compromiso'];
}
if($_GET['fecha']!='')
{
	$fecha=$_GET['fecha'];
	$ano=substr($fecha,6,4);
	$mes=substr($fecha,3,2);

}else
die("");


if($_GET[id_factura])
{
	$idoc_2=$_GET[id_factura];
}
if($_GET[partida]!="")
{
	$partida_completa=$_GET[partida];
	$part1=substr($partida_completa,0,3);
	$gene1=substr($partida_completa,3,2);
}


			
/*$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = 1;
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
*/// the actual query for the grid data
/*
cobnsultando la fecha de la orden de servicio
*/
$sql_fechas="SELECT  
       fecha_compromiso, fecha_causado, fecha_pagado, usuario_estatus, 
       fecha_estatus, usuario_anula, fecha_anula, comentario, ultimo_usuario, 
       fecha_modificacion, usuario_windows, serial_maquina
  FROM \"presupuesto_ejecutadoD\"
  where
  		numero_compromiso='$compromiso'
  ";
  	//die($sql_fechas);

$row_fechas=& $conn->Execute($sql_fechas);
if (!$row->EOF) 
{
	$fecha=$row_fechas->fields("fecha_compromiso");
	$ano_orden=substr($fecha,0,4);
	$mes_orden=substr($fecha,5,2);
}
//////////////////////////////////////////////
$id_presu=$_GET['id'];
//die($id_presu);
$vector = split( ";", $id_presu);
//$vector=sort($vector);				
$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
$i=0;
			
while($i < $contador)
{
$id_presu2=$vector[$i];
		$sql="SELECT	    \"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,
							\"presupuesto_ejecutadoR\".partida,
							\"presupuesto_ejecutadoR\".generica,
							\"presupuesto_ejecutadoR\".especifica,
							\"presupuesto_ejecutadoR\".sub_especifica,
							\"presupuesto_ejecutadoR\".monto_comprometido[".$mes_orden."] as compromiso,
							\"presupuesto_ejecutadoR\".monto_causado[".$mes."] as causado,  
							\"presupuesto_ejecutadoR\".ultimo_usuario,
							 \"presupuesto_ejecutadoR\".fecha_actualizacion
					 FROM 
							\"presupuesto_ejecutadoR\"
					where id_presupuesto_ejecutador='$id_presu2'
					order by
					id_presupuesto_ejecutador
							
							";
							//die($sql);
		$rowx=& $conn->Execute($sql);
		$total_renglon=0;
		if(!$rowx->EOF)
		{
												 	
		if($acum_id=="")
			$acum_id=$rowx->fields("id_presupuesto_ejecutador");
		else
			$acum_id=$acum_id.";".$rowx->fields("id_presupuesto_ejecutador");
		
				$partida_v1=$rowx->fields("partida").$rowx->fields("generica").$rowx->fields("especifica").$rowx->fields("sub_especifica");
				if($partida_v!="")
				$partida_v=$partida_v.";".$partida_v1;	
				else
				if($partida_v=="")
				$partida_v=$partida_v1;	
					$acum_compromiso=$acum_compromiso+$rowx->fields("compromiso");
					$acum_causado=$acum_causado+$rowx->fields("causado");
				$partida=$rowx->fields("partida").$rowx->fields("generica");	
				
			}
							
$i++;
}					
	//die($sql);				
$row=& $conn->Execute($sql);

//////////////7
$c=0;
	
	$idss=$_GET[cuentas_por_pagar_db_id];
	$pagado=$acum_compromiso;
	$causados=$acum_causado;
	//$partida=$row->fields("partida").$row->fields("generica").$row->fields("especifica").$row->fields("sub_especifica");

	//$partida2=$row->fields("partida").$row->fields("generica");
	///////////////////////////
	$sql="SELECT 
																								SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)+(((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)*\"orden_compra_servicioD\".impuesto)/100)) as total_renglon,
SUM((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)) as base_imponible
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
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										and
											\"orden_compra_servicioD\".partida='".$part1."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$gene1."'
						 	    	
										";
								//die($sql);
								/*
									and	
											\"orden_compra_servicioD\".especifica='".$row->fields("especifica")."'  
						 	    		and
											\"orden_compra_servicioD\".subespecifica='".$row->fields("sub_especifica")."'	
								*/	
									$row_orden_compra=& $conn->Execute($sql);
									$total_renglon=0;
									if(!$row_orden_compra->EOF)
									{
										$total_bi=$row_orden_compra->fields("base_imponible");
										$total_renglon=$row_orden_compra->fields("total_renglon");
										$row_orden_compra->MoveNext();
									}
									/*if($total_renglon==$total_bi)
									{
										$ivas=str_replace(".","",$_GET[iva]);
										$ivas2=str_replace(",",".",$ivas);

										if (($ivas2!="")&&($ivas2!=0))
										{
											$valor_del_iva=($ivas2*$total_bi)/100;
											$total_bi=$total_bi-$valor_del_iva;
										}
									}	
									esto es una magia para q los montos medio cuadraran y nunk funciono, q se asigne el monto del iva correspondiente y todo funciona sin novedad y validado
									*/
//////////////////////////////////////////
					if($compromiso!="")
					{
							
								$sql_doc_det="select sum(monto) as monto 
												from 
													doc_cxp_detalle
												inner join
													documentos_cxp
												on
													doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
												where
														
														 substr(partida::varchar,0,6)='$partida_completa'
												and
																  compromiso='$compromiso'			 
								";
								//die($sql_doc_det);
								$row_doc_det=& $conn->Execute($sql_doc_det);
								if(!$row_doc_det->EOF)
								{
									$causado=number_format($row_doc_det->fields("monto"),2,',','.');
									$a_causar=$row_doc_det->fields("monto");
										//	
									$xxx=$causados;
								}
								else
								{
									$causado="0,00";
									$xxx=$causados;
									$a_causar="0,00";
								}
						}//si compromiso es blanco
								else
								{
									$causado="0,00";
									$xxx=$causados;
									$a_causar="0,00";
								}		
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				if($idoc_2!="")
				{	
					$where_otro="and id_doc!='$idoc_2'";
				}else
				$valor_fact='0';
					$sql_docuotro="select sum(monto) as monto 
														from 
															doc_cxp_detalle
														inner join
															documentos_cxp
														on
															doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
														where
																
																   substr(partida::varchar,0,6)='$partida_completa'
														and
															  compromiso='$compromiso'
														$where_otro	  				  
										";
										$row_doc_otro=& $conn->Execute($sql_docuotro);
										if(!$row_doc_otro->EOF)
										{
											$valor_fact=$row_doc_otro->fields("monto");
										}
										else
										{
											$valor_fact='0';
										}
						if($valor_fact=="")
						{
							$valor_fact='0';
						}				
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
					$s_resta=round($total_bi,2)-round($a_causar,2);
			//	echo(round($total_renglon,2)."-".round($a_causar,2)."/");
			//	echo($total_renglon);
				//
				//die($a_causar);
			//	die($valor_fact);
		  			   $responce=$partida."*".$causado."*".$pagado."*".$xxx."*".number_format($total_bi,2,',','.')."*".number_format($total_renglon,2,',','.')."*".number_format($a_causar,2,',','.')."*".number_format($s_resta,2,',','.')."*".number_format($total_bi,2,',','.')."*".number_format(round($s_resta,2),2,',','.')."*".number_format(round($valor_fact,2),2,',','.')."*".$partida_v;
					 //DOCUMENTACION DE QUE SIGNIFCA CADA VARIABLE : LAS CUALES SE CREARON SEGUN LA LOGICA DE UN ANALISIS PARA TODAS LAS OPCIONES POSIBLES DE ACTUALIZACION Y ALMACENAMIENTO 
					 /*TODO SEGUN EL CAMBIO NUMERO 3 QUE ENTRES AÑOS SE LE REALIZA AL MODULO DE CXP YA NO MAS MAGIA
					 1-PARTIDA=LA PARTIDA Y LA GENERICA DE LO Q SE ESTA CAUSANDO
					 2-CASUADO=EL MONTO Q ESTA GUARDADO EN LAS TABLAS DE DETALLE PARA ESA PARTIDA+GENERICA O LO Q ESTA CAUSADO EN LA TABLA DE PRESUPUESTO EJECUTADO R
					 3-PAGADO=EL MONTO PAGADO Q ESTA EN LA TABLA DE PRESUPUESTO EJECUTADO R PARA ESA PARTIDA Y ESE COMPROMISO
					 4-XXX= ES EL MISMO MONTO  DEL CUASADO INICIAL MANDADO PARA LA OTRA PAGINA PARA HACER COMPARACIONES
					 5-total_bi= valor de la base imponible de las partidas/generica señaladas
					 6-total_renglon= acumulador de los totales base imponible de las partidas/gnericas
					 7-a_causar=monto q derberia ser causado
					 8-s_resta? retsa de la bi-elmonto a causar.. para ver
					 9-valor_fact:total de los detalles de facturas almacenados
					 10-impuesto:el valor del impuesto del detalle
					 
					 */
					   //el 9no item a pasar es el total renglon para poder comprar cual sino se excede al monto de la orden de compra u servicio
//}	
// return the formated data
	
echo ($responce);
?>