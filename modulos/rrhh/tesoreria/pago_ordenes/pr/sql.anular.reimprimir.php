<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$id_banco=$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo];
	$cuenta_banco=$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo];
	$n_cheque="";
							
$sql = "
			SELECT 
				cheques.id_cheques,
				cheques.id_banco,
				cuenta_banco,
				cheques.numero_cheque,
				cheques.tipo_cheque,
				cheques.id_proveedor,	
				cheques.monto_cheque,
				cheques.concepto,
				cheques.estatus,
				cheques.comentarios,
				cheques.porcentaje_itf,
				cheques.id_organismo,
				cheques.fecha_cheque,
				cheques.ordenes,
				cheques.cedula_rif_beneficiario,
				cheques.nombre_beneficiario,
				cheques.porcentaje_islr,
				cheques.base_imponible,
				cheques.fecha_firma,
				cheques.estado,
				cheques.estado_fecha,
				cheques.sustraendo,
				cheques.fecha_ultima_modificacion,
				cheques.contabilizado
			FROM 
				cheques
			INNER JOIN
				organismo
			ON
			cheques.id_organismo=organismo.id_organismo	
			WHERE
				cheques.id_cheques='$_POST[tesoreria_cheque_reimpresion_pr_id_cheque]' 
			AND
				cheques.numero_cheque='$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]'
			AND
				cheques.secuencia='$_POST[tesoreria_cheque_reimpresion_pr_secuencia]'
			AND
				cheques.id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_banco]'	
			AND	
				cheques.cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]'
			AND	
				cheques.id_organismo=".$_SESSION["id_organismo"]."	
			
			";
$row= $conn->Execute($sql);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
if(!$row->EOF)
	{
	if($row->fields("contabilizado")==1)					
		die("integrado");
	else
		$contabilizado=$row->fields("contabilizado");
	
if (strlen($contabilizado)==0)
{
	$contabilizado=0;
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$sql_saldo_actual = "SELECT 
							 saldo_actual
					   FROM 
					   		 banco_cuentas
					   	WHERE
							cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]'
			   		    AND 
			   		 		id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_banco]'
						AND
							estatus='1'		
					  ";
$row_saldo_actual= $conn->Execute($sql_saldo_actual);
			if(!$row_saldo_actual->EOF)	
			{		
		
						$saldo_actual=$row_saldo_actual->fields("saldo_actual");
						$monto_cheque=$_POST[tesoreria_cheque_reimpresion_pr_monto_pagar];
						$monto_cheque= str_replace(".","",$monto_cheque);
						$monto_cheque=str_replace(",",".",$monto_cheque);
						$saldo_total=($saldo_actual)+($monto_cheque);
								$sql = "UPDATE banco_cuentas 
								 SET
									saldo_actual='$saldo_total'
								WHERE cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]'
								AND
									id_organismo=$_SESSION[id_organismo]
											";
							if (!$conn->Execute($sql)) 
							{die ($sql);}
				}
								
			
