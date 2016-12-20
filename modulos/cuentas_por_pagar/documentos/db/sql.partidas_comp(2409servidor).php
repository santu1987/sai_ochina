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
$where="WHERE 1=1 ";
if($_GET['compromiso']!='')
{
	$compromiso=$_GET['compromiso'];
	$where.=" and \"orden_compra_servicioE\".numero_compromiso='$compromiso'";
}
else
	$where.=" and \"orden_compra_servicioE\".numero_compromiso=''";
	//$fecha=$_GET['fecha'];
	/*$ano=substr($fecha,6,4);
	$mes=substr($fecha,3,2);*/
/*if($_GET['fecha']=='')
{
	
	$mes=date("n");
	$ano=date("Y");

}else
die("erro");
*/
	$mes=date("n");
	$ano=date("Y");
	//nota : 30/08/2012
	//tuve que colocar el mes=1 debido a que los usuarios no ingresan en el módulo de compras la fecha de la orden y se esta procesando una prueba de carga de un mes.... despues de la puesta en marcha debe qitarse la linea siguiente... no teng otra tabla de presupuestos o adquisiciones para sacar el valor de la fecha en durante esta prueba
	$mes="01";
//*******************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$Sql="SELECT count(id_orden_compra_servicioe)
				  
 			 FROM 
					\"orden_compra_servicioE\"
			".$where."	
				";
				//die($Sql);
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
/////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
*** SE TUVO QUE MODIFICAR NUEVAMENTE ESTE SCRIPT FECHA 29/02/2012 CAMBIANDO LAS RELACIONES CON LAS TABLAS REQUISICION ENCABEZADO  A ORDEN ENCABEZADO, PARA AGOSTO DE 2011 ESTABA ASI PERO DEBIDO A INDICACIONES DADAS PARA CREA UN PROCESO QUE PERMITIERA CREAR ORDENES A CONTABILIDAD (PROGRAMA PRESUPUESTARIO) SE REALIZÓ EL CAMBIO Y AHORA DEBE REVERTIRSE EN TODOS LOS PROGRAMAS DE LOS MODULOS DE CXP Y TESORERIA...
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_fechas="SELECT  
       fecha_compromiso, fecha_causado, fecha_pagado, usuario_estatus, 
       fecha_estatus, usuario_anula, fecha_anula, comentario, ultimo_usuario, 
       fecha_modificacion, usuario_windows, serial_maquina
  FROM \"presupuesto_ejecutadoD\"
  where		
		numero_compromiso='$compromiso'
		
  ";
 // die($sql_fechas);
$row_fechas=& $conn->Execute($sql_fechas);
if (!$row->EOF) 
{
	//die($sql_fechas);
	$fecha=$row_fechas->fields("fecha_compromiso");
	$ano_orden=substr($fecha,0,4);
	$mes_orden=substr($fecha,5,2);
}
/////////////////////////////////////////////////////////////////////////
$Sql="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						\"orden_compra_servicioE\".id_unidad_ejecutora, 
						\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
						\"orden_compra_servicioE\".id_accion_especifica,
						\"orden_compra_servicioE\".tipo,
 
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
			$where
				group by
						tipo,
						id_orden_compra_servicioe,
						\"orden_compra_servicioE\".id_unidad_ejecutora,
						\"orden_compra_servicioE\".id_proyecto_accion_centralizada,
						\"orden_compra_servicioE\".id_accion_especifica,
						partida,
						generica,
						especifica,
						subespecifica
					order by
							partida, generica, especifica, subespecifica
	
					";
				//	die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$partida_ant=1;
