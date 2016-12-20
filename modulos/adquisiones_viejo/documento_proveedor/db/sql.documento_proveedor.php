<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$estatus=$_POST['documento_proveedor_db_estatus'];
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$codigo=$_POST['documento_proveedor_db_codigo'];
$sqlBus = "SELECT * FROM documento WHERE (codigo_documento='$codigo') AND(upper(nombre) ='".strtoupper($_POST[documento_proveedor_db_nombre])."')";

$row=& $conn->Execute($sqlBus);
if($row->EOF){
	$sql = "	
					INSERT INTO 
						documento
							(
							codigo_documento, 
							nombre, 
							estatus,
							comentario,
							id_organismo,
							fecha_actualizacion,
							ultimo_usuario
							)
					VALUES
						(
							'$_POST[documento_proveedor_db_codigo]', 			
							'$_POST[documento_proveedor_db_nombre]', 
							'$estatus' ,
							'$_POST[documento_proveedor_db_observacion]',
							".$_SESSION['id_organismo'].", 
							'".date("Y-m-d H:i:s")."',
						    ".$_SESSION['id_usuario']."		 
						)
				";
	}
	
   else
{	
	//echo $sqlBus;
	//echo $sql;
	die("Existe");
}
if (!$conn->Execute($sql)) 
{		echo $sqlBus;
	echo $sql;
	die ('Error al Registrar: '.$conn->ErrorMsg());
}
else
	echo("Registrado");
?>