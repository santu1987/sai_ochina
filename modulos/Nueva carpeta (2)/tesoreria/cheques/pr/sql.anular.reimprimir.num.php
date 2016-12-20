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
	$n_cheque_form=$_POST[tesoreria_cheque_reimpresion_pr_n_cheque];
							
$sql = "
			SELECT 
				id_cheques,
				id_banco,
				cuenta_banco,
				numero_cheque,
				tipo_cheque,
				id_proveedor,	
				monto_cheque,
				concepto,
				estatus,
				comentarios,
				porcentaje_itf,
				cheques.id_organismo,
				fecha_cheque,
				cheques.ordenes,
				benef_nom,
				porcentaje_islr,
				base_imponible,
				secuencia,
				estado,
				estado_fecha,
				fecha_ultima_modificacion,
				benef_nom
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
			AND
				cheques.secuencia='$_POST[tesoreria_cheque_reimpresion_pr_secuencia]'	
			";
$row= $conn->Execute($sql);

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
if(!$row->EOF)
	{
//----------------------revirtiendo proceso de pagado en tablas de presupuesto-------------------------------------------------------------------------------------
 //////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
				$ordenes=$row->fields("ordenes");
		/*if(($ordenes==null)||($ordenes==0))	
		{
						
		
		}
		else	*/	
		/*if(($ordenes!=null)&&($ordenes!=0))	
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
					$fecha_cheque=$row->fields("fecha_ultima_modificacion");
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
																		
																					
											$row_documentos=& $conn->Execute($sql_facturas);
											$numero_compromiso=$row_documentos->fields("numero_compromiso");
						if(($numero_compromiso!='0')&&($numero_compromiso!=null))
						{
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
																			$where
																			";	
																			die($actu);
																			if (!$conn->Execute($actu))
																			die ('Error al Actulizar: '.$conn->ErrorMsg());
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										}						
							}						
					$i_fact++;
						}						
				$i++;							
				}	
			}			
*////////////////////////////////////////////////////////////////////////////////////////////////////		


		//--- modificando el n_cheque en la tabla cheques
							if($_POST[tesoreria_cheques_reimpresion_pr_tipo]=='1')
							{
								 $id_proveedor=$row->fields("id_proveedor");
								 $opcion='1';
							}else
							if($_POST[tesoreria_cheques_reimpresion_pr_tipo]=='2')
							{
								 $opcion='2';
								 $beneficiario=$row->fields("benef_nom");
								// $rif=$row->fields("cedula_rif_beneficiario");
							}
								 
								 $tipo_cheque=$row->fields("tipo_cheque");
								 $n_cheque=$row->fields("numero_cheque");
							     $secuencia=$row->fields("secuencia");
								 

							
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
						
						/*$n_cheque=$row_emitido->fields("ultimo_emitido");
						$n_cheque=$n_cheque-1;
						$secuencia=$row_emitido->fields("secuencia");
*/
						if($n_cheque==$n_cheque_form)
						{
							$n_cheque_ant=$n_cheque_form;
						}else
						die('Error');
				}				
							
							
														
														
				
	 }
	 else{	$bloqueado=true;
			die("Error-REIMPRIMIR");
			}
		/*	//die($sql);
		if (!$conn->Execute($sql_reg))
		{   //die ('Error'.$sql);
			die ('Error-REIMPRIMIR');
			//die ('Error DUPLICAR REGISTRO: '.$sql_reg);
			}*/

if ($bloqueado){
//die($sql);
	echo (($bloqueado)?$msgBloqueado:'Error-REIMPRIMIR: '.$conn->ErrorMsg().'<br />');
	}
	else
	{
			//---modificando estatus de cheque a reimpreso
			$sql_contab="UPDATE cheques
							SET
								
								reimpreso='1',
								codigo_banco_reimpreso=$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo],
								cuenta_banco_reimpreso='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]',
								numero_cheque_reimpreso=$n_cheque_form,
								concepto='$_POST[tesoreria_cheque_reimpresion_pr_concepto]',
								fecha_reimpresion='".date("Y-m-d H:i:s")."',
								usuario_reimpresion=".$_SESSION['id_usuario'].",
								estatus='2'														
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
			////////////////////////////--------------------------------------------------------------------------------
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
		}else
		{
			$unidad_ejec="";
			$proyecto="";
			$partida="";
		}
						
		//	$responce=$n_cheque."*".$secuencia."*".$tipo_cheque."*".$opcion;
			$responce=$n_cheque."*".$secuencia."*".$tipo_cheque."*".$opcion."*".$unidad_ejec."*".$proyecto."*".$partida;

	die($responce);		
	}

		
?>