<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$ano=substr($fecha,0,4);
$mes=substr($fecha,5,2);
$alet=1;
$comporbante_int=$_POST[contabilidad_comprobante_pr_tipo].$_POST[contabilidad_comprobante_pr_numero_comprobante];
$sql_sumas=" SELECT
					SUM(monto_debito) as debe,
					SUM(monto_credito) as haber,
					fecha_comprobante
				from
					integracion_contable
				where numero_comprobante='$comporbante_int'
				group by fecha_comprobante
";
$row_sumas=& $conn->Execute($sql_sumas);
	if(!$row_sumas->EOF)
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-verficando si la fecha del comprobante le permite al mismo ser modificado luego del proceso de cierre.....
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
$fecha_comprobante=substr($row_sumas->fields("fecha_comprobante"),0,10);

$sqlfecha_cierre = "SELECT  fecha_cierre_anual,fecha_cierre_mensual FROM parametros_contabilidad WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//die($sqlfecha_cierre);
if(!$row_fecha_cierre->EOF){
	$fecha_cierre_anual = substr($row_fecha_cierre->fields('fecha_cierre_anual'),0,10);
	$fecha_cierre_mensual =substr($row_fecha_cierre->fields('fecha_cierre_mensual'),0,10);
}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre_mensual);
list($dia2,$mes2,$ano2)=split("-",$fecha_comprobante);
list($dia3,$mes3,$ano3)=split("-",$fecha_cierre_anual);

if(($dia1 >= $dia2) && ($mes1 >= $mes2) && ($ano1 >= $ano2))
{
	$cerrado="mes";
	
}else
if(($mes1 >= $mes2) && ($ano1 >= $ano2))
{
	$cerrado="mes";
}
if(($dia3 >= $dia2) && ($mes3 >= $mes2) && ($ano3 >= $ano2))
{
	$cerrado="ano";
}else
if(($mes3 >= $mes2) && ($ano3 >= $ano2))
{
	$cerrado="ano";
}
if($cerrado=="ano")
{
	die("modulo cerrado");
}
else if($cerrado=="mes")
{
	die("modulo cerrado");
}

