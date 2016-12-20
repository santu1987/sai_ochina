<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

					$valor =str_replace('.','',$_POST['sareta_avisos_anuales_pr_montoTotal']);

						$sql = "
										UPDATE  sareta.planilla
								 				SET
										 		obs='$_POST[sareta_avisos_anuales_pr_vista_observacion]',
												id_armador =$_POST[sareta_avisos_anuales_pr_id_armador],
												id_agencia_naviera =$_POST[sareta_avisos_anuales_pr_id_agencia],
												id_cambio_moneda =$_POST[sareta_avisos_anuales_pr_id_moneda],
												moneda_cambio=$_POST[sareta_avisos_anuales_valor_moneda],
												monto=".str_replace(',','.',$valor).",
												ultimo_usuario='".$_SESSION['usuario']."',
												fecha_actualizacion='".date("Y-m-d H:i:s")."'
												WHERE id=$_POST[vista_id_avisos_anuales]	
											";
								
											
if (!$conn->Execute($sql)) 
{
die ('Error al Actualizar: '.$conn->ErrorMsg());
//echo $sql;
}else{
die("Actualizado");
}
							
?>
