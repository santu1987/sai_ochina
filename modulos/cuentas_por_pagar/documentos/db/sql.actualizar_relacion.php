<?
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
/////////////////////////////////////////
$id=$_POST['cuentas_por_pagar_db_rel_doc_id'];
if($id!='')
{
	$sql_relacion="
					SELECT * FROM rel_doc_cta WHERE id='$id'
	";
	$row=& $conn->Execute($Sql);
	if(!$row->EOF)
	{
		$sql_actualizar_relacion="
									UPDATE
											rel_doc_cta
									SET
											id_cta_contable='$_POST[cuentas_por_pagar_db_reldoc_cuenta_id]',
											id_tipo='$_POST[cuentas_por_pagar_db_rel_doc_cta_tipo]'
									WHERE
											rel_doc_cta.id='$id'			
		";
		if (!$conn->Execute($sql_actualizar_relacion)) 
		die ('Error al registrar: '.$sql_actualizar_relacion);
	}
}
else
{
	die('NoActualizo');	
}
	die('Actualizado');
?>