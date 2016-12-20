<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = $_POST[contabilidad_comp_pr_fecha];
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
$tipo_comp=$_POST[contabilidad_comp_pr_tipo];
$saldol=0;
$id_comprobante=$_POST[contabilidad_comp_id_comprobante];
$tipo_comprobante=$_POST[contabilidad_comp_pr_tipo];
$id_auxiliar=$_POST[contabilidad_comp_contabilidad_id];
$cuenta_auxiliar=$_POST[contabilidad_comp_pr_auxiliar];
$comprobante_x=$_POST['contabilidad_comp_pr_tipo'].$_POST['contabilidad_comp_pr_numero_comprobante'];
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

		$id_cuenta=$row_comprobante->fields("id");
		$id_sumas=$row_comprobante->fields("id_cuenta_suma");
		$sql_eliminar_movimientos="DELETE 	from movimientos_contables where id_movimientos_contables='$id_comprobante' AND	ano_comprobante='$ano';
												
												";				
					
					
//					die($sql_eliminar_movimientos);	
		if ($conn->Execute($sql_eliminar_movimientos)) 
		{
			//proceso para reestablecer los numeros correlativos de secuencia
			
			//die("Eliminado");
			$sql_sumas=" SELECT
								SUM(monto_debito) as debe,
								SUM(monto_credito) as haber
							from
								movimientos_contables
							where numero_comprobante='$comprobante_x'
							and
								
			movimientos_contables.id_tipo_comprobante='$_POST[contabilidad_comp_pr_tipo_id]'
				ano_comprobante='$ano'
			
								
			";
			
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
				$debe=number_format($row_sumas->fields("debe"),2,',','.');
				$haber=number_format($row_sumas->fields("haber"),2,',','.');
				$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
				$resta=number_format($resta,2,',','.');
				$responce="Eliminado"."*".$debe."*".$haber."*".$resta;
				die($responce);
			}
			else
			die("elimino_con_errores");
			
												
		
		
		}else
		{
		echo("ExisteRelacion");
		$responce=$sql_eliminar_movimientos."*".$debe."*".$haber."*".$resta;
				die($responce);
		}	
			
}
?>			