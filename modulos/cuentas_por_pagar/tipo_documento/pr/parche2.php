<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT id, id_organismo, ano_comprobante, mes_comprobante, id_tipo_comprobante, 
       numero_comprobante, secuencia, comentario, cuenta_contable, descripcion, 
       referencia, monto_debito, monto_credito, fecha_comprobante, id_auxiliar, 
       id_unidad_ejecutora, id_proyecto, id_utilizacion_fondos, ultimo_usuario, 
       fecha_actualizacion, debito_credito, numero_comprobante_movimientos, 
       estatus, id_accion_central
  FROM integracion_contable;";
//*********
$row=& $conn->Execute($sql);
while (!$row->EOF)
{
	$fecha=$row->fields("fecha_comprobante");

	$dia=substr($fecha,9,2)
	$ano=substr($fecha,0,4);
	$mes=substr($fecha,6,2);
	$comp=$dia.$mes.$ano.$row->fields("numero_comprobante");				
	$id=$row->fields("id");
$sql_act="UPDATE integracion_contable
   SET  
       numero_comprobante=$comp;
	where
	id='$id'
";die($sql_act."---");
/*if (!$conn->Execute($sql_act)) {
					
					die ('Error al Actualizar: '.$sql_act);
					}
					else*/
	//				echo($comp."-");
$row->MoveNext();
}



?>