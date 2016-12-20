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
$anot=substr($fecha2,0,4);
$mest=substr($fecha2,5,2);
$diat=substr($fecha2,8,2);

//
$debe=0;
$haber=0;
$resta=0;
$comprobante_text=$_POST[contabilidad_comp_pr_numero_comprobante2];
$comprobante_ant=$comprobante_text;


if($comprobante_text!="")
{
$comprobante=$_POST[contabilidad_comp_pr_numero_comprobante2];
$tipo=$_POST[contabilidad_comp_pr_tipo];
/////VERIFICANDO SI EL USUARIO CAMBIO O NO LA FECHA O EL TIPO DE COMPROBANTE
//
$tipo_ant_v=substr($comprobante,8,2);
$mes_ant_v=substr($comprobante,10,2);
//die($mes_ant_v."".$tipo_ant_v);
if ((($mes_ant_v!=$mes)&&($tipo_ant_v!=$tipo))or(($mes_ant_v!=$mes)or($tipo_ant_v!=$tipo)))
{
	$comprobante_text="";
	//$mes=$mes_ant_v;
}
//
//////////////////////////////////////////////////////////////////////////////
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
			//echo($secuencia);
	//		$comprobante=$tipo.$comprobante;

}else
if($comprobante_text=="")
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
								ano_comprobante='$ano'
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
	$comprobante=$anot.$mest.$diat.$comprobante;	
	//echo($comprobante);
	///////////proceso de verificacion si el comprobante ya fue creado claves_comprobante y secuencia
	// si el codigo esta perfecto esto no deberia de pasar..si ocurre un error evita q se cree el comprobante....
	$sql_comp_prueba="SELECT * FROM movimientos_contables where numero_comprobante='$comprobante' and secuencia='$secuencia'";
	//die($sql_comp_prueba);
	$rs_comp_prueba =& $conn->Execute($sql_comp_prueba);
	if(!$rs_comp_prueba->EOF)
	{
		$mensaje="error_num_comprobante";
		$responce=$mensaje."*".$comprobante."*".$secuencia;
		die($responce);
	}
	/////////
//proceso para cambiarle el numero de comprobante solo s se realizo un cambio de fecha
	//die($comprobante);
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
			//die($comprobante);
					$sql_act_comp="UPDATE
											tipo_comprobante	
										set
											numero_comprobante='$sig_comp'
										where
											
											id='$_POST[contabilidad_comp_pr_tipo_id]'
								";
							
}
			/*else
			{
				die("numero_comprobante");
			}*/
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
																			estatus,
																			comprobante_ant,
																			fecha_ant  
																			
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
																			 '0',
																			 '$comprobante',
																			 '".date("Y-m-d H:i:s")."'
																		);
																
																$sql_update;

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