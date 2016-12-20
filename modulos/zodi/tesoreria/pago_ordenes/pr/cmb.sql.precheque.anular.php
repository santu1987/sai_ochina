<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$opcion=$_POST[tesoreria_cheque_anular_db_tipo];
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
if($opcion=='1')
{
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
						ordenes,
						cheques.secuencia,
						porcentaje_islr,
						base_imponible,
						fecha_firma,
						estado,
						estado_fecha,
						sustraendo,
						contabilizado
					FROM 
						cheques
					INNER JOIN
						organismo
					ON
					cheques.id_organismo=organismo.id_organismo	
					WHERE
						cheques.id_cheques='$_POST[tesoreria_cheque_anular_pr_id_cheque]' 
					AND
						cheques.numero_cheque='$_POST[tesoreria_cheque_anular_pr_n_cheque]'
					AND
						cheques.secuencia='$_POST[tesoreria_cheque_anular_pr_secuencia]'
					AND
						cheques.id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'	
					AND	
						cheques.cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
					AND	
						cheques.id_organismo=".$_SESSION["id_organismo"]."	
					";
}else
	if($opcion=='2')
		{
				$sql = "
							SELECT 
								id_cheques,
								id_banco,
								cuenta_banco,
								numero_cheque,
								tipo_cheque,
								cedula_rif_beneficiario,
								nombre_beneficiario,	
								monto_cheque,
								concepto,
								estatus,
								comentarios,
								porcentaje_itf,
								cheques.id_organismo,
								fecha_cheque,
								ordenes,
								cheques.secuencia,
								porcentaje_islr,
								base_imponible,
								fecha_firma,
								estado,
								estado_fecha,
								sustraendo,
								contabilizado
							FROM 
								cheques
							INNER JOIN
								organismo
							ON
							cheques.id_organismo=organismo.id_organismo	
							WHERE
								cheques.id_cheques='$_POST[tesoreria_cheque_anular_pr_id_cheque]' 
							AND
								cheques.numero_cheque='$_POST[tesoreria_cheque_anular_pr_n_cheque]'
							AND
								cheques.secuencia='$_POST[tesoreria_cheque_anular_pr_secuencia]'
							AND
								cheques.id_banco='$_POST[tesoreria_cheque_anular_pr_banco_id_banco]'	
							AND	
								cheques.cuenta_banco='$_POST[tesoreria_cheque_anular_pr_n_cuenta]'
							AND	
								cheques.id_organismo=".$_SESSION["id_organismo"]."	
							";
		}				
