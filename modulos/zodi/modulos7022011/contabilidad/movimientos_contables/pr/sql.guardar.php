<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[contabilidad_comp_pr_fecha];
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
$tipo_saldo=$_POST[contabilidad_comp_pr_tipo];
$fecha2 =date("Y-m-d H:i:s") ;
$ano2=substr($fecha2,0,4);

//
$debe=0;
$haber=0;
$resta=0;
/*if($ano2!=$ano)
{
	die("no_ayo");
	//die("no_modificar_año"." ".$ano_comprobante." ".$ano);
	//$valoress=cambio_fecha($_SESSION['id_usuario'],$
	_x,$fecha);
	
	//die($valores);
}
*/
/*if($_POST[contabilidad_comp_pr_numero_comprobante]!="")
{
$comprobantex=$_POST[contabilidad_comp_pr_numero_comprobante];
	$tipo_x=$_POST[contabilidad_comp_pr_tipo];
	$comprobante=$tipo_x.$comprobantex;
$sql_sec="SELECT secuencia FROM movimientos_contables
							inner join
								organismo
							on
								movimientos_contables.id_organismo=organismo.id_organismo
							where		
									(organismo.id_organismo =".$_SESSION['id_organismo'].")
							and
								numero_comprobante='$comprobante'
							order by
								id_movimientos_contables desc	
			";
			$rs_sec =& $conn->Execute($sql_sec);
			$secuencia=$rs_sec->fields("secuencia");	
			$secuencia=$secuencia+1;
}else
$secuencia=1;*/
//
if($_POST[contabilidad_comp_pr_numero_comprobante]!="")
{
$comprobante=$_POST[contabilidad_comp_pr_numero_comprobante];
$tipo=$_POST[contabilidad_comp_pr_tipo];
$comprobante_sec=$tipo.$comprobante;
$sql_sec="SELECT secuencia FROM movimientos_contables
							inner join
								organismo
							on
								movimientos_contables.id_organismo=organismo.id_organismo
							where		
									(organismo.id_organismo =".$_SESSION['id_organismo'].")
							and
								numero_comprobante='$comprobante_sec'
							and	
								ano_comprobante='$ano'
							order by
								id_movimientos_contables desc
						
			";
			//die($sql_sec);
			$rs_sec =& $conn->Execute($sql_sec);
			$secuencia=$rs_sec->fields("secuencia");	
			$secuencia=$secuencia+1;
			$comprobante=$tipo.$comprobante;

}else
if($_POST[contabilidad_comp_pr_numero_comprobante]=="")
{
	$secuencia=1;

	$tipo=$_POST[contabilidad_comp_pr_tipo];
	$sql="SELECT numero_comprobante FROM tipo_comprobante
							inner join
								organismo
							on
								tipo_comprobante.id_organismo=organismo.id_organismo
							where		
									(organismo.id_organismo =".$_SESSION['id_organismo'].") 
							and
								ayo='$ano'
							and
								codigo_tipo_comprobante='$tipo'				
			ORDER BY 
					 tipo_comprobante.id desc
			";
			//die($sql);
			$rs_comprobante =& $conn->Execute($sql);
			if(!$rs_comprobante->EOF)
			{
				$comprobante3=$rs_comprobante->fields("numero_comprobante");	
				$sig_comp=$comprobante3+1;
				//echo($sig_comp);
				/*if(($comprobante2!="")&&($comprobante2!="0000"))
				$comprobante3=$comprobante2+1;
				else*/
				if($comprobante3=="0000")
				$comprobante3="0001";
				//
				$valor_medida=strlen($comprobante3);												//echo($comprobante3);

												//	echo($valor_medida);
												if($valor_medida==1)
												{
													$comprobante3="000".$comprobante3;
													$comprobante4="000".$sig_comp;
													
										}
												else
												if($valor_medida==2)
												{
													$comprobante3="00".$comprobante3;
														$comprobante4="00".$sig_comp;
												}
												else	
												if($valor_medida==3)
												{
															$comprobante3="0".$comprobante3;
																$comprobante4="0".$sig_comp;
												}
												
												$comprobante=$tipo.$comprobante3;
												$comprobante5=$tipo.$comprobante4;
												//echo($comprobante);
											//die('hola');
													
				$sql_act_comp="UPDATE
						tipo_comprobante	
					set
						numero_comprobante='$sig_comp'
					where
						
						id='$_POST[contabilidad_comp_pr_tipo_id]'
						";
					//die($sql_act_comp);
			}
			else
			{
				die("numero_comprobante");
			}
}
else
if($_POST[contabilidad_comp_pr_numero_comprobante]!="")
{
	$comprobantex=$_POST[contabilidad_comp_pr_numero_comprobante];
	$tipo_x=$_POST[contabilidad_comp_pr_tipo];
	$comprobante=$tipo_x.$comprobantex;
}

$monto = str_replace(".","",$_POST[contabilidad_comp_pr_monto]);
$monto2 = str_replace(",",".",$monto);
$debe_haber=$_POST[contabilidad_comp_pr_debe_haber];
if($debe_haber==1)
{
	$monto_debito=$monto2 ;
	$monto_credito=0;
}
if($debe_haber==2)
{
	$monto_credito=$monto2 ;
	$monto_debito=0;
}
if($comentario=="")
{
	$comentario="0";
}
//verificando datos
$contabilidad_comp_pr_ubicacion=$_POST[contabilidad_comp_pr_ejec_id];
$contabilidad_comp_pr_centro_costo=$_POST[contabilidad_pr_centro_costo_id_cmp];
$contabilidad_comp_pr_auxiliar=$_POST[contabilidad_comp_contabilidad_id];
$contabilidad_comp_pr_utf=$_POST[contabilidad_comp_pr_utf_id];
$contabilidad_comp_pr_acc=$_POST[contabilidad_comp_pr_acc_id];
if($contabilidad_comp_pr_ubicacion=="")
	$contabilidad_comp_pr_ubicacion=0;
if($contabilidad_comp_pr_centro_costo=="")
	$contabilidad_comp_pr_centro_costo=0;
if($contabilidad_comp_pr_auxiliar=="")
	$contabilidad_comp_pr_auxiliar=0;
if($contabilidad_comp_pr_utf=="")
	$contabilidad_comp_pr_utf=0;
if($contabilidad_comp_pr_acc=="")
	$contabilidad_comp_pr_acc=0;

/////////////////////////////////////*********************************************************************************************************************************************************************************************************************
			/*$sql_comprobante="select distinct(numero_comprobante)
							 from 
									movimientos_contables
								where
								
							movimientos_contables.id_organismo = $_SESSION[id_organismo]
							and
								numero_comprobante=$comprobante
							
					";
//								$_POST[contabilidad_comp_pr_numero_comprobante]	

							//	die($sql_comprobante);
			echo($comprobante4);
			$row_comprobante=& $conn->Execute($sql_comprobante);
			if(!$row_comprobante->EOF)
			{//$comprobante2=$comprobante3+1;
				$sql_act_comp="UPDATE
						tipo_comprobante	
					set
						numero_comprobante='$comprobante4'
					where
						
						id='$_POST[contabilidad_comp_pr_tipo_id]'
						";
					
			}else
			if($row_comprobante->EOF)
			{
				//$sql_act_comp="";
				//$comprobante2=$comprobante3+1;
				$sql_act_comp="UPDATE
						tipo_comprobante	
					set
						numero_comprobante='$comprobante4'
					where
						
						id='$_POST[contabilidad_comp_pr_tipo_id]'
						";
			}*/
			//die($sql_act_comp);
//////////////////////////////////////*********************************************************************************************************************************************************************************************************************************
											if(!$row->EOF)
											{
											
////////////////////////////////////////////////////-CASO A :VERIFICANDO EN LAS TABLAS DE SALDO CONTABLES//////////////////////////////////////////////////////////////////////////////////////////////////////
											$id_cc=$_POST[contabilidad_auxiliares_db_id_cuenta];
///////////////////////////////////////////-- VERIFICANDO SI LA CUENTA CONTABLE TIENE CUENTA SUMA 

$turnos=1;
$contadores=0;
$id_sumas=$id_cc;
/************************************/
$sql_tipo="select 
									cuenta_contable_contabilidad.id,
									naturaleza_cuenta.codigo  AS codigo
								
								from
										cuenta_contable_contabilidad 
								inner join
											naturaleza_cuenta
								on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id"
							;
	$rs_tipo_c=& $conn->Execute($sql_tipo);
	if (!$rs_tipo_c->EOF) 
	{
		$codigo=$rs_tipo_c->fields("codigo");
	}						
/************************************/
		while($turnos>$contadores)
		{
					$sqlw="select 
									*
								
								from
										cuenta_contable_contabilidad 
								
								where id='$id_sumas'";				
							//	die($sqlw);

					$rs_suma=& $conn->Execute($sqlw);
					if (!$rs_suma->EOF) 
					{
							
						$suma_cuenta=$rs_suma->fields("id_cuenta_suma");
						if($suma_cuenta!="")
						{
								$sql_mov_suma="SELECT  
										   (debe[".$mes."])as debe,
										   (haber[".$mes."])as haber 
									FROM 
											saldo_contable
									WHERE
										cuenta_contable='$suma_cuenta'
									and
										ano='$ano'	
									";
									//die($sql_mov_suma);
												$rs_mov_suma=& $conn->Execute($sql_mov_suma);
												if (!$rs_mov->EOF) 
												{
													$monto_debe_suma = $rs_mov_suma->fields("debe")+$monto_debito;
													$monto_haber_suma = $rs_mov_suma->fields("haber")+$monto_credito;
													//die($rs_mov_suma->fields("haber")."+".$monto_credito);
///////////////////////////////////////////////// realizando saldo inicial////////////////////////////////////
///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
if($tipo_saldo=='10')
{
	if(($monto_debe_suma!="0")&&($monto_haber_suma=="0"))
	{
		$monto_saldon=$monto_debe_suma;	
	}
	if(($monto_haber_suma!="0")&&($monto_debe_suma=="0"))
	{
		$monto_saldon=$monto_haber_suma;	
	}
	if(($monto_haber_suma!="0")&&($monto_debe_suma!="0"))
	{
		if(($codigo=='A   ')||($codigo=='G   '))
		{
			$monto_saldon=$monto_debe_suma-$monto_haber_suma;
		}
		else
		if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
		{
			$monto_saldon=$monto_haber_suma-$monto_debe_suma;
		}
		else
		if($codigo=='R   ')
		{
			$monto_saldon=$monto_haber_suma-$monto_debe_suma;
		}
		if($codigo=='CO  ')
		{
			$monto_saldon=$monto_debe_suma-$monto_haber_suma;

		}
	}
	$sql_saldo_ini_suma="
																		update
																				saldo_contable
																			SET 
																					saldo_inicio[".$mes."]= '$monto_saldon'
																			WHERE
																						cuenta_contable='$suma_cuenta'
																			and
																						ano='$ano'
	";
	}
else
{
	
	$sql_saldo_ini_suma="";
	
	}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

													$sql_mod_suma="update
																	saldo_contable
																SET 
																		debe[".$mes."]= '$monto_debe_suma',
																		haber[".$mes."]= '$monto_haber_suma'
																WHERE
																			cuenta_contable='$suma_cuenta'
																and
																			ano='$ano';
																$sql_saldo_ini_suma
																";
												//die($sql_mod_suma);
												}
												else
												$sql_mod_suma="";	
						
						$turnos++;
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
//die($sql_mod_sumas_todas);			

												$sql_mov="SELECT  
																   (debe[".$mes."])as debe,
																   (haber[".$mes."])as haber 
															FROM 
																	saldo_contable
															WHERE
																cuenta_contable='$id_cc'
															and
																	ano='$ano'	
															";
															//die($sql_mov);
																		$rs_mov=& $conn->Execute($sql_mov);
																		if (!$rs_mov->EOF) 
																		{
																			$monto_debe2 = $rs_mov->fields("debe")+$monto_debito;
																			$monto_haber2 = $rs_mov->fields("haber")+$monto_credito;
///////////////////////////////////////////////// realizando saldo inicial////////////////////////////////////
///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
if($tipo_saldo=='10')
{
	if(($monto_debe2!="0")&&($monto_haber2=="0"))
	{
		$monto_saldo=$monto_debe2;	
	}
	if(($monto_haber2!="0")&&($monto_debe2=="0"))
	{
		$monto_saldo=$monto_haber2;	
	}
	if(($monto_haber2!="0")&&($monto_debe2!="0"))
	{
			if(($codigo=='A   ')||($codigo=='G   '))
				{
					$monto_saldo=$monto_debe2-$monto_haber2;
				}
				else
				if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
				{
					$monto_saldo=$monto_haber2-$monto_debe2;
				}
				else
				if($codigo=='R   ')
				{
					$monto_saldo=$monto_haber2-$monto_debe2;
				}
				if($codigo=='CO  ')
				{
					$monto_saldo=$monto_debe2-$monto_haber2;
		
				}
	}			
	$sql_saldo_inicial="
																		update
																				saldo_contable
																			SET 
																					saldo_inicio[".$mes."]= '$monto_saldo'
																			WHERE
																						cuenta_contable='$id_cc'	and
																						ano='$ano'			
	
	";
	}
else
{
	
	$sql_saldo_inicial="";
	
	}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
																$sql_mod="update
																				saldo_contable
																			SET 
																					debe[".$mes."]= '$monto_debe2',
																					haber[".$mes."]= '$monto_haber2'
																			WHERE
																					cuenta_contable='$id_cc'	and
																					ano='$ano'		
																					;
																			$sql_saldo_inicial			
																						";
																		}
																		else
																		$sql_mod="";
														//die($sql_mod);								
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////-CASOB :VERIFICANDO EN LAS TABLAS DE SALDO AUXILIARES//////////////////////////////////////////////////////////////////////////////////////////////////////
											$id_cc=$_POST[contabilidad_auxiliares_db_id_cuenta];
											$id_aux=$_POST[contabilidad_comp_contabilidad_id];
											if($id_aux!="")
											{
												

													
													
													
													$sql_mov2="SELECT  
																	   (debe[".$mes."])as debe,
																	   (haber[".$mes."])as haber 
																FROM 
																		saldo_auxiliares
																WHERE
																	cuenta_contable='$id_cc'	AND	
																	cuenta_auxiliar='$id_aux'	
																and
																	ano='$ano'	
																";
																//die($sql_mov);
																			$rs_mov2=& $conn->Execute($sql_mov2);
																			if (!$rs_mov2->EOF) 
																			{
																				$monto_debe3 = $rs_mov2->fields("debe")+$monto_debito;
																				$monto_haber3 = $rs_mov2->fields("haber")+$monto_credito;
																			}
/////////////////////////////////////////////SALDO INICIAL PARA AUXILIARES
///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
												
												if($tipo_saldo=='10')
													{
														if(($monto_debe3!="0")&&($monto_haber3=="0"))
														{
															$monto_saldo=$monto_debe3;	
														}
														if(($monto_haber3!="0")&&($monto_debe3=="0"))
														{
															$monto_saldo=$monto_haber3;	
														}
												if(($monto_haber3!="0")&&($monto_debe3!="0"))
												{
													if(($codigo=='A   ')||($codigo=='G   '))
													{
														$monto_saldo=$monto_debe3-$monto_haber3;
													}
													else
													if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
													{
														$monto_saldo=$monto_haber3-$monto_debe3;
													}
													else
													if($codigo=='R   ')
													{
														$monto_saldo=$monto_debe3-$monto_debe3;
													}
													if($codigo=='CO  ')
													{
														$monto_saldo=$monto_debe3-$monto_haber3;
											
													}
												}	
														$sql_saldo_inicial_aux="
														update
																saldo_auxiliares
															SET 
																	saldo_inicio[".$mes."]= '$monto_saldo'
															WHERE
																cuenta_contable='$id_cc'	AND	
																	cuenta_auxiliar='$id_aux'
																and
																	ano='$ano'	
";
//
														}
													else
													{
														
														$sql_saldo_inicial_aux="";
														
														}	
													//////////////////////////////////////////////////////////////////////////////////////////////////////////////
																			
																			
																			
																	$sql_mod2="update
																					saldo_auxiliares
																				SET 
																						debe[".$mes."]= '$monto_debe3',
																						haber[".$mes."]= '$monto_haber3'
																				WHERE
																					cuenta_contable='$id_cc'	
																				and
																					cuenta_auxiliar='$id_aux'
																				and
																					ano='$ano'	
																					;
																				$sql_saldo_inicial_aux	
																					
																					";	
																					//die($sql_mod2);
										//cuenta_contable='$id_cc' AND
										}else
										$sql_mod2="";
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
																			comentario,
																			cuenta_contable,
																			descripcion,
																			referencia,
																			debito_credito,
																			monto_debito,
																			monto_credito,
																			id_unidad_ejecutora,
																			id_proyecto,
																			id_accion_central,
																			id_utilizacion_fondos,
																			id_auxiliar,
																			fecha_comprobante,
																			ultimo_usuario,
																			ultima_modificacion,
																			estatus  
																			
																		) 
																		VALUES
																		(
																			".$_SESSION["id_organismo"].",
																			'$comprobante',
																			'$secuencia',
																			'$_POST[contabilidad_comp_pr_contable_ano]',
																			'$_POST[contabilidad_comp_pr_contable_mes]',
																			'$_POST[contabilidad_comp_pr_tipo_id]',
																			'$_POST[contabilidad_comp_pr_comentarios]',
																			'$_POST[contabilidad_comp_pr_cuenta_contable]',
																			'$_POST[contabilidad_comp_pr_desc]',
																			'$_POST[contabilidad_comp_pr_ref]',
																			'$_POST[contabilidad_comp_pr_debe_haber]',
																			$monto_debito,
																			$monto_credito,
																			$contabilidad_comp_pr_ubicacion,
																			$contabilidad_comp_pr_centro_costo,
																			$contabilidad_comp_pr_acc,
																			$contabilidad_comp_pr_utf,
																			$contabilidad_comp_pr_auxiliar,
																			'".$fecha."',
																			 ".$_SESSION['id_usuario'].",
																			 '".date("Y-m-d H:i:s")."',
																			 '0'
																		);
																$sql_act_comp;
																$sql_mod;
																$sql_mod2;
																$sql_mod_sumas_todas
																";
														//$sql="";	
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
											}
											else
											{	
												
												$responce="NoActualizo"."*".$debe."*".$haber."*".$comprobante."*".$resta;	
												die($responce);
											
												}			
								//die($sql);
											if (!$conn->Execute($sql)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
												$responce=$responce."*".$debe."*".$haber."*".$comprobante."*".$resta;
												die($responce);
											}
											else
											{
												$sql_sumas=" SELECT
																	SUM(monto_debito) as debe,
																	SUM(monto_credito) as haber
																from
																	movimientos_contables
																where numero_comprobante='$comprobante'	
																and
																	movimientos_contables.estatus!='3'
																and	
																	ano_comprobante='$ano';										
												";
												$row_sumas=& $conn->Execute($sql_sumas);
												if(!$row_sumas->EOF)
												{
													$debe=number_format($row_sumas->fields("debe"),2,',','.');
													$haber=number_format($row_sumas->fields("haber"),2,',','.');
													$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
													$resta=number_format($resta,2,',','.');
													$responce="Registrado"."*".$debe."*".$haber."*".substr($comprobante,2,4)."*".$resta;
													die($responce);
												}
											
												
											}	
			/*}else
			{
			$responce="numero_existe"."*".$debe."*".$haber;
				die($responce);
			}*/
			
?>