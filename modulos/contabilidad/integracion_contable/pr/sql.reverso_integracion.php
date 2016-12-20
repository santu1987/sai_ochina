<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$dia_actual=date("d");
$mes_actual=date("m");
$ano_actual=date("Y");
$modulo=$_POST[contabilidad_integracion_reverso_mod];
$comprobante=$_POST["contabilidad_integracion_reverso_numero_c_desde2"];
$ano=$_POST["contabilidad_reverso_int_pr_ayo"];
$opcion_modulo="";

//echo($modulo);
//die("a");
$where="WHERE movimientos_contables.id_organismo = $_SESSION[id_organismo]	 ";

	/*if(($desde_fecha!="")&&($hasta_fecha!=""))
{
	$where.=" and integracion_contable.fecha_comprobante >= '$desde_fecha' AND integracion_contable.fecha_comprobante <='$hasta_fecha'";
}
*/
if(($comprobante!="")&&($ano!=""))
{
	$where.="  and movimientos_contables.numero_comprobante ='$comprobante'
			   and ano_comprobante='$ano'
			   and estatus='0'
			   ";
}

//************************************************************************
/***LA SIGUIENTE RUTINA VERIFICA EN EL CASO DE QUE EL MODULO A REVERSAR SEA CN DIRECCION A TESORERIA SE CONSULTA EL RESPECTIVO CHEQUE YA QUE ENTRE SUS CAMPOS ESTA EL DE NUMERO_COMPROBANTE DEL MOVMIENTO E INTEGRACIÓN  ESTOS EBEN SER MODIFICADOS **/
									if($modulo=='4')
									{
										$sql_cheques="  SELECT 
															distinct (numero_cheque)
														FROM 
															cheques
														INNER JOIN 
																movimientos_contables
														ON
															cheques.numero_comprobante=movimientos_contables.numero_comprobante		 
														where
															 movimientos_contables.numero_comprobante='$comprobante'
														AND
															movimientos_contables.ano_comprobante='$ano'	 
														
														";
														//die($sql_cheques);
										$row_cheques=& $conn->Execute($sql_cheques);
										if($row_cheques->EOF)
										{
											die("no_cheque");
										}else
										{
											$numero_cheque=$row_cheques->fields("numero_cheque");
											$opcion_modulo="tesoreria";
											
										}				
										
									}//fin modulo==4	
									else
									{
										$opcion_modulo="";
									}
