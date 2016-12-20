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
//
$debe=0;
$haber=0;
$resta=0;
$cerrado="";
//
$comprobante_x=$_POST['contabilidad_comp_pr_tipo'].$_POST['contabilidad_comp_pr_numero_comprobante'];
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sql_comprobante="select *
							 from 
									movimientos_contables
								where
								
							movimientos_contables.id_organismo = $_SESSION[id_organismo]
							and
							movimientos_contables.id_movimientos_contables=$_POST[contabilidad_comp_id_comprobante]								
							
								";
							//die($sql_comprobante);
			$row_comprobante=& $conn->Execute($sql_comprobante);
			if(!$row_comprobante->EOF)
			{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-verficando si la fecha del comprobante le permite al mismo ser modificado luego del proceso de cierre.....
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
$fecha_comprobante=substr($row_comprobante->fields("fecha_comprobante"),0,10);
$ano_comprobante=substr($fecha_comprobante,0,4);
$mes_comprobante=substr($fecha_comprobante,5,2);
if($ano_comprobante!=$ano)
{
	die("no_modificar_año"." ".$ano_comprobante." ".$ano);
 
}
if($mes_comprobante!=$mes)
{
	die("no_modificar_año"." ".$mes_comprobante." ".$mes);
 
}
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

if(($dia2 >= $dia1) && ($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
	
}else
if(($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}
if(($dia2 >= $dia3) && ($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
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
die($dia2."-".$mes2."-".$ano2."comp");*/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			
				//VERIFICANDO SI EL DOCUMENTO ESTA ABIERTO
				if($row_comprobante->fields("estatus")==1)
				{
					$responce="documento_cerrado"."*".$debe."*".$haber;	
					die($responce);
				}
//////////////////////////////////////********************************************************************************************************************************************************************************************************************************
					if(!$row->EOF)
						{
////////////////////////////////////////////////////////--------caso 1 actualizando saldos contables/////////////////////////////////////////////////
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
					$sqlw="select * from  cuenta_contable_contabilidad where id='$id_sumas'";
					//die($sqlw);
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
												cuenta_contable='$suma_cuenta'";
											//die($sql_mov_suma);
												$rs_mov_suma=& $conn->Execute($sql_mov_suma);
												if (!$rs_mov_suma->EOF) 
												{
													
													//
													
													$debe_ant_suma=$row_comprobante->fields("monto_debito");
													$haber_ant_suma=$row_comprobante->fields("monto_credito");
													//
													if($rs_mov_suma->fields("debe")!=0)//-($debe_ant_suma;
													$monto_debe3 = $rs_mov_suma->fields("debe")-($debe_ant_suma);
													if($rs_mov_suma->fields("haber")!=0)//);
													$monto_haber3 = $rs_mov_suma->fields("haber")-($haber_ant_suma);
													$monto_debe_suma =$monto_debe3+$monto_debito;
													$monto_haber_suma =$monto_haber3+$monto_credito;
													/*echo($rs_mov_suma->fields("haber")."-".($haber_ant_suma));
													die($monto_haber3."-".$monto_credito);//die($monto_haber_suma);*/
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
			///////////////////saldo borrar 1//////////////////////////////////////////////////////////////////////////////////////
			$sql_borrar1="update
																						saldo_contable
																					SET 
																							saldo_inicio[".$mes_comprobante."]= '0'
																					WHERE
																								cuenta_contable='$suma_cuenta'
																					and
																						ano='$ano_comprobante'			
									
										";
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////							
	$sql_saldo_ini_suma="
																		update
																				saldo_contable
																			SET 
																					saldo_inicio[".$mes."]= '$monto_saldon'
																			WHERE
																						cuenta_contable='$suma_cuenta'
																			and
																				ano='$ano';
	
	";
	//die();
	}
else
{
	
	$sql_saldo_ini_suma="";
	
	}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
												/*	$monto_debe_suma = $rs_mov_suma->fields("debe")+$monto_debito;
													$monto_haber_suma = $rs_mov_suma->fields("haber")+$monto_credito;
*/	
				///////////////////////saldoborrar 2//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$sql_borrar2="update
																	saldo_contable
																SET 
																		debe[".$mes_comprobante."]= '0',
																		haber[".$mes_comprobante."]= '0'
																WHERE
																			cuenta_contable='$suma_cuenta'
																and
																			ano='$ano_copmprobante'			
																";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////											
													$sql_mod_suma="update
																	saldo_contable
																SET 
																		debe[".$mes."]= '$monto_debe_suma',
																		haber[".$mes."]= '$monto_haber_suma'
																WHERE
																			cuenta_contable='$suma_cuenta'
																and
																			ano='$ano';
																$sql_saldo_ini_suma";
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
				//	die($sql_mod_sumas_todas);
			$contadores=$contadores+1;	
			$monto_debe_suma=0;
			$monto_haber_suma=0;

		}//fin del whiler	
//die($sql_mod_sumas_todas);			

												$sql_mov="SELECT  
																   (debe[".$mes."])as debe,
																   (haber[".$mes."])as haber 
															FROM 
																	saldo_contable
															WHERE
																cuenta_contable='$id_cc'
															";
																		$rs_mov=& $conn->Execute($sql_mov);
																		if (!$rs_mov->EOF) 
																		{
																			//
																			$debe_ant=$row_comprobante->fields("monto_debito");
																			$haber_ant=$row_comprobante->fields("monto_credito");
																			//
																			if($rs_mov->fields("debe")!=0)
																			$monto_debe2 = $rs_mov->fields("debe")-($debe_ant);
																			//die($rs_mov->fields("debe")."-".($debe_ant));																	
																			if($rs_mov->fields("haber")!=0)
																			$monto_haber2 = $rs_mov->fields("haber")-($haber_ant);
																			$monto_debe_principal =$monto_debe2+$monto_debito;
																			
																			$monto_haber_principal =$monto_haber2+$monto_credito;
																			//die($monto_debe2."+".$monto_debito."=".$monto_debe_principal );
///////////////////////////////////////////////// realizando saldo inicial////////////////////////////////////
///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
if($tipo_saldo=='10')
{
	if(($monto_debe_principal!="0")&&($monto_haber_principal=="0"))
	{
		$monto_saldo=$monto_debe_principal;	
	}
	if(($monto_haber_principal!="0")&&($monto_debe_principal=="0"))
	{
		$monto_saldo=$monto_haber_principal;	
	}
	if(($monto_haber_principal!="0")&&($monto_debe_principal!="0"))
	{
			if(($codigo=='A   ')||($codigo=='G   '))
				{
					$monto_saldo=$monto_debe_principal-$monto_haber_principal;
				}
				else
				if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
				{
					$monto_saldo=$monto_haber_principal-$monto_debe_principal;
				}
				else
				if($codigo=='R   ')
				{
					$monto_saldo=$monto_haber_principal-$monto_debe_principal;
				}
				if($codigo=='CO  ')
				{
					$monto_saldo=$monto_debe_principal-$monto_haber_principal;
		
				}
	}			
	//////////////////////////////////////sql_borrar3/////////////////////////////////////////////////////////////////////////////////////////
	$sql_borrar3="
																		update
																				saldo_contable
																			SET 
																					saldo_inicio[".$mes_comprobante."]= '0'
																			WHERE
																						cuenta_contable='$id_cc'
																			and
																						ano='$ano_copmprobante'";
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql_saldo_inicial="
																		update
																				saldo_contable
																			SET 
																					saldo_inicio[".$mes."]= '$monto_saldo'
																			WHERE
																						cuenta_contable='$id_cc'
																			and
																						ano='$ano'";
	
	
	}
else
{
	
	$sql_saldo_inicial="";
	
	}	
////////////////////////////////////sql_borrar4////////////////////////////////////////////////////
																		$sql_borrar4="update
																							saldo_contable
																						SET 
																								debe[".$mes_comprobante."]= '0',
																								haber[".$mes_comprobante."]= '0'
																						WHERE
																									cuenta_contable='$id_cc'
																						and
																									ano='$ano_comprobante'			
																									";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
																		
																			$sql_mod="update
																							saldo_contable
																						SET 
																								debe[".$mes."]= '$monto_debe_principal',
																								haber[".$mes."]= '$monto_haber_principal'
																						WHERE
																									cuenta_contable='$id_cc'
																						and
																									ano='$ano';
																						$sql_saldo_inicial			
																									
																									";
																								//	die($sql_mod);
																		}else
																		$sql_mod="";	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////-CASO2 :actualizando EN LAS TABLAS DE SALDO AUXILIARES//////////////////////////////////////////////////////////////////////////////////////////////////////
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
																	cuenta_contable='$id_cc'	
																AND	
																	cuenta_auxiliar='$id_aux'"	
																;
															
																
																			$rs_mov2=& $conn->Execute($sql_mov2);
																			if (!$rs_mov2->EOF) 
																		{
																			//
																			$debe_ant2=$row_comprobante->fields("monto_debito");
																			$haber_ant2=$row_comprobante->fields("monto_credito");
																			//
																			if($rs_mov2->fields("debe")!=0)
																			$monto_debe4 = $rs_mov2->fields("debe")-($debe_ant2);
																			if($rs_mov2->fields("haber")!=0)
																			$monto_haber4 = $rs_mov2->fields("haber")-($haber_ant2);
																			$monto_debe_a =$monto_debe4+$monto_debito;
	
																			$monto_haber_a =$monto_haber4+$monto_credito;
	/////////////////////////////////////////////SALDO INICIAL PARA AUXILIARES
///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
												
												if($tipo_saldo=='10')
													{
														if(($monto_debe4!="0")&&($monto_haber4=="0"))
														{
															$monto_saldo_aux=$monto_debe_a;	
														}
														if(($monto_haber4!="0")&&($monto_debe4=="0"))
														{
															$monto_saldo_aux=$monto_haber_a;	
														}
												if(($monto_haber4!="0")&&($monto_debe4!="0"))
												{
													if(($codigo=='A   ')||($codigo=='G   '))
													{
														$monto_saldo_aux=$monto_debe_a-$monto_haber_a;
													}
													else
													if(($codigo=='P   ') ||($codigo=='PAT ')or($codigo=='I   '))
													{
														$monto_saldo_aux=$monto_haber_a-$monto_debe_a;
													}
													else
													if($codigo=='R   ')
													{
														$monto_saldo_aux=$monto_haber_a-$monto_debe_a;
													}
													if($codigo=='CO  ')
													{
														$monto_saldo_aux=$monto_debe_a-$monto_haber_a;
											
													}
												}	
											//////////////////////////////////////sql_borrar_aux1/borrar5/////////////////////////////////////////
											$sql_borrar_aux1="
														update
																saldo_auxiliares
															SET 
																	saldo_inicio[".$mes_comprobante."]= '0'
															WHERE
																	
																	cuenta_auxiliar='$id_aux'
															and
																	ano='$ano_comprobante'
															";		

											//////////////////////////////////////////////////////////////////////////////////////////			
														
														
														
														$sql_saldo_inicial_aux="
														update
																saldo_auxiliares
															SET 
																	saldo_inicio[".$mes."]= '$monto_saldo_aux'
															WHERE
																	
																	cuenta_auxiliar='$id_aux'
															and	
																cuenta_contable='$id_cc'	
																		
															and
																	ano='$ano';

";//die($monto_debe4."-".$monto_haber4."--".$monto_saldo_aux);
//cuenta_contable='$id_cc'	AND
														}
													else
													{
														
														$sql_saldo_inicial_aux="";
														
														}	
													//////////////////////////////////////////////////////////////////////////////////////////////////////////////
													///////////////////////////////////////////sql_borrar_aux2////////////////////////////////////////////////////
													$sql_borrar_aux2="update
																					saldo_auxiliares
																				SET 
																						debe[".$mes_comprobante."]= '0',
																						haber[".$mes_comprobante."]= '0'
																				WHERE
																					
																					cuenta_auxiliar='$id_aux';
																				AND
																					cuenta_contable='$id_cc'	
																				AND	
																					ano='$ano_comprobante'
																					";	
													////////////////////////////////////////////////////////////////////////////////////////////////////////////////
																	
																	$sql_mod2="update
																					saldo_auxiliares
																				SET 
																						debe[".$mes."]= '$monto_debe_a',
																						haber[".$mes."]= '$monto_haber_a'
																				WHERE
																					cuenta_auxiliar='$id_aux'
																				AND
																					ano='$ano'
																				AND
																					cuenta_contable='$id_cc'		
																				;
																				$sql_saldo_inicial_aux	
																					";	
																		}else
																		$sql_mod2="";
																		//die($sql_mod2);				
										}else
										$sql_mod2="";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

						
												$sql="
														UPDATE	
																movimientos_contables
														set
															cuenta_contable='$_POST[contabilidad_comp_pr_cuenta_contable]',
															referencia='$_POST[contabilidad_comp_pr_ref]',
															debito_credito='$debe_haber',
															monto_debito=$monto_debito,
															monto_credito=$monto_credito,
															id_unidad_ejecutora=$contabilidad_comp_pr_ubicacion,
															id_proyecto=$contabilidad_comp_pr_centro_costo,
															id_accion_central=$contabilidad_comp_pr_acc,
															id_utilizacion_fondos=$contabilidad_comp_pr_utf,
															id_auxiliar=$contabilidad_comp_pr_auxiliar,
															fecha_comprobante='".$fecha."',
															ultimo_usuario= ".$_SESSION['id_usuario'].",
															ultima_modificacion= '".date("Y-m-d H:i:s")."',
															comentario='$_POST[contabilidad_comp_pr_comentarios]',
															descripcion='$_POST[contabilidad_comp_pr_desc]'
														WHERE	
															movimientos_contables.id_movimientos_contables=$_POST[contabilidad_comp_id_comprobante];								
														UPDATE	
																movimientos_contables
														set
															
															fecha_comprobante='".$fecha."',
															ultimo_usuario= ".$_SESSION['id_usuario'].",
															ultima_modificacion= '".date("Y-m-d H:i:s")."'
														WHERE	
															movimientos_contables.numero_comprobante='$comprobante_x'								
														;
														$sql_mod;
														$sql_mod2;
														$sql_mod_sumas_todas
															";	
															
													//
												//die($sql);		
											}												
											else
											{	
												
												$responce="NoActualizo"."*".$debe."*".$haber;	
												die($responce);
											
												}			
										//	die($sql);
											if (!$conn->Execute($sql)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
												$responce=$responce."*".$debe."*".$haber;
												die($responce);
											}
											else
											{
												$sql_sumas=" SELECT
																	SUM(monto_debito) as debe,
																	SUM(monto_credito) as haber
																from
																	movimientos_contables
																where numero_comprobante='$comprobante_x'
																and
																	
movimientos_contables.id_tipo_comprobante='$_POST[contabilidad_comp_pr_tipo_id]'

																	
												";
												
												$row_sumas=& $conn->Execute($sql_sumas);
												if(!$row_sumas->EOF)
												{
													$debe=number_format($row_sumas->fields("debe"),2,',','.');
													$haber=number_format($row_sumas->fields("haber"),2,',','.');
													$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
													$resta=number_format($resta,2,',','.');
													$responce="Registrado"."*".$debe."*".$haber."*".$resta;
													die($responce);
												}
											
												
											}	
/////////////////////
}
//////////////////////////////////////////fin de if de  verificacion de fechas...
			}else
			{
			$responce="numero_existe"."*".$debe."*".$haber."*".$resta;
				die($responce);
			}
			
?>