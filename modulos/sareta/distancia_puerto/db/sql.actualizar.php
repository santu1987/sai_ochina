<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT * FROM sareta.distancia_puerto WHERE id_puerto_desde='".$_POST['vista_id_desde']."' and id_puerto_hasta='".$_POST['vista_id_hasta']."'";
if (!$conn->Execute($sql)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);



if($_POST['vista_id_desde']!=$_POST['vista_id_hasta']){
if(($row->fields("id_puerto_desde")!=$_POST['vista_id_desde'] &&
	$row->fields("id_puerto_hasta")!=$_POST['vista_id_hasta']) || 
	($row->fields("id_distancia")== $_POST['vista_id_distancia']) ){
	
	$sql = "SELECT * FROM sareta.distancia_puerto WHERE id_puerto_desde='".$_POST['vista_id_hasta']."' and id_puerto_hasta='".$_POST['vista_id_desde']."'";
if (!$conn->Execute($sql)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
   
  if(($row->fields("id_puerto_desde")!=$_POST['vista_id_hasta'] &&
		 $row->fields("id_puerto_hasta")!=$_POST['vista_id_desde'])
										   ){
			
			$sql = "	
					UPDATE sareta.distancia_puerto 
						 SET
							id_bandera_desde=$_POST[vista_id_bandera_desde],
							id_puerto_desde=$_POST[vista_id_desde],
							id_bandera_hasta=$_POST[vista_id_bandera_hasta],
							id_puerto_hasta=$_POST[vista_id_hasta],
							millas=$_POST[sareta_distancia_db_millas],
							comentario='$_POST[sareta_distancia_db_vista_observacion]',						
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_distancia = $_POST[vista_id_distancia]
							
				";
			

		
		}else{
		die("NoActualizo");
		}


	}else{
	die("NoActualizo");
	}
}else{
die("MismoPtoInvalido");
}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Actualizado");
?>
