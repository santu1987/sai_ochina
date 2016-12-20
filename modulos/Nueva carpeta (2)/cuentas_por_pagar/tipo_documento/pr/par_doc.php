<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT id_documentos, id_organismo, ano, id_proveedor, tipo_documentocxp, 
       numero_documento, numero_control, porcentaje_iva, porcentaje_retencion_iva, 
       monto_bruto, monto_base_imponible, orden_pago, numero_compromiso, 
       descripcion_documento, comentarios, ultimo_usuario, fecha_ultima_modificacion, 
       fecha_documento, porcentaje_retencion_islr, estatus, fecha_vencimiento, 
       beneficiario, cedula_rif_beneficiario, estatus_404, retencion_ex1, 
       retencion_ex2, pret1, pret2, desc_ex1, desc_ex2, amortizacion, 
       aplica_bi_ret_ex1, aplica_bi_ret_ex2, numero_comprobante, n_comprobante_co, 
       monto_base_imponible2, porcentaje_iva2, retencion_iva2, contabilizado
  FROM documentos_cxp;;
";
//*********
//die($sql);
$row=& $conn->Execute($sql);
while (!$row->EOF)
{
	$fecha=$row->fields("fecha_ultima_modificacion");

	$dia=substr($fecha,8,2);
	$ano=substr($fecha,0,4);
	$mes=substr($fecha,5,2);
/*	$uno=substr($mes,0,1);
	if($uno==0)
	$mes=substr($mes,1,1);
*/	
//die($dia.$mes.$ano);
$comp=$ano.$mes.$dia.$row->fields("numero_comprobante");				
$id=$row->fields("id_documentos");
$sql_act="UPDATE documentos_cxp
   SET  
       numero_comprobante=$comp
	where
	id_documentos='$id'
";//echo($sql_act."---");
if($row->fields("numero_comprobante")!="")
{
		if(strlen($row->fields("numero_comprobante"))<=6)
		{
			if (!$conn->Execute($sql_act)) {
									
									die ('Error al Actualizar: '.$sql_act);
									}
									else
									echo($comp."-");
		}
}		
$row->MoveNext();
}



?>