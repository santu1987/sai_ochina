<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//EN ESTE PROCESO SE ASIGNAN LOS NUMEROS DE COMPROBANTES A LAS FACTURAS SELECCIONADAS...
$comprobante_x=$_POST['cuentas_por_pagar_numero_pr_numero_comprobante2'];
				$sql_doc="
						UPDATE
								documentos_cxp
						set
								estatus='1'
						WHERE
								numero_comprobante='$comprobante_x'";
				if (!$conn->Execute($sql_doc)) 
				{
					die("error a actualizar documento_".$sql_doc);
				}//FIN DE 	if (!$conn->Execute($sql_doc)) 	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
die("Actualizado");
?>