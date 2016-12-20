<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
//include_once('../../../../controladores/numero_to_letras.php');
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
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
	
	$proveedor=$_GET['proveedor'];
	$beneficiario=$_GET['beneficiario'];
	$ncuenta=$_GET['ncuenta'];
	$banco=$_GET['banco'];
	$precheque=$_GET['precheque'];
	$islr=$_GET['islr'];
	$retislr=str_replace(",",".",$islr);
	$ordenes=$_GET['ordenes'];
	$rif=$_GET['rif'];
	$sustarendo=$_GET['sustarendo'];
if(($ncuenta!="")and($banco!=""))
{	
	$where="WHERE(orden_pago.id_organismo=$_SESSION[id_organismo])
			AND orden_pago.estatus='2'
			";
		 if($precheque!="")
		{
			$where.="AND 
						(orden_pago.cheque='$precheque'
					 OR	
					  	(orden_pago.cheque='0'))	
					";
		
		}else
		{
			$where.="AND 
						(orden_pago.cheque='0')	
						";
		
		}	if($proveedor!="")
			{
				$where.="AND orden_pago.id_proveedor='$proveedor'";			
			}else
			if($beneficiario!="")
			{
				$where.="AND orden_pago.cedula_rif_beneficiario='$beneficiario'";			
			}
  }
   //AND id_banco='$banco'
   //AND cuenta_banco='$ncuenta'
	else
	{
		$where="WHERE 1=2 ";
	}
$Sql="
			SELECT 
				count(orden_pago.id_orden_pago) 
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
			
			$where	
	
		";

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
			SELECT distinct
				orden_pago.id_orden_pago,
				orden_pago.orden_pago,
				fecha_orden_pago
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
			
			$where
			order by 
			orden_pago.orden_pago ASC
		
";		
 		
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	
$row=& $conn->Execute($Sql);
$idem=0;
$vector = split( ",", $ordenes);
sort($vector);
///

///
while (!$row->EOF) 
{
	

	$orden=$row->fields("orden_pago");
				$Sql2="
						SELECT 
							documentos_cxp.monto_bruto,
							documentos_cxp.monto_base_imponible,
							documentos_cxp.porcentaje_iva,
							documentos_cxp.porcentaje_retencion_iva,
							documentos_cxp.porcentaje_retencion_islr
						FROM 
							documentos_cxp
						WHERE
							documentos_cxp.orden_pago='$orden'
							
							
			";
			$row_orden=& $conn->Execute($Sql2);
	$pagar="";
	$numero_orden=$orden;
	

		if($vector[$idem]==$numero_orden)
		{
			
			$retislr2=$retislr;
			$idem=$idem+1;
			$asd2='1';
			
		}
		else
		{
			$retislr2=0;
		}
		$base_imponible=0;
		$monto_bruto=0;
	while (!$row_orden->EOF) 
	{
		
		$base=$row_orden->fields("monto_base_imponible");
		$bruto=$row_orden->fields("monto_bruto");
		$iva=$row_orden->fields("porcentaje_iva");
		if($retislr2==0)
		{
			$retislr2=$row_orden->fields("porcentaje_retencion_islr");
		}
		$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
		/*$base_iva=(($base)*($iva))/100;
		$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
		$bruto_iva=($bruto)+($base_iva)-($monto_restar);//
		//-
		$islr=((($bruto)*($retislr2))/100);
		if(($base>=4138)&&($rif=='V')&&($islr!='0'))$islr=$islr-138;
		$monto_def=($bruto_iva)-$islr;
		$pagar=$pagar+$monto_def;
		//-*/
		$base_imponible=$base_imponible+$base;
		$monto_bruto=$monto_bruto+$bruto;
		$row_orden->MoveNext();		
	}
		//----calculos
		$base_iva=(($base_imponible)*($iva))/100;
		$monto_restar=(($base_iva)*($porcentaje_iva_ret))/100;
		$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);//
		//-
		$islr=((($monto_bruto)*($retislr2))/100);
		if($asd2=='1')
		{
			if($sustraendo=='1')$islr=$islr+138;
		}		$monto_def=($bruto_iva)-$islr;
		$pagar=$pagar+$monto_def;
		//-
	/*if($porcentaje_iva_ret==100)
	{
	$iva=0;
	$monto_iva=0;
	$monto_total=$monto_bruto;
	}
	else
	{
		$porcentaje_iva=((($iva)*($porcentaje_iva_ret))/100);
		$porcentaje_iva=($iva)-($porcentaje_iva);
		$monto_total=$monto_bruto;
		$iva_monto=(($monto_total)*($porcentaje_iva))/100;
		$monto_total=($monto_total)+($iva_monto);
	}
		
	$islr=((($monto_total)*($retislr))/100);
	$monto_def=$monto_total-$islr;*/
	$fecha=substr($row->fields("fecha_orden_pago"),0,10);
	$fecha2=split("-",$fecha);
	$dia=$fecha2[2];
	$mes=$fecha2[1];
	$ayo=$fecha2[0];			
	$fecha_final=$dia."/".$mes."/".$ayo;
	$responce->rows[$i]['id']=$row->fields("id_orden_pago");

	$responce->rows[$i]['cell']=array(	
																								
															$row->fields("id_orden_pago"),
															$row->fields("orden_pago"),
															$fecha_final,
															number_format($monto_bruto,2,',','.'),
															number_format($base_imponible,2,',','.'),
															$iva,
															$porcentaje_iva_ret,
															number_format($bruto_iva,2,',','.'),
															$retislr2,
															number_format($islr,2,',','.'),
															number_format($pagar,2,',','.'),
														);
	$i++;
	$asd2='0';
	$row->MoveNext();
}
// return the formated data     number_format($row->fields("monto_pagar"),2,',','.')

echo $json->encode($responce);

?>