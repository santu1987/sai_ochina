<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT * FROM sareta.tipo_documento WHERE nombre='".$_POST["sareta_tipo_documento_db_vista_nombre"]."'" ;
		$row= $conn->Execute($sql);
			if($row->EOF || $_POST['vista_id_tipo_documento']==$row->fields("id")){
	

	$sql = "	
					UPDATE sareta.tipo_documento 
						 SET
						nombre='".$_POST["sareta_tipo_documento_db_vista_nombre"]."',
					    factor=".$_POST['sareta_tipo_documento_db_vista_factor'].",
						vida_propia=".$_POST['sareta_tipo_documento_db_vista_vida_propia'].",
						pago_inmediato=".$_POST['sareta_tipo_documento_db_vista_paso_inmediato'].",
						pago_posterior=".$_POST['sareta_tipo_documento_db_vista_pago_posterior'].",
						calculo_mora=".$_POST['sareta_tipo_documento_db_vista_mora'].",
						id_numero_control=".$_POST['sareta_tipo_documento_db_vista_codigo_numero_control'].",
						ultimo_numero=".$_POST['sareta_tipo_documento_db_vista_numero'].",
						obs='".$_POST['sareta_tipo_documento_db_vista_obs']."',
  						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion ='".date("Y-m-d H:i:s")."',
						id_nombre_documento= $_POST[sareta_tipo_documento_db_vista_codigo_nombre_docmento]
						WHERE id= $_POST[vista_id_tipo_documento]
							
				";
				
				}else{die("NoActualizo");}
				

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>