else//en el caso q este abierto el modulo
{
	/*echo($dia1."-".$mes1."-".$ano1."cierre");
die($dia2."-".$mes2."-".$ano2."comp");

	die*/		
		
		
		
			$debe=number_format($row_sumas->fields("debe"),2,',','.');
			$haber=number_format($row_sumas->fields("haber"),2,',','.');
			if($debe!=$haber)
			{
				die("disparejo");
			}else
			if($debe==$haber)
			{
				
							
									/*$sql="SELECT numero_comprobante FROM numeracion_comprobante 
									inner join
										organismo
									on
										numeracion_comprobante.id_organismo=organismo.id_organismo
									where		
											(organismo.id_organismo =".$_SESSION['id_organismo'].")
									and
											ano='$ano'			
									ORDER BY 
											 numeracion_comprobante.id desc		 
									";*/
									$tipo_comprobante=$_POST[contabilidad_comprobante_pr_tipo];
									$sql="SELECT numero_comprobante,codigo_tipo_comprobante
										 FROM tipo_comprobante
										inner join
											organismo
										on
											tipo_comprobante.id_organismo=organismo.id_organismo
										where		
												(organismo.id_organismo =".$_SESSION['id_organismo'].") 
										and
											tipo_comprobante.codigo_tipo_comprobante='$tipo_comprobante'";
									$rs_comprobante =& $conn->Execute($sql);
									if(!$rs_comprobante->EOF)
									{
										$comprobante2=$rs_comprobante->fields("numero_comprobante");
										$cod_tipo=$rs_comprobante->fields("codigo_tipo_comprobante");
										if(($comprobante2!="")&&($comprobante2!='0000'))
											$comprobante3=$comprobante2+1.00;
										else
										if($comprobante2=='0000')
											$comprobante3="0001";
																								                                        $valor_medida=strlen($comprobante3);													//echo($numero_comprobantex3);

												//	echo($valor_medida);
												if($valor_medida==1)
												{
													$comprobante3="000".$comprobante3;
												}
												else
												if($valor_medida==2)
												{
													$comprobante3="00".$comprobante3;
												}
												else	
												if($valor_medida==3)
												{
															$comprobante3="0".$comprobante3;
												}
												
												$comprobante=$cod_tipo.$comprobante3;
													//die($numero_comprobantex3);
													
									}
									
									$sql_datos="SELECT 
													integracion_contable.id,
													integracion_contable.id_organismo,
													integracion_contable.ano_comprobante,
													integracion_contable.mes_comprobante,
													integracion_contable.id_tipo_comprobante,
													integracion_contable.numero_comprobante,
													integracion_contable.secuencia,
													integracion_contable.cuenta_contable,
													integracion_contable.descripcion,
													integracion_contable.referencia,
													integracion_contable.debito_credito,
													integracion_contable.monto_debito,
													integracion_contable.monto_credito,
													integracion_contable.fecha_comprobante,
													integracion_contable.id_auxiliar,
													integracion_contable.id_unidad_ejecutora,
													integracion_contable.id_proyecto,
													integracion_contable.id_accion_central,
													integracion_contable.id_utilizacion_fondos,
													cuenta_contable_contabilidad.nombre as descripcion_cuenta,
													cuenta_contable_contabilidad.id as id_cc,
													tipo_comprobante.codigo_tipo_comprobante				
												FROM 
													integracion_contable
												inner join
														organismo
														on
														integracion_contable.id_organismo=integracion_contable.id_organismo
												inner join 
													cuenta_contable_contabilidad 
												on 
												integracion_contable.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
												inner join
													tipo_comprobante
												on
													integracion_contable.id_tipo_comprobante=tipo_comprobante.id
												where		
															(organismo.id_organismo =".$_SESSION['id_organismo'].")	
												AND
													integracion_contable.numero_comprobante='$comporbante_int'		 			
												";	
											//	die($sql_datos);
											$row_comp=& $conn->Execute($sql_datos);
											while(!$row_comp->EOF)
											{
															//verificando datos
																$id=$row_comp->fields("id");
																$debe_haber=$row_comp->fields("debito_credito");
																$monto_debito=$row_comp->fields("monto_debito");
																$monto_credito=$row_comp->fields("monto_credito");
																			
															$contabilidad_comp_pr_ubicacion=$row_comp->fields("id_unidad_ejecutora");
															$contabilidad_comp_pr_centro_costo=$row_comp->fields("id_proyecto");
															$contabilidad_comp_pr_auxiliar=$row_comp->fields("id_auxiliar");
															$contabilidad_comp_pr_utf=$row_comp->fields("id_utilizacion_fondos");
															$contabilidad_comp_pr_acc=$row_comp->fields("id_accion_central");
															if($contabilidad_comp_pr_ubicacion=="")
																$contabilidad_comp_pr_ubicacion=0;
															if($contabilidad_comp_pr_centro_costo=="")
																$contabilidad_comp_pr_centro_costo=0;
															if($contabilidad_comp_pr_auxiliar=="")
																$contabilidad_comp_pr_auxiliar=0;
															if($contabilidad_comp_pr_utf=="")
																$contabilidad_comp_pr_utf=0;
															
															//////////llenando variables para realizar el pase entre tablas///////////////////////////*********************************************************************************************************************************************************************************************************************
																$tipo_id=$row_comp->fields("id_tipo_comprobante");
																$cuenta_contable=$row_comp->fields("cuenta_contable");
																$descripcion=$row_comp->fields("descripcion");
																$ref=$row_comp->fields("referencia");
																$secuencia=$row_comp->fields("secuencia");
																$fecha_comprobante=$row_comp->fields("fecha_comprobante");
																$ano=$row_comp->fields("ano_comprobante");
																$mes=$row_comp->fields("mes_comprobante");
																$ref=$row_comp->fields("referencia");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*********************************************************************************************************************************************************************************************************************************
////////////////////////////////////////////////////////--------caso 1 actualizando saldos contables---------------------------/////////////////////////////////////////////////
											$id_cc=$row_comp->fields("id_cc");	
///////////////////////////////////////////-- VERIFICANDO SI LA CUENTA CONTABLE TIENE CUENTA SUMA 
$turnos=1;
$contadores=0;
$id_sumas=$id_cc;
		while($turnos>$contadores)
		{			
				
					$sqlw="select * from  cuenta_contable_contabilidad where id='$id_sumas'";
					$rs_suma=& $conn->Execute($sqlw);
					if (!$rs_suma->EOF) 
					{
					
					$suma_cuenta=$rs_suma->fields("id_cuenta_suma");
							
						if($suma_cuenta!="")
						{
								$sql_mov_suma="SELECT  
										   (debe[".date("n")."])as debe,
										   (haber[".date("n")."])as haber 
											FROM 
													saldo_contable
											WHERE
												cuenta_contable='$suma_cuenta'";
					
									//die($sql_mov);
												$rs_mov_suma=& $conn->Execute($sql_mov_suma);
												if (!$rs_mov_suma->EOF) 
												{
													
													//
													$debe_ant_suma=$row_comp->fields("monto_debito");
													$haber_ant_suma=$row_comp->fields("monto_credito");
													//
												/*	$monto_debe3 = $rs_mov_suma->fields("debe")-($debe_ant_suma);
													$monto_haber3 = $rs_mov_suma->fields("haber")-($haber_ant_suma);
													$monto_debe_suma =$monto_debe3+$monto_debito;
													$monto_haber_suma =$monto_haber3+$monto_credito;*/
													$monto_debe_suma = $rs_mov_suma->fields("debe")+$monto_debito;
													$monto_haber_suma = $rs_mov_suma->fields("haber")+$monto_credito;												
													$sql_mod_suma="update
																	saldo_contable
																SET 
																		debe[".date("n")."]= '$monto_debe_suma',
																		haber[".date("n")."]= '$monto_haber_suma'
																WHERE
																			cuenta_contable='$suma_cuenta'";
					
												}
												else
												$sql_mod_suma="";	
						
						$turnos++;
						$alet++;
						$id_sumas=$suma_cuenta;
						
						}else
						$sql_mod_suma="";

					}else
						$sql_mod_suma="";
			if($contadores==0)$sql_mod_sumas_todas=$sql_mod_suma;
				else	
					$sql_mod_sumas_todas=$sql_mod_sumas_todas.";".$sql_mod_suma;	
			$contadores=$contadores+1;	
		}//fin del whiler
			
											
												$sql_mov="SELECT  
																   (debe[".date("n")."])as debe,
																   (haber[".date("n")."])as haber 
															FROM 
																	saldo_contable
															WHERE
																cuenta_contable='$id_cc'
															";
																		$rs_mov=& $conn->Execute($sql_mov);
																		if (!$rs_mov->EOF) 
																		{
																			//
																			$monto_debe2 = $rs_mov->fields("debe")+$monto_debito;
																			$monto_haber2 = $rs_mov->fields("haber")+$monto_credito;
													
																		
																			$sql_mod="update
																							saldo_contable
																						SET 
																								debe[".date("n")."]= '$monto_debe2',
																								haber[".date("n")."]= '$monto_haber2'
																						WHERE
																									cuenta_contable='$id_cc'";
																								
															//die($sql_mod);
																		}else
														
																		$sql_mod="";	
																											
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////-CASOB :VERIFICANDO EN LAS TABLAS DE SALDO AUXILIARES//////////////////////////////////////////////////////////////////////////////////////////////////////
											$id_cc=$row_comp->fields("id_cc");	
											$id_aux=$row_comp->fields("id_auxiliar");
											
											if($id_aux!="")
											{
													$sql_mov2="SELECT  
																	   (debe[".date("n")."])as debe,
																	   (haber[".date("n")."])as haber 
																FROM 
																		saldo_auxiliares
																WHERE
																	cuenta_contable='$id_cc'
																AND
																	cuenta_auxiliar='$id_aux'	
																";
																			$rs_mov2=& $conn->Execute($sql_mov2);
																		if (!$rs_mov2->EOF) 
																			{
																				$monto_debe3 = $rs_mov2->fields("debe")+$monto_debito;
																				$monto_haber3 = $rs_mov2->fields("haber")+$monto_credito;
																			
																	$sql_mod2="update
																					saldo_auxiliares
																				SET 
																						debe[".date("n")."]= '$monto_debe3',
																						haber[".date("n")."]= '$monto_haber3'
																				WHERE
																					cuenta_contable='$id_cc'
																				AND
																					cuenta_auxiliar='$id_aux'";	
																				}else
																				$sql_mod2="";
										}else
										$sql_mod2="";
										;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
																$sql = "INSERT INTO 
																			movimientos_contables
																			(
																				id_organismo,
																				numero_comprobante,
																				secuencia,
																				ano_comprobante,
																				mes_comprobante,
																				id_tipo_comprobante,
																				cuenta_contable,
																				descripcion,
																				referencia,
																				debito_credito,
																				monto_debito,
																				monto_credito,
																				id_unidad_ejecutora,
																				id_proyecto,
																				id_utilizacion_fondos,
																				id_auxiliar,
																				fecha_comprobante,
																				ultimo_usuario,
																				ultima_modificacion,
																				estatus,
																				id_accion_central  
																				
																			) 
																			VALUES
																			(
																				".$_SESSION["id_organismo"].",
																				$comprobante,
																				$secuencia,
																				'$ano',
																				'$mes',
																				'$tipo_id',
																				'$cuenta_contable',
																				'$descripcion',
																				$ref,
																				'$debe_haber',
																				$monto_debito,
																				$monto_credito,
																				$contabilidad_comp_pr_ubicacion,
																				$contabilidad_comp_pr_centro_costo,
																				$contabilidad_comp_pr_utf,
																				$contabilidad_comp_pr_auxiliar,
																				'".date("Y-m-d H:i:s")."',
																				 ".$_SESSION['id_usuario'].",
																				 '".date("Y-m-d H:i:s")."',
																				 '1',
																				 '$contabilidad_comp_pr_acc'
																			);
																			
																			UPDATE
																				tipo_comprobante	
																			set
																				numero_comprobante='$comprobante3'
																			where 
																			    id='$tipo_id'																					;
																			 delete from integracion_contable where integracion_contable.id='$id';
																			 $sql_mod;
																			 $sql_mod2;
																			 $sql_mod_sumas_todas	
																	";
																															
																//	die($sql);														
																															/*UPDATE
																																	cheques
																																	set
																																		contabilizado='1',
																																		fecha_contab='".date("Y-m-d H:i:s")."',
																																		usuario_contab='".$_SESSION['id_usuario']."',
																																		numero_comprobante_integracion='$numero_comprobante',
																																		cuenta_contable_banco='$cuenta_contable'	
																																	where
																																		numero_cheque='$numero_cheque';*/
																											
																				//die($sql_mod);
																				if (!$conn->Execute($sql)) 
																				{
																					$responce='Error al Actualizar: '.$conn->ErrorMsg();
																					$responce=$responce."*".$debe."*".$haber;
																					die($sql);
																				}
																				else
																				{	
																					$responce="cerrado"."*".$comprobante;
																					
																				}	
											$row_comp->MoveNext();
											$alet++;
											}
							die($responce);	
							/*}
							else
							{
								die("existe");
							}			*/
			}//debe==haber
}//fin de if de verificacion de fecha de cierre de modulo
	}else
	die("NoActualizo");
?>