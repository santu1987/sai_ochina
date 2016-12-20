<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if($_POST['sareta_tipo_documento_db_vista_codigo_numero_control']!="")
{
	if($_POST['sareta_tipo_documento_db_vista_codigo_nombre_docmento']!="")
	{
			$sql3 = "SELECT id_unidad_ejecutora FROM usuario WHERE 
							id_usuario=".$_SESSION['id_usuario'];
												$row1= $conn->Execute($sql3);
												$delegacion=0;
												if(!$row1->EOF){
												$delegacion=$row1->fields("id_unidad_ejecutora");
												}
				$sql = "SELECT * FROM sareta.tipo_documento WHERE nombre='".$_POST["sareta_tipo_documento_db_vista_nombre"]."'";
					$row= $conn->Execute($sql);
						if($row->EOF){
										
														$sql="	
														INSERT INTO 
														sareta.tipo_documento
														(
														  nombre,
														  factor,
														  vida_propia,
														  pago_inmediato,
														  pago_posterior,
														  calculo_mora,
														  id_numero_control,
														  ultimo_numero,
														  obs,
														  ultimo_usuario,
														  fecha_creacion,
														  fecha_actualizacion,
														  id_delegacion,
														  id_nombre_documento
														) 
														VALUES
														(
														
															'".$_POST["sareta_tipo_documento_db_vista_nombre"]."',
															$_POST[sareta_tipo_documento_db_vista_factor],
															$_POST[sareta_tipo_documento_db_vista_vida_propia],
															$_POST[sareta_tipo_documento_db_vista_paso_inmediato],
															$_POST[sareta_tipo_documento_db_vista_pago_posterior],
															$_POST[sareta_tipo_documento_db_vista_mora],
															$_POST[sareta_tipo_documento_db_vista_codigo_numero_control],
															$_POST[sareta_tipo_documento_db_vista_numero],
															'$_POST[sareta_tipo_documento_db_vista_obs]',
															".$_SESSION['id_usuario'].",
															'".date("Y-m-d H:i:s")."',
															'".date("Y-m-d H:i:s")."',
															".$delegacion.",
															$_POST[sareta_tipo_documento_db_vista_codigo_nombre_docmento]
														)";
					}else{die("NoRegistro");}
								
							
			if (!$conn->Execute($sql)) 
				die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
			else
				die("Registrado");
	}else{
	die("nombre_documento_vacio");
	}
}else{
die("numero_control_vacio");
}
?>