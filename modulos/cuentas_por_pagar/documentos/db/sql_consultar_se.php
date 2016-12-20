<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$numero_comprobante=$_POST[cuentas_por_pagar_numero_pr_numero_comprobante2];
////////////////////////////////////////////////////////////////////
//proceso que consulta documentos relacionados por comprobante
				$sql_facturas_com="
					SELECT
							id_documentos
					FROM
							documentos_cxp
					WHERE
							numero_comprobante='$numero_comprobante'
					ORDER BY
							id_documentos						
					";
					$rs_factura_com=& $conn->Execute($sql_facturas_com);
					$contador_fac=0;
					while(!$rs_factura_com->EOF)
					{
						if($contador_fac=='0')
						{
							$vector_documento2=$rs_factura_com->fields("id_documentos");
						}
						else
						{	
							$vector_documento2=$vector_documento2.",".$rs_factura_com->fields("id_documentos");
						}
						$contador_fac++;
						$rs_factura_com->MoveNext();
					}
////////////////////////////////////////////////////////////////////
die($vector_documento2);				
?>