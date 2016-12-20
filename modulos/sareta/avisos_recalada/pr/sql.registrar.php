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
				
		$sqlb = "SELECT * FROM sareta.planilla WHERE id_buque =".$_POST['avisos_recalada_id_buque']." AND fecha_recalada= '".$_POST[avisos_recalada_pr_fecha_recalada]." ". $_POST[sareta_ley_pr_vista_hora_rec].":00+00'";
	
	if (!$conn->Execute($sqlb)) die ('Error al Registrar: '.$conn->ErrorMsg());
	
	$row= $conn->Execute($sqlb);
					
					if(!$row->EOF){
					$matricula_buque=$row->fields("matricula_buque");
					$nombre_buque=$row->fields("nombre_buque");
					$fecha_rec_buque=$row->fields("fecha_recalada");
					$delegacion=$row->fields("id_delegacion");
					}
	if($row->EOF){
	
					$sqlb = "
					SELECT
						sareta.tipo_documento.ultimo_numero 
					FROM 
						sareta.tipo_documento
					INNER JOIN 
						sareta.nombre_documento
					ON 
						sareta.tipo_documento.id_nombre_documento=sareta.nombre_documento.id 
					INNER JOIN 
						sareta.numero_control
					ON 
						sareta.numero_control.id_numero_control=sareta.tipo_documento.id_numero_control 
					WHERE 
						sareta.tipo_documento.id_delegacion=$_SESSION[id_unidad_ejecutora] AND 
						sareta.tipo_documento.id_nombre_documento=
						(select id from sareta.nombre_documento where codigo=1)  
					";
	
					if (!$conn->Execute($sqlb)) die ('Error al Registrar: '.$conn->ErrorMsg());
					
					$row= $conn->Execute($sqlb);
									
									if(!$row->EOF){
									$ultimo_numero=$row->fields("ultimo_numero");
									
									}
					if(!$row->EOF){
					
	
					$valor =str_replace('.','',$_POST['sareta_avisos_recalada_pr_montoTotal_rec']);
					
						$sql = "	
										INSERT INTO 
											 sareta.planilla
											(
												numero_documento,
												id_buque,
												matricula_buque,
												call_sign_buque,
												nombre_buque,
												registro_bruto_buque,
												id_ley_buque,
												tarifa_buque,
												id_bandera_buque,
												id_clase_buque,
												id_actividad_buque,
												obs,
												
												id_compania ,
												id_delegacion ,
												
												
												id_bandera_origen,
												id_puerto_origen ,
												id_bandera_recalada ,
												id_puerto_recalada ,
												id_bandera_destino ,
												id_puerto_destino ,
												
												id_armador ,
												id_agencia_naviera ,
												
												id_cambio_moneda ,
												moneda_cambio,
												monto,
												
												
												".(($_POST[avisos_recalada_id_remolcador])?"
												id_remolcador ,
												nombre_remolcador,
												matricula_remolcador,
												call_sign_remolcador,
												registro_bruto_remolcador ,
												tarifa_remolcador ,
												id_bandera_remolcador ,
												id_ley_remolcador ,
												id_clase_remolcador ,
												id_actividad_remolcador,
												":"")."
												
												
												fecha_recalada ,
												fecha_zarpe,
												".(($_POST[avisos_recalada_estatus]==0)?"
												estatus,
												":"")."
												
												ultimo_usuario,
												fecha_creacion,
												fecha_actualizacion,
												tipo_documento_codigo
											) 
											VALUES
											(
											 ".$ultimo_numero.",
												$_POST[avisos_recalada_id_buque],
												'$_POST[sareta_avisos_recalada_pr_matricula]',
												'$_POST[sareta_avisos_recalada_pr_call_sign]',
												'$_POST[sareta_avisos_recalada_pr_buque]',
												$_POST[arqueo_bruto_buq],
												$_POST[avisos_recalada_id_ley_buque],
												$_POST[tarifa_buq],
												$_POST[avisos_recalada_id_bandera_buque],
												$_POST[avisos_recalada_id_clase_buque],
												$_POST[avisos_recalada_id_actividad_buque],
												'$_POST[sareta_avisos_recalada_pr_vista_observacion]',
												
												".$id_compania.",
												".$_SESSION["id_unidad_ejecutora"].",
												
												$_POST[avisos_recalada_id_bandera_org],
												$_POST[avisos_recalada_id_puerto_org],
												".$id_VENEZUELA.",
												$_POST[avisos_recalada_id_puerto_rec],
												$_POST[avisos_recalada_id_bandera_det],
												$_POST[avisos_recalada_id_puerto_det],
												
												$_POST[avisos_recalada_id_armador],
												$_POST[avisos_recalada_id_agencia_naviera],
												
												$_POST[avisos_recalada_id_cambio_moneda],
												$_POST[avisos_recalada_valor_moneda],
												".str_replace(',','.',$valor).",
												
												".(($_POST[avisos_recalada_id_remolcador])?"
												$_POST[avisos_recalada_id_remolcador],
												'$_POST[sareta_avisos_recalada_pr_remolcador]',
												'$_POST[sareta_avisos_recalada_pr_matricula_remolcador]',
												'$_POST[sareta_avisos_recalada_pr_call_sign_remolcador]',
												$_POST[arqueo_bruto_rem],
												$_POST[tarifa_rem],
												$_POST[avisos_recalada_id_bandera_remolcador],
												$_POST[avisos_recalada_id_ley_remolcador],
												$_POST[avisos_recalada_id_clase_remolcador],
												$_POST[avisos_recalada_id_actividad_remolcador],
												":"")."
												
												
												'".$_POST[avisos_recalada_pr_fecha_recalada]." ". $_POST[sareta_ley_pr_vista_hora_rec].":00+00',
												'".$_POST[avisos_recalada_pr_fecha_zarpe]." ". $_POST[sareta_ley_pr_vista_hora_zap].":00+00',
												
												".(($_POST[avisos_recalada_estatus]==0)?"
												'0',
												":"")."
												
												'".$_SESSION['usuario']."',
												'".date("Y-m-d H:i:s")."',
												'".date("Y-m-d H:i:s")."',
												1												
											);
											
											UPDATE 
											sareta.tipo_documento 
											SET 
											ultimo_numero=ultimo_numero+1 
											WHERE 
							sareta.tipo_documento.id_delegacion=$_SESSION[id_unidad_ejecutora] AND 
						sareta.tipo_documento.id_nombre_documento=
						(select id from sareta.nombre_documento where codigo=1)  ;
											";
											
								
											/*estatus
											0=perdiente
											1=cancelado
											2=reversado
											*/
											
											/*sareta.nombre_documento.codigo
											1=PLR
											2=PLA
											3=NOTA DE CREDITO
											4=PCP
											5=PCI
											6=PPM
											7=DEPOCITO
											12=TRANSFERENCIA
											*/
								
					}
					else				
					{
					
					die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>
					No se encontró secuencia valida para este tipo de documento.<br /> Posible Causas: <br />
						1.- Esta delegación no ha creado registro para este tipo de documento. <br />
						2.- El tipo de documento no tiene nombre de tabla para la relación.<br />
						3.- Este tipo de documento no tiene relación con numero control </p></div>");
					}			
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
die ('Error al Registrar: '.$conn->ErrorMsg());
//echo $sql;
}else{
die("Registrado");
}
							
?>