//cambio
$sql_saldo_actual_reimp = "SELECT 
							 saldo_actual
					   FROM 
					   		 banco_cuentas
					   	WHERE
							cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
			   		    AND 
			   		 		id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo]'
						AND
							estatus='1'		
					  ";
	$row_saldo_actual_reimp= $conn->Execute($sql_saldo_actual_reimp);
	if(!$row_saldo_actual_reimp->EOF)	
	{		

				$saldo_actual2=$row_saldo_actual_reimp->fields("saldo_actual");
			    $monto_cheque2=$_POST[tesoreria_cheque_reimpresion_pr_monto_pagar];
				$monto_cheque2= str_replace(".","",$monto_cheque2);
				$monto_cheque2=str_replace(",",".",$monto_cheque2);

				$saldo_total2=($saldo_actual2)-($monto_cheque2);
					if($saldo_total2<'0')
					{
						die("no_disponible_saldo");
					}
					else
					{
						///
						$sql = "UPDATE banco_cuentas 
						 SET
						 	saldo_actual='$saldo_total2'
						WHERE cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
						AND
							id_organismo=$_SESSION[id_organismo]
									";
					if (!$conn->Execute($sql))
					{die ($sql);}
				}
						
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------							

		
		
		//INACTIVANDO EL CHEQUE ANTERIOR
		$sql_contab="UPDATE cheques
							SET
								
								fecha_anula='".date("Y-m-d H:i:s")."',
								usuario_anula=".$_SESSION['id_usuario'].",
								estatus='5',
								ordenes='{0}'					
							WHERE
								cheques.numero_cheque='$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]'
							AND
								cheques.secuencia='$_POST[tesoreria_cheque_reimpresion_pr_secuencia]'
							AND
								cheques.id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_banco]'	
							AND	
								cheques.cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]'
							AND	
								cheques.id_organismo=".$_SESSION["id_organismo"]."	
									";		
						if (!$conn->Execute($sql_contab)) 
						//die('Error-act cheque');
						die($sql_contab);
	//---------------busqueda del ultimo CHEQUE----------------
			$sql_ultimo_emitido = "SELECT 
										ultimo_emitido,cantidad_cheques,secuencia
								   FROM 
										chequeras 
									WHERE
										chequeras.cuenta='$cuenta_banco'
									AND 
										chequeras.id_banco='$id_banco'
									AND
										chequeras.estatus='1'		
								  ";
			if (!$conn->Execute($sql_ultimo_emitido))
					//die ('Error_impresion' );
			//die ('Error 	consulta: '.$conn->ErrorMsg());
			die($sql_ultimo_emitido);	
				$row_emitido= $conn->Execute($sql_ultimo_emitido);
		if(!$row_emitido->EOF)	
		{
						$cantidad=$row_emitido->fields("cantidad_cheques");
						$n_cheque=$row_emitido->fields("ultimo_emitido");
						$secuencia=$row_emitido->fields("secuencia");
						$secuencia2=$secuencia;
						$n_cheque_resultado=intval($n_cheque)+1;
						$n_ultimo=intval($n_cheque_resultado)+1;
						$proximo_emitir=$n_cheque_resultado;
						$estatus=1;
							//}
							//----------------------revirtiendo proceso de pagado en tablas de presupuesto-------------------------------------------------------------------------------------
//////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
				$fecha_cheque=$row->fields("fecha_ultima_modificacion");
				$ordenes=$row->fields("ordenes");
		if(($ordenes!=null)&&($ordenes!=0))	
		{
				$numero=$row->fields("numero_cheque");
				$ord1=str_replace("{","",$ordenes);
				$ord2=str_replace("}","",$ord1);
				$vector = split(",",$ord2);
				$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
				while($i < $contador)
				{///////////consultando las orden de pago
					$sql_orden="SELECT  orden_pago,documentos
														FROM
															orden_pago
														WHERE(orden_pago.id_orden_pago='$vector[$i]')";	
															
					$row_orden=$conn->Execute($sql_orden); 
					$documentos=$row_orden->fields("documentos");
					$ano=substr($fecha_cheque,0,4);
					$mes=substr($fecha_cheque,5,2);
					$doc1=str_replace("{","",$documentos);
					$doc2=str_replace("}","",$doc1);
					$facturas= split(",",$doc2);
					$contador_fact=count($facturas);
					$i_fact=0;
					while($i_fact < $contador_fact)
					{//////////consultando las facturas			
											$sql_facturas="
																SELECT 
																		numero_compromiso,monto_bruto
																FROM
																		documentos_cxp
																where
																		id_documentos='$facturas[$i_fact]'";
																		
											//die($sql_facturas);										
											$row_documentos=& $conn->Execute($sql_facturas);
											$numero_compromiso=$row_documentos->fields("numero_compromiso");
											$monto_restar=$row_documentos->fields("monto_bruto");
						if(($numero_compromiso=="0")or($numero_compromiso=="")or($numero_compromiso=="NULL"))
						{
													$partida=0;
													$unidad_ejecutora=0;
													$accion_central=0;
													$accion_especifica=0;
													$pre_orden=0;
													$tipo=0;
																		
							}else
							{						
//////////////////////////////// datos segun numero de compromiso////////////////////////////////////////
					
												$sql="SELECT 
															\"orden_compra_servicioE\".id_proveedor, 
															\"orden_compra_servicioE\".id_unidad_ejecutora,
															\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
															\"orden_compra_servicioE\".id_accion_especifica, 
															\"orden_compra_servicioE\".numero_compromiso, 
															\"orden_compra_servicioE\".numero_pre_orden,
															\"orden_compra_servicioE\".tipo,
															partida, 
															   generica, 
															   especifica, 
															   subespecifica
														FROM 
															\"orden_compra_servicioE\"
														INNER JOIN
															organismo
														ON
															\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
														INNER JOIN
															\"orden_compra_servicioD\"
														ON
															\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
														where
															\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
													$row_orden_compra=& $conn->Execute($sql);
													$partida=$row_orden_compra->fields("partida");
													$unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
													$accion_central=$row_orden_compra->fields("id_proyecto_accion_centralizada");
													$accion_especifica=$row_orden_compra->fields("id_accion_especifica");
													$pre_orden=$row_orden_compra->fields("id_accion_especifica");
													$tipo=$row_orden_compra->fields("tipo");
													if($tipo=='1')
													{
													
													$where="AND id_proyecto = '$accion_central'"; 
													}else
													$where="AND id_accion_centralizada ='$accion_central'"; 
													$resumen_suma = "
																		SELECT  
																			   (monto_pagado[".date("n")."]) AS monto
																		FROM 
																			\"presupuesto_ejecutadoR\"
																		WHERE
																			id_unidad_ejecutora='$unidad_ejecutora'
																		AND	id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica")."
																		AND
																			partida = '".$row_orden_compra->fields("partida")."'  AND	generica = '".$row_orden_compra->fields("generica")."'  AND	especifica = '".$row_orden_compra->fields("especifica")."'  AND	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																		$where
																		";
														$rs_resumen_suma=& $conn->Execute($resumen_suma);
														
														if (!$rs_resumen_suma->EOF) 
															$monto_pagado = $rs_resumen_suma->fields("monto");
														else
															$monto_pagado = 0;
															$monto_total = $monto_pagado - $monto_restar;	
															$monto_total2 = $monto_total + $monto_restar;
															$actu=
															"UPDATE 
																	\"presupuesto_ejecutadoR\"
															SET 
																	monto_pagado[".$mes."]= '$monto_total'
															WHERE
																	(id_organismo = ".$_SESSION['id_organismo'].") 
																AND
																	(id_unidad_ejecutora = '$unidad_ejecutora') 
																AND 
																	(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																AND 
																	(ano = '".$ano."')
																AND
																	partida = '".$row_orden_compra->fields("partida")."'  
																AND	
																	generica = '".$row_orden_compra->fields("generica")."'
																AND	
																	especifica = '".$row_orden_compra->fields("especifica")."'  
																AND
																	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																$where;
																UPDATE 
																	\"presupuesto_ejecutadoR\"
																SET 
																	monto_pagado[".date("n")."]= '$monto_total2'
																WHERE
																	(id_organismo = ".$_SESSION['id_organismo'].") 
																AND
																	(id_unidad_ejecutora = '$unidad_ejecutora') 
																AND 
																	(id_accion_especifica = ".$row_orden_compra->fields("id_accion_especifica").")
																AND 
																	(ano ='".date("Y")."')
																AND
																	partida = '".$row_orden_compra->fields("partida")."'  
																AND	
																	generica = '".$row_orden_compra->fields("generica")."'
																AND	
																	especifica = '".$row_orden_compra->fields("especifica")."'  
																AND
																	sub_especifica = '".$row_orden_compra->fields("subespecifica")."'
																$where;
																UPDATE		
																	 \"presupuesto_ejecutadoD\"
																SET
																			id_tipo_documento='6', 
																			numero_documento=$n_cheque, 
																			ultimo_usuario=".$_SESSION['id_usuario'].", 
																			fecha_modificacion='".date("Y-m-d H:i:s")."'
																WHERE
																			id_tipo_documento='6'
																		AND	
																			numero_documento='$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]'												
																";		
																//die($actu);
																if (!$conn->Execute($actu))
																die ('Error al Actulizar: '.$conn->ErrorMsg());
							}						
					$i_fact++;
						}						
				$i++;							
				}	
		}				
///////////////////////////////////////////////////////////////////////////////////////////////////	
						//////////////////////////////////////////////////
						$sql_def=" UPDATE chequeras
										SET
												ultimo_emitido='$proximo_emitir',
												estatus=$estatus,
												fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
												ultimo_usuario=".$_SESSION['id_usuario']."
										WHERE
											id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo]'
										AND	
											cuenta='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
										AND
											secuencia='$secuencia2'";
						if (!$conn->Execute($sql_def))
							{	echo ('ERROR-P:MODIFICAR-CHEQUERA' );}
						//die ('Error al modificar datos chequera: '.$sql_def);
						
				}
					else
						die('chequera_agotada');
						if($n_cheque!="")
						{
							//--- modificando el n_cheque en la tabla cheques
							if($_POST[tesoreria_cheques_reimpresion_pr_tipo]=='1')
							{
								 $id_proveedor=$row->fields("id_proveedor");
								 $beneficiario="0";
								 $rif="0";
								 $opcion='1';
							}
							else
							if($_POST[tesoreria_cheques_reimpresion_pr_tipo]=='2')
							{
								 $opcion='2';
								 $beneficiario=$row->fields("nombre_beneficiario");
								 $rif=$row->fields("cedula_rif_beneficiario");
							}
								 
								 $tipo_cheque=$row->fields("tipo_cheque");
								
								 $monto_cheque = str_replace(".","",$_POST[tesoreria_cheque_reimpresion_pr_monto_pagar]);
				
								 $concepto=$row->fields("concepto");
								 $estatus=$row->fields("estatus");
							
								 $porcentaje_itf=$row->fields("porcentaje_itf");
								 $id_organismo=$row->fields("id_organismo");
								 $fecha_cheque=$row->fields("fecha_cheque");
								
								 $usuario_cheque=$_SESSION['id_usuario'];
								 $fecha_ultima_modificacion=date("Y-m-d H:i:s");
								 $ultimo_usuario=$_SESSION['id_usuario'];
								 $porcentaje_islr=$row->fields("porcentaje_islr");
								 $base_imponible=$row->fields("base_imponible");
								 $fecha_firma=$row->fields("fecha_firma");
								 $estado=$row->fields("estado");
				 				 $estado_fecha=$row->fields("estado_fecha");
								 $sustraendo=$row->fields("sustraendo");
							if($_POST[tesoreria_cheques_reimpresion_pr_tipo]=='1')
							{		$sql_reg = "	
															INSERT INTO 
																cheques
																(
																	id_banco,
																	cuenta_banco,
																	numero_cheque,
																	tipo_cheque,
																	id_proveedor,	
																	monto_cheque,
																	concepto,
																	estatus,
																	porcentaje_itf,
																	id_organismo,
																	fecha_cheque,
																	ordenes,
																	usuario_cheque,
																	fecha_ultima_modificacion,
																	ultimo_usuario,
																	reimpreso,
																	codigo_banco_reimpreso,
																	cuenta_banco_reimpreso,
																	numero_cheque_reimpreso,
																	fecha_reimpresion,
																	usuario_reimpresion,
																	secuencia,
																	porcentaje_islr,
																	fecha_firma,
																	base_imponible,
																	estado,
																	estado_fecha,
																	sustraendo,
																	contabilizado																										
														
																) 
																VALUES
																(
																	 $_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo],
																	 '$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]',
																	 $n_cheque,
																	 $tipo_cheque,
																	 $id_proveedor,
																	 '".str_replace(",",".",$monto_cheque)."',
																	 '$concepto',
																	 '2',
																	 $porcentaje_itf,
																	 $id_organismo,
																	 '".date("Y-m-d H:i:s")."',
																	'{".$_POST[tesoreria_cheque_reimpresion_pr_ordenes]."}',
																	".$_SESSION['id_usuario'].",
																	'".date("Y-m-d H:i:s")."',
																	 ".$_SESSION['id_usuario'].",
																	 '1',
																	 '$_POST[tesoreria_cheque_reimpresion_pr_banco_id_banco]',
																	 '$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]',
																	 '$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]',
																	 '".date("Y-m-d H:i:s")."',
																	 ".$_SESSION['id_usuario'].",
																	 $secuencia,
																	 '".str_replace(",",".",$porcentaje_islr)."',
																	  '".$fecha_firma."',
																	  '".str_replace(",",".",$base_imponible)."',
																	  '".$estado."',
													  				  '".$estado_fecha."',
																	  $sustraendo,
																	  $contabilizado
																										
																)
														";
							}
							else
							{
								$sql_reg = "	INSERT INTO 
													cheques
													(
														id_banco,
														cuenta_banco,
														numero_cheque,
														tipo_cheque,
														monto_cheque,
														concepto,
														estatus,
														porcentaje_itf,
														id_organismo,
														fecha_cheque,
														ordenes,
														usuario_cheque,
														fecha_ultima_modificacion,
														ultimo_usuario,
														reimpreso,
														codigo_banco_reimpreso,
														cuenta_banco_reimpreso,
														numero_cheque_reimpreso,
														fecha_reimpresion,
														usuario_reimpresion,
														secuencia,
														nombre_beneficiario,
														cedula_rif_beneficiario,
														porcentaje_islr,
														base_imponible,
														fecha_firma,
														estado,
														estado_fecha,
														sustraendo,
														contabilizado
													) 
													VALUES
													(
														 $_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo],
														 '$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]',
														 $n_cheque,
														 $tipo_cheque,
														 '".str_replace(",",".",$monto_cheque)."',
														 '$concepto',
														 '2',
														 $porcentaje_itf,
														 $id_organismo,
														 '".date("Y-m-d H:i:s")."',
														'{".$_POST[tesoreria_cheque_reimpresion_pr_ordenes]."}',
														".$_SESSION['id_usuario'].",
														'".date("Y-m-d H:i:s")."',
														 ".$_SESSION['id_usuario'].",
														 '1',
														 '$_POST[tesoreria_cheque_reimpresion_pr_banco_id_banco]',
														 '$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]',
														 '$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]',
														 '".date("Y-m-d H:i:s")."',
														 ".$_SESSION['id_usuario'].",
														 $secuencia,
														 '$beneficiario',
														 '$rif',
														 '".str_replace(",",".",$porcentaje_islr)."',
														 '".str_replace(",",".",$base_imponible)."',
														 '".$fecha_firma."',
														 '".$estado."',
													  	 '".$estado_fecha."',
														 $sustraendo,
														 $contabilizado
														 )
											";
										
							
							
							
							}
							
							
														
						}								
				
	 }
	 else{
			
			
			$bloqueado=true;
			die("Error-REIMPRIMIR");
			//die($sql);
			}
			
		if (!$conn->Execute($sql_reg))
		{   //die ('Error'.$sql);
			//die ('Error-REIMPRIMIR');
			die ('Error DUPLICAR REGISTRO: '.$sql_reg);
			}
		else
		{

		//----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
				if($_POST[tesoreria_cheque_reimpresion_pr_ordenes]!="")
				{
						$vector = split( ",",$_POST[tesoreria_cheque_reimpresion_pr_ordenes]);
						
						$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
						$i=0;
						while($i < $contador)
						{
								$sql_orden="UPDATE orden_pago
											SET
													cheque='$n_cheque',	
													id_banco='$id_banco',
													cuenta_banco='$cuenta_banco',
													secuencia='$secuencia'
													
											WHERE
													(orden_pago.id_orden_pago='$vector[$i]')
														
											";	
						
								//die($sql_orden);
								$i=$i+1;	
								if (!$conn->Execute($sql_orden)) 
										die ('ERROR-EN ORDEN');
//											die ('ERROR-EN ORDEN: '.$sql_orden);
												
						}
					}			
							
		//-----------------------------------------------------------
		}	
																
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*if(!$row->EOF)
	{
		$sql_contab="UPDATE cheques
					SET
						
						fecha_anula='".date("Y-m-d H:i:s")."',
						usuario_anula=".$_SESSION['id_usuario'].",
						estatus='5',
						ordenes='{0}'						
					WHERE
						cheques.numero_cheque='$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]'
					AND
						cheques.secuencia='$_POST[tesoreria_cheque_reimpresion_pr_secuencia]'
					AND
						cheques.id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo]'	
					AND	
						cheques.cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
					AND	
						cheques.id_organismo=".$_SESSION["id_organismo"]."	
							";		
				if (!$conn->Execute($sql_contab)) 
				die('Error-act cheque');
					//die ('Error al reimprimir: '.$sql_contab);
					
		}																	
		else
			$bloqueado=true;*/
