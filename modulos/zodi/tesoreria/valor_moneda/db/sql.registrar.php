<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sqlBus="SELECT 
			 COUNT(id_val_moneda)
			 FROM 
			 	valor_moneda
			INNER JOIN 
				moneda
			ON
				valor_moneda.id_moneda = moneda.id_moneda
			 WHERE 
			 	codigo_moneda = '".$_POST['valor_moneda_db_codigo_moneda']."' 
			AND
				fecha_valor = '".$_POST['valor_moneda_db_fecha']."'";
$row=& $conn->Execute($sqlBus);
$cad = substr($row,7,2);
//$sqlBus = "SELECT * FROM \"solicitud_cotizacionD\" WHERE (id_solicitud_cotizacion = ".$id.") AND (id_organismo = ".$_SESSION['id_organismo'].")";
//$row=& $conn->Execute($sqlBus);

if ($cad==0 && substr($_POST['valor_moneda_db_fecha'],0,2) > date("d") && 
	substr($_POST['valor_moneda_db_fecha'],3,2) >= date("m") && 
	substr($_POST['valor_moneda_db_fecha'],6,4) == date("Y")){
		$sql = "INSERT INTO 
				valor_moneda
				(id_moneda,id_organismo,fecha_valor,valor_moneda,comentarios,fecha_modificacion,ultimo_usuario)
			VALUES
('".$_POST['valor_moneda_db_id_moneda']."','".$_SESSION['id_organismo']."','".$_POST['valor_moneda_db_fecha']."','".ereg_replace(',','.',$_POST['valor_moneda_db_porcentaje'])."','".$_POST['valor_moneda_db_comentario']."','".date("d/m/Y")."','".$_SESSION['id_usuario']."')";
if (!$conn->Execute($sql)) 
				die ('Error al Actulizar: '.$conn->ErrorMsg());
			else
				die("Actualizado");
	}
	
if ($cad!=0 && substr($_POST['valor_moneda_db_fecha'],0,2) > date("d") && 
	substr($_POST['valor_moneda_db_fecha'],3,2) >= date("m") && 
	substr($_POST['valor_moneda_db_fecha'],6,4) == date("Y")){
		 $sql = "UPDATE
					valor_moneda
				SET	
					valor_moneda='".ereg_replace(',','.',$_POST['valor_moneda_db_porcentaje'])."', comentarios='".$_POST['valor_moneda_db_comentario']."', fecha_modificacion='".date("d-m-Y")."', ultimo_usuario='".$_SESSION['id_usuario']."'
				WHERE	
					id_val_moneda = ".$_POST['valor_moneda_db_id_val_moneda']." 
				AND
					".substr($_POST['valor_moneda_db_fecha'],0,2).">".date("d")."
				AND ".substr($_POST['valor_moneda_db_fecha'],3,2).">=".date("m")."
				AND ".substr($_POST['valor_moneda_db_fecha'],6,4)."=".date("Y");
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





