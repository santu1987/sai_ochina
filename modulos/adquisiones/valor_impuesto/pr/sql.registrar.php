<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sqlBus="SELECT 
			 COUNT(id_val_impu)
			 FROM 
			 	valor_impuesto
			INNER JOIN 
				impuesto
			ON
				valor_impuesto.id_impuesto = impuesto.id_impuesto
			 WHERE 
			 	codigo_impuesto = '".$_POST['valor_impuesto_db_codigo_impuesto']."' 
			AND
				fecha_valor = '".$_POST['valor_impuesto_db_fecha']."'";
$row=& $conn->Execute($sqlBus);
$cad = substr($row,7,2);
//$sqlBus = "SELECT * FROM \"solicitud_cotizacionD\" WHERE (id_solicitud_cotizacion = ".$id.") AND (id_organismo = ".$_SESSION['id_organismo'].")";
//$row=& $conn->Execute($sqlBus);

if ($cad==0 && substr($_POST['valor_impuesto_db_fecha'],0,2) > date("d") && 
	substr($_POST['valor_impuesto_db_fecha'],3,2) >= date("m") && 
	substr($_POST['valor_impuesto_db_fecha'],6,4) == date("Y")){
		$sql = "INSERT INTO 
				valor_impuesto
				(id_impuesto,id_organismo,fecha_valor,porcentaje_impuesto,comentarios,fecha_modificacion, ultimo_usuario)
			VALUES
('".$_POST['valor_impuesto_db_id_impuesto']."','".$_SESSION['id_organismo']."','".$_POST['valor_impuesto_db_fecha']."','".ereg_replace(',','.',$_POST['valor_impuesto_db_porcentaje'])."','".$_POST['valor_impuesto_db_comentario']."','".date("d/m/Y")."','".$_SESSION['id_usuario']."')";
if (!$conn->Execute($sql)) 
				die ('Error al Actulizar: '.$conn->ErrorMsg());
			else
				die("Actualizado");
	}
	
if ($cad!=0 && substr($_POST['valor_impuesto_db_fecha'],0,2) > date("d") && 
	substr($_POST['valor_impuesto_db_fecha'],3,2) >= date("m") && 
	substr($_POST['valor_impuesto_db_fecha'],6,4) == date("Y")){
		 $sql = "UPDATE
					valor_impuesto
				SET	
					porcentaje_impuesto='".ereg_replace(',','.',$_POST['valor_impuesto_db_porcentaje'])."', comentarios='".$_POST['valor_impuesto_db_comentario']."', fecha_modificacion='".date("d-m-Y")."', ultimo_usuario='".$_SESSION['id_usuario']."'
				WHERE	
					id_val_impu = ".$_POST['valor_impuesto_db_id_val_impu']." 
				AND
					".substr($_POST['valor_impuesto_db_fecha'],0,2).">".date("d")."
				AND ".substr($_POST['valor_impuesto_db_fecha'],3,2).">=".date("m")."
				AND ".substr($_POST['valor_impuesto_db_fecha'],6,4)."=".date("Y");
				if (!$conn->Execute($sql)) 
				die ('Error al Actulizar: '.$conn->ErrorMsg());
			else
				die("Actualizado");
				
	}
	else{die ("Error fecha");}
/*
if(!$row->EOF){
			
	
}else{
	die("Existe");
}*/											
?>





