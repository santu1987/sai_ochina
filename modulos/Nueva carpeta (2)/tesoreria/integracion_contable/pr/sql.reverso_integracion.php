<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$where="WHERE integracion_contable.id_organismo = $_SESSION[id_organismo]	 ";
$where_int="WHERE cheques.id_organismo=$_SESSION[id_organismo]";

	$desde_num=$_POST["tesoreria_integracion_reverso_numero_c_desde2"];
	$hasta_num=$_POST["tesoreria_integracion_reverso_numero_c_hasta2"];
	$desde_fecha=$_POST["tesoreria_integracion_reverso_rp_fecha_desde"];
	$hasta_fecha=$_POST["tesoreria_integracion_reverso_rp_fecha_hasta"];
/*if(($desde_fecha!="")&&($hasta_fecha!=""))
{
	$where.=" and integracion_contable.fecha_comprobante >= '$desde_fecha' AND integracion_contable.fecha_comprobante <='$hasta_fecha'";
}
*/
if(($desde_num!="")&&($hasta_num==""))
{
	$where.="  and integracion_contable.numero_comprobante ='$desde_num'";
	$where_int.="  and cheques.numero_comprobante_integracion ='$desde_num'";
}else
{
///////////////////////////////////////////////////////////////////
$where.="  and integracion_contable.numero_comprobante >='$desde_num' and  integracion_contable.numero_comprobante <='$hasta_num'";
$where_int.="and cheques.numero_comprobante_integracion  >='$desde_num' and cheques.numero_comprobante_integracion <='$hasta_num'";
}
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$sql_integracion_contable="
							SELECT  distinct
									integracion_contable.cuenta_contable,
									integracion_contable.numero_comprobante,
									integracion_contable.secuencia,
									integracion_contable.descripcion,
									integracion_contable.referencia,
									integracion_contable.debito_credito,
									integracion_contable.monto_debito,
									integracion_contable.monto_credito,
									integracion_contable.fecha_comprobante ,
									integracion_contable.id_tipo_comprobante,
									tipo_comprobante.nombre as tipo,
									tipo_comprobante.codigo_tipo_comprobante,
									cuenta_contable_contabilidad.nombre as descripcion_cuenta,
									integracion_contable.id_auxiliar,
									integracion_contable.fecha_comprobante 
							from
									 integracion_contable 
									 
							INNER JOIN
											tipo_comprobante
									on
										integracion_contable.id_tipo_comprobante=tipo_comprobante.id											 
							INNER JOIN
										cuenta_contable_contabilidad
								on
									cuenta_contable_contabilidad.cuenta_contable=integracion_contable.cuenta_contable		
							INNER JOIN
										organismo
								on
										integracion_contable.id_organismo=organismo.id_organismo				
							$where
							order by
									
									integracion_contable.secuencia";
$row=& $conn->Execute($sql_integracion_contable);
if(!$row->EOF)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql_borrar_integracion="delete 
								from
										 integracion_contable
								$where;
						UPDATE
								cheques
								set
									contabilizado='0',
									fecha_contab='".date("Y-m-d H:i:s")."',
									usuario_contab='".$_SESSION['id_usuario']."',
									numero_comprobante_integracion='0',
									cuenta_contable_banco='$cuenta_contable'	
								$where_int";
								//die($sql_borrar_integracion);
	if (!$conn->Execute($sql_borrar_integracion)) die ('Error al Registrar: '.$conn->ErrorMsg());
	die("Registrado");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}else
die("no_reverso");
			
								
	?>				
					