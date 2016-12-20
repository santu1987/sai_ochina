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
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$id_prove=$_POST['cuentas_por_pagar_vencido_rp_proveedor_id'];
if($id_prove!="")
$where2="WHERE orden_pago.id_proveedor='$id_prove'";
////
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
//
//die($_POST['cuentas_por_pagar_vencido_rp_tipo_documento']);

if((isset($_POST['cuentas_por_pagar_vencido_documentos_rp_fecha_desde']))&&(isset($_POST['cuentas_por_pagar_vencido_documentos_rp_fecha_hasta'])))
{
	$desde=$_POST['cuentas_por_pagar_vencido_documentos_rp_fecha_desde'];
	$hasta=$_POST['cuentas_por_pagar_vencido_documentos_rp_fecha_hasta'];
}
list($dia,$mes,$ayo)=split("/",$hasta,3);
if(($dia=="30")&&($mes=="3"||$mes=='5'||$mes=='7'||$mes=='9'||$mes=='11'))
{
	$dia=01;
	$mes=$mes+1;
 
 }
 else
if($dia=="31")
{
	$dia=01;
	$mes=$mes+1;
	 if($mes=="12")
	 {
		$mes="10";
		$ayo=$ayo+1;
	  }	
 }
 else
 $dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;
 if(isset($_POST['cuentas_por_pagar_vencido_documentos_rp_fecha_desde']))
{
	$where=" WHERE
				 documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]."
				 AND documentos_cxp.estatus!='3' 
				 AND documentos_cxp.fecha_vencimiento>='$desde' AND documentos_cxp.fecha_vencimiento<='$hasta'
		 ";
}
if(isset($_POST['cuentas_por_pagar_vencido_rp_tipo_documento']))	
{
	
	$tipo=$_POST['cuentas_por_pagar_vencido_rp_tipo_documento'];
	if($tipo!=0)
	$where.="AND documentos_cxp.tipo_documentocxp='$tipo'";
	//die($tipo);
	
}	
 	
	     
 if(isset($_POST['cuentas_por_pagar_vencido_rp_proveedor_id']))
{
	$proveedor=$_POST['cuentas_por_pagar_vencido_rp_proveedor_id'];
	if($proveedor!="")
	{
		$where.=" AND documentos_cxp.id_proveedor='$proveedor'";
		$a=" AND documentos_cxp.id_proveedor='$proveedor'";
	}
}
	     	     


//**************************** validando que tipo de reporte es *******************//////////
if(($proveedor=="")and($beneficiario=="") and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="VACIO";
}else
if(($proveedor=="")and($beneficiario=="")and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="tipo";
	$sql_tipo="
				SELECT
						nombre 
				FROM
					tipo_documento_cxp
				WHERE
					 id_tipo_documento='$tipo'";
	$row_tipo=& $conn->Execute($sql_tipo);
	$nombre_documento=$row_tipo->fields("nombre");
	$where.=" AND documentos_cxp.tipo_documentocxp='$tipo'";


}			  
else
if((($proveedor!="")or($beneficiario!=""))and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="proveedor";
}
else
if((($proveedor!="")or($beneficiario!=""))and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="TODOS";
	$sql_tipo="
				SELECT
						nombre 
				FROM
					tipo_documento_cxp
				WHERE
					 id_tipo_documento='$tipo'";
				//	 die($sql_tipo);
	$row_tipo=& $conn->Execute($sql_tipo);
	$nombre_documento=$row_tipo->fields("nombre");
}
$Sql="
			SELECT 
				 documentos_cxp.id_documentos,	
				 documentos_cxp.id_organismo,
				 documentos_cxp.id_proveedor,
		    	 documentos_cxp.beneficiario,
				 documentos_cxp.cedula_rif_beneficiario,
				 documentos_cxp.ano,
				 documentos_cxp.tipo_documentocxp,
				 documentos_cxp.numero_documento,
				 documentos_cxp.numero_control,
				 documentos_cxp.fecha_vencimiento,
				 documentos_cxp.porcentaje_iva,
				 documentos_cxp.porcentaje_retencion_iva,
				 documentos_cxp.porcentaje_retencion_islr,
				 documentos_cxp.monto_bruto,
				 documentos_cxp.monto_base_imponible,
				 documentos_cxp.numero_compromiso,
				 documentos_cxp.comentarios,
				 tipo_documento_cxp.nombre as doc,
				documentos_cxp.tipo_documentocxp,
				documentos_cxp.amortizacion,
				documentos_cxp.retencion_ex1,
				documentos_cxp.retencion_ex2,
				documentos_cxp.orden_pago 
			FROM 
				 documentos_cxp
			INNER JOIN
				organismo
			ON
				documentos_cxp.id_organismo=organismo.id_organismo
			INNER JOIN
				tipo_documento_cxp
			ON
				documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento		
			$where
			ORDER BY
				 documentos_cxp.id_documentos
";
		//	
		//die($Sql);
$row=& $conn->Execute($Sql);
while (!$row->EOF) 
{
///
	$sql2="SELECT 
					orden_pago.id_orden_pago,
					orden_pago.cheque,
					orden_pago.estatus
				FROM 
					orden_pago
				INNER JOIN
					orden_cheque
				ON
					orden_cheque.id_orden=orden_pago.id_orden_pago
				$where2	
					";
					//echo($sql2);
	$row2=& $conn->Execute($sql2);
	while(!$row2->EOF)
	{
		$cheque=$row2->fields("cheque");
		if(($cheque!="")&&($cheque!="0"))
			$alphas="no_pasa";
		else
			if(($cheque=="")or($cheque=="0"))
					$alphas="pasa";	
		
	$row2->MoveNext();		
	}if($row2->EOF){$alphas="pasa";}
	
///
	
$row->MoveNext();
					
}
die($alphas)
?>