//**** si opcion modulo es dif a vacio(como debe ser) se ejecuta todo sino no proviene de ningun modulo por lo tanto no se ejecuta el proceso, la variable opcion_modulo define de q modulo proviene la integracion cntable
if($opcion_modulo!="")
{									
	//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$sql_mov_cont="
							SELECT 

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

									movimientos_contables.comprobante_ant,

									movimientos_contables.fecha_ant,
									tipo_comprobante.codigo_tipo_comprobante

  						FROM

									 movimientos_contables

									 

							INNER JOIN

											tipo_comprobante

									on

										movimientos_contables.id_tipo_comprobante=tipo_comprobante.id											 

							INNER JOIN

										cuenta_contable_contabilidad

								on

									 cuenta_contable_contabilidad.cuenta_contable=movimientos_contables.cuenta_contable		

							INNER JOIN

										organismo

								on

										movimientos_contables.id_organismo=organismo.id_organismo				

							
							$where
							order by
									
									movimientos_contables.secuencia";
//die($sql_mov_cont);
$row=& $conn->Execute($sql_mov_cont);
$cont=0;
while(!$row->EOF)
{

//die($sql_mov_cont);
			///////////////////////asignando variables para no complicarme en la sintaxis
			$id_movimientos_contables=$row->fields("id_movimientos_contables");
			$ano_comprobante=$row->fields("ano_comprobante");
			$mes_comprobante=$row->fields("mes_comprobante");
			$id_tipo_comprobante=$row->fields("id_tipo_comprobante");
       		$numero_comprobante=$row->fields("numero_comprobante");
			$secuencia=$row->fields("secuencia");
			$comentario=$row->fields("comentario");
			$cuenta_contable=$row->fields("cuenta_contable"); 
		    $descripcion=$row->fields("descripcion");
   			$referencia=$row->fields("referencia"); 
			$debito_credito=$row->fields("debito_credito"); 
			$monto_debito=$row->fields("monto_debito"); 
			$monto_credito=$row->fields("monto_credito"); 
			$fecha_comprobante=$row->fields("fecha_comprobante"); 
			$dia_comprobante=substr($row->fields("fecha_comprobante"),8,2);
       		$id_auxiliar=$row->fields("id_auxiliar");
			$id_unidad_ejecutora=$row->fields("id_unidad_ejecutora");
			$id_proyecto=$row->fields("id_proyecto");
			$id_utilizacion_fondos=$row->fields("id_utilizacion_fondos"); 
       		$ultimo_usuario=$row->fields("ultimo_usuario");
			$id_organismo=$row->fields("id_organismo");
			$ultima_modificacion=$row->fields("ultima_modificacion");
			$estatus=$row->fields("estatus");
			$id_accion_central=$row->fields("id_accion_central"); 
     		$comprobante_ant=$row->fields("comprobante_ant");
			$fecha_ant=$row->fields("fecha_ant");
			$id_organismo=$_SESSION[id_organismo];
			$cod_tipo=$row->fields("codigo_tipo_comprobante");
	
	////////////////////////////////////////////////////////////////////////////////////////////
	// con los nuevos datos se debe generar un nuevo numero de cmprobante integracion y asignarcele al  cheque ...
if($cont==0)
{
	
$sql_num="
	SELECT  
		max(integracion_contable.numero_comprobante) as maximo
	FROM 
		integracion_contable
	INNER JOIN
		tipo_comprobante
	ON
		tipo_comprobante.id=integracion_contable.id_tipo_comprobante
	where		
		(integracion_contable.id_organismo =".$_SESSION['id_organismo'].") 
	and
		codigo_tipo_comprobante='$cod_tipo'
	and	
		mes_comprobante='$mes_actual'
	and
		ano_comprobante='$ano_actual'
		";
	//die($sql_num);
$ci++;
$rs_comprobante =& $conn->Execute($sql_num);
	if(!$rs_comprobante->EOF)
	{
				$numero_comprobante_automatico=substr($rs_comprobante->fields("maximo"),8)+1;
				$sig_comp=substr($numero_comprobante_automatico,2);
			//	echo($rs_comprobante->fields("maximo"));
	}
	if($numero_comprobante_automatico=='1')
	{		
	
			$uno=substr($mes_actual,0,1);
			if($uno==0)
			$mes2=substr($mes_actual,1,1);
			$numero_comprobante_automatico=$cod_tipo.$mes2.'000';
			//die($comprobante);
			$sig_comp=substr($numero_comprobante_automatico,2);
			//	$comprobante=$ano.$mes.$dia.$comprobante;		
	
	}
	$numero_comprobante_integracion=$ano_actual.$mes_actual.$dia_actual.$numero_comprobante_automatico;
																													
}	
//echo($numero_comprobante_integracion);
//die("q");	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/*Con los resultados de la consulta de arriba voy llenando las tablas de integracion contable */
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sql_mov_guardar="
								INSERT
								 		INTO
								integracion_contable
								(
									id_organismo, 
									ano_comprobante, 
									mes_comprobante, 
									id_tipo_comprobante, 
       							    numero_comprobante, 
									secuencia, 
									comentario, 
									cuenta_contable, 
									descripcion, 
            						referencia, 
									monto_debito, 
									monto_credito, 
									fecha_comprobante, 
									id_auxiliar, 
       							    id_unidad_ejecutora,
									id_proyecto,
									id_utilizacion_fondos, 
									ultimo_usuario, 
           							fecha_actualizacion, 
									debito_credito, 
									estatus, 
									id_accion_central,
									modulo_origen
								)
								VALUES
								(
									'$id_organismo',
									'$ano_comprobante',
									'$mes_comprobante',
									'$id_tipo_comprobante',
									'$numero_comprobante_integracion',
									'$secuencia',
									'$comentario',
									'$cuenta_contable', 
									'$descripcion', 
   								    '$referencia', 
									'$monto_debito', 
									'$monto_credito', 
									'$fecha_comprobante',
									'$id_auxiliar',
									'$id_unidad_ejecutora',
									'$id_proyecto',
									'$id_utilizacion_fondos', 
       								 ".$_SESSION['id_usuario'].",
									'".date("Y-m-d H:i:s")."',
									'$debito_credito', 
									 '1',
									 '$id_accion_central',
									 $modulo
								)			
									";
	//		echo($sql_mov_guardar);
			//ejecuto el sql
							if (!$conn->Execute($sql_mov_guardar)) 
							{
								$error=1;
							}
							
							$cont=$cont+1;
						if($error==1)
							die ('Error al Registrar: '.$sql);
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$row->MoveNext();
}
							if($error==1)
								die ('Error al Registrar: '.$sql);
							else
							{
								if($modulo=='4')
									{
										
								
									
									$sql_cheques="UPDATE
																	cheques
															SET
																	numero_comprobante_integracion='$numero_comprobante_integracion',
																	numero_comprobante='0'
															where
																	numero_comprobante='$numero_comprobante'				
																AND numero_cheque='$numero_cheque'";
									$sql_guardar_reverso="
															INSERT
																	INTO
															reverso_integracion
															(
																id_unidad,
																numero_comprobante_mov,
																numero_comprobante_integracion,
																fecha,
																usuario,
																organismo
															)
															values
															(
																'$modulo',
																'$numero_comprobante',
																'$numero_comprobante_integracion',
																'".date("Y-m-d H:i:s")."',
																".$_SESSION['id_usuario'].",
																'$id_organismo'
															)			
															";							
															//	die($sql_guardar_reverso);
									$sql_borrar_integracion="
															$sql_guardar_reverso;
															delete 
															from
																	 movimientos_contables
															$where;
															$sql_cheques
															
															";
														//die($sql_borrar_integracion);
									if (!$conn->Execute($sql_borrar_integracion)) 
									
										die ('Error al Registrar: '.$conn->ErrorMsg());
									else	
										die("Registrado"."*".substr($numero_comprobante_integracion,8));
									
								
									}//fin si modulo=='4'//cheque tesoreria
							}									

/*

*/
			
								
				
					
}
else
{
	die("no_modulo");
}					
?>	