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
if(!$sidx) $sidx =1;
$where="WHERE 1=1 ";
$id_presu=$_GET['id'];
if($id_presu!="")
{
	$where.="and id_presupuesto_ejecutador='$id_presu'";
}
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
//die($where );			
$row=& $conn->Execute($Sql);
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
// the actual query for the grid data


$sql="SELECT	    \"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,
				  	\"presupuesto_ejecutadoR\".partida,
				 	\"presupuesto_ejecutadoR\".generica,
				  	\"presupuesto_ejecutadoR\".especifica,
				  	\"presupuesto_ejecutadoR\".sub_especifica,
				  	\"presupuesto_ejecutadoR\".monto_comprometido[".$mes."],
					\"presupuesto_ejecutadoR\".monto_causado[".$mes."],  
				   	\"presupuesto_ejecutadoR\".ultimo_usuario,
				     \"presupuesto_ejecutadoR\".fecha_actualizacion
 			 FROM 
					\"presupuesto_ejecutadoR\"
			$where
			order by
			id_presupuesto_ejecutador
					
					";
					
	//die($sql);				
$row=& $conn->Execute($sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
//$i=0;
$c=0;
if (!$row->EOF) 
{	
	$idss=$_GET[cuentas_por_pagar_db_id];
	$pagado=$row->fields("monto_comprometido");
	$causados=$row->fields("monto_causado");
	$partida=$row->fields("partida").$row->fields("generica").$row->fields("especifica").$row->fields("sub_especifica");
	///////////////////////////
	$sql="SELECT 
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
											\"orden_compra_servicioE\".numero_compromiso='$compromiso'
										and
											\"orden_compra_servicioD\".partida='".$row->fields("partida")."' 
										and	
							    			\"orden_compra_servicioD\".generica='".$row->fields("generica")."'
						 	    		and	
											\"orden_compra_servicioD\".especifica='".$row->fields("especifica")."'  
						 	    		and
											\"orden_compra_servicioD\".subespecifica='".$row->fields("sub_especifica")."'	
										";
								//die($sql);
									$row_orden_compra=& $conn->Execute($sql);
									$total_renglon=0;
									while(!$row_orden_compra->EOF)
									{
										$total=$row_orden_compra->fields("monto")*$row_orden_compra->fields("cantidad");
										$iva=$total*($row_orden_compra->fields("impuesto")/100);//+$iva;
										$total_total=$total;
										//echo($total_total);
										$total_renglon=$total_renglon+$total_total;
										$row_orden_compra->MoveNext();
									}
//////////////////////////////////////////
					if($idss!="")
					{
							$sql_doc_det3="select  monto 
											from 
												doc_cxp_detalle
											inner join
												documentos_cxp
											on
												doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
											where
													
													  partida='$partida'
											and
													  id_doc='$idss'			  
							";
							$row_doc_det3=& $conn->Execute($sql_doc_det3);
							//die($sql_doc_det3);
							if(!$row_doc_det3->EOF)
							{
								$a_causar=number_format($row_doc_det3->fields("monto"),2,',','.');
							}
					}else
					{
						$a_causar="0,00";
					}						
					
					
					$sql_doc_det="select sum(monto) as monto 
									from 
										doc_cxp_detalle
									inner join
										documentos_cxp
									on
										doc_cxp_detalle.id_doc=documentos_cxp.id_documentos		
									where
											
											  partida='$partida'
					";
					$row_doc_det=& $conn->Execute($sql_doc_det);
					if(!$row_doc_det->EOF)
					{
						$causado=number_format($row_doc_det->fields("monto"),2,',','.');
						$xxx=$causados;
					}
					else
					{
						$causado=number_format($row->fields("monto_causado"),2,',','.');
						$xxx=$causados;
					}
				//	die($a_causar);
		  			   $responce=$partida."*".$causado."*".$pagado."*".$xxx."*".$a_causar."*".$total_renglon;
}	
// return the formated data
	
echo ($responce);
?>