//die($sql);
$row= $conn->Execute($sql);
if(!$row->EOF)
	{	
		
		/////
		if($row->fields("contabilizado")==1)					
				die("integrado");
			else
				$contabilizado=$row->fields("contabilzado");
		if (strlen($contabilizado)==0)
		{
			$contabilizado=0;
		}
		/////
			 $sql_ultimo_negativo = "SELECT 
										numero_cheque
									FROM 
										cheques
									WHERE 
										numero_cheque<0 
									AND	
										cheques.id_organismo=".$_SESSION["id_organismo"]."
							
									order by 
										numero_cheque asc limit 1 ";
			$row_negativo= $conn->Execute($sql_ultimo_negativo);
				if(!$row_negativo->EOF)	
				{
					$n_cheque=$row_negativo->fields("numero_cheque");
					$n_precheque=$n_cheque-1;
				}
				else
					{
						$n_precheque=-1;
				 }
				 	if($opcion=='1')
					{
					  $id_proveedor=$row->fields("id_proveedor");
					}
					if($opcion=='2')
					{
						$beneficiario=$row->fields("nombre_beneficiario");
						$rif=$row->fields("cedula_rif_beneficiario");
					}	
				$id_banco=$_POST[tesoreria_cheque_anular_pr_banco_id_banco];
				$cuenta_banco=$_POST[tesoreria_cheque_anular_pr_n_cuenta];
				 //$numero_cheque=$row->fields("numero_cheque");
				 $tipo_cheque=$row->fields("tipo_cheque");
				// $monto_cheque=$_POST[tesoreria_cheque_anular_pr_monto_pagar];
				 $monto_cheque = str_replace(".","",$_POST[tesoreria_cheque_anular_pr_monto_pagar]);

				 $concepto=$row->fields("concepto");
				 $estatus=$row->fields("estatus");
			
				 $porcentaje_itf=$row->fields("porcentaje_itf");
				 $id_organismo=$row->fields("id_organismo");
				 $fecha_cheque=$row->fields("fecha_cheque");
				 $fecha_firma=$row->fields("fecha_firma");
				
				 $usuario_cheque=$_SESSION['id_usuario'];
				 $fecha_ultima_modificacion=date("Y-m-d H:i:s");
				 $ultimo_usuario=$_SESSION['id_usuario'];
				 $secuencia=$row->fields("secuencia");
				 $porcentaje_islr=$row->fields("porcentaje_islr");
				 $base_imponible=$row->fields("base_imponible");
				 $estado=$row->fields("estado");
				 $estado_fecha=$row->fields("estado_fecha");
				 $sustraendo=$row->fields("sustraendo");
				 
				
		if($opcion=='1')
		{	
					$sql = "	
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
													 $id_banco,
													 '$cuenta_banco',
													 $n_precheque,
													 $tipo_cheque,
													 $id_proveedor,
													 '".str_replace(",",".",$monto_cheque)."',
													 '$concepto',
													 '1',
													 $porcentaje_itf,
													 $id_organismo,
													 '".date("Y-m-d H:i:s")."',
													'{".$_POST[tesoreria_cheque_anular_pr_ordenes]."}',
													".$_SESSION['id_usuario'].",
													'".date("Y-m-d H:i:s")."',
													 ".$_SESSION['id_usuario'].",
													 '".str_replace(",",".",$porcentaje_islr)."',
													 '".$fecha_firma."'	,
													  '".str_replace(",",".",$base_imponible)."',
													  '".$estado."',
													  '".$estado_fecha."',
													  '$sustraendo',
													  $contabilizado						
													)
										";
			}
			else
			if($opcion=='2')
			{	
					$sql = "	
											INSERT INTO 
												cheques
												(
													id_banco,
													cuenta_banco,
													numero_cheque,
													tipo_cheque,
													cedula_rif_beneficiario,
													nombre_beneficiario,	
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
													 $id_banco,
													 '$cuenta_banco',
													 $n_precheque,
													 $tipo_cheque,
													 $rif,
													 '$beneficiario',
													 '".str_replace(",",".",$monto_cheque)."',
													 '$concepto',
													 '1',
													 $porcentaje_itf,
													 $id_organismo,
													 '".date("Y-m-d H:i:s")."',
													'{".$_POST[tesoreria_cheque_anular_pr_ordenes]."}',
													".$_SESSION['id_usuario'].",
													'".date("Y-m-d H:i:s")."',
													 ".$_SESSION['id_usuario'].",
													 '".str_replace(",",".",$porcentaje_islr)."',
													 '".str_replace(",",".",$base_imponible)."',
													  '".$fecha_firma."',
													  '".$estado."',
													  '".$estado_fecha."',
													  '$sustraendo',
													  $contabilizado		
													)
										";
			}
		//
										
		}
		else
			echo("NoRegistro");
			//die($sql);
		if (!$conn->Execute($sql)) 
			//die ('Error al Actualizar: '.$conn->ErrorMsg());
			die ('Error al RegistrarHHH: '.$sql);
		else
		{//----------------------busqueda para guardar en la tabla de orden de pago-----------------------------
				if($_POST[tesoreria_cheque_anular_pr_ordenes]!="")
				{
						$vector = split( ",",$_POST[tesoreria_cheque_anular_pr_ordenes]);
						
						$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
						$i=0;
						while($i < $contador)
						{
								$sql_orden="UPDATE orden_pago
											SET
													cheque='$n_precheque',	
													id_banco='$id_banco',
													cuenta_banco='$cuenta_banco',
													secuencia='0'
											WHERE
													(orden_pago.id_orden_pago='$vector[$i]')
														
											";	
						
								//echo($sql_orden);
								$i=$i+1;	
								if (!$conn->Execute($sql_orden)) 
											die ('Error al registrar: '.$sql_orden);
												//die ('Error al Actualizar: '.$conn->ErrorMsg());
												//die ("NoActualizo");
						}
					}			
							
		//-----------------------------------------------------------
		}	
			
	
			die("ANULADO");
     //}
?>