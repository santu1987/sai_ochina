<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
function validar_factura($monto_total,$monto_compromiso,$numero_compromiso,$numero_documento)
{
			$sql_validar="
				SELECT SUM( (documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)+(documentos_cxp.porcentaje_iva2*documentos_cxp.monto_base_imponible2/100))) as total_factura
			FROM
				documentos_cxp
			WHERE
				 numero_compromiso='$numero_compromiso'	
				";
			$rs_validare=& $conn->Execute($sql_validar);
			if(!$rs_validare->EOF)
			{
				$monto_suma=$rs_validare->fields("total_factura")+$monto_total;
				
				if($monto_suma>$monto_compromiso)
					$valor="no_pasa";
				else
					$valor="pasa";
						
			}else
				$valor="no_pasa";	
			return $valor;
}
?>