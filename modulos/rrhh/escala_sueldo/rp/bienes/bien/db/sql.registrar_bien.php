<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$fcomp=$_POST['registrar_bienes_db_fecha_compra'];
$dia=substr($fcomp,0,2);
$mes=substr($fcomp,3,2);
$ano=substr($fcomp,8,2);
if($mes==1){$mes="ENE";} if($mes==2){$mes="FEB";}
if($mes==3){$mes="MAR";} if($mes==4){$mes="ABR";}
if($mes==5){$mes="MAY";} if($mes==6){$mes="JUN";}
if($mes==7){$mes="JUL";} if($mes==8){$mes="AGO";}
if($mes==9){$mes="SEP";} if($mes==10){$mes="OCT";}
if($mes==11){$mes="NOV";} if($mes==12){$mes="DIC";}
if($_POST['bienes_bien_num_seguro']==""){$seguro="NULL";}
$sql_cod = "	SELECT 
				id_bienes
			FROM
				bienes
			ORDER BY
				id_bienes
			";
$row_cod=$conn->Execute($sql_cod);
while(!$row_cod->EOF)
 {
	$i=$row_cod->fields["id_bienes"];
	$row_cod->MoveNext();
 }
if($i==" "){ $i=1;} 
else{ 
	 $i++;
	 $tam=strlen($i);
	 $tam=5-$tam;
	 $cad="0";
	 for($t=1;$t<$tam;$t++){
		 $cad.="0";
	 }
	 $cad.=$i;
 }
$codigo="01".$dia.$mes.$ano.$cad;
if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				count(id_bienes) 
			FROM 
				bienes
			WHERE
				upper(bienes.codigo_bienes)='".strtoupper($_POST['registrar_bienes_db_codigo'])."'
			OR
				upper(bienes.serial_bien)='".strtoupper($_POST['registrar_bienes_db_serial'])."'
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
if($_POST['bienes_bien_num_seguro']!=""){
	$sql = "	
					INSERT INTO 
						bienes 
						(
							id_organismo,
							nombre,
							valor_compra,
							fecha_compra,
							valor_rescate,
							id_tipo_bienes,
							id_sitio_fisico,
							id_unidad_ejecutora,
							id_mayor,
							vida_util,
							id_custodio,
							descripcion_general,
							marca,
							modelo,
							anobien,
							serial_motor,
							serial_carroceria,
							color,
							placa,
							estatus_bienes,
							comentarios,
							ultimo_usuario,
							codigo_bienes,
							serial_bien,
							calcular_depreciacion,
							fecha_actualizacion,
							id_orden_compra_servicioe,
							ano_orden_compra,
							numero_factura,
							num_seguro
						) 
						VALUES
						(
							'$_SESSION[id_organismo]',
							'$_POST[registrar_bienes_db_nombre]',
							'$_POST[registrar_bienes_db_valor_compra]',
							'$_POST[registrar_bienes_db_fecha_compra]',
							'$_POST[registrar_bienes_db_valor_rescate]',
							'$_POST[bien_db_id_tipo]',
							'$_POST[bien_db_id_sitio_fisico]',
							'$_POST[bien_db_id_unidad]',
							'$_POST[bien_db_id_mayor]',
							'$_POST[registrar_bienes_db_vida_util]',
							'$_POST[bien_db_id_custodio]',
							'$_POST[registrar_bienes_db_comentarios2]',
							'$_POST[registrar_bienes_db_marca]',
							'$_POST[registrar_bienes_db_modelo]',
							'$_POST[registrar_bienes_db_ano]',
							'$_POST[registrar_bienes_db_serial_motor]',
							'$_POST[registrar_bienes_db_serial_car]',
							'$_POST[registrar_bienes_db_color]',
							'$_POST[registrar_bienes_db_placa]',
							'$_POST[estatus]',
							'$_POST[registrar_bienes_db_comentarios]',
							'$_SESSION[id_usuario]',
							'$codigo',
							'$_POST[registrar_bienes_db_serial]',
							'$_POST[val_depreciacion]',
							'$fecha',
							'$_POST[bien_db_id_orden]',
							'$_POST[registrar_bienes_db_orden_ano]',
							'$_POST[registrar_bienes_db_factura]',
							'$_POST[bienes_bien_num_seguro]'
						)
				";
}
if($_POST['bienes_bien_num_seguro']==""){
	$sql = "	
					INSERT INTO 
						bienes 
						(
							id_organismo,
							nombre,
							valor_compra,
							fecha_compra,
							valor_rescate,
							id_tipo_bienes,
							id_sitio_fisico,
							id_unidad_ejecutora,
							id_mayor,
							vida_util,
							id_custodio,
							descripcion_general,
							marca,
							modelo,
							anobien,
							serial_motor,
							serial_carroceria,
							color,
							placa,
							estatus_bienes,
							comentarios,
							ultimo_usuario,
							codigo_bienes,
							serial_bien,
							calcular_depreciacion,
							fecha_actualizacion,
							id_orden_compra_servicioe,
							ano_orden_compra,
							numero_factura
						) 
						VALUES
						(
							'$_SESSION[id_organismo]',
							'$_POST[registrar_bienes_db_nombre]',
							'$_POST[registrar_bienes_db_valor_compra]',
							'$_POST[registrar_bienes_db_fecha_compra]',
							'$_POST[registrar_bienes_db_valor_rescate]',
							'$_POST[bien_db_id_tipo]',
							'$_POST[bien_db_id_sitio_fisico]',
							'$_POST[bien_db_id_unidad]',
							'$_POST[bien_db_id_mayor]',
							'$_POST[registrar_bienes_db_vida_util]',
							'$_POST[bien_db_id_custodio]',
							'$_POST[registrar_bienes_db_comentarios2]',
							'$_POST[registrar_bienes_db_marca]',
							'$_POST[registrar_bienes_db_modelo]',
							'$_POST[registrar_bienes_db_ano]',
							'$_POST[registrar_bienes_db_serial_motor]',
							'$_POST[registrar_bienes_db_serial_car]',
							'$_POST[registrar_bienes_db_color]',
							'$_POST[registrar_bienes_db_placa]',
							'$_POST[estatus]',
							'$_POST[registrar_bienes_db_comentarios]',
							'$_SESSION[id_usuario]',
							'$codigo',
							'$_POST[registrar_bienes_db_serial]',
							'$_POST[val_depreciacion]',
							'$fecha',
							'$_POST[bien_db_id_orden]',
							'$_POST[registrar_bienes_db_orden_ano]',
							'$_POST[registrar_bienes_db_factura]'
						)
				";
}
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{		
$sql = "SELECT 
			id_bienes
		FROM
			bienes
		WHERE
			upper(bienes.nombre) = '".strtoupper($_POST['registrar_bienes_db_nombre'])."'
		AND
			upper(serial_bien) = '".strtoupper($_POST['registrar_bienes_db_serial'])."'";
			$row=& $conn->Execute($sql);
			}
			die ("Registrado_".$row->fields("id_bienes"));
}
if ($row!=0){
	die('Existe');
} 
?>