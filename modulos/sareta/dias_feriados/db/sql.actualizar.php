<?php
	
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
session_start();
$sql0 = "	
							
							 UPDATE sareta.dias_feriados
								 SET
							 descripcion='".strtoupper($_POST['dias_feriados_db_nombre'])."',
							 fecha_dia_feriado='".$_POST['dias_feriados_db_fecha_ano']."',
							 tipo=".$_POST['dias_feriados_db_tipo'].",
							 delegacion=".$_POST['dias_feriados_db_delegacion'].",
							 comentario='".$_POST['dias_feriados_db_comentario']."', 
							 ultimo_usuario =".$_SESSION['id_usuario'].",
							 fecha_actualizacion='".date("Y-m-d H:i:s")."'
								WHERE id_dia_feriado=$_POST[dias_feriados_db_id]
								
								";
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


if($_POST['dias_feriados_db_tipo']==1){	

		$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and tipo=1 and 
		fecha_dia_feriado='".$_POST['dias_feriados_db_fecha_ano']."' and comentario='".$_POST['dias_feriados_db_comentario']."'";
		if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
		$row= $conn->Execute($sql);
			if($row->EOF){	
			 
					 $sql = "SELECT id_dia_feriado,descripcion FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and
					tipo=1 ";
					if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
					$row= $conn->Execute($sql);
						if($row->fields("id_dia_feriado")==$_POST['dias_feriados_db_id']){	
							$sql =$sql0;
						}else if($row->fields("descripcion")==strtoupper($_POST['dias_feriados_db_nombre'])){
						die('ExisteN');
						}else{
						$sql = $sql0;
						}			
			}else{
			die('NoExisteCambio');
			}
			
}else if($_POST['dias_feriados_db_tipo']==2){		
	if($_POST['dias_feriados_db_delegacion']!=0){
	
			$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."'
			and delegacion=".$_POST['dias_feriados_db_delegacion']." and tipo=2 and 
			fecha_dia_feriado='".$_POST['dias_feriados_db_fecha_ano']."' and comentario='".$_POST['dias_feriados_db_comentario']."'";
			if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
			$row= $conn->Execute($sql);	
			if($row->EOF){
				$sql = "SELECT id_dia_feriado,descripcion FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and
					tipo=2 and delegacion=".$_POST['dias_feriados_db_delegacion'];
					if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
					$row= $conn->Execute($sql);
						if($row->fields("id_dia_feriado")==$_POST['dias_feriados_db_id']){	
							$sql =$sql0;
						}else if($row->fields("descripcion")==strtoupper($_POST['dias_feriados_db_nombre'])){
						die('ExisteR');
						}else{
						$sql = $sql0;
						}
			}else{
			die('NoExisteCambio');
			}
			
	}else{	
	die('falta_delegacion');	
	}
}else if($_POST['dias_feriados_db_tipo']==3){	

		$sql = "SELECT id_dia_feriado FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and tipo=3 and 
		fecha_dia_feriado='".$_POST['dias_feriados_db_fecha_ano']."' and comentario='".$_POST['dias_feriados_db_comentario']."'";
		if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
		$row= $conn->Execute($sql);
			if($row->EOF){	
			 
					 $sql = "SELECT id_dia_feriado,descripcion FROM sareta.dias_feriados WHERE upper(descripcion) ='".strtoupper($_POST['dias_feriados_db_nombre'])."' and
					tipo=3 ";
					if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
					$row= $conn->Execute($sql);
						if($row->fields("id_dia_feriado")==$_POST['dias_feriados_db_id']){	
							$sql =$sql0;
						}else if($row->fields("descripcion")==strtoupper($_POST['dias_feriados_db_nombre'])){
						die('ExisteV');
						}else{
						$sql = $sql0;
						}			
			}else{
			die('NoExisteCambio');
			}
}
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
//	'$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]'
//$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]
}
else
{	
		die ('Actualizado');
}
?>