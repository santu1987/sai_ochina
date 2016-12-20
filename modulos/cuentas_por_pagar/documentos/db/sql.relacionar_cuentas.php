<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
/////////////////////////////////////////
$sql_guardar_relacion=
					"
						INSERT
								INTO
									rel_doc_cta
								(
									id_tipo,
									id_cta_contable
								)
								VALUES
								(
									'$_POST[cuentas_por_pagar_db_rel_doc_cta_tipo]',
									'$_POST[cuentas_por_pagar_db_reldoc_cuenta_id]'
								)	
					";
	if(!$conn->Execute($sql_guardar_relacion))				
	{
		die ('Error al Registrar: '.$conn->ErrorMsg().$sql_guardar_relacion);

	}
	else
	{
		die('Registrado');
	}

?>