//echo($sql_pago);
if ($bloqueado){
//die($sql);
	echo (($bloqueado)?$msgBloqueado:'Error-REIMPRIMIR: '.$conn->ErrorMsg().'<br />');
	}
	else
	{
			if($tipo_cheque=='1')
			{
				$opcion='0';
				
			}
if(($numero_compromiso!='0')&&($numero_compromiso!=null))
	{	
			if($tipo==1)
			{
				$sql_proyecto="SELECT id_proyecto,codigo_proyecto,nombre FROM proyecto WHERE id_proyecto='$accion_central'";
				$row_proyecto=& $conn->Execute($sql_proyecto);
				$proyecto=$row_proyecto->fields("codigo_proyecto");
			}else
			if($tipo==2)
			{
				$sql_proyecto="SELECT codigo_accion_central,denominacion FROM accion_centralizada WHERE id_accion_central='$accion_central'";
				$row_proyecto=& $conn->Execute($sql_proyecto);
				$proyecto=$row_proyecto->fields("codigo_accion_central");
			}
			
			$sql_ejec="SELECT id_unidad_ejecutora, nombre FROM unidad_ejecutora WHERE id_unidad_ejecutora='$unidad_ejecutora'";
			$row_ejec=& $conn->Execute($sql_ejec);
			$unidad_ejec=$row_ejec->fields("nombre");
		}

			$responce=$n_cheque."*".$secuencia."*".$tipo_cheque."*".$opcion."*".$unidad_ejec."*".$proyecto."*".$partida;
			
	die($responce);		
	}

		
?>