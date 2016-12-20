<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	
$sqlBus = "SELECT * FROM demanda WHERE (upper(nombre) = '".strtoupper($_POST[demanda_db_nombre])."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);
			
if($row->EOF){
$sql = "	
		INSERT INTO demanda(
				id_organismo, 
				codigo_demanda, 
				nombre, 
				comentario, 
				ultimo_usuario, 
				fecha_actualizacion
		)VALUES 	(
				".$_SESSION['id_organismo'].",
				'".$_POST['demanda_db_codigo']."', 
				'".$_POST['demanda_db_nombre']."', 
				'".$_POST['demanda_db_comentario']."', 
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'
		)
";
//echo($sql);
}else{
	die("NoRegistro");
	}
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>