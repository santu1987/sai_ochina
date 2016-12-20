<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
//
$ano=substr($fecha,0,4);
$mes=substr($fecha,5,2);
$debe=0;
$haber=0;
$secuencia=1;
//
$sql_numero_comprobante="
						select
								numero_comprobante
							from
								 numeracion_comprobante
						where
								numero_comprobante='$_POST[contabilidad_comprobante_pr_numero_comprobante]'		 			

";
//die($sql_numero_comprobante);
$row_ncomprobante=& $conn->Execute($sql_numero_comprobante);
//die($sql_numero_comprobante);
if($row_ncomprobante->EOF)
{
	    $sql_datos="SELECT 
					integracion_contable.id,
					integracion_contable.id_organismo,
					integracion_contable.ano_comprobante,
					integracion_contable.mes_comprobante,
					integracion_contable.id_tipo_comprobante,
					integracion_contable.numero_comprobante,
					integracion_contable.secuencia,
					integracion_contable.cuenta_contable,
					integracion_contable.descripcion,
					integracion_contable.referencia,
					integracion_contable.debito_credito,
					integracion_contable.monto_debito,
					integracion_contable.monto_credito,
					integracion_contable.fecha_comprobante,
					integracion_contable.id_auxiliar,
					integracion_contable.id_unidad_ejecutora,
					integracion_contable.id_proyecto,
					integracion_contable.id_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id as id_cc				
				FROM 
					integracion_contable
				inner join
						organismo
						on
						integracion_contable.id_organismo=integracion_contable.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				integracion_contable.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
							
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	
				AND
					numero_comprobante='$_POST[contabilidad_comprobante_pr_numero_comprobante_oculto]'		 			
				";	
			//	die($sql_datos);
			$row_comp=& $conn->Execute($sql_datos);
			while(!$row_comp->EOF)
			{
							//verificando datos
								$debe_haber=$row_comp->fields("debito_credito");
								$monto_debito=$row_comp->fields("monto_debito");
								$monto_credito=$row_comp->fields("monto_credito");
											
							$contabilidad_comp_pr_ubicacion=$row_comp->fields("id_unidad_ejecutora");
							$contabilidad_comp_pr_centro_costo=$row_comp->fields("id_proyecto");
							$contabilidad_comp_pr_auxiliar=$row_comp->fields("id_auxiliar");
							$contabilidad_comp_pr_utf=$row_comp->fields("id_utilizacion_fondos");
							if($contabilidad_comp_pr_ubicacion=="")
								$contabilidad_comp_pr_ubicacion=0;
							if($contabilidad_comp_pr_centro_costo=="")
								$contabilidad_comp_pr_centro_costo=0;
							if($contabilidad_comp_pr_auxiliar=="")
								$contabilidad_comp_pr_auxiliar=0;
							if($contabilidad_comp_pr_utf=="")
								$contabilidad_comp_pr_utf=0;
							
							//////////llenando variables para realizar el pase entre tablas///////////////////////////*********************************************************************************************************************************************************************************************************************
								$tipo_id=$row_comp->fields("id_tipo_comprobante");
								$cuenta_contable=$row_comp->fields("cuenta_contable");
								$descripcion=$row_comp->fields("descripcion");
								$ref=$row_comp->fields("referencia");
								
								$fecha_comprobante=$row_comp->fields("fecha_comprobante");
								
							//////////////////////////////////////*********************************************************************************************************************************************************************************************************************************
							
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
																										'$_POST[contabilidad_comprobante_pr_numero_comprobante]',
																										$secuencia,
																										'$ano',
																										'$mes',
																										'$tipo_id',
																										'$_POST[contabilidad_comprobante_pr_comentarios]',
																										'$cuenta_contable',
																										'$descripcion',
																										'$_POST[contabilidad_comprobante_pr_ref]',
																										'$debe_haber',
																										$monto_debito,
																										$monto_credito,
																										$contabilidad_comp_pr_ubicacion,
																										$contabilidad_comp_pr_centro_costo,
																										$contabilidad_comp_pr_auxiliar,
																										$contabilidad_comp_pr_utf,
																										'".date("Y-m-d H:i:s")."',
																										 ".$_SESSION['id_usuario'].",
																										 '".date("Y-m-d H:i:s")."',
																										 '0'
																									);
																									UPDATE
																										numeracion_comprobante	
																									set
																										numero_comprobante='$_POST[contabilidad_comprobante_pr_numero_comprobante]'
																							";
																							
																							
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
																			
																	//	die($sql);
																		if (!$conn->Execute($sql)) 
																		{
																			$responce='Error al Actualizar: '.$conn->ErrorMsg();
																			//$responce=$resp."*".$debe."*".$haber;
																			die($responce);
																		}
																		else
																		{	
																			$responce="Registrado"."*".$debe."*".$haber;
																			$entro=$entro+1;
																		}	
										/*}else
										{
										$responce="numero_existe"."*".$debe."*".$haber;
											die($responce);
										}*/
			$secuencia=$secuencia+1;
			$row_comp->MoveNext();
			}
//

}
else
{
	die("existe");
}			
?>