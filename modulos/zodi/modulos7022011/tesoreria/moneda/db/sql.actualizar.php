<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$cad = $_POST["tesoreria_moneda_pr_partida_numero"];
$tam = strlen($cad);
	for($i=1; $i<=4; $i++){
		if ($i<=3){
			$pos = strpos($cad, ".");
			$result[$i] = substr($cad, 0, $pos);
			$cad = substr($cad, $pos+1, $tam);
		}
		$tam = strlen($cad);
		if ($i==4)
			$result[$i] = substr($cad, 0, $tam);
	}

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlb = "SELECT id_moneda FROM moneda WHERE id_moneda <> $_POST[tesoreria_moneda_db_id] AND upper(nombre) ='".strtoupper($_POST['tesoreria_moneda_db_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF)
	$sql = "	
					UPDATE moneda  
						 SET
							nombre = '$_POST[tesoreria_moneda_db_nombre]',
							comentario = '$_POST[tesoreria_moneda_db_observacion]',							
							id_organismo=	 ".$_SESSION["id_organismo"].",
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."'	
							WHERE id_moneda = $_POST[tesoreria_moneda_db_id]
							
				";
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>