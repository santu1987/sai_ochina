<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$vector_comprobante = split( ",",$_POST[ids_comprobante] );
sort($vector_comprobante);
$contador=count($ids_comprobante);  ///$_POST['covertir_req_cot_titulo']
//echo($contador);
$is=0;
$monto_total=0;
while($is < $contador)
{	
			$id_comprobante=$vector_comprobante[$is];
			$sql_comprobante="SELECT   movimientos_contables.id_movimientos_contables,
													   cuenta_contable_contabilidad.id,
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
													   movimientos_contables.ultimo_usuario,
													   movimientos_contables.id_organismo,
													   movimientos_contables.ultima_modificacion,
													   movimientos_contables.estatus,
													   movimientos_contables.id_accion_central,
													   movimientos_contables.id_auxiliar,
													   movimientos_contables.ano_comprobante,
													   tipo_comprobante.codigo_tipo_comprobante,
													   naturaleza_cuenta.codigo as codigo
													
												FROM movimientos_contables
												INNER JOIN
													tipo_comprobante	
												ON
													movimientos_contables.id_tipo_comprobante=tipo_comprobante.id
												INNER JOIN
													cuenta_contable_contabilidad
												ON 
													movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable		
												INNER JOIN
													naturaleza_cuenta
												ON
													cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
												where
													movimientos_contables.id_organismo = $_SESSION[id_organismo]
												and
													id_movimientos_contables=$id_comprobante
												
												";
							$row_comprobante=& $conn->Execute($sql_comprobante);
						//	die($sql_comprobante);
							/********************************************************************************************************************************************************************/
			if(!$row_comprobante->EOF)
			{
				
				$numero_comprobante=$row_comprobante->fields("numero_comprobante");
				$sql_comp="SELECT * FROM movimientos_contables where numero_comprobante='$numero_comprobante' and estatus='0'";
				//die($sql_comp);
				$row_comp=& $conn->Execute($sql_comp);
				if(!$row_comp->EOF)
				{
					$ano=$row_comprobante->fields("ano_comprobante");
					$sql = "UPDATE
							movimientos_contables
							set
							estatus='0'
							where	
							id_movimientos_contables='$id_comprobante'
							and	ano_comprobante='$ano'	;";
				}else
				die("abrir_comprobante");																		
			}
				else
				{	
					$responce="NoActualizo"."*".$debe."*".$haber."*".$comprobante."*".$resta;	
					die($responce);
				
					}			
															//		die($sql);
				if (!$conn->Execute($sql)) 
				{
					$responce='Error al Actualizar: '.$conn->ErrorMsg().$monto_saldo;
				}
				else
				{
						$responce="Registrado";
				}

																			
//
$is++;
}
die($responce);

			
?>