$partida_siguente=0;
$uno=1;
$dos=0;
while (!$row->EOF) 
{
				
/////////////////////////// sacando cuanto es que deberia pagar
/*$sql="SELECT 
											\"orden_compra_servicioE\".numero_compromiso, 
											\"orden_compra_servicioE\".numero_precompromiso,
											\"orden_compra_servicioE\".id_orden_compra_servicioe as id,
											requisicion_encabezado.id_unidad_ejecutora, 
											requisicion_encabezado.id_proyecto,
											requisicion_encabezado.id_accion_centralizada, 
											requisicion_encabezado.id_accion_especifica, 
											(\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)+(((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)*\"orden_compra_servicioD\".impuesto)/100) as total_total,
											(\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto) as base_imponible,
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
										INNER JOIN 
											 requisicion_encabezado
										 ON
										 	\"orden_compra_servicioE\".numero_requisicion=requisicion_encabezado.numero_requisicion 
										where
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										and
											\"orden_compra_servicioD\".partida='".$row->fields("partida")."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$row->fields("generica")."'
			 	    					and 
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										GROUP BY
											\"orden_compra_servicioE\".numero_compromiso, 
											\"orden_compra_servicioE\".numero_precompromiso,
											\"orden_compra_servicioE\".id_orden_compra_servicioe,
											requisicion_encabezado.id_unidad_ejecutora, 
											requisicion_encabezado.id_proyecto,
											requisicion_encabezado.id_accion_centralizada, 
											requisicion_encabezado.id_accion_especifica, 
											(\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)+(((\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto)*\"orden_compra_servicioD\".impuesto)/100),
											(\"orden_compra_servicioD\".cantidad*\"orden_compra_servicioD\".monto),
											\"orden_compra_servicioD\".impuesto			
											";
*/		//por peticion de los usarioa se retiro esto de la consulta se filtraran las ordenes hasta la generica
									/*and	
											\"orden_compra_servicioD\".especifica='".$row->fields("especifica")."'  
						 	    		and
											\"orden_compra_servicioD\".subespecifica='".$row->fields("subespecifica")."'*/						
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
											\"orden_compra_servicioD\".partida='".$row->fields("partida")."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$row->fields("generica")."'
			 	    					and 
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
											
											";
								//	die($sql);		
									$row_orden_compra=& $conn->Execute($sql);
									$total_renglon=0;
									if(!$row_orden_compra->EOF)
									{
										$total_renglon=$row_orden_compra->fields("total_renglon");
										$total_bi=$row_orden_compra->fields("base_imponible");
									}
									
///////////////////////////////////////////////////////////////////////////////////	
	//concatenar ids y partidas
		$sql2="SELECT    \"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,
				  	\"presupuesto_ejecutadoR\".partida,
				 	\"presupuesto_ejecutadoR\".generica,
				  	\"presupuesto_ejecutadoR\".especifica,
				  	\"presupuesto_ejecutadoR\".sub_especifica
			 FROM 
					\"presupuesto_ejecutadoR\"
			where
					(\"presupuesto_ejecutadoR\".ano = '".date("Y")."')
				AND
					\"presupuesto_ejecutadoR\".partida = '".$row->fields("partida")."'  
				AND	
					\"presupuesto_ejecutadoR\".generica = '".$row->fields("generica")."'
				AND
					\"presupuesto_ejecutadoR\".id_unidad_ejecutora = '".$row->fields("id_unidad_ejecutora")."'	
				
				AND   
				    id_accion_especifica='".$row->fields("id_accion_especifica")."'	
								
			ORDER BY
			\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador";
	$rowx=& $conn->Execute($sql2);//die($sql2);
	

while(!$rowx->EOF)		
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

		$rowx->MoveNext();
	
}
// 	\"presupuesto_ejecutadoR\".monto_comprometido[".$mes_orden."] as compromiso, 
				
/////////////////////////////////////////////////////////////////////////////////////////	
$sql2="SELECT    
				  	sum(\"presupuesto_ejecutadoR\".monto_comprometido[".$mes."]) as compromiso, 
					sum(\"presupuesto_ejecutadoR\".monto_causado[".$mes."]) as causa
				   
 			 FROM 
					\"presupuesto_ejecutadoR\"
			where
					(\"presupuesto_ejecutadoR\".ano = '".date("Y")."')
				AND
					\"presupuesto_ejecutadoR\".partida = '".$row->fields("partida")."'  
				AND	
					\"presupuesto_ejecutadoR\".generica = '".$row->fields("generica")."'
				
				AND
					\"presupuesto_ejecutadoR\".id_unidad_ejecutora = '".$row->fields("id_unidad_ejecutora")."'	
				
				AND   
				    id_accion_especifica='".$row->fields("id_accion_especifica")."'	
			
					 
			";
		/*aplicar cuando se comience a trabajar con presupuesto
					monto_comprometido[".$mes_orden."]!='0'
					
					//12/09/2012: le quite esto a query de arriba debido que el acumulado del compromiso me lo esta dando individual
				
				//1
				\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,
				  	\"presupuesto_ejecutadoR\".partida,
				 	\"presupuesto_ejecutadoR\".generica,
				  	\"presupuesto_ejecutadoR\".especifica,
				  	\"presupuesto_ejecutadoR\".sub_especifica,
						\"presupuesto_ejecutadoR\".ultimo_usuario,
				     \"presupuesto_ejecutadoR\".fecha_actualizacion
				//2
					AND	
					\"presupuesto_ejecutadoR\".especifica = '".$row->fields("especifica")."'  
				AND
					\"presupuesto_ejecutadoR\".sub_especifica = '".$row->fields("subespecifica")."'
				//3
				ORDER BY
				\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador	
		;*/
		//die($sql2);	$row2=& $conn->Execute($sql2);
	

if(!$row2->EOF)		
{ 
		if($acum_compromiso=="")
			$acum_compromiso=$row2->fields("compromiso");
		else
			$acum_compromiso=$acum_compromiso+$row2->fields("compromiso");
	
//$acum_compromiso=1000;
	if($partida_ant!=$partida_siguente)
	{//echo($partida_ant."=".$partida_siguente."-");
	$a_causar="0,00";
			$idss=$_GET[cuentas_por_pagar_db_id];
			$partida=$row2->fields("partida").$row2->fields("generica");

			/*if($idss!="")
				{*/
					//////////////////////////////////////////
					if($compromiso!="")
					{	$sql_doc_det="select sum(monto) as monto 
									from 
										doc_cxp_detalle
									inner join
										documentos_cxp
									on
										doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
									where
										substr(partida::varchar,0,6)='$partida'
									and
										compromiso='$compromiso'	
					";
					$row_doc_det=& $conn->Execute($sql_doc_det);
					/*if($partida=='40208')
					die($sql_doc_det);*/
					if(!$row_doc_det->EOF)
					{
						$causado=number_format($row_doc_det->fields("monto"),2,',','.');
						$a_causar=$row_doc_det->fields("monto");
					}else{$causado="0,00";$a_causar="0,00";}
				}else
				{
					$causado="0,00";$a_causar="0,00";
				}
				/*}else{$causado=number_format($row2->fields("monto_causado"),2,',','.');}
				//echo($causado);*/
				/*if($row2->fields("monto_comprometido")!=0)
				{*/
				/*if($total_renglon==$total_bi)
				{
					$ivasss=$total_bi*(12/100);
					$total_bi=$total_bi-$ivasss;
				}
				*/
			
			
				/** cambio realizado 27/07/2011 debido a que se mostraran las ordenes agrupadas por generica deben colocarse una condicion para que en el caso q se parezca una partida a la otra no c repita el dato en la tabla q se visualiza  */
				
				/*****************************/
				$a_causar=round($a_causar,2);
				$total_bi=round($total_bi,2);	
					if($a_causar<$total_bi)
					
							$estatus = "<img id='cxp_doc_estatus_imagen' src='imagenes/bien.png' />";
					
					else
					if($a_causar==$total_bi)
							$estatus = "<img id='cxp_doc_estatus_imagen' src='imagenes/close.png' />";

				/****************************/
							$responce->rows[$i]['id']=$row2->fields("id_presupuesto_ejecutador");
							$responce->rows[$i]['cell']=array(	$acum_id,	
																$partida,	
																number_format($acum_compromiso,2,',','.'),
																$causado,
																number_format($total_renglon,2,',','.'),
																number_format($total_bi,2,',','.'),
																number_format($a_causar,2,',','.'),
																$partida_v,
																$estatus
																					);
				//echo("comparacion=".$partida_ant2."!=".$partida_sig2);
					
					$i++;
					$a_causar="0";
					$total_bi="0";
					$total_renglon="0";
				//}
			
			
			
	}
	$acum_id="";
	$partida_v="";			
				
	//end if
}	
	$partida_ant=$partida;

    	$row->MoveNext();
		$total_bi="0";
		$total_renglon="0";
	$partida_siguente=$row->fields("partida").$row->fields("generica");
}
// return the formated data
echo $json->encode($responce);

?>