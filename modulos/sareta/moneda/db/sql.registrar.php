<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT id_moneda FROM sareta.moneda WHERE upper(nombre) ='".strtoupper($_POST['sareta_moneda_db_vista_nombre'])."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
	$sqlb = "SELECT id_moneda FROM sareta.moneda WHERE upper(abreviatura) ='".strtoupper($_POST['sareta_moneda_db_vista_abreviatura'])."'";
	if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
	$row=& $conn->Execute($sqlb);
	if($row->EOF){
$sql = "	
				INSERT INTO 
					sareta.moneda
					(
						nombre,
						multiplica_divide,
						abreviatura,
						obs,
						ultimo_usuario,
						fecha_creacion,
						fecha_actualizacion
					) 
					VALUES
					(
						upper('$_POST[sareta_moneda_db_vista_nombre]'),
						upper('$_POST[sareta_moneda_db_vista_multiplica_divide]'),
						upper('$_POST[sareta_moneda_db_vista_abreviatura]'),
						'$_POST[sareta_moneda_db_vista_observacion]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
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