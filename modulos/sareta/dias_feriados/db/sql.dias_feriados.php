<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if($_POST['dias_feriados_db_delegacion']!=0 || $_POST['dias_feriados_db_tipo']!=2){


	$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and tipo=1";
	if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql);
		if($row->EOF){
		$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and tipo=3";
	if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql);
		if($row->EOF){	
			$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and delegacion=".$_POST['dias_feriados_db_delegacion']."";
			if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
			$row= $conn->Execute($sql);	
				if($row->EOF){
					$sql = "	
							INSERT INTO 
								sareta.dias_feriados
								  (
								  descripcion,
								  fecha_dia_feriado,
								  tipo,
								  delegacion,
								  comentario,
								  ultimo_usuario,
								  fecha_creacion,
								  fecha_actualizacion
								  )
							 VALUES (
							 
							 '".strtoupper($_POST['dias_feriados_db_nombre'])."',
							 '".$_POST['dias_feriados_db_fecha_ano']."',
							 ".$_POST['dias_feriados_db_tipo'].",
							 ".$_POST['dias_feriados_db_delegacion'].",
							 '".$_POST['dias_feriados_db_comentario']."',
							 ".$_SESSION['id_usuario'].",
							 '".date("Y-m-d H:i:s")."',
							 '".date("Y-m-d H:i:s")."'
				
									)
								";
					}else{
					die('ExisteR');
					}
			}else{
			die('ExisteV');
			}			
		}else{
		die('ExisteN');
		}
}else{
	
die('falta_delegacion');
	
}

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	echo 'Registrado';

?>