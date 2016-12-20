<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id FROM sareta.bandera WHERE upper(nombre) ='".strtoupper($_POST['sareta_bandera_db_vista_nombre'])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
	$sqlb = "SELECT id FROM sareta.bandera WHERE upper(abreviatura) ='".strtoupper($_POST['sareta_bandera_db_vista_abreviatura'])."'";
	if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
	$row=& $conn->Execute($sqlb);
	if($row->EOF){
$sql = "	
				INSERT INTO 
					sareta.bandera 
					(
						nombre,
						abreviatura,
						obs,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						upper('$_POST[sareta_bandera_db_vista_nombre]'),
						upper('$_POST[sareta_bandera_db_vista_abreviatura]'),
						'$_POST[sareta_bandera_db_vista_observacion]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'
						
					)";
	}else{
		die("Abreviatura_Existe");	}

}else{
	die("NoRegistro");
}
	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Registrado");
?>