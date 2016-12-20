<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT id_movimientos_contables, ano_comprobante, mes_comprobante, id_tipo_comprobante, 
       numero_comprobante, secuencia, comentario, cuenta_contable, descripcion, 
       referencia, debito_credito, monto_debito, monto_credito, fecha_comprobante, 
       id_auxiliar, id_unidad_ejecutora, id_proyecto, id_utilizacion_fondos, 
       ultimo_usuario, id_organismo, ultima_modificacion, estatus, id_accion_central
  FROM movimientos_contables
  
  ;
";
//*********
$row=& $conn->Execute($sql);
while (!$row->EOF)
{
	$fecha=$row->fields("fecha_comprobante");

	$dia=substr($fecha,8,2);
	$ano=substr($fecha,0,4);
	$mes=substr($fecha,5,2);
	//$uno=substr($mes,0,1);
	/*if($uno==0)
	$mes=substr($mes,1,1);
	*/
//die($dia.$mes.$ano);
$comp=$ano.$mes.$dia.$row->fields("numero_comprobante");				
$id=$row->fields("id_movimientos_contables");
$sql_act="UPDATE movimientos_contables
   SET  
       numero_comprobante=$comp
	where
	id_movimientos_contables='$id'
";//echo($sql_act."---");
if(strlen($row->fields("numero_comprobante"))<=6)
{
	if (!$conn->Execute($sql_act)) {
							
							die ('Error al Actualizar: '.$sql_act);
							}
							else
							echo($comp."-");
}
$row->MoveNext();
}



?>