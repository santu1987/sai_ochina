<?php
ini_set("memory_limit","20M");

/* parche creado para pasar los saldos contables de un mes a otro*/
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha =date("Y-m-d H:i:s") ;
$ano=substr($fecha,0,4);
$mes=substr($fecha,5,2);
$ano='2011';
$mes='1';
$sesion=1;
$ce=0;
$sql_comprobante="
					select
							 substr(movimientos_contables.numero_comprobante::varchar,9) as n_comp,
							 movimientos_contables.id_movimientos_contables,
							 movimientos_contables.ano_comprobante,
							 movimientos_contables.mes_comprobante,
							 movimientos_contables.id_tipo_comprobante,
							 movimientos_contables.numero_comprobante,
							 movimientos_contables.secuencia,
							 movimientos_contables.comentario,
							 movimientos_contables.cuenta_contable,
							 movimientos_contables.descripcion,
							 movimientos_contables.referencia,
							 movimientos_contables.debito_credito,
							 movimientos_contables.monto_debito,
							 movimientos_contables.monto_credito,
							 movimientos_contables.fecha_comprobante,
							 movimientos_contables.id_auxiliar, 
							 movimientos_contables.id_unidad_ejecutora,
							 movimientos_contables.id_proyecto,
							 movimientos_contables.id_utilizacion_fondos,
							 movimientos_contables.ultimo_usuario,
							 movimientos_contables.id_organismo,
							 movimientos_contables.ultima_modificacion,
							 movimientos_contables.estatus,
							 movimientos_contables.id_accion_central,
							 cuenta_contable_contabilidad.id,
							 cuenta_contable_contabilidad.id_cuenta_suma,
							 naturaleza_cuenta.codigo AS codigo,
							 tipo_comprobante.codigo_tipo_comprobante as tipo_comprobante
							 from 
							 	movimientos_contables
							 inner join 
							 	cuenta_contable_contabilidad on movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
							 inner join 
							 	naturaleza_cuenta on cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
							 INNER JOIN 
							 	tipo_comprobante on movimientos_contables.id_tipo_comprobante=tipo_comprobante.id 
							where 
									movimientos_contables.id_organismo = 1 
								and
									estatus='1'	
								and
									mes_comprobante='1' 
								";
						//die($sql_comprobante);substr(movimientos_contables.numero_comprobante::varchar,9)='101000'";
						$row_comprobante=& $conn->Execute($sql_comprobante);
						while(!$row_comprobante->EOF)
						{
									$id_cuenta=$row_comprobante->fields("id");
									$id_cuenta_suma=$row_comprobante->fields("cuenta_suma");
									$id_aux=$row_comprobante->fields("id_auxiliar");
									$debe_ant=$row_comprobante->fields("monto_debito");
									$haber_ant=$row_comprobante->fields("monto_credito");
									$tipo_comprobante=$row_comprobante->fields("tipo_comprobante");
							//	echo($tipo_comprobante);
									$fecha_comprobante=$row_comprobante->fields("fecha_comprobante") ;
									$ano_comprobante=substr($fecha_comprobante,0,4);
									$mes_comprobante=substr($fecha_comprobante,5,2);
//////////////////////////////////////////////////////////////////////cta contable//////////////////////////////////////////////////////
										if($id_cuenta!="")
												{
														$sql_mov="SELECT  
																   (debe[".$mes."])as debe,(haber[".$mes."])as haber
																  
																	FROM 
																			saldo_contable
																	WHERE
																		cuenta_contable='$id_cuenta'
																	and
																		ano='2011'	
																		";
																//	echo("*-*-".$sql_mov."-*-*-");
																		$rs_mov=& $conn->Execute($sql_mov);
																		if (!$rs_mov->EOF) 
																		{
																			//
																				$monto_saldo_d =$rs_mov->fields("debe")+($debe_ant);																																						$monto_saldo_h=$rs_mov->fields("haber")+($haber_ant);
																																					
																			
																																	
																			///sql para saldos cuentas normales
																						$sql_mod_cuentas="update
																									saldo_contable
																								SET 
																										debe[".$mes."]= '$monto_saldo_d',
																																															haber[".$mes."]= '$monto_saldo_h'
																								WHERE
																										cuenta_contable='$id_cuenta'
																								and
																									ano='$ano';
																								";
											/*if (!$conn->Execute($sql_mod_cuentas)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
											}*/	
												
																			 
																		
																	    }//end de if mov	
												}//end cuenta_contable!=""					

/////////////////////////////////////////// trabajando la cuenta que suma ////////////////////////////////////////////
									
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*
$turnos=1;
$contadores=0;
//$id_sumas=$id_cc;
$id_sumas=$id_cuenta;


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
		while($turnos>$contadores)
		{
					$sqlw="select 
									*
								
								from
										cuenta_contable_contabilidad 
								
								where id='$id_sumas'";				
					$rs_suma=& $conn->Execute($sqlw);
					if (!$rs_suma->EOF) 
					{
						$suma_cuenta=$rs_suma->fields("id_cuenta_suma");
						if($suma_cuenta!="")
						{
								$sql_mov_suma="SELECT  
								(debe[".$mes."])as debe,(haber[".$mes."])as haber
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
												
														
														$monto_saldon_d =$rs_mov_suma->fields("debe")+($debe_ant);
														$monto_saldon_h=$rs_mov_suma->fields("haber")+($haber_ant);
																																																			///////////////////////////////////////////////// realizando saldo inicial////////////////////////////////////
														///verifico si el tipo de comprobante es el 10 en caso de serlo se registra la info como saldo inicial , si algun dia cambia el tipo de comprobante 10 se debe modificart este codigo , ya que no hay forma de determinar los tipos de comprobante saldo inicial ya que la definiciion tipo de comprobante es totalmente generica.... y es un requerimiento de usuario no un error de programacion
														
																$sql_mod_suma="
																				update
																				saldo_contable
																				SET 
																					debe[".$mes."]= '$monto_saldon_d',
																					haber[".$mes."]= '$monto_saldon_h'
																				WHERE
																				cuenta_contable='$suma_cuenta'
																				and
																				ano='2010'
																				";
											/*if (!$conn->Execute($sql_mod_suma)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
											}	*/		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
//////////////////////////////////////////////////////////////////////////// auxiliares /////////////////////////////////////////////////
											//$id_aux=5;
											$id_cc=$id_cuenta;
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
																	cuenta_auxiliar='$id_aux'
																and
																	ano='2011'
																	"	
																;
														  $rs_mov2=& $conn->Execute($sql_mov2);
																		if (!$rs_mov2->EOF) 
																		{
																				$monto_saldo_aux_d =$rs_mov2->fields("debe")+($debe_ant);
																				$monto_saldo_aux_h=$rs_mov2->fields("haber")+($haber_ant);
																									
																			

												
													$sql_act_inicial2="
																		update
																				saldo_auxiliares
																			SET 
																					
																					debe[".$mes."]= '$monto_saldo_aux_d',
																																					haber[".$mes."]= '$monto_saldo_aux_h'
																			WHERE
																					
																					cuenta_auxiliar='$id_aux'
																			and	
																					cuenta_contable='$id_cc'			
																			and
																					ano='$ano'
																			";		


														$sql_act_aux=$sql_act_inicial2;	
													if(($id_aux=='')&&($id_cc=='822'))
													{
														die($sql_act_inicial2);
													}
														}else
														$sql_act_aux="";
														//die($sql_act_aux);
														/////////////////////////////
														
														/////////////////////////////				
										}else
										$sql_act_aux="";
									
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
				//	die($sql_mod_sumas_todas);
						
							$sql_mod_cuentas_todas=$sql_mod_cuentas.";".$sql_act_inicial2.";".$sql_mod_sumas_todas;
							//$sql_mod_cuentas_todas=$sql_act_inicial2;
			if (!$conn->Execute($sql_mod_cuentas_todas)) 
				die ('Error al Actualizar: '.$conn->ErrorMsg()."".$sql_mod_cuentas_todas);
			else
				//echo($ce);?></BR><?
				//."sql=".$sql_mod_cuentas_todas);
			
				//die("Actualizado");
				echo($sql_mod_cuentas_todas);
				echo($ce."".$conn->ErrorMsg());?></BR><?
				//echo($sql_mod_sumas_todas."///");
							
						//$sql_mod_cuentas="";
						//$sql_mod_sumas_todas="";
						//$sql_act_inicial2="";
			
						$ce++;
					
			$row_comprobante->MoveNext();

						}
					die("Terminado");				

?>
