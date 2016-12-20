<?php
/* parche creado para pasar los saldos contables de un mes a otro*/
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha =date("Y-m-d H:i:s") ;
$ano=substr($fecha,0,4);
$mes=substr($fecha,5,2);
$sesion=1;
$comprobante='101000';
$sql_comprobante="select substr(movimientos_contables.numero_comprobante::varchar,9) as n_comp,movimientos_contables.id_movimientos_contables, movimientos_contables.ano_comprobante, movimientos_contables.mes_comprobante, movimientos_contables.id_tipo_comprobante, movimientos_contables.numero_comprobante, movimientos_contables.secuencia, movimientos_contables.comentario, movimientos_contables.cuenta_contable, movimientos_contables.descripcion, movimientos_contables.referencia, movimientos_contables.debito_credito, movimientos_contables.monto_debito, movimientos_contables.monto_credito, movimientos_contables.fecha_comprobante, movimientos_contables.id_auxiliar, movimientos_contables.id_unidad_ejecutora, movimientos_contables.id_proyecto, movimientos_contables.id_utilizacion_fondos, movimientos_contables.ultimo_usuario, movimientos_contables.id_organismo, movimientos_contables.ultima_modificacion, movimientos_contables.estatus, movimientos_contables.id_accion_central, cuenta_contable_contabilidad.id, cuenta_contable_contabilidad.id_cuenta_suma, naturaleza_cuenta.codigo AS codigo, tipo_comprobante.codigo_tipo_comprobante as tipo_comprobante from movimientos_contables inner join cuenta_contable_contabilidad on movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable inner join naturaleza_cuenta on cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id INNER JOIN tipo_comprobante on movimientos_contables.id_tipo_comprobante=tipo_comprobante.id where movimientos_contables.id_organismo = 1 and substr(movimientos_contables.numero_comprobante::varchar,9)='101000'";
											die($sql_comprobante);
						/*$row_comprobante=& $conn->Execute($sql_comprobante);
						while(!$row_comprobante->EOF)
						{
									$id_cuenta=$row_comprobante->fields("id");
									$id_cuenta_suma=$row_comprobante->fields("cuenta_suma");
									$id_sumas=$id_cuenta;
									$debe_ant=$row_comprobante->fields("monto_debito");
									$haber_ant=$row_comprobante->fields("monto_credito");
									$tipo_comprobante=$row_comprobante->fields("tipo_comprobante");
									$fecha_comprobante=$row_comprobante->fields("fecha_comprobante") ;
									$ano_comprobante=substr($fecha_comprobante,0,4);
									$mes_comprobante=substr($fecha_comprobante,5,2);
						}*/			

?>
