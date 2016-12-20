<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$comprobante_x=$_POST[contabilidad_comp_pr_numero_comprobante2];
$sql_sumas=" SELECT
					SUM(monto_debito) as debe,
					SUM(monto_credito) as haber
				from
					movimientos_contables
				where numero_comprobante='$comprobante_x'
				and
				estatus='1'
												";
$row_sumas=& $conn->Execute($sql_sumas);
	if(!$row_sumas->EOF)
	{
			$debe=number_format($row_sumas->fields("debe"),2,',','.');
			$haber=number_format($row_sumas->fields("haber"),2,',','.');
			if($debe!=$haber)
			{
				die("disparejo");
			}else
			if($debe==$haber)
			{
				$sql_cerrar="UPDATE
									movimientos_contables	
								set
							 estatus='1'
							 where
							 	numero_comprobante='$comprobante_x'	
							and
																	
movimientos_contables.id_tipo_comprobante='$_POST[contabilidad_comp_pr_tipo_id]'	
and
estatus!='3'
							 ";
					if (!$conn->Execute($sql_cerrar)) 
						die ('Error al Actualizar: '.$conn->ErrorMsg());
					else
						die("cerrado");	
			}
	}else
	die("NoActualizo");
?>