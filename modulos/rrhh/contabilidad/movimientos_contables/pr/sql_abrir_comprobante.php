<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sql_dos="";
$comprobante_x=$_POST[contabilidad_comp_pr_tipo].$_POST[contabilidad_comp_pr_numero_comprobante];

$sql_sumas=" SELECT
					SUM(monto_debito) as debe,
					SUM(monto_credito) as haber,
					fecha_comprobante
				from
					movimientos_contables
				where numero_comprobante='$comprobante_x'
				and
					(id_organismo = ".$_SESSION['id_organismo'].")
				group by fecha_comprobante
												";
				//die($sql_sumas);								
$row_sumas=& $conn->Execute($sql_sumas);
	if(!$row_sumas->EOF)
	{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-verficando si la fecha del comprobante le permite al mismo ser modificado luego del proceso de cierre.....
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
$fecha_comprobante=substr($row_sumas->fields("fecha_comprobante"),0,10);

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

if(($dia2 <= $dia1) && ($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	$cerrado="mes";
}else
if(($mes2 <= $mes1) && ($ano2 <= $ano1))
{
	$cerrado="mes";
}
/*if(($dia2 >= $dia3) && ($mes2 >= $mes3) && ($ano2 >= $ano3))
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
		
		
			
				$sql_cerrar="UPDATE
									movimientos_contables	
								set
							 estatus='0'
							 where
							 	numero_comprobante='$comprobante_x'
							and
																
movimientos_contables.id_tipo_comprobante='$_POST[contabilidad_comp_pr_tipo_id]'		
							;
							 ";
							 
					if (!$conn->Execute($sql_cerrar)) 
						die ('Error al Actualizar: '.$conn->ErrorMsg());
					else
						die("Abierto");	
			
}
	}else
	die("NoActualizo");
?>