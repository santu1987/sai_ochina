<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql_doc='SELECT id_documentos, id_organismo, ano, id_proveedor, tipo_documentocxp, 
       numero_documento, numero_control, porcentaje_iva, porcentaje_retencion_iva, 
       monto_bruto, monto_base_imponible, orden_pago, numero_compromiso, 
       descripcion_documento, comentarios, ultimo_usuario, fecha_ultima_modificacion, 
       fecha_documento, porcentaje_retencion_islr, estatus, fecha_vencimiento, 
       beneficiario, cedula_rif_beneficiario, estatus_404, retencion_ex1, 
       retencion_ex2, pret1, pret2, desc_ex1, desc_ex2, amortizacion, 
       aplica_bi_ret_ex1, aplica_bi_ret_ex2, numero_comprobante, n_comprobante_co, 
       monto_base_imponible2, porcentaje_iva2, retencion_iva2, contabilizado
  FROM documentos_cxp;
';
$row_doc=& $conn->Execute($Sql_partidas);
$is=0;
	while (!$row_partidas->EOF) 
	{
		}













?>