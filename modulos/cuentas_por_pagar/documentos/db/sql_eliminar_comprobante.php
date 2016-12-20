<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[cuentas_por_pagar_db_fecha_f];
$ano=substr($fecha,6,4);

$mes=substr($fecha,3,2);
$tipo_comp=$_POST[cuentas_por_pagar_integracion_tipo];
$saldol=0;
$id_comprobante=$_POST[cxp_id_comprobante];
$tipo_comprobante=$_POST[cuentas_por_pagar_integracion_tipo];
$id_auxiliar=$_POST[cuentas_por_pagar_integracion_id_aux];
$cuenta_auxiliar=$_POST[cuentas_por_pagar_integracion_auxiliar_p];
$comprobante_x=$_POST['cuentas_por_pagar_numero_pr_numero_comprobante2'];
$numer_comprobante_completo=$_POST['cuentas_por_pagar_numero_pr_numero_comprobante2'];
	
///die($id_auxiliar."-".$cuenta_auxiliar);
$sql_comprobante_monto="select 
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
								movimientos_contables.id_organismo,
								cuenta_contable_contabilidad.id,
								cuenta_contable_contabilidad.id_cuenta_suma,
								naturaleza_cuenta.codigo  AS codigo
								
						FROM
							 movimientos_contables
						INNER JOIN
						

									cuenta_contable_contabilidad
						on
							    cuenta_contable_contabilidad.cuenta_contable=movimientos_contables.cuenta_contable
						inner join
											naturaleza_cuenta
								on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id			 	 
						where
							 id_movimientos_contables='$id_comprobante'	 					
";
//die($sql_comprobante_monto);
$row_comprobante=& $conn->Execute($sql_comprobante_monto);
if(!$row_comprobante->EOF)
{
		//query que pone en estatus 3 las lineas del asiento
		$sql_eliminar_movimientos="UPDATE
										movimientos_contables
									set
										estatus='3'
									where	
										id_movimientos_contables='$id_comprobante'
									and
										ano_comprobante='$ano'	
													";			
	//ejecuto el query
		if ($conn->Execute($sql_eliminar_movimientos)) 
		{
			$n_comprobante=$numer_comprobante_completo;
			$n_comprobante2=substr($numer_comprobante_completo,10);
			$sql_eliminado="SELECT * FROM movimientos_contables where numero_comprobante='$numer_comprobante_completo' and estatus!='3' order by id_movimientos_contables";
			//die($sql_eliminado);
		$row_eliminado=& $conn->Execute($sql_eliminado);
		if(!$row_eliminado->EOF)
		{
		//
			$contadoress=1;
			while(!$row_eliminado->EOF)
			{
					$id_actual=$row_eliminado->fields("id_movimientos_contables");
					$sql_update="UPDATE 
										movimientos_contables
								SET
										secuencia='$contadoress'
								where
										id_movimientos_contables='$id_actual'";
					if($contadoress==1)
						$sql_updatex=$sql_update;					
					else
						$sql_updatex=$sql_updatex.";".$sql_update;					
					
					$contadoress=$contadoress+1;
					$row_eliminado->MoveNext();
			}
			//- actualizando la secuencia: no deeria dar error ya q es solo actualizar las secuencias de los q no elimino
			if (!$conn->Execute($sql_updatex)) 
			{
				$responce='Error al Actualizar: '.$conn->ErrorMsg();
				die($responce);
			}
		////
		}//fin if(!$row_eliminado->EOF)	
		else
		if($row_eliminado->EOF)
		{
				$n_comprobante="";
				$n_comprobante2="";
				$id_doc=$_POST['cuentas_por_pagar_db_id'];
				$sql_doc="
						UPDATE
								documentos_cxp
						set
								numero_comprobante='0'
						WHERE
								numero_comprobante='$comprobante_x'";
				if (!$conn->Execute($sql_doc)) 
				{
					die("error a actualizar documento_ si elimino".$sql_doc);
				}//FIN DE 	if (!$conn->Execute($sql_doc)) 	
		}
		//////////////////////////////////////////////////////////////
		//consulto las smas por debe/haber
		$sql_sumas=" SELECT
								SUM(monto_debito) as debe,
								SUM(monto_credito) as haber
							from
								movimientos_contables
							where numero_comprobante='$comprobante_x'
							and
			movimientos_contables.id_tipo_comprobante='$_POST[cuentas_por_pagar_integracion_tipo_id]'
			and
			estatus!='3';
			
			";
		//	die($sql_sumas);
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
				$debe=number_format($row_sumas->fields("debe"),2,',','.');
				$haber=number_format($row_sumas->fields("haber"),2,',','.');
				$resta=round($row_sumas->fields("debe"),2)-round($row_sumas->fields("haber"),2);
				$resta=number_format($resta,2,',','.');
				$responce="Eliminado"."*".$debe."*".$haber."*".$resta."*".$n_comprobante."*".$n_comprobante2;
				die($responce);
			}//if(!$row_sumas->EOF)
			else
			die("elimino_con_errores");
		///////////////////////////////////////////////////////////////
		}//fin if ($conn->Execute($sql_eliminar_movimientos)) 
		else
		{
		echo("ExisteRelacion");
		$responce=$sql_eliminar_movimientos."*".$debe."*".$haber."*".$resta;
				die($responce);
		}	 												
}//fin de if(!$row_comprobante->EOF)
?>