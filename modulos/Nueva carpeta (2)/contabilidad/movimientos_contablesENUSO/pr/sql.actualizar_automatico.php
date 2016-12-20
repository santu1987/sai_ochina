<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha =$_POST[contabilidad_comprobante_pr_fecha];
$ano=substr($fecha,6,4);
$mes=substr($fecha,2,2);
//
$debe=0;
$haber=0;
//
$comprobantex=$_POST[contabilidad_comprobante_pr_tipo].$_POST[contabilidad_comprobante_pr_numero_comprobante_oculto];
$comprobantex2=$_POST[contabilidad_comprobante_pr_tipo].$_POST[contabilidad_comprobante_pr_numero_comprobante];
$monto = str_replace(".","",$_POST[contabilidad_comprobante_pr_monto]);
$monto2 = str_replace(",",".",$monto);
$debe_haber=$_POST[contabilidad_comprobante_pr_debe_haber];
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
$contabilidad_comp_pr_ubicacion=$_POST[contabilidad_comprobante_pr_ejec_id];
$contabilidad_comp_pr_centro_costo=$_POST[contabilidad_pr_centro_costo_id];
$contabilidad_comp_pr_auxiliar=$_POST[contabilidad_comprobante_contabilidad_id];
$contabilidad_comp_pr_utf=$_POST[contabilidad_comprobante_pr_utf_id];
$contabilidad_comp_pr_acc=$_POST[contabilidad_comprobante_pr_acc_id];
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
			$sql_comprobante="select *
							 from 
									integracion_contable
								where
								
							integracion_contable.id_organismo = $_SESSION[id_organismo]
							and
							integracion_contable.id=$_POST[contabilidad_vista_id_comprobante]								
							and
							ano_comprobante='$ano'
								";
							//	die($sql_comprobante);
			$row_comprobante=& $conn->Execute($sql_comprobante);
			if(!$row_comprobante->EOF)
			{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-verficando si la fecha del comprobante le permite al mismo ser modificado luego del proceso de cierre.....
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
$fecha_actual = date("Y-m-d H:i:s");	
$fecha_comprobante=substr($row_comprobante->fields("fecha_comprobante"),0,10);

$sqlfecha_cierre = "SELECT  fecha_cierre_anual,fecha_cierre_mensual FROM parametros_contabilidad WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//die($sqlfecha_cierre);
if(!$row_fecha_cierre->EOF){
	$fecha_cierre_anual = substr($row_fecha_cierre->fields('fecha_cierre_anual'),0,10);
	$fecha_cierre_mensual =substr($row_fecha_cierre->fields('fecha_cierre_mensual'),0,10);
}
list($ano1,$mes1,$dia1)=split("-",$fecha_cierre_mensual);
list($ano2,$mes2,$dia2)=split("-",$fecha_comprobante);
list($ano3,$mes3,$dia3)=split("-",$fecha_cierre_anual);
$ano_actual=substr($fecha_actual,0,4);


//
if($ano2!=$ano_actual)
{
	die("no_ano");
}
if(($dia2 <= $dia1) && ($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	$cerrado="mes";
}else
if(($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	$cerrado="mes";
}

//
/*if(($dia2 >= $dia1) && ($mes2 >= $mes1) && ($ano2>= $ano1))
{
	$cerrado="mes";
	
}else
if(($mes2 >= $mes1) && ($ano2 >= $ano1))
{

	$cerrado="mes";//	die($mes2."-".$mes1."-".$ano2."-".$ano1);
		///die("entrro"); 

}
if(($dia2 >= $dia3) && ($mes2>= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
die($mes3."-".$mes2);die($fecha_cierre_anual);

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
	/*echo($dia1."-".$mes1."-".$ano1."cierre");
die($dia2."-".$mes2."-".$ano2."comp");

	die($cerrado);*/
				
				//VERIFICANDO SI EL DOCUMENTO ESTA ABIERTO
			/*	if($row_comprobante->fields("estatus")==1)
				{
					$responce="documento_cerrado"."*".$debe."*".$haber;	
					die($responce);
				}*/
//////////////////////////////////////********************************************************************************************************************************************************************************************************************************
									if(!$row->EOF)
										{
												$sql="
														UPDATE	
																integracion_contable
														set
															cuenta_contable='$_POST[contabilidad_comprobante_pr_cuenta_contable]',
															referencia='$_POST[contabilidad_comprobante_pr_ref]',
															debito_credito='$debe_haber',
															descripcion='$_POST[contabilidad_comprobante_pr_desc]',
															monto_debito=$monto_debito,
															monto_credito=$monto_credito,
															id_unidad_ejecutora=$contabilidad_comp_pr_ubicacion,
															id_proyecto=$contabilidad_comp_pr_centro_costo,
															id_utilizacion_fondos=$contabilidad_comp_pr_utf,
															id_auxiliar=$contabilidad_comp_pr_auxiliar,
															id_accion_central='$contabilidad_comp_pr_acc',
															fecha_comprobante='$fecha',
															ultimo_usuario= ".$_SESSION['id_usuario'].",
															fecha_actualizacion= '".date("Y-m-d H:i:s")."'
														WHERE	
															integracion_contable.id=$_POST[contabilidad_vista_id_comprobante]
															and
															ano_comprobante='$ano'
															;								
														UPDATE	
																integracion_contable
														set
															descripcion='$_POST[contabilidad_comprobante_pr_desc]',
															fecha_comprobante='$fecha',
															ultimo_usuario= ".$_SESSION['id_usuario'].",
															fecha_actualizacion= '".date("Y-m-d H:i:s")."'
														WHERE	
															integracion_contable.numero_comprobante='$_POST[ncomp2]'								
														and
															ano_comprobante='$ano'									";	
											}												
											else
											{	
												
												$responce="NoActualizo"."*".$debe."*".$haber;	
												die($responce);
											
												}			
										//die($sql);
											if (!$conn->Execute($sql)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
												die($responce);
											}
											else
											{
													$responce="Actualizado";
													die($responce);
												}
											
	}//fin de if verificacion comprobante abierto											
			}else
			{
			$responce="numero_existe";
				die($responce);
			}
			
?>




















