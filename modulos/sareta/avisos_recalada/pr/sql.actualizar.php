<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	
	$sql = "SELECT id_organismo FROM unidad_ejecutora WHERE id_unidad_ejecutora=".$_SESSION["id_unidad_ejecutora"];
	
	$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$id_compania=$row->fields("id_organismo");
					}

	$sql = "SELECT id FROM sareta.bandera WHERE nombre='VENEZUELA'";
	
	$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$id_VENEZUELA=$row->fields("id");
					}
				//UN AVISO DE RECALADA ESTA DUPLICADO CUANDO TIENE EL MISMO BUQUE Y LA MISMA FECHA DE RECALADA, PERO Y SI TIENE DIFERENTE HORA DE RECALADA, SE PUEDE???	
				
		$sqlb = "SELECT * FROM sareta.planilla WHERE id_buque =".$_POST['avisos_recalada_id_buque']." AND fecha_recalada= '".$_POST[avisos_recalada_pr_fecha_recalada]." ". $_POST[sareta_ley_pr_vista_hora_rec].":00+00' and id!=$_POST[vista_id_avisos_recalada]";
	
	if (!$conn->Execute($sqlb)) die ('Error al Registrar: '.$conn->ErrorMsg());
	
	$row= $conn->Execute($sqlb);
					
					if(!$row->EOF){
					$matricula_buque=$row->fields("matricula_buque");
					$nombre_buque=$row->fields("nombre_buque");
					$fecha_rec_buque=$row->fields("fecha_recalada");
					$delegacion=$row->fields("id_delegacion");
					}
	if($row->EOF){
	
					
					
	
					$valor =str_replace('.','',$_POST['sareta_avisos_recalada_pr_montoTotal_rec']);
					
						$sql = "
										UPDATE  sareta.planilla
								 				SET
												id_buque=$_POST[avisos_recalada_id_buque],
												matricula_buque='$_POST[sareta_avisos_recalada_pr_matricula]',
												call_sign_buque='$_POST[sareta_avisos_recalada_pr_call_sign]',
												nombre_buque='$_POST[sareta_avisos_recalada_pr_buque]',
												registro_bruto_buque=$_POST[arqueo_bruto_buq],
												id_ley_buque=$_POST[avisos_recalada_id_ley_buque],
												tarifa_buque=$_POST[tarifa_buq],
												id_bandera_buque=$_POST[avisos_recalada_id_bandera_buque],
												id_clase_buque=$_POST[avisos_recalada_id_clase_buque],
												id_actividad_buque=$_POST[avisos_recalada_id_actividad_buque],
												obs='$_POST[sareta_avisos_recalada_pr_vista_observacion]',
												
												id_compania =".$id_compania.",
												id_delegacion =".$_SESSION["id_unidad_ejecutora"].",
												
												id_bandera_origen=$_POST[avisos_recalada_id_bandera_org],
												id_puerto_origen =$_POST[avisos_recalada_id_puerto_org],
												id_bandera_recalada =".$id_VENEZUELA.",
												id_puerto_recalada =$_POST[avisos_recalada_id_puerto_rec],
												id_bandera_destino =$_POST[avisos_recalada_id_bandera_det],
												id_puerto_destino =$_POST[avisos_recalada_id_puerto_det],
												
												id_armador =$_POST[avisos_recalada_id_armador],
												id_agencia_naviera =$_POST[avisos_recalada_id_agencia_naviera],
												
												id_cambio_moneda =$_POST[avisos_recalada_id_cambio_moneda],
												moneda_cambio=$_POST[avisos_recalada_valor_moneda],
												monto=".str_replace(',','.',$valor).",
												
												".(($_POST[avisos_recalada_id_remolcador]!='' )?"
												id_remolcador =$_POST[avisos_recalada_id_remolcador],
												nombre_remolcador='$_POST[sareta_avisos_recalada_pr_remolcador]',
												matricula_remolcador='$_POST[sareta_avisos_recalada_pr_matricula_remolcador]',
												call_sign_remolcador='$_POST[sareta_avisos_recalada_pr_call_sign_remolcador]',
												registro_bruto_remolcador =$_POST[arqueo_bruto_rem],
												tarifa_remolcador =$_POST[tarifa_rem],
												id_bandera_remolcador =$_POST[avisos_recalada_id_bandera_remolcador],
												id_ley_remolcador =$_POST[avisos_recalada_id_ley_remolcador],
												id_clase_remolcador =$_POST[avisos_recalada_id_clase_remolcador],
												id_actividad_remolcador=$_POST[avisos_recalada_id_actividad_remolcador],
												":"")."
												
												
												fecha_recalada ='".$_POST[avisos_recalada_pr_fecha_recalada]." ". $_POST[sareta_ley_pr_vista_hora_rec].":00+00',
												fecha_zarpe='".$_POST[avisos_recalada_pr_fecha_zarpe]." ". $_POST[sareta_ley_pr_vista_hora_zap].":00+00',
												ultimo_usuario='".$_SESSION['usuario']."',
											
												fecha_actualizacion='".date("Y-m-d H:i:s")."'
												WHERE id=$_POST[vista_id_avisos_recalada]	
											";
								
							
		}
		else				
		{
		$sql = "SELECT * FROM unidad_ejecutora WHERE id_unidad_ejecutora=".$delegacion;
	
	$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$nombre_delegacion=$row->fields("nombre");
					}
		die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>
		EL AVISO DE RECALADA QUE INTENTA REGISTRAR YA SE ENCUENTRA EN EL SISTEMA<br /> <br />
					DATOS DEL AVISO DE RECALADA REGISTRADO: <br />
					Delegaci&oacute;n: ".$nombre_delegacion."<br />
					Buque: ".$matricula_buque." - ".$nombre_buque." <br /> Fecha de Recalada: ".$fecha_rec_buque." </p></div>");
		}				
if (!$conn->Execute($sql)) 
{
die ('Error al Actualizar: '.$conn->ErrorMsg());
//echo $sql;
}else{
die("Actualizado");
}
							
?>
