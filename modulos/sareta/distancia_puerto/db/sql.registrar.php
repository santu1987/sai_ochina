<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT * FROM sareta.distancia_puerto WHERE id_puerto_desde='".$_POST['vista_id_desde']."' and id_puerto_hasta='".$_POST['vista_id_hasta']."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);



if($_POST['vista_id_desde']!=$_POST['vista_id_hasta']){
if(($row->fields("id_puerto_desde")!=$_POST['vista_id_desde'] &&
	$row->fields("id_puerto_hasta")!=$_POST['vista_id_hasta'])){
	
	$sql = "SELECT * FROM sareta.distancia_puerto WHERE id_puerto_desde='".$_POST['vista_id_hasta']."' and id_puerto_hasta='".$_POST['vista_id_desde']."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
   
  if(($row->fields("id_puerto_desde")!=$_POST['vista_id_hasta'] &&
		 $row->fields("id_puerto_hasta")!=$_POST['vista_id_desde'])
										   ){
			
$sql = "	
				INSERT INTO 
					 sareta.distancia_puerto
					(
						id_bandera_desde,
						id_puerto_desde,
						id_bandera_hasta,
						id_puerto_hasta,
						millas,
						comentario,
						ultimo_usuario,
						fecha_creacion,
						fecha_actualizacion
					) 
					VALUES
					(
					 	$_POST[vista_id_bandera_desde],
						$_POST[vista_id_desde],
						$_POST[vista_id_bandera_hasta],
						$_POST[vista_id_hasta],
						$_POST[sareta_distancia_db_millas],
						'$_POST[sareta_distancia_db_vista_observacion]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
						'".date("Y-m-d H:i:s")."'
						
					)";
		
		}else{
		die("NoRegistro");
		}


	}else{
	die("NoRegistro");
	}
}else{
die("MismoPtoInvalido");
}
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Registrado");
?>