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
$ayo=date('Y');
$numero_documento2="";

//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
//************************************************************************
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
///////////////////////////////////////////////////////////////////////////
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
///////////////////////////////////////////////////////////////////////////////////////////////-/-/-/-/---///////////////////////////////////////////////////////////////////////////////////////
$limit = 15;
if(!$sidx) $sidx =1;
	
	$proveedor=$_GET['proveedor'];
	$banco=$_GET['banco'];
	$ordenes=$_GET['ordenes'];
if(($proveedor!=""))
{	
	$where="WHERE(orden_pago.id_organismo=$_SESSION[id_organismo])
            AND orden_pago.id_proveedor='$proveedor'
			AND orden_pago.estatus='2'
			";
		 
			$where.="AND 
						(orden_pago.cheque='0')	
						";
		
  }
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
			INNER JOIN 
				proveedor
			ON 
				orden_pago.id_proveedor=proveedor.id_proveedor
			$where	
	
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
			INNER JOIN 
				proveedor
			ON 
				orden_pago.id_proveedor=proveedor.id_proveedor
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
while (!$row->EOF) 
{
	$orden=$row->fields("orden_pago");
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
				 			documentos_cxp.retencion_ex2
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
			$asd='1';
		}
		else
		{
			$retislr2=0;
		}
		$base_imponible=0;
		$monto_bruto=0;
	//ciclo de los documentos	
	while (!$row_orden->EOF) 
	{
		//datos
		$numero_documento=$row_orden->fields("numero_documento");
		if($numero_documento2=="")
		$numero_documento2=$numero_documento;
		else
		$numero_documento2=$numero_documento2."-".$numero_documento;
		$monto_bruto=$row_orden->fields("monto_bruto");
		$iva=$row_orden->fields("porcentaje_iva");
		$ret1=$row_orden->fields("retencion_ex1");
		$ret2=$row_orden->fields("retencion_ex2");
		
		if(($retislr2==0)||($retislr2=='0.00'))
		{
			/*if(($retislr!='0.00')||($retislr!=0))
			$retislr2=$retislr;
			else*/
			$retislr2=$row_orden->fields("porcentaje_retencion_islr");
		}
		/*if($retislr2==0)
		{
			$retislr2=0;
		}*/
						
		$porcentaje_iva_ret=$row_orden->fields("porcentaje_retencion_iva");
		//-/si es factura con anticipo
		 if(($row_orden->fields("tipo_documentocxp")==$tipos_fact)&&($row_orden->fields("amortizacion")!='0'))
		 {
				$monto_bruto=$row_orden->fields("monto_bruto");
				$amort=$row_orden->fields("amortizacion");
				$base_imponible=$monto_bruto;//+$amort
				$islr=($base_imponible*$retislr2)/100;
		 }else
		 {
				$islr=($monto_bruto*$retislr2)/100;
				$base_imponible=$row_orden->fields("monto_base_imponible");
		}
		//----calculos
		$base_iva=($base_imponible*$iva)/100;
		$monto_restar=($base_iva*$porcentaje_iva_ret)/100;
		$bruto_iva=($monto_bruto)+($base_iva)-($monto_restar);
		//
		//-
		if(($asd=='1')&&($islr!='0'))
		{
			if(($sustraendo=='1')&&($monto_bruto>=$monto_det))
			{
				$islr=$islr-138;
			}	
		}	
		$retenciones=$ret1+$ret2;
		$monto_def=($bruto_iva)-($islr+$retenciones);
		$total_orden=$total_orden+$monto_def;
		$monto_def=0;
		$islr_m=$retislr2;
		$monto_bruto2=$monto_bruto2+$monto_bruto;
		$base_imponible2=$base_imponible2+$base_imponible;
		$retencion1=$row_orden->fields("retencion_ex1");
		$retencion2=$row_orden->fields("retencion_ex2");
		$ret_total=$retencion1+$retencion2;
		//----//
		$sql_comp = "		UPDATE documentos_cxp 
										 SET
											porcentaje_retencion_islr=$retislr2,
											ultimo_usuario=".$_SESSION['id_usuario'].", 
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."'
										WHERE 
													id_organismo=$_SESSION[id_organismo]
											AND
													ano='$ayo'
											AND
													numero_documento='$numero_documento'
										   ";
		$conn->Execute($sql_comp);
		//----//
		$row_orden->MoveNext();		
	}
		
		$retislr2=0;
		$pagar=$pagar+$total_orden;
		$total_orden=0;
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
															$numero_documento2,
															$fecha_final,
															number_format($pagar,2,',','.')
															
														);
	$i++;
	$asd='0';
	$islr_m=0;
	$monto_bruto2=0;
	$base_imponible2=0;
//	$retislr2=0;
$numero_documento2="";
	$row->MoveNext();
}
// return the formated data     number_format($row->fields("monto_pagar"),2,',','.')

echo $json->encode($responce);

?>