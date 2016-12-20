<?php
session_start();
ini_set("memory_limit","20M");

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/fecha_contabilidad.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[contabilidad_comp_pr_fecha];
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
$dia=substr($fecha,0,2);

$tipo_saldo=$_POST[contabilidad_comp_pr_tipo];
//
$debe=0;
$haber=0;
$resta=0;
$cerrado="";
//////////////////////////////////-PROCES EN CASO DE QUE EL USUARIO MODIFIQE LA FECHA O TIPO DE COMPROBANTE
$comprobante=$_POST[contabilidad_comp_pr_numero_comprobante2];
$tipo=$_POST[contabilidad_comp_pr_tipo];
$comprobante_ant=$_POST[contabilidad_comp_pr_numero_comprobante2];
/////VERIFICANDO SI EL USUARIO CAMBIO O NO LA FECHA O EL TIPO DE COMPROBANTE
//
$tipo_ant_v=substr($comprobante,8,2);
$mes_ant_v=substr($comprobante,10,2);
$comprobante_x=$comprobante;
//echo($mes_ant_v."".$tipo_ant_v);
if (($mes_ant_v!=$mes)&&($tipo_ant_v!=$tipo)or($mes_ant_v!=$mes)or($tipo_ant_v!=$tipo))
{

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
		//	die($sql_num);
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
		//	die($comprobante);
			//proceso para cambiarle el numero de comprobante solo s se realizo un cambio de fecha
			$comprobante_x=$comprobante;
			//echo($comprobante_ant);
		//	die("h");
			if($comprobante_ant!="")
			{
			$sql_update="UPDATE movimientos_contables
						   SET 
							   numero_comprobante='$comprobante',
							   mes_comprobante='$mes',
						  	   ano_comprobante='$ano',
							   id_tipo_comprobante='$_POST[contabilidad_comp_pr_tipo_id]',
							   referencia='$_POST[contabilidad_comp_pr_ref]',
							   fecha_comprobante='".$fecha."', 
							   ultimo_usuario= ".$_SESSION['id_usuario'].", 
							   ultima_modificacion='".date("Y-m-d H:i:s")."'
						 WHERE numero_comprobante='$comprobante_ant';";
			
			}else
			$sql_update="";
//die($sql_update);
}//FIN DE IF if ((($mes_ant_v!=$mes)&&($tipo_ant_v!=$tipo))or(($mes_ant_v!=$mes)or($tipo_ant_v!=$tipo)))


//
//
;
//$comprobante_x=$_POST['contabilidad_comp_pr_numero_comprobante2'];
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
$debe_haber_op=$_POST[contabilidad_comp_pr_debe_haber];
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
			$sql_comprobante="select 
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
										cuenta_contable_contabilidad.id as cuenta_contable_id,
										cuenta_contable_contabilidad.id_cuenta_suma as cuenta_contable_id_suma,
										tipo_comprobante.codigo_tipo_comprobante as codigo_tipo
							 from 
									movimientos_contables
							inner join
									cuenta_contable_contabilidad
							ON
									movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable				
							inner join
									tipo_comprobante
							ON
									movimientos_contables.id_tipo_comprobante=tipo_comprobante.id		
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$id_cc_g=$_POST[contabilidad_auxiliares_db_id_cuenta];
$id_aux_g=$_POST[contabilidad_comp_contabilidad_id];
/////////			
$cuenta_contable_inicial=$row_comprobante->fields("cuenta_contable_id");
$id_auxiliar=$row_comprobante->fields("id_auxiliar");
$cuenta_contable_inicial_suma=$row_comprobante->fields("cuenta_contable_id_suma");
$debito_credito=$row_comprobante->fields("debito_credito");
$tipo_comp=$row_comprobante->fields("codigo_tipo");
$fecha_comprobante=substr($row_comprobante->fields("fecha_comprobante"),0,10);

$ano=substr($fecha_comprobante,0,4);
$mes=substr($fecha_comprobante,5,2);
//echo($ano_comprobante);
$fecha_comprobante_pagina=$_POST[contabilidad_comp_pr_fecha];
$ano_comprobante2=substr($fecha_comprobante_pagina,6,4);
$mes_comprobante2=substr($fecha_comprobante_pagina,3,2);


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
//echo($dia1."-".$mes1."-".$ano1."cierre".$cerrado);
//die($mes2.">=".$mes1);*/
if(($dia2 <= $dia1) && ($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	$cerrado="mes";
}else
if(($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	$cerrado="mes";
}
/*if(($dia2 <= $dia3) && ($mes2<= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}*/
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			
				//VERIFICANDO SI EL DOCUMENTO ESTA ABIERTO
				if($row_comprobante->fields("estatus")==1)
				{
					$responce="documento_cerrado"."*".$debe."*".$haber;	
					die($responce);
				}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if(!$row->EOF)
						{

///////////////////////////////////////////////verificando el codig de la cuenta contable
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
							 cuenta_contable_contabilidad.id='$cuenta_contable_inicial'
							"
							;
				//die($sql_tipo);			
	$rs_tipo_c=& $conn->Execute($sql_tipo);
	if (!$rs_tipo_c->EOF) 
	{
		$codigo=$rs_tipo_c->fields("codigo");
	}						
/************************************/
					
													
						
					
		////////////////////////////////LA MODIFICACION A LA TABLA DE MOVIMIENTOS CNTABLES
								$sql_up="UPDATE	
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
															fecha_comprobante='".$fecha_comprobante_pagina."',
															ultimo_usuario= ".$_SESSION['id_usuario'].",
															ultima_modificacion= '".date("Y-m-d H:i:s")."',
															comentario='$_POST[contabilidad_comp_pr_comentarios]',
															descripcion='$_POST[contabilidad_comp_pr_desc]',
															ano_comprobante='$ano_comprobante2',
															mes_comprobante='$mes_comprobante2'
														WHERE	
															movimientos_contables.id_movimientos_contables=$_POST[contabilidad_comp_id_comprobante];								
																												$sql_update								
														";	
															
													//
										//die($sql_up);		
						////////////////////////////////////////////////////////////////
						}//fin si(!$row->EOF)*PRINCIPAL*												
											else
											{	
												
												$responce="NoActualizo"."*".$debe."*".$haber;	
												die($responce);
											
												}			
											//die($sql);
											if (!$conn->Execute($sql_up)) 
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
and
																	movimientos_contables.estatus!='3'	
																and
																ano_comprobante='$ano'
												";
											//	die($sql_sumas);
												$row_sumas=& $conn->Execute($sql_sumas);
												if(!$row_sumas->EOF)
												{
													$debe=number_format($row_sumas->fields("debe"),2,',','.');
													$haber=number_format($row_sumas->fields("haber"),2,',','.');
													$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
													$resta=number_format($resta,2,',','.');
													$responce="Registrado"."*".$debe."*".$haber."*".$resta."*".$comprobante."*".substr($comprobante,10);
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