<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$registrados="Para esta Delegaci&oacute;n se registro:";
$registrado;
$esp;
$desde=$_POST['aviso_anual_pr_desde'];
$hasta=$_POST['aviso_anual_pr_hasta'];

$sql = "SELECT id_organismo FROM unidad_ejecutora WHERE id_unidad_ejecutora=".$_SESSION["id_unidad_ejecutora"];
	
	$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$id_compania=$row->fields("id_organismo");
					}
					


if($desde<=$hasta){

	for($ano=$desde;$ano<=$hasta;$ano++)
	{
		$sql = "".$_SESSION["id_unidad_ejecutora"];
	
	
		
	$sql2 = "SELECT 
	planilla.id_delegacion,
	(SELECT nombre FROM unidad_ejecutora WHERE id_unidad_ejecutora=planilla.id_delegacion) as nombre
	FROM sareta.planilla WHERE ano_pago=".$ano." 
	and id_buque=".$_POST['sareta_aviso_anual_id_buque']." ";
			if (!$conn->Execute($sql2)) die ('Error al Registrar: '.$conn->ErrorMsg());
			
				$row= $conn->Execute($sql2);
					$delegacion=0;
					if(!$row->EOF){
					$nombre_delegacion=$row->fields("nombre");
					}
			if($row->EOF)
				{
				
				//*****************************************************
			//*****************************************************
			//							BUSCANDO FECHA RECALADA
			if ($ano==date("Y"))
			{
				$fecha_actual=mktime(0,0,0,date("m"),date("d"),date("Y"));
			}
			else
			{
				$fecha_actual=mktime(0,0,0,12,$dia=31,$ano);
				while (date(w,$fecha_actual)==0 or date(w,$fecha_actual)==6) 
				{
					$fecha_actual=mktime(0,0,0,12,$dia--,$ano);
				}
			}		
			//*****************************************************	
			//			VERIFICANDO EXISTENCIA DE numero_documento
				
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
						(select id from sareta.nombre_documento where codigo=2)  
					";
	
					if (!$conn->Execute($sqlb)) die ('Error al Registrar: '.$conn->ErrorMsg());
					
					$row= $conn->Execute($sqlb);
									
									if(!$row->EOF){
									$ultimo_numero=$row->fields("ultimo_numero");
									
									}
					if(!$row->EOF){
			$valor1=str_replace('.','',$_POST['sareta_aviso_anual_pr_rb']);
			$valor2=str_replace('.','',$_POST['sareta_aviso_anual_tarifa_buque']);
			$sql = "	
							INSERT INTO 
								 sareta.planilla
								(
								 estatus,
								id_compania ,
								id_delegacion ,
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
									ano_pago,
									fecha_recalada,
									ultimo_usuario,
									fecha_creacion,
									fecha_actualizacion,
									tipo_documento_codigo
								) 
								VALUES
								(
								 '0',
								".$id_compania.",
								".$_SESSION["id_unidad_ejecutora"].",
								".$ultimo_numero.",
									$_POST[sareta_aviso_anual_id_buque],
									'$_POST[sareta_aviso_anual_pr_matricula]',
									'$_POST[sareta_aviso_anual_pr_call_sign]',
									'$_POST[sareta_aviso_anual_pr_buque]',
									".str_replace(',','.',$valor1).",
									$_POST[sareta_aviso_anual_id_ley_buque],
									".str_replace(',','.',$valor2).",
									$_POST[sareta_aviso_anual_id_bandera_buque],
									$_POST[sareta_aviso_anual_id_clase_buque],
									$_POST[sareta_aviso_anual_id_actividad_buque],
									'$_POST[sareta_aviso_anual_pr_vista_observacion]',
									".$ano.",
									'".date("Y-m-d H:i:s",$fecha_actual)."',
									'".$_SESSION['usuario']."',
									'".date("Y-m-d H:i:s")."',
									'".date("Y-m-d H:i:s")."',
									'2'
									
								);
								
											UPDATE 
											sareta.tipo_documento 
											SET 
											ultimo_numero=ultimo_numero+1 
											WHERE 
							sareta.tipo_documento.id_delegacion=$_SESSION[id_unidad_ejecutora] AND 
						sareta.tipo_documento.id_nombre_documento=
						(select id from sareta.nombre_documento where codigo=2)  ;
								
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
						
								if (!$conn->Execute($sql)) 
								{
								die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
								}
								else
								{
								$registrados.=" ".$ano;
								$esp.=" ";
								if($ano==$hasta)
								{
								$registrado="Registrado";	
								}			
				}//fin for	
				
		}
		else
		{
			$ano_compara=0;
			if(($ano>$ano_compara) ){
		$AnoExistentes=" <br>El a&ntilde;o ".$ano." Fue Registrado por la Delegaci&oacute;n:".$nombre_delegacion."" ;
			$ano_compara=$ano;
			}
		}



	}
	
			if($registrado=="Registrado")
			{
				
				$compara="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>  Las siguientes fechas:</p></div>";
				if($AnoExistentes==$compara)
				{
					die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>Para el Buque:".$_POST['sareta_aviso_anual_pr_buque'].
						" ".$registrados."</p></div>");
				}
				else if($AnoExistentes!=$compara)
				{
					die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>    Para el Buque:".$_POST['sareta_aviso_anual_pr_buque'].
						"<br>".$AnoExistentes." <br /><br>".$registrados." <</p></div>");
				}
				else
				{
					die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>   Para el Buque:".$_POST['sareta_aviso_anual_pr_buque'].
						"Se registraron para esta Delegaci&oacute;n ".$registrados."</p></div>");
				}
			}
			else 
			{
				die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>   Para el Buque:".$_POST['sareta_aviso_anual_pr_buque']."<br>".$AnoExistentes."</p></div>");
			}
	
	
	}else{
	die("ErrorDeFechas");
	}
	
	
?>