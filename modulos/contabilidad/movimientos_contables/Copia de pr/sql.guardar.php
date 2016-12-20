<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[contabilidad_comp_pr_fecha];
//$fecha = date("Y-m-d H:i:s");

$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
$dia=substr($fecha,0,2);
/////////
$fecha_comp = date("Y-m-d H:i:s");
$ano_comp=substr($fecha_comp,0,4);
$mes_comp=substr($fecha_comp,5,2);
$dia_comp=substr($fecha_comp,8,2);
//die($dia.$mes.$ano);
$tipo_saldo=$_POST[contabilidad_comp_pr_tipo];
$fecha2 =date("Y-m-d H:i:s") ;
$ano2=substr($fecha2,0,4);

//
$debe=0;
$haber=0;
$resta=0;

if($_POST[contabilidad_comp_pr_numero_comprobante]!="")
{
$comprobante=$_POST[contabilidad_comp_pr_numero_comprobante2];
$tipo=$_POST[contabilidad_comp_pr_tipo];
//$comprobante_sec=$tipo.$comprobante;
$sql_sec="SELECT secuencia FROM movimientos_contables
							inner join
								organismo
							on
								movimientos_contables.id_organismo=organismo.id_organismo
							where		
									(organismo.id_organismo =".$_SESSION['id_organismo'].")
							and
								numero_comprobante='$comprobante'
							and	
								ano_comprobante='$ano'
							order by
								id_movimientos_contables desc
						
			";
			//die($sql_sec);
			$rs_sec =& $conn->Execute($sql_sec);
			$secuencia=$rs_sec->fields("secuencia");	
			$secuencia=$secuencia+1;
	//		$comprobante=$tipo.$comprobante;

}else
if($_POST[contabilidad_comp_pr_numero_comprobante]=="")
{
	    $secuencia=1;
		$tipo=$_POST[contabilidad_comp_pr_tipo];
		if($tipo=='')
		die('error en tipo');
		$sql_num="SELECT  
				  max(movimientos_contables.numero_comprobante) as maximo
			  FROM movimientos_contables
			  INNER JOIN
			  	tipo_comprobante
			 ON
			 tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
							where		
									(movimientos_contables.id_organismo =".$_SESSION['id_organismo'].") 
							and
								ayo='$ano'
							and
								codigo_tipo_comprobante='$tipo'
							and	
								mes_comprobante='$mes'
							and
								estatus!='3'	
				
				
				";
	//die($sql_num);
    $rs_comprobante =& $conn->Execute($sql_num);
	if(!$rs_comprobante->EOF)
	{
				$comprobante=substr($rs_comprobante->fields("maximo"),8)+1;
				$sig_comp=substr($comprobante,2);
//				echo($comprobante);die("!");
				$sql_act_comp="UPDATE
						tipo_comprobante	
					set
						numero_comprobante='$sig_comp'
					where
						
						id='$_POST[contabilidad_comp_pr_tipo_id]'
						";
						
	}
	if($comprobante=='1')
	{		
	$uno=substr($mes,0,1);
	if($uno==0)
	$mes2=substr($mes,1,1);
	else
	$mes2=$mes;
	$comprobante=$tipo.$mes2.'000';
	$sig_comp=substr($comprobante,2);
	}
	$comprobante=$ano.$mes.$dia.$comprobante;		

					$sql_act_comp="UPDATE
											tipo_comprobante	
										set
											numero_comprobante='$sig_comp'
										where
											
											id='$_POST[contabilidad_comp_pr_tipo_id]'
								";
							
}
			else
			{
				die("numero_comprobante");
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
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
							where
							 cuenta_contable_contabilidad.id='$id_cc'
							"
							;
							//die($sql_tipo);
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
										   (haber[".$mes."])as haber,
										   (saldo_inicio[".$mes."])as saldo_inicio  
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
	/*if(($monto_debe_suma!="0")&&($monto_haber_suma=="0"))
	{
		$monto_saldon=$monto_debe_suma;	
	}
	if(($monto_haber_suma!="0")&&($monto_debe_suma=="0"))
	{
		$monto_saldon=$monto_haber_suma;	
	}*/
	$saldo_inicial=$rs_mov_suma->fields("saldo_inicio");
	if($monto_debito!="0")
	{
		if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
		{
			$monto_saldon=$saldo_inicial+$monto_debito;
		}
		else
		if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
		{
			$monto_saldon=$saldo_inicial-$monto_debito;
		}
	}
	if($monto_credito!="0")
	{
		if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
		{
			$monto_saldon=$saldo_inicial-$monto_credito;
		}
		else
		if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
		{
			$monto_saldon=$saldo_inicial+$monto_credito;
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
						
						}else//si suma cuenta=""
						$sql_mod_suma="";
					}else//!$rs_suma->EOF)
						$sql_mod_suma="";
			if($contadores==0)$sql_mod_sumas_todas=$sql_mod_suma;
				else	
					$sql_mod_sumas_todas=$sql_mod_sumas_todas.";".$sql_mod_suma;	
			$contadores=$contadores+1;	

		}//fin del whiler	
//die($sql_mod_sumas_todas);			

												$sql_mov="SELECT  
															   (debe[".$mes."])as debe,
		    												   (haber[".$mes."])as haber,
       														    (saldo_inicio[".$mes."])as saldo_inicio   
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
		$saldo_inicial=$rs_mov->fields("saldo_inicio");
		if($monto_debito!="0")
		{
			if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
			{
				$monto_saldon=$saldo_inicial+$monto_debito;
			}
			else
			if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
			{
				$monto_saldon=$saldo_inicial-$monto_debito;
			}
		}
		if($monto_credito!="0")
		{
			if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
			{
				$monto_saldon=$saldo_inicial-$monto_credito;
			}
			else
			if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
			{
				$monto_saldon=$saldo_inicial+$monto_credito;
			}
		}
				
	$sql_saldo_inicial="
																		update
																				saldo_contable
																			SET 
																					saldo_inicio[".$mes."]= '$monto_saldon'
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
																	   (haber[".$mes."])as haber,
																	   (saldo_inicio[".$mes."])as saldo_inicio    
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
													$saldo_inicial=$rs_mov->fields("saldo_inicio");
													if($monto_debito!="0")
													{
														if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
														{
															$monto_saldon=$saldo_inicial+$monto_debito;
														}
														else
														if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
														{
															$monto_saldon=$saldo_inicial-$monto_debito;
														}
													}
													if($monto_credito!="0")
													{
														if(($codigo=='A   ')||($codigo=='G   ')or($codigo=='CO  '))
														{
															$monto_saldon=$saldo_inicial-$monto_credito;
														}
														else
														if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   ')or($codigo=='R   '))
														{
															$monto_saldon=$saldo_inicial+$monto_credito;
														}
													}		
													
														$sql_saldo_inicial_aux="
														update
																saldo_auxiliares
															SET 
																	saldo_inicio[".$mes."]= '$monto_saldon'
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

												$sql ="
												$sql_act_comp;
												INSERT INTO 
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
																			'$ano',
																			'$mes',
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
																
																$sql_mod;
																$sql_mod2;
																$sql_mod_sumas_todas
																";
														//$sql="";	
												//die($sql);
																
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
													$responce="Registrado"."*".$debe."*".$haber."*".substr($comprobante,10)."*".$resta."*".$comprobante;
													die($responce);
												}
											
												
											}	
			/*}else
			{
			$responce="numero_existe"."*".$debe."*".$haber;
				die($responce);
			}*/
			
?>