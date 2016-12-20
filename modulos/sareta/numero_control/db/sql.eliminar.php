<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql);
									$delegacion=0;
									if(!$row1->EOF){
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}
									
$sql = "SELECT id_numero_control FROM sareta.numero_control WHERE id_delegacion=".$delegacion." and estatus=true " ;
if (!$conn->Execute($sql)) die ('Error al Eliminar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
if($row->fields("id_numero_control")!=$_POST['vista_id_numero_control']){			
		$sql="DELETE FROM sareta.numero_control WHERE id_numero_control = $_POST[vista_id_numero_control]";
		if (!$conn->Execute($sql)) {
			$ErroForanio=strcmp("viola la llave foránea",$conn->ErrorMsg());
		if($ErroForanio=true && $_SESSION['perfil']==0 ){
			die("Foranio");
		}if($ErroForanio=true && $_SESSION['perfil']==1 ){
			die ('Error al Eliminar: '.$conn->ErrorMsg());
			
			}
		
		}
		else
			die("Eliminado");

	}else{
	die("NoPuedeSerEliminado");
